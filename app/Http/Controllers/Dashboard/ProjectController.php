<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:projects_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_project', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:show_project', ['only' => ['show']]);
        $this->middleware('check.permission:edit_project', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:delete_project', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->all());
        try {
            $query = Project::with(['users', 'equipment']);

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by client name
            if ($request->filled('type')) {
                $query->byType($request->type);
            }

            // Filter by date range
            if ($request->filled(['start_date', 'end_date'])) {
                $query->byDateRange($request->start_date, $request->end_date);
            }

            // Filter by PO number
            if ($request->filled('po_num')) {
                $query->where('po_num', 'like', '%' . $request->po_num . '%');
            }

            // Search in name or description
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }

            // Sort
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            $projects = $query->paginate(15);

            return view('dashboard.projects.index', compact('projects'));
        } catch (\Exception $e) {
            Log::error('Error fetching projects: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء استرداد المشاريع.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('dashboard.projects.create');
        } catch (\Exception $e) {
            Log::error('Error loading project create form: ' . $e->getMessage());
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
                'po_num' => 'required|string|max:255|unique:projects',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'type' => 'required|in:government,commercial,residential',
                'status' => 'required|in:active,completed,pending,cancelled',
                'project_cost' => 'required|integer|min:0',
            ]);

            $project = Project::create($validatedData);

            notify('Created Project Successfully', 'success');
            return redirect()->route('projects.show', $project);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating project: ' . $e->getMessage());
            notify('An error occurred during the creation of the project.', 'error');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        try {
            $project->load([
                'projectItems.section',
                'projectItems.sectionItem',
                'projectTeams.user',
                'projectEquipment.equipment'
            ]);

            return view('dashboard.projects.show', compact('project'));
        } catch (\Exception $e) {
            Log::error('Error loading project details: ' . $e->getMessage());
            notify('An error occurred during the showing of the project.', 'error');
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        try {
            return view('dashboard.projects.edit', compact('project'));
        } catch (\Exception $e) {
            Log::error('Error loading project edit form: ' . $e->getMessage());
            notify('An error occurred during the editing of the project.', 'error');
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        try {
            $validatedData = $request->validate([
                'po_num' => 'required|string|max:255|unique:projects,po_num,' . $project->id,
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'type' => 'required|in:government,commercial,residential',
                'status' => 'required|in:active,completed,pending,cancelled',
                'project_cost' => 'required|integer|min:0',
            ]);

            $project->update($validatedData);

            notify('The project has been successfully updated.', 'success');
            return redirect()->route('projects.show', $project);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating project: ' . $e->getMessage());

            notify('An error occurred during the editing of the project.', 'error');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();

            notify('The project was successfully deleted.', 'success');
            return redirect()->route('projects.index');
        } catch (\Exception $e) {
            Log::error('Error deleting project: ' . $e->getMessage());

            notify('An error occurred during the deleting of the project.', 'error');
            return back();
        }
    }
}
