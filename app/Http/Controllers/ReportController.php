<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Response;
use App\Models\Product;
use App\Models\Customer;
use App\Models\InventoryItem;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Get today's sales
        $todaySales = Sale::whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        // Get monthly revenue
        $monthlyRevenue = Sale::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->sum('total_amount');

        // Get low stock items
        $lowStockCount = Product::whereHas('inventoryItem', function ($query) {
            $query->where('quantity', '<=', 10);
        })->count();

        // Get active customers
        $activeCustomers = Customer::whereHas('sales', function ($query) {
            $query->whereBetween('created_at', [
                Carbon::now()->subMonth(),
                Carbon::now()
            ]);
        })->count();

        return view('reports', compact(
            'todaySales',
            'monthlyRevenue',
            'lowStockCount',
            'activeCustomers'
        ));
    }

    public function salesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $groupBy = $request->input('group_by', 'day');
        $salesData = $this->getSalesData($startDate, $endDate, $groupBy);
        $totalSales = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $totalTransactions = Sale::whereBetween('created_at', [$startDate, $endDate])->count();
        $averageTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;
        $topProducts = $this->getTopSellingProducts($startDate, $endDate, 10);
        $paymentMethods = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();
        return view('reports.sales', compact(
            'salesData',
            'totalSales',
            'totalTransactions',
            'averageTransaction',
            'topProducts',
            'paymentMethods',
            'startDate',
            'endDate',
            'groupBy'
        ));
    }

    public function financialReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $salesData = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('SUM(tax_amount) as total_tax'),
                DB::raw('SUM(discount_amount) as total_discounts'),
                DB::raw('COUNT(*) as total_transactions')
            )
            ->first();
        $cogs = SaleItem::whereHas('sale', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->sum(DB::raw('sale_items.quantity * products.purchase_price'));
        $grossProfit = $salesData->total_revenue - $cogs;
        $profitMargin = $salesData->total_revenue > 0 ? ($grossProfit / $salesData->total_revenue) * 100 : 0;
        $dailyRevenue = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as transactions')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        return view('reports.financial', compact(
            'salesData',
            'cogs',
            'grossProfit',
            'profitMargin',
            'dailyRevenue',
            'startDate',
            'endDate'
        ));
    }

    private function getSalesData($startDate, $endDate, $groupBy)
    {
        $dateFormat = match ($groupBy) {
            'day' => '%Y-%m-%d',
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            default => '%Y-%m-%d'
        };
        return Sale::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '$dateFormat') as period"),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('AVG(total_amount) as average_sale')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    private function getTopSellingProducts($startDate, $endDate, $limit = 10)
    {
        return SaleItem::whereHas('sale', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit($limit)
            ->get();
    }

    public function inventoryReport()
    {
        $inventoryData = InventoryItem::with(['product', 'product.category'])
            ->get()
            ->map(function ($item) {
                $stockValue = $item->quantity * $item->product->purchase_price;
                return [
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'alert_threshold' => $item->alert_threshold,
                    'location' => $item->location,
                    'stock_value' => $stockValue,
                    'status' => $this->getStockStatus($item->quantity, $item->alert_threshold),
                ];
            });
        $totalStockValue = $inventoryData->sum('stock_value');
        $lowStockCount = $inventoryData->where('status', 'Low Stock')->count();
        $outOfStockCount = $inventoryData->where('status', 'Out of Stock')->count();
        $stockByCategory = $inventoryData->groupBy('product.category.name')
            ->map(function ($items, $category) {
                $itemsCollection = collect($items);
                return [
                    'category' => $category ?: 'Uncategorized',
                    'total_items' => $itemsCollection->count(),
                    'total_quantity' => $itemsCollection->sum(fn($item) => $item['quantity']),
                    'total_value' => $itemsCollection->sum(fn($item) => $item['stock_value']),
                ];
            })->values();
        return view('reports.inventory', compact(
            'inventoryData',
            'totalStockValue',
            'lowStockCount',
            'outOfStockCount',
            'stockByCategory'
        ));
    }

    private function getStockStatus($quantity, $threshold)
    {
        if ($quantity == 0) return 'Out of Stock';
        if ($quantity <= $threshold) return 'Low Stock';
        return 'In Stock';
    }

    public function customerReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfYear());
        $endDate = $request->input('end_date', Carbon::now()->endOfYear());
        $topCustomers = Customer::withSum(['sales' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }], 'total_amount')
            ->withCount(['sales' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->having('sales_sum_total_amount', '>', 0)
            ->orderBy('sales_sum_total_amount', 'desc')
            ->limit(20)
            ->get();
        $newCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as new_customers')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::whereHas('sales', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->count();
        $averageOrderValue = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('customer_id')
            ->avg('total_amount');
        return view('reports.customers', compact(
            'topCustomers',
            'newCustomers',
            'totalCustomers',
            'activeCustomers',
            'averageOrderValue',
            'startDate',
            'endDate'
        ));
    }

    public function productPerformance(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $productPerformance = Product::withSum(['saleItems' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('sale', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }], 'quantity')
            ->withSum(['saleItems' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('sale', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                });
            }], 'subtotal')
            ->with(['category', 'inventoryItem'])
            ->get()
            ->map(function ($product) {
                $quantitySold = $product->sale_items_sum_quantity ?? 0;
                $revenue = $product->sale_items_sum_subtotal ?? 0;
                $profit = $quantitySold * ($product->selling_price - $product->purchase_price);
                return [
                    'product' => $product,
                    'quantity_sold' => $quantitySold,
                    'revenue' => $revenue,
                    'profit' => $profit,
                    'current_stock' => $product->inventoryItem->quantity ?? 0,
                    'stock_turns' => $product->inventoryItem && $product->inventoryItem->quantity > 0
                        ? $quantitySold / $product->inventoryItem->quantity
                        : 0,
                ];
            })
            ->sortByDesc('revenue');
        return view('reports.product-performance', compact('productPerformance', 'startDate', 'endDate'));
    }

    public function exportSalesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        $sales = Sale::with(['customer', 'user', 'items.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $filename = 'sales_report_' . $startDate . '_to_' . $endDate . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        $callback = function () use ($sales) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Invoice Number',
                'Date',
                'Customer',
                'Cashier',
                'Subtotal',
                'Tax',
                'Discount',
                'Total',
                'Payment Method'
            ]);
            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->invoice_number,
                    $sale->created_at->format('Y-m-d H:i:s'),
                    $sale->customer ? $sale->customer->name : 'Walk-in',
                    $sale->user ? $sale->user->name : 'System',
                    $sale->subtotal,
                    $sale->tax_amount,
                    $sale->discount_amount,
                    $sale->total_amount,
                    $sale->payment_method,
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportInventoryReport()
    {
        $inventory = InventoryItem::with(['product', 'product.category'])->get();
        $filename = 'inventory_report_' . Carbon::now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        $callback = function () use ($inventory) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'SKU',
                'Product Name',
                'Category',
                'Current Stock',
                'Alert Threshold',
                'Location',
                'Purchase Price',
                'Selling Price',
                'Stock Value',
                'Status'
            ]);
            foreach ($inventory as $item) {
                $stockValue = $item->quantity * $item->product->purchase_price;
                $status = $this->getStockStatus($item->quantity, $item->alert_threshold);
                fputcsv($file, [
                    $item->product->sku,
                    $item->product->name,
                    $item->product->category ? $item->product->category->name : 'Uncategorized',
                    $item->quantity,
                    $item->alert_threshold,
                    $item->location,
                    $item->product->purchase_price,
                    $item->product->selling_price,
                    $stockValue,
                    $status,
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
