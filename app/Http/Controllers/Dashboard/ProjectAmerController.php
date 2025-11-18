<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\ProjectAmer;
use App\Models\ProjectAmerItem;
use App\Models\ProjectCapacity;
use App\Models\ProjectModel;
use App\Models\ProjectType;
use App\Models\ProjectVolt;
use App\Models\Store;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProjectAmerController extends Controller
{

    // function __construct()
    // {
    //     $this->middleware('check.permission:projects_list', ['only' => ['index']]);
    //     $this->middleware('check.permission:add_project', ['only' => ['create', 'store']]);
    //     $this->middleware('check.permission:show_project', ['only' => ['show']]);
    //     $this->middleware('check.permission:edit_project', ['only' => ['edit', 'update']]);
    //     $this->middleware('check.permission:delete_project', ['only' => ['destroy']]);
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->all());
        try {
            $query = ProjectAmer::with(['user', 'store']);

            // Filter by status
            if ($request->filled('request_status')) {
                $query->byStatus($request->request_status);
            }

            // Filter by region
            if ($request->filled('region')) {
                $query->byRegion($request->region);
            }

            // Filter by department
            if ($request->filled('dept')) {
                $query->byDept($request->dept);
            }

            // Filter by priority
            if ($request->filled('priority')) {
                $query->byPriority($request->priority);
            }

            // Filter by PO number
            if ($request->filled('po_num')) {
                $query->where('po_num', 'like', '%' . $request->po_num . '%');
            }

            // Sort
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            $projects = $query->paginate(15);

            return view('dashboard.project_amers.index', compact('projects'));
        } catch (\Exception $e) {
            Log::error('Error fetching projects americana: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء استرداد المشاريع.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $users = User::all();
            $stores = Store::all();
            $typesMaintenance = ProjectType::where('type', 'maintenance')->orderBy('name')->get();
            $typesProject = ProjectType::where('type', 'project')->orderBy('name')->get();
            $capacities = ProjectCapacity::orderBy('name')->get();
            $volts = ProjectVolt::orderBy('value')->get();
            $brands = Brand::orderBy('name')->get();
            $models = ProjectModel::orderBy('name')->get();
            return view('dashboard.project_amers.create', compact('users', 'stores', 'typesMaintenance', 'typesProject', 'capacities', 'volts', 'brands', 'models'));
        } catch (\Exception $e) {
            Log::error('Error loading project americana create form: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل نموذج إنشاء المشروع.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'po_num' => 'required|string|max:255|unique:project_amers,po_num',
                'dept' => 'required|in:project,facility,maintenance,other',
                'region' => 'required|in:western_province,central_province,eastern_province,general',
                'store_id' => 'required|exists:stores,id',
                'user_id' => 'required|exists:users,id',
                'po_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048', // حسب نوع الملف المطلوب
                'priority' => 'required|in:high,medium,low',
                'date' => 'required|date',
                'request_status' => 'required|in:new_order,cancelled,under_working,completed,on_hold',
                'amount' => 'required|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            if ($request->hasFile('po_file')) {
                $fileName = time() . '_' . uniqid() . '.' . $request->file('po_file')->getClientOriginalExtension();
                $filePath = $request->file('po_file')->storeAs('project_amer', $fileName, 'public');

                $validatedData['po_file'] = $filePath;
            }


            $project_amer = ProjectAmer::create($validatedData);

            $maintenanceItems = $request->input('items_maintenance', []);
            foreach ($maintenanceItems as $item) {
                if (!empty($item['project_type_id']) && !empty($item['project_model_id']) && !empty($item['qty'])) {
                    ProjectAmerItem::create([
                        'project_amer_id' => $project_amer->id,
                        'project_type_id' => $item['project_type_id'],
                        'project_model_id' => $item['project_model_id'],
                        'qty' => (int) $item['qty'],
                    ]);
                }
            }

            $projectItems = $request->input('items_project', []);
            foreach ($projectItems as $item) {
                if (!empty($item['project_type_id']) && !empty($item['project_capacity_id']) && !empty($item['project_volt_id']) && !empty($item['brand_id']) && !empty($item['qty'])) {
                    ProjectAmerItem::create([
                        'project_amer_id' => $project_amer->id,
                        'project_type_id' => $item['project_type_id'],
                        'project_capacity_id' => $item['project_capacity_id'],
                        'project_volt_id' => $item['project_volt_id'],
                        'brand_id' => $item['brand_id'],
                        'qty' => (int) $item['qty'],
                    ]);
                }
            }

            notify('Created Project Successfully', 'success');
            return redirect()->route('project_amers.show', $project_amer);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating project americana: ' . $e->getMessage());
            notify('An error occurred during the creation of the project.', 'error');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectAmer $project_amer)
    {
        try {
            $project_amer->load([
                'user',
                'store',
                'items.projectType',
                'items.projectModel',
                'items.projectCapacity',
                'items.projectVolt',
                'items.brand',
            ]);

            return view('dashboard.project_amers.show', compact('project_amer'));
        } catch (\Exception $e) {
            Log::error('Error loading project americana details: ' . $e->getMessage());
            notify('An error occurred during the showing of the project americana.', 'error');
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectAmer $project_amer)
    {
        try {
            $users = User::all();
            $stores = Store::all();
            $typesMaintenance = ProjectType::where('type', 'maintenance')->orderBy('name')->get();
            $typesProject = ProjectType::where('type', 'project')->orderBy('name')->get();
            $capacities = ProjectCapacity::orderBy('name')->get();
            $volts = ProjectVolt::orderBy('value')->get();
            $brands = Brand::orderBy('name')->get();
            $models = ProjectModel::orderBy('name')->get();
            $project_amer->load('items');
            return view('dashboard.project_amers.edit', compact('project_amer', 'users', 'stores', 'typesMaintenance', 'typesProject', 'capacities', 'volts', 'brands', 'models'));
        } catch (\Exception $e) {
            Log::error('Error loading project americana edit form: ' . $e->getMessage());
            notify('An error occurred during the editing of the project americana.', 'error');
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectAmer $project_amer)
    {
        try {
            $validatedData = $request->validate([
                'po_num' => 'required|string|max:255|unique:project_amers,po_num,' . $project_amer->id,
                'dept' => 'required|in:project,facility,maintenance,other',
                'region' => 'required|in:western_province,central_province,eastern_province,general',
                'store_id' => 'required|exists:stores,id',
                'user_id' => 'required|exists:users,id',
                'po_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048', // حسب نوع الملف المطلوب
                'priority' => 'required|in:high,medium,low',
                'date' => 'required|date',
                'request_status' => 'required|in:new_order,cancelled,under_working,completed,on_hold',
                'amount' => 'required|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            // Handle file upload if provided
            if ($request->hasFile('po_file')) {
                // Delete old file
                if ($project_amer->po_file) {
                    Storage::disk('public')->delete($project_amer->po_file);
                }

                // Upload new file
                $validatedData['po_file'] = $request->file('po_file')->store('project_amer', 'public');
            }

            $project_amer->update($validatedData);

            $project_amer->items()->delete();

            $maintenanceItems = $request->input('items_maintenance', []);
            foreach ($maintenanceItems as $item) {
                if (!empty($item['project_type_id']) && !empty($item['project_model_id']) && !empty($item['qty'])) {
                    ProjectAmerItem::create([
                        'project_amer_id' => $project_amer->id,
                        'project_type_id' => $item['project_type_id'],
                        'project_model_id' => $item['project_model_id'],
                        'qty' => (int) $item['qty'],
                    ]);
                }
            }

            $projectItems = $request->input('items_project', []);
            foreach ($projectItems as $item) {
                if (!empty($item['project_type_id']) && !empty($item['project_capacity_id']) && !empty($item['project_volt_id']) && !empty($item['brand_id']) && !empty($item['qty'])) {
                    ProjectAmerItem::create([
                        'project_amer_id' => $project_amer->id,
                        'project_type_id' => $item['project_type_id'],
                        'project_capacity_id' => $item['project_capacity_id'],
                        'project_volt_id' => $item['project_volt_id'],
                        'brand_id' => $item['brand_id'],
                        'qty' => (int) $item['qty'],
                    ]);
                }
            }

            notify('The project has been successfully updated.', 'success');
            return redirect()->route('project_amers.show', $project_amer);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating project americana: ' . $e->getMessage());

            notify('An error occurred during the editing of the project americana.', 'error');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectAmer $project_amer)
    {
        try {

            // Delete old file
            if ($project_amer->po_file) {
                Storage::disk('public')->delete($project_amer->po_file);
            }

            $project_amer->delete();

            notify('The project was successfully deleted.', 'success');
            return redirect()->route('project_amers.index');
        } catch (\Exception $e) {
            Log::error('Error deleting project americana: ' . $e->getMessage());

            notify('An error occurred during the deleting of the project americana.', 'error');
            return back();
        }
    }

    /**
     * Download Service Completion Form as PDF
     */
    public function downloadServiceCompletionPDF(ProjectAmer $project_amer)
    {
        try {
            // Load relationships
            $project_amer->load([
                'user',
                'store',
                'items.projectType',
                'items.projectModel',
                'items.projectCapacity',
                'items.projectVolt',
                'items.brand',
            ]);

            // dd($project_amer->items);

            // Generate PDF using service completion view
            $pdf = app('dompdf.wrapper')->loadView('dashboard.report_pdf.service_completion', compact('project_amer'));

            // Set paper size and orientation
            $pdf->setPaper('a4', 'portrait');

            // Generate filename
            $filename = 'service_completion_' . $project_amer->po_num . '_' . date('Y-m-d') . '.pdf';

            // Download the PDF
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Error downloading service completion PDF: ' . $e->getMessage());
            notify('حدث خطأ أثناء تحميل ملف PDF.', 'error');
            return back();
        }
    }
}
