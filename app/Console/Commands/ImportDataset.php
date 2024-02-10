<?php

namespace App\Console\Commands;

use Domain\Department\Models\Department;
use League\Csv\Reader;
use Domain\EmploymentType\Models\EmploymentType;
use Domain\JobTitle\Models\JobTitle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Services\CsvImporterService\CsvImporterService;
use Services\CsvImporterService\Exceptions\CsvImporterServiceException;

class ImportDataset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:dataset {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import employee dataset from CSV file to database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws CsvImporterServiceException
     */
    public function handle()
    {
        $m1 = memory_get_usage();
        $path = $this->argument('path');
        if (!file_exists($path) || !is_readable($path)) {
            $this->error("The file at path {$path} does not exist or is not readable.");
            return 1;
        }
        $csvImporter = new CsvImporterService();

        $csvImporter->linesInFile($path);

        $csvImporter->validateHeaders($path, [
            'Name',
            'Job Titles',
            'Department',
            'Full or Part-Time',
            'Salary or Hourly',
            'Typical Hours',
            'Annual Salary',
            'Hourly Rate'
        ]);

        $bar = $this->output->createProgressBar($csvImporter->linesInFile($path) - 1);
        $bar->start();

        $count = 0;
        $employees = [];

        DB::beginTransaction();
        try {
            $csvImporter->import($path, function ($record) use ($bar, &$count, &$employees) {
                $chunk = 1000;
                $department = Department::firstOrCreate(['name' => $record['Department']]);
                $jobTitle = JobTitle::firstOrCreate(['title' => $record['Job Titles']]);
                $employmentType = EmploymentType::firstOrCreate([
                    'type' => $record['Salary or Hourly'],
                    'full_or_part_time' => $record['Full or Part-Time'],
                ]);
                $fullName = explode(',', $record['Name']);
                $lastName = trim($fullName[0]);
                $firstName = trim($fullName[1]);
                $employees[] = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'department_id' => $department->id,
                    'job_title_id' => $jobTitle->id,
                    'employment_type_id' => $employmentType->id,
                    'salary' => $record['Annual Salary'],
                    'hourly_rate' => $record['Hourly Rate'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $bar->advance();

                // as soon as we reach the chunk size, we insert the data
                if (++$count === $chunk) {
                    DB::table('employees')->insert($employees);
                    $employees = [];
                    $count = 0;
                }
            });

            // Insert the remaining records
            DB::table('employees')->insert($employees);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
            return 1;
        }

        $bar->finish();
        $this->info("\nDataset imported successfully.");
        $m2 = memory_get_usage();
        $this->info('Memory usage: ' . round(($m2 - $m1) / 1024 / 1024, 2) . ' MB');
    }
}
