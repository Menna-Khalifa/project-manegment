<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Brand;
use App\Models\Store;
use App\Models\ProjectAmer;
use App\Models\InvoiceAmer;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardAmerController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        // الحصول على قائمة السنوات المتاحة
        $availableYears = $this->getAvailableYears();

        // إحصائيات عامة
        $stats = [
            'total_brands' => Brand::count(),
            'total_stores' => Store::count(),
            'total_projects' => ProjectAmer::whereYear('date', $year)->count(),
            'total_invoices' => InvoiceAmer::whereYear('date', $year)->count(),
            'total_reports' => Report::whereYear('created_at', $year)->count(),
        ];

        // إحصائيات المشاريع حسب الحالة
        $projectsByStatus = ProjectAmer::whereYear('date', $year)
            ->select('request_status', DB::raw('count(*) as count'))
            ->groupBy('request_status')
            ->get()
            ->pluck('count', 'request_status')
            ->toArray();

        // إحصائيات المشاريع حسب الأولوية
        $projectsByPriority = ProjectAmer::whereYear('date', $year)
            ->select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority')
            ->toArray();

        // إحصائيات المشاريع حسب القسم
        $projectsByDept = ProjectAmer::whereYear('date', $year)
            ->select('dept', DB::raw('count(*) as count'))
            ->groupBy('dept')
            ->get()
            ->pluck('count', 'dept')
            ->toArray();

        // إحصائيات المشاريع حسب المنطقة
        $projectsByRegion = ProjectAmer::whereYear('date', $year)
            ->select('region', DB::raw('count(*) as count'))
            ->groupBy('region')
            ->get()
            ->pluck('count', 'region')
            ->toArray();

        // إحصائيات الفواتير حسب الحالة
        $invoicesByStatus = InvoiceAmer::whereYear('date', $year)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // إجمالي المبالغ المالية
        $financialStats = [
            'total_projects_amount' => ProjectAmer::whereYear('date', $year)->sum('amount'),
            'total_invoices_amount' => InvoiceAmer::whereYear('date', $year)->sum('amount'),
            'paid_invoices_amount' => InvoiceAmer::whereYear('date', $year)
                ->where('status', 'paid')->sum('amount'),
            'pending_invoices_amount' => InvoiceAmer::whereYear('date', $year)
                ->where('status', 'pending')->sum('amount'),
        ];

        // إحصائيات التقارير حسب النوع
        $reportsByType = Report::whereYear('created_at', $year)
            ->select('report_type', DB::raw('count(*) as count'))
            ->groupBy('report_type')
            ->get()
            ->pluck('count', 'report_type')
            ->toArray();

        // البيانات الشهرية للمشاريع
        $monthlyProjects = ProjectAmer::whereYear('date', $year)
            ->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('count(*) as count'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();

        // البيانات الشهرية للفواتير
        $monthlyInvoices = InvoiceAmer::whereYear('date', $year)
            ->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('count(*) as count'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();

        // البيانات الشهرية للتقارير
        $monthlyReports = Report::whereYear('created_at', $year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();

        // أحدث المشاريع
        $recentProjects = ProjectAmer::with(['store', 'user'])
            ->whereYear('date', $year)
            ->latest()
            ->take(10)
            ->get();

        // أحدث الفواتير
        $recentInvoices = InvoiceAmer::with(['projectAmer.store', 'createdBy'])
            ->whereYear('date', $year)
            ->latest()
            ->take(5)
            ->get();

        // أحدث التقارير
        $recentReports = Report::with(['store', 'projectAmer', 'creator'])
            ->whereYear('created_at', $year)
            ->latest()
            ->take(5)
            ->get();

        // أعلى 5 علامات من حيث عدد المشاريع
        $topBrandsByProjects = Brand::select('brands.*')
            ->where('brands.type', 'store')
            ->join('stores', 'brands.id', '=', 'stores.brand_id')
            ->join('project_amers', 'stores.id', '=', 'project_amers.store_id')
            ->whereYear('project_amers.date', $year)
            ->selectRaw('COUNT(DISTINCT project_amers.id) as project_amers_count')
            ->groupBy('brands.id', 'brands.name', 'brands.description', 'brands.type', 'brands.created_at', 'brands.updated_at')
            ->having('project_amers_count', '>', 0)
            ->orderByDesc('project_amers_count')
            ->take(5)
            ->get();

        // أعلى 5 علامات من حيث قيمة المشاريع
        $topBrandsByAmount = Brand::select('brands.*')
            ->where('brands.type', 'store')
            ->join('stores', 'brands.id', '=', 'stores.brand_id')
            ->join('project_amers', 'stores.id', '=', 'project_amers.store_id')
            ->whereYear('project_amers.date', $year)
            ->selectRaw('SUM(project_amers.amount) as total_amount')
            ->groupBy('brands.id', 'brands.name', 'brands.description', 'brands.type', 'brands.created_at', 'brands.updated_at')
            ->orderByDesc('total_amount')
            ->take(5)
            ->get();

        return view('dashboard.index_amer', compact(
            'stats',
            'projectsByStatus',
            'projectsByPriority',
            'projectsByDept',
            'projectsByRegion',
            'invoicesByStatus',
            'financialStats',
            'reportsByType',
            'monthlyProjects',
            'monthlyInvoices',
            'monthlyReports',
            'recentProjects',
            'recentInvoices',
            'recentReports',
            'topBrandsByProjects',
            'topBrandsByAmount',
            'year',
            'availableYears'
        ));
    }

    private function getAvailableYears()
    {
        $years = collect();

        // الحصول على أقدم سنة من المشاريع
        $oldestProject = ProjectAmer::orderBy('date')->first();
        if ($oldestProject) {
            $startYear = Carbon::parse($oldestProject->date)->year;
            $currentYear = now()->year;

            for ($year = $currentYear; $year >= $startYear; $year--) {
                $years->push($year);
            }
        } else {
            $years->push(now()->year);
        }

        return $years;
    }

    // API endpoint للحصول على البيانات بصيغة JSON (اختياري)
    public function getStats(Request $request)
    {
        $year = $request->get('year', now()->year);

        return response()->json([
            'projects' => ProjectAmer::whereYear('date', $year)->count(),
            'invoices' => InvoiceAmer::whereYear('date', $year)->count(),
            'reports' => Report::whereYear('created_at', $year)->count(),
            'total_amount' => ProjectAmer::whereYear('date', $year)->sum('amount'),
        ]);
    }
}
