<?php

namespace App\Reports;

use App\Models\Order;

/**
 * Sales report generator.
 *
 * Demonstrates:
 * - Implementation of ReportGeneratorInterface
 * - Tagged service for container tagging pattern
 */
class SalesReport implements ReportGeneratorInterface
{
    /**
     * Get the name of the report.
     */
    public function getName(): string
    {
        return 'Sales Report';
    }

    /**
     * Generate the sales report data.
     *
     * @return array<string, mixed>
     */
    public function generate(): array
    {
        return [
            'report_name' => $this->getName(),
            'generated_at' => now()->toIso8601String(),
            'data' => [
                'total_orders' => Order::count(),
                'total_revenue' => Order::sum('total'),
                'average_order_value' => Order::avg('total') ?? 0,
                'orders_this_month' => Order::whereMonth('created_at', now()->month)->count(),
            ],
        ];
    }
}
