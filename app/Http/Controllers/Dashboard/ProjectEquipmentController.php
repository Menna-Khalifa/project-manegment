<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProjectEquipment;
use App\Models\Project;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectEquipmentController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:project_equipments_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_project_equipment', ['only' => ['create', 'store', 'bulkAssign']]);
        $this->middleware('check.permission:show_project_equipment', ['only' => ['show']]);
        $this->middleware('check.permission:edit_project_equipment', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:edit_status_project_equipment', ['only' => ['updateStatus']]);
        $this->middleware('check.permission:delete_project_equipment', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = ProjectEquipment::with(['project', 'equipment']);

            // Filter by project
            if ($request->filled('project_id')) {
                $query->byProject($request->project_id);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->byStatus($request->status);
            }

            // Filter by equipment
            if ($request->filled('equipment_id')) {
                $query->where('equipment_id', $request->equipment_id);
            }

            // Search in project name or equipment name
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('project', function ($subQ) use ($request) {
                        $subQ->where('name', 'like', '%' . $request->search . '%');
                    })->orWhereHas('equipment', function ($subQ) use ($request) {
                        $subQ->where('name', 'like', '%' . $request->search . '%');
                    });
                });
            }

            // Sort
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            $projectEquipment = $query->paginate(50);
            $projects = Project::all(['id', 'name']);
            $equipment = Equipment::all(['id', 'name']);

            return view('dashboard.project_equipments.index', compact('projectEquipment', 'projects', 'equipment'));
        } catch (\Exception $e) {
            Log::error('Error fetching project equipment: ' . $e->getMessage());

            notify('An error occurred during the recovery of enterprise equipment.', 'error');

            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $projects = Project::active()->get(['id', 'name']);
            $equipment = Equipment::all(['id', 'name']);

            return view('dashboard.project_equipments.create', compact('projects', 'equipment'));
        } catch (\Exception $e) {
            Log::error('Error loading project equipment create form: ' . $e->getMessage());

            notify('An error occurred while loading the project equipment creation form.', 'error');

            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'project_id' => 'required|exists:projects,id',
                'equipment_id' => 'required|exists:equipment,id',
                'qty' => 'required|integer|min:1',
                'status' => 'required|in:available,unavailable,delivered',
            ]);

            // Check if equipment is already assigned to this project
            $existingAssignment = ProjectEquipment::where('project_id', $validatedData['project_id'])
                                                ->where('equipment_id', $validatedData['equipment_id'])
                                                ->first();

            if ($existingAssignment) {
                return back()->with('error', 'هذا المعدة مضافة بالفعل إلى هذا المشروع.')
                             ->withInput();
            }

            $projectEquipment = ProjectEquipment::create($validatedData);

            notify('The stomach was added to the project successfully.', 'success');

            return redirect()->route('project-equipment.show', $projectEquipment);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating project equipment: ' . $e->getMessage());

            notify('An error occurred while adding the stomach to the project.', 'error');

            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectEquipment $projectEquipment)
    {
        try {
            $projectEquipment->load(['project', 'equipment']);

            return view('dashboard.project_equipments.show', compact('projectEquipment'));
        } catch (\Exception $e) {
            Log::error('Error loading project equipment details: ' . $e->getMessage());

            notify('An error occurred while loading project equipment details.', 'error');

            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectEquipment $projectEquipment)
    {
        try {
            $projects = Project::all(['id', 'name']);
            $equipment = Equipment::all(['id', 'name']);

            return view('dashboard.project_equipments.edit', compact('projectEquipment', 'projects', 'equipment'));
        } catch (\Exception $e) {
            Log::error('Error loading project equipment edit form: ' . $e->getMessage());

            notify('An error occurred while loading the project equipment editing form.', 'error');

            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectEquipment $projectEquipment)
    {
        try {
            $validatedData = $request->validate([
                'project_id' => 'required|exists:projects,id',
                'equipment_id' => 'required|exists:equipment,id',
                'qty' => 'required|integer|min:1',
                'status' => 'required|in:available,unavailable,delivered',
            ]);

            // Check if equipment is already assigned to this project (excluding current record)
            $existingAssignment = ProjectEquipment::where('project_id', $validatedData['project_id'])
                                                ->where('equipment_id', $validatedData['equipment_id'])
                                                ->where('id', '!=', $projectEquipment->id)
                                                ->first();

            if ($existingAssignment) {
                notify('This stomach is already added to this project.', 'error');

                return back();
            }

            $projectEquipment->update($validatedData);

            notify('The project equipment has been successfully updated.', 'success');

            return redirect()->route('dashboard.project_equipments.show', $projectEquipment);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating project equipment: ' . $e->getMessage());


            notify('An error occurred while updating the project equipment.', 'error');

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectEquipment $projectEquipment)
    {
        try {
            $projectEquipment->delete();

            notify('The stomach was successfully removed from the project.', 'success');

            return redirect()->route('project-equipment.index');
        } catch (\Exception $e) {
            Log::error('Error deleting project equipment: ' . $e->getMessage());

            notify('An error occurred while removing the stomach from the project.', 'error');

            return back();
        }
    }

    /**
     * Update equipment status
     */
    public function updateStatus(Request $request, ProjectEquipment $projectEquipment)
    {
        try {
            $validatedData = $request->validate([
                'status' => 'required|in:available,unavailable,delivered',
            ]);

            $projectEquipment->update($validatedData);

            notify('The condition of the stomach has been successfully updated.', 'success');

            return back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error updating equipment status: ' . $e->getMessage());

            notify('An error occurred while updating the status of the stomach.', 'error');

            return back();
        }
    }

    /**
     * Bulk assign equipment to a project
     */
    public function bulkAssign(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'project_id' => 'required|exists:projects,id',
                'equipment_data' => 'required|array|min:1',
                'equipment_data.*.equipment_id' => 'required|exists:equipments,id',
                'equipment_data.*.qty' => 'required|integer|min:1',
                'equipment_data.*.status' => 'required|in:available,unavailable,delivered',
            ]);

            $projectId = $validatedData['project_id'];
            $equipmentData = $validatedData['equipment_data'];

            // Get existing assignments
            $existingEquipmentIds = ProjectEquipment::where('project_id', $projectId)
                                                  ->pluck('equipment_id')
                                                  ->toArray();

            // Create new assignments
            $assignments = [];
            $skipped = 0;

            foreach ($equipmentData as $data) {
                if (!in_array($data['equipment_id'], $existingEquipmentIds)) {
                    $assignments[] = [
                        'project_id' => $projectId,
                        'equipment_id' => $data['equipment_id'],
                        'qty' => $data['qty'],
                        'status' => $data['status'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    $skipped++;
                }
            }

            if (!empty($assignments)) {
                ProjectEquipment::insert($assignments);
                $message = 'تم إضافة ' . count($assignments) . ' معدة جديدة إلى المشروع.';
            } else {
                $message = 'جميع المعدات المحددة مضافة بالفعل إلى هذا المشروع.';
            }

            if ($skipped > 0) {
                $message .= ' تم تخطي ' . $skipped . ' معدة موجودة بالفعل.';
            }

            notify($message, 'success');
            return back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            notify($e->errors(), 'error');
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error bulk assigning project equipment: ' . $e->getMessage());
            notify('Error bulk assigning project equipment: ' . $e->getMessage(), 'error');
            return back()->with('error', 'حدث خطأ أثناء الإضافة المجمعة لمعدات المشروع.');
        }
    }

    /**
     * Get available equipment for a project
     */
    public function getAvailableEquipment(Request $request)
    {
        try {
            $projectId = $request->get('project_id');

            if (!$projectId) {
                return response()->json(['error' => 'Project ID is required'], 400);
            }

            $assignedEquipmentIds = ProjectEquipment::where('project_id', $projectId)
                                                  ->pluck('equipment_id')
                                                  ->toArray();

            $availableEquipment = Equipment::whereNotIn('id', $assignedEquipmentIds)
                                         ->get(['id', 'name']);

            return response()->json($availableEquipment);
        } catch (\Exception $e) {
            Log::error('Error fetching available equipment: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء استرداد المعدات المتاحة'], 500);
        }
    }
}
