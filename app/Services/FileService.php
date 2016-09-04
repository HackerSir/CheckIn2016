<?php

namespace App\Services;

use App\Booth;
use App\Point;
use App\Student;
use Excel;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use PHPExcel_Cell;
use PHPExcel_Style_Border;
use PHPExcel_Style_NumberFormat;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

class FileService
{
    /**
     * 產生檔案，doc
     *
     * @link https://github.com/PHPOffice/PHPWord 套件專案
     * @link http://phpword.readthedocs.org/ 開發者文件
     *
     * @param Booth|Booth[] $booths
     * @return PhpWord
     */
    public function generateQRCodeDocFile($booths)
    {
        //包裝成集合
        if ($booths instanceof Booth) {
            $booths = collect([$booths]);
        }
        //建立檔案
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('微軟正黑體');
        //建立Section
        foreach ($booths as $booth) {
            $phpWord = $this->generateQRCodeSection($phpWord, $booth);
        }

        return $phpWord;
    }

    private function generateQRCodeSection(PhpWord $phpWord, Booth $booth)
    {
        //建立Section
        $section = $phpWord->addSection();
        //活動標題
        $section->addText('學生社團博覽會 打卡集點抽獎', ['size' => 22], ['alignment' => Jc::CENTER]);
        //社團類型與名稱
        if ($booth->type) {
            $section->addText($booth->type->name, ['size' => 48], ['alignment' => Jc::CENTER]);
        } else {
            $section->addTextBreak(1, ['size' => 48], ['alignment' => Jc::CENTER]);
        }
        $nameSize = 72;
        if (mb_strlen($booth->name) > 6) {
            //把攤位名稱塞在同一行的字體大小，公式是實驗結果丟給Excel分析後得出
            $nameSize = 443.4 * pow(mb_strlen($booth->name), -1.004);
        }
        $section->addText($booth->name, ['size' => $nameSize], ['alignment' => Jc::CENTER]);
        //QR Code
        $section->addImage($booth->QR, ['height' => 450, 'width' => 450, 'alignment' => Jc::CENTER]);
        //課外活動組與黑客社廣告
        $textRun = $section->addTextRun(['alignment' => Jc::CENTER]);
        $textRun->addImage(resource_path('assets/image/fcu.png'), ['height' => 50, 'width' => 50]);
        $textRun->addText('課外活動組', ['size' => 22]);
        $textRun->addText(str_repeat(' ', 10), ['size' => 22]);
        $textRun->addImage(resource_path('assets/image/hacker.png'), ['height' => 50, 'width' => 50]);
        $textRun->addText('黑客社', ['size' => 22]);

        return $phpWord;
    }

    /**
     * 產生檔案，打卡集點記錄，doc
     *
     * @param string $fileName
     * @return mixed
     */
    public function generateXlsxFile($fileName = 'fileName')
    {
        $excelFile = Excel::create($fileName, function ($excel) {
            /* @var LaravelExcelWriter $excel */
            //檔案基本資料
            $excel->setTitle('打卡集點記錄')
                ->setCreator('KID')
                ->setDescription('學生社團博覽會 打卡集點抽獎 打卡集點完整記錄報表');

            $excel->sheet('打卡集點', function ($sheet) {
                /* @var LaravelExcelWorksheet $sheet */
                //標題列
                $staticTitleRow = ['完成序號', 'NID', '姓名', '系級', '科系', '學院', '入學年度', '性別'];
                $titleRow = $staticTitleRow;
                $dataStartColumns = [];
                for ($i = 1; $i <= 10; $i++) {
                    $titleRow = array_merge($titleRow, [
                        '時間' . $i,
                        '社團' . $i,
                        '類型' . $i,
                    ]);
                    $startColumnName = PHPExcel_Cell::stringFromColumnIndex(count($staticTitleRow) + ($i - 1) * 3);
                    $dataStartColumns[] = $startColumnName;
                }
                $sheet->row(1, $titleRow);
                //設定凍結
                $sheet->setFreeze(PHPExcel_Cell::stringFromColumnIndex(count($staticTitleRow)) . '2');

                //有抽獎券的學生
                $studentHasTickets = Student::with('ticket', 'points.booth.type')
                    ->has('ticket', '>', 0)->get()
                    ->sortBy(function ($student) {
                        return $student->ticket->created_at;
                    });
                //沒抽獎券的學生
                $studentNoTickets = Student::with('ticket', 'points.booth.type')
                    ->has('ticket', '=', 0)->get()
                    ->sortBy(function ($student) {
                        return $student->points->count();
                    }, SORT_REGULAR, true);
                /* @var Student[] $students */
                $students = $studentHasTickets->merge($studentNoTickets);
                foreach ($students as $student) {
                    //抽獎編號（完成序號）、NID、姓名
                    $rowData = [
                        ($student->ticket) ? $student->ticket->id : '未完成',
                        $student->nid,
                        $student->name,
                        $student->class,
                        $student->unit_name,
                        $student->dept_name,
                        $student->in_year,
                        $student->sex,
                    ];
                    //打卡紀錄
                    /* @var Point[] $points */
                    $points = $student->points()->groupBy('booth_id')->orderBy('created_at')->distinct()->get();
                    foreach ($points as $point) {
                        //時間、社團、類型
                        $pointData = [
                            ($point->check_at) ? $point->check_at->format('H:i') : '',
                            $point->booth->name,
                            ($point->booth->type) ? $point->booth->type->name : '',
                        ];
                        $rowData = array_merge($rowData, $pointData);
                    }
                    //新增一列
                    $sheet->appendRow($rowData);
                }
                $lastRow = $sheet->getHighestRow();
                //調整格式
                foreach ($dataStartColumns as $startColumn) {
                    //時間格式
                    //FIXME: 設定後，雖套用格式，但開啟檔案後，需編輯儲存格才會確實套用
                    $sheet->setColumnFormat([
                        $startColumn => PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3,
                    ]);
                    //左邊線
                    $sheet->getStyle($startColumn . '1:' . $startColumn . $lastRow)->applyFromArray([
                        'borders' => [
                            'left' => [
                                'style' => PHPExcel_Style_Border::BORDER_THICK,
                            ],
                        ],
                    ]);
                }
                //水平分隔線
                $dividerRow = count($studentHasTickets) + 1;
                $lastColumn = $sheet->getHighestColumn();
                $dividerStyle = [
                    'borders' => [
                        'bottom' => [
                            'style' => PHPExcel_Style_Border::BORDER_THICK,
                        ],
                    ],
                ];
                //標題下方
                $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($dividerStyle);
                //完成與未完成之間
                $sheet->getStyle('A' . $dividerRow . ':' . $lastColumn . $dividerRow)->applyFromArray($dividerStyle);
            });
        });

        return $excelFile;
    }
}
