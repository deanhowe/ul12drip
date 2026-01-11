<?php

namespace App\Console\Commands;

use App\Reports\ReportGeneratorInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Custom Artisan command to generate reports.
 *
 * Demonstrates:
 * - Custom Artisan command creation
 * - Command arguments and options
 * - Progress bars and output styling
 * - Using tagged services from the container
 * - File output generation
 *
 * Usage:
 *   php artisan reports:generate
 *   php artisan reports:generate --output=storage/reports
 *   php artisan reports:generate --format=json
 */
class GenerateReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:generate
                            {--output=storage/app/reports : Directory to save reports}
                            {--format=json : Output format (json or table)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all available reports using tagged report generators';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Starting report generation...');
        $this->newLine();

        // Get all tagged report generators from the container
        $reports = app()->tagged('reports');
        $reportGenerators = iterator_to_array($reports);

        if (empty($reportGenerators)) {
            $this->warn('No report generators found.');

            return self::SUCCESS;
        }

        $this->info(sprintf('Found %d report generator(s)', count($reportGenerators)));
        $this->newLine();

        $outputDir = $this->option('output');
        $format = $this->option('format');

        // Ensure output directory exists
        if (! File::isDirectory($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
            $this->line("Created output directory: {$outputDir}");
        }

        // Create progress bar
        $bar = $this->output->createProgressBar(count($reportGenerators));
        $bar->start();

        $generatedReports = [];

        /** @var ReportGeneratorInterface $generator */
        foreach ($reportGenerators as $generator) {
            $reportData = $generator->generate();
            $generatedReports[] = $reportData;

            // Save report to file
            $filename = str($generator->getName())->slug()->append('.json');
            $filepath = "{$outputDir}/{$filename}";
            File::put($filepath, json_encode($reportData, JSON_PRETTY_PRINT));

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Display results based on format option
        if ($format === 'table') {
            $this->displayAsTable($generatedReports);
        } else {
            $this->displayAsJson($generatedReports);
        }

        $this->newLine();
        $this->info("✅ Reports saved to: {$outputDir}");

        return self::SUCCESS;
    }

    /**
     * Display reports as a table.
     *
     * @param  array<int, array<string, mixed>>  $reports
     */
    private function displayAsTable(array $reports): void
    {
        $this->info('Generated Reports:');
        $this->newLine();

        foreach ($reports as $report) {
            $this->line("📊 <comment>{$report['report_name']}</comment>");
            $this->line("   Generated: {$report['generated_at']}");

            $tableData = [];
            foreach ($report['data'] as $key => $value) {
                $tableData[] = [str($key)->headline(), is_numeric($value) ? number_format($value, 2) : $value];
            }

            $this->table(['Metric', 'Value'], $tableData);
            $this->newLine();
        }
    }

    /**
     * Display reports as JSON.
     *
     * @param  array<int, array<string, mixed>>  $reports
     */
    private function displayAsJson(array $reports): void
    {
        $this->info('Generated Reports (JSON):');
        $this->newLine();
        $this->line(json_encode($reports, JSON_PRETTY_PRINT));
    }
}
