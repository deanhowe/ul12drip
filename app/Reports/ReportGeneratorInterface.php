<?php

namespace App\Reports;

/**
 * Interface for report generators.
 *
 * Demonstrates:
 * - Contract/Interface pattern
 * - Used with container tagging for collecting multiple implementations
 *
 * Usage with tagging:
 *   // In AppServiceProvider
 *   $this->app->tag([SalesReport::class, InventoryReport::class], 'reports');
 *
 *   // Resolve all tagged services
 *   $reports = app()->tagged('reports');
 *   foreach ($reports as $report) {
 *       $report->generate();
 *   }
 */
interface ReportGeneratorInterface
{
    /**
     * Get the name of the report.
     */
    public function getName(): string;

    /**
     * Generate the report data.
     *
     * @return array<string, mixed>
     */
    public function generate(): array;
}
