<?php

namespace App\Services;

use App\Booth;
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
        $section->addText($booth->name, ['size' => 72], ['alignment' => Jc::CENTER]);
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
}
