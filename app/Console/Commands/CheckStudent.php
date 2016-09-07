<?php

namespace App\Console\Commands;

use App\Services\CheckInService;
use App\Student;
use Illuminate\Console\Command;

class CheckStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:student {nid?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and fix missing ticket';
    /**
     * @var CheckInService
     */
    private $checkInService;

    /**
     * Create a new command instance.
     *
     * @param CheckInService $checkInService
     */
    public function __construct(CheckInService $checkInService)
    {
        parent::__construct();
        $this->checkInService = $checkInService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //取得指定NID
        $nid = $this->argument('nid');
        $this->info('NID:' . $nid);
        if ($nid) {
            //指定NID
            $student = Student::where('nid', $nid)->first();
            if (!$student) {
                $this->error('Student not found: ' . $nid);

                return;
            }
            $result = $this->check($student);
            if ($result) {
                $this->info('Ticket created: ' . $nid);
            }

            return;
        }
        //檢查全部
        $students = Student::all();
        $createdCount = 0;
        foreach ($students as $student) {
            $result = $this->check($student);
            if ($result) {
                $this->info('Ticket created: ' . $student->nid);
                $createdCount++;
            }
        }
        $this->info('Total created: ' . $createdCount);
    }

    public function check(Student $student)
    {
        $this->info('Check:' . $student->nid);
        if ($student->ticket) {
            //若已有抽獎券，則直接結束
            return false;
        }
        //強制檢查
        $this->checkInService->checkTarget($student);
        if ($student->fresh()->ticket) {
            return true;
        }

        return false;
    }
}
