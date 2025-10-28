<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Group;
use App\Models\Project;
use App\Models\Equipment;
use App\Models\Section;
use App\Models\SectionItem;
use App\Models\ProjectInvoice;
use App\Models\ProjectItems;
use App\Models\ProjectEquipment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // تحديد المشاريع المسموح للمستخدم برؤيتها
        $allowedProjects = $this->getAllowedProjects($user);
        
        // إحصائيات المشاريع بناءً على الصلاحيات
        $totalProjects = $allowedProjects->count();
        $activeProjects = $allowedProjects->where('status', 'active')->count();
        $compeletedProjects = $allowedProjects->where('status', 'completed')->count();
        $pendingProjects = $allowedProjects->where('status', 'pending')->count();
        $canceledProjects = $allowedProjects->where('status', 'cancelled')->count();

        // استخراج معرفات المشاريع المسموحة
        $allowedProjectIds = $allowedProjects->pluck('id')->toArray();

        // إحصائيات المالية (فقط للمشاريع المسموحة)
        $totalProjectsCost = $this->canViewAllData($user) 
            ? Project::sum('project_cost')
            : Project::whereIn('id', $allowedProjectIds)->sum('project_cost');
            
        $totalPaidAmount = $this->canViewAllData($user)
            ? ProjectInvoice::where('status', 'approved')->sum('amount')
            : ProjectInvoice::where('status', 'approved')
                ->whereIn('project_id', $allowedProjectIds)
                ->sum('amount');
                
        $pendingPayments = $this->canViewAllData($user)
            ? ProjectInvoice::where('status', 'pending')->sum('amount')
            : ProjectInvoice::where('status', 'pending')
                ->whereIn('project_id', $allowedProjectIds)
                ->sum('amount');
                
        $totalRemainingAmount = $totalProjectsCost - $totalPaidAmount;

        // إحصائيات اليوم (مفلترة حسب المشاريع المسموحة)
        $todayProjects = $this->canViewAllData($user)
            ? Project::whereDate('created_at', Carbon::today())->count()
            : Project::whereIn('id', $allowedProjectIds)
                ->whereDate('created_at', Carbon::today())->count();
                
        $todayApprovedPayments = $this->canViewAllData($user)
            ? ProjectInvoice::where('status', 'approved')
                ->whereDate('approved_at', Carbon::today())
                ->sum('amount')
            : ProjectInvoice::where('status', 'approved')
                ->whereIn('project_id', $allowedProjectIds)
                ->whereDate('approved_at', Carbon::today())
                ->sum('amount');

        // إحصائيات المستخدمين والفرق (حسب الصلاحيات)
        $totalUsers = $this->canViewAllUsers($user) 
            ? User::where('type', 'user')->count()
            : $this->getUsersInUserProjects($user)->count();
            
        $activeUsers = $this->canViewAllUsers($user)
            ? User::where('status', 'active')->count()
            : $this->getUsersInUserProjects($user)->where('status', 'active')->count();
            
        $totalGroups = $this->canViewAllData($user) ? Group::count() : 0;
        $totalEquipments = $this->canViewAllData($user) ? Equipment::count() : 0;
        $totalSections = $this->canViewAllData($user) ? Section::count() : 0;
        $totalSectionItems = $this->canViewAllData($user) ? SectionItem::count() : 0;

        // المشاريع حسب النوع (مفلترة)
        $projectsByType = $this->canViewAllData($user)
            ? Project::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type')
            : Project::whereIn('id', $allowedProjectIds)
                ->select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type');

        // أحدث المشاريع (مفلترة)
        $recentProjects = $this->canViewAllData($user)
            ? Project::with(['users', 'invoices'])
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
            : Project::whereIn('id', $allowedProjectIds)
                ->with(['users', 'invoices'])
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

        // المعدات المستخدمة في المشاريع (مفلترة)
        $equipmentUsage = $this->canViewAllData($user)
            ? ProjectEquipment::with('equipment', 'project')
                ->select('equipment_id', DB::raw('SUM(qty) as total_used'))
                ->groupBy('equipment_id')
                ->orderBy('total_used', 'desc')
                ->limit(5)
                ->get()
            : ProjectEquipment::with('equipment', 'project')
                ->whereIn('project_id', $allowedProjectIds)
                ->select('equipment_id', DB::raw('SUM(qty) as total_used'))
                ->groupBy('equipment_id')
                ->orderBy('total_used', 'desc')
                ->limit(5)
                ->get();

        // الفواتير المعلقة (مفلترة)
        $pendingInvoices = $this->canViewAllData($user)
            ? ProjectInvoice::with('project')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
            : ProjectInvoice::with('project')
                ->whereIn('project_id', $allowedProjectIds)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

        // إحصائيات أداء المشاريع (آخر 6 شهور) - مفلترة
        $monthlyProjectsData = [];
        $monthlyRevenueData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M Y');

            if ($this->canViewAllData($user)) {
                $monthlyProjects = Project::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
                    
                $monthlyRevenue = ProjectInvoice::where('status', 'approved')
                    ->whereYear('approved_at', $month->year)
                    ->whereMonth('approved_at', $month->month)
                    ->sum('amount');
            } else {
                $monthlyProjects = Project::whereIn('id', $allowedProjectIds)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
                    
                $monthlyRevenue = ProjectInvoice::whereIn('project_id', $allowedProjectIds)
                    ->where('status', 'approved')
                    ->whereYear('approved_at', $month->year)
                    ->whereMonth('approved_at', $month->month)
                    ->sum('amount');
            }

            $monthlyProjectsData[] = [
                'month' => $monthName,
                'count' => $monthlyProjects
            ];

            $monthlyRevenueData[] = [
                'month' => $monthName,
                'amount' => $monthlyRevenue
            ];
        }

        // إحصائيات تقدم المشاريع (مفلترة)
        $projectsProgressQuery = $this->canViewAllData($user)
            ? Project::with('projectItems')
            : Project::whereIn('id', $allowedProjectIds)->with('projectItems');
            
        $projectsProgress = $projectsProgressQuery->get()->map(function ($project) {
            $totalItems = $project->projectItems->sum('qty');
            $executedItems = $project->projectItems->sum('executed_qty');
            $progress = $totalItems > 0 ? ($executedItems / $totalItems) * 100 : 0;

            return [
                'project' => $project,
                'progress' => round($progress, 2),
                'total_items' => $totalItems,
                'executed_items' => $executedItems
            ];
        })->sortByDesc('progress')->take(5);

        // أفضل المجموعات (حسب الصلاحيات)
        $topGroups = $this->canViewAllData($user)
            ? Group::withCount(['users' => function ($query) {
                $query->whereHas('projectTeams.project');
            }])->orderBy('users_count', 'desc')->limit(5)->get()
            : collect(); // مجموعة فارغة للمستخدمين العاديين

        $todayInvoices = $this->canViewAllData($user)
            ? ProjectInvoice::whereDate('created_at', Carbon::today())->count()
            : ProjectInvoice::whereIn('project_id', $allowedProjectIds)
                ->whereDate('created_at', Carbon::today())->count();
                
        $approvedProjects = $this->canViewAllData($user)
            ? ProjectInvoice::where('status', 'approved')->count()
            : ProjectInvoice::whereIn('project_id', $allowedProjectIds)
                ->where('status', 'approved')->count();

        return view('dashboard.index', compact(
            'totalProjects',
            'activeProjects',
            'approvedProjects',
            'compeletedProjects',
            'pendingProjects',
            'canceledProjects',
            'totalProjectsCost',
            'totalPaidAmount',
            'pendingPayments',
            'totalRemainingAmount',
            'todayProjects',
            'todayInvoices',
            'todayApprovedPayments',
            'totalUsers',
            'activeUsers',
            'totalGroups',
            'totalEquipments',
            'totalSections',
            'totalSectionItems',
            'projectsByType',
            'recentProjects',
            'equipmentUsage',
            'pendingInvoices',
            'monthlyProjectsData',
            'monthlyRevenueData',
            'projectsProgress',
            'topGroups'
        ));
    }

    /**
     * تحديد المشاريع المسموح للمستخدم برؤيتها
     */
    private function getAllowedProjects($user)
    {
        // إذا كان المستخدم Admin أو لديه صلاحية عرض جميع المشاريع
        if ($this->canViewAllData($user)) {
            return Project::all();
        }

        // إذا كان المستخدم عادي، إرجاع المشاريع التي يشارك فيها فقط
        return Project::whereHas('projectTeams', function($query) use ($user) {
            $query->whereHas('user', function($subQuery) use ($user) {
                $subQuery->where('users.id', $user->id);
            });
        })->get();
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه عرض جميع البيانات
     */
    private function canViewAllData($user)
    {
        return $user->hasRole('admin') || 
               $user->can('projects_list') || 
               $user->type === 'admin';
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه عرض جميع المستخدمين
     */
    private function canViewAllUsers($user)
    {
        return $user->hasRole('admin') || 
               $user->can('users_list') || 
               $user->type === 'admin';
    }

    /**
     * الحصول على المستخدمين في مشاريع المستخدم الحالي
     */
    private function getUsersInUserProjects($user)
    {
        $userProjectIds = $this->getAllowedProjects($user)->pluck('id')->toArray();
        
        return User::whereHas('projectTeams', function($query) use ($userProjectIds) {
            $query->whereIn('project_id', $userProjectIds);
        });
    }

    /**
     * الحصول على بيانات الرسوم البيانية للمشاريع (مفلترة)
     */
    public function getProjectsChartData()
    {
        $user = Auth::user();
        $allowedProjectIds = $this->getAllowedProjects($user)->pluck('id')->toArray();

        $query = $this->canViewAllData($user)
            ? Project::select('status', DB::raw('count(*) as count'))
            : Project::whereIn('id', $allowedProjectIds)
                ->select('status', DB::raw('count(*) as count'));

        $data = $query->groupBy('status')->get();

        return response()->json($data);
    }

    /**
     * الحصول على بيانات الأداء المالي (مفلترة)
     */
    public function getFinancialPerformance()
    {
        $user = Auth::user();
        $allowedProjectIds = $this->getAllowedProjects($user)->pluck('id')->toArray();
        $last6Months = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');

            if ($this->canViewAllData($user)) {
                $totalCost = Project::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('project_cost');

                $paidAmount = ProjectInvoice::where('status', 'approved')
                    ->whereYear('approved_at', $month->year)
                    ->whereMonth('approved_at', $month->month)
                    ->sum('amount');
            } else {
                $totalCost = Project::whereIn('id', $allowedProjectIds)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('project_cost');

                $paidAmount = ProjectInvoice::whereIn('project_id', $allowedProjectIds)
                    ->where('status', 'approved')
                    ->whereYear('approved_at', $month->year)
                    ->whereMonth('approved_at', $month->month)
                    ->sum('amount');
            }

            $last6Months[] = [
                'month' => $monthName,
                'total_cost' => $totalCost,
                'paid_amount' => $paidAmount
            ];
        }

        return response()->json($last6Months);
    }

    /**
     * الحصول على بيانات الرسوم البيانية (مفلترة)
     */
    public function getChartsData()
    {
        $user = Auth::user();
        $allowedProjectIds = $this->getAllowedProjects($user)->pluck('id')->toArray();

        // بيانات المشاريع (مفلترة)
        if ($this->canViewAllData($user)) {
            $projectsData = [
                'active' => Project::where('status', 'active')->count(),
                'delivered' => Project::where('status', 'delivered')->count(),
                'pending' => Project::where('status', 'pending')->count(),
                'cancelled' => Project::where('status', 'cancelled')->count(),
                'total' => Project::count()
            ];
        } else {
            $projectsData = [
                'active' => Project::whereIn('id', $allowedProjectIds)->where('status', 'active')->count(),
                'delivered' => Project::whereIn('id', $allowedProjectIds)->where('status', 'delivered')->count(),
                'pending' => Project::whereIn('id', $allowedProjectIds)->where('status', 'pending')->count(),
                'cancelled' => Project::whereIn('id', $allowedProjectIds)->where('status', 'cancelled')->count(),
                'total' => count($allowedProjectIds)
            ];
        }

        // بيانات المعدات المستخدمة (مفلترة)
        $equipmentQuery = $this->canViewAllData($user)
            ? ProjectEquipment::with('equipment')
            : ProjectEquipment::whereIn('project_id', $allowedProjectIds)->with('equipment');

        $equipmentData = $equipmentQuery
            ->select('equipment_id', DB::raw('SUM(qty) as total_used'))
            ->groupBy('equipment_id')
            ->orderBy('total_used', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->equipment->name,
                    'used' => $item->total_used
                ];
            });

        // بيانات المدفوعات الشهرية (آخر 6 شهور) - مفلترة
        $monthlyPayments = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->locale('ar')->format('F');

            if ($this->canViewAllData($user)) {
                $paidAmount = ProjectInvoice::where('status', 'approved')
                    ->whereYear('approved_at', $month->year)
                    ->whereMonth('approved_at', $month->month)
                    ->sum('amount');

                $totalCost = Project::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('project_cost');
            } else {
                $paidAmount = ProjectInvoice::whereIn('project_id', $allowedProjectIds)
                    ->where('status', 'approved')
                    ->whereYear('approved_at', $month->year)
                    ->whereMonth('approved_at', $month->month)
                    ->sum('amount');

                $totalCost = Project::whereIn('id', $allowedProjectIds)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('project_cost');
            }

            $monthlyPayments[] = [
                'month' => $monthName,
                'paid' => $paidAmount,
                'total' => $totalCost
            ];
        }

        // بيانات المشاريع حسب النوع (مفلترة)
        $projectsByTypeQuery = $this->canViewAllData($user)
            ? Project::select('type', DB::raw('count(*) as count'))
            : Project::whereIn('id', $allowedProjectIds)->select('type', DB::raw('count(*) as count'));

        $projectsByType = $projectsByTypeQuery
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                $typeName = match ($item->type) {
                    'government' => 'حكومي',
                    'commercial' => 'تجاري',
                    'residential' => 'سكني',
                    default => 'أخرى'
                };
                return [$typeName => $item->count];
            });

        return response()->json([
            'projects' => $projectsData,
            'equipment' => $equipmentData,
            'payments' => $monthlyPayments,
            'types' => $projectsByType,
            'updated_at' => now()->format('Y-m-d H:i:s'),
            'user_type' => $this->canViewAllData($user) ? 'admin' : 'user'
        ]);
    }
}