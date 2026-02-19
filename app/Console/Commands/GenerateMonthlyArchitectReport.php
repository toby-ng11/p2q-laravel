<?php

namespace App\Console\Commands;

use App\Exports\MonthlyArchitectsExport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GenerateMonthlyArchitectReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:monthly-architects {--month=} {--year=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly Excel report for new architects';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        /** @var int $month */
        $month = $this->option('month') ?? Carbon::now()->subMonth()->month;
        /** @var int $year */
        $year = $this->option('year') ?? Carbon::now()->subMonth()->year;

        $this->info("Generating report for {$year}-{$month}...");

        $fileName = 'architects_report_' . $year . '_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '.xlsx';
        $filePath = 'reports/architects/' . $fileName;

        Excel::store(
            new MonthlyArchitectsExport($month, $year),
            $filePath,
            'local'
        );

        $this->info("Report generated successfully: {$filePath}");
        $this->info("Full path: " . Storage::path($filePath));

        return Command::SUCCESS;
    }
}
