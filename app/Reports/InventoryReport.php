<?php

namespace App\Reports;

use App\Models\Product;

/**
 * Inventory report generator.
 *
 * Demonstrates:
 * - Implementation of ReportGeneratorInterface
 * - Tagged service for container tagging pattern
 */
class InventoryReport implements ReportGeneratorInterface
{
    /**
     * Get the name of the report.
     */
    public function getName(): string
    {
        return 'Inventory Report';
    }

    /**
     * Generate the inventory report data.
     *
     * @return array<string, mixed>
     */
    public function generate(): array
    {
        return [
            'report_name' => $this->getName(),
            'generated_at' => now()->toIso8601String(),
            'data' => [
                'total_products' => Product::count(),
                'active_products' => Product::active()->count(),
                'out_of_stock' => Product::where('stock', 0)->count(),
                'low_stock' => Product::where('stock', '>', 0)->where('stock', '<', 10)->count(),
                'total_stock_value' => Product::selectRaw('SUM(price * stock) as value')->value('value') ?? 0,
            ],
        ];
    }
}
