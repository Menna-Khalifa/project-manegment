<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProjectType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectTypeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $types = ProjectType::where('type', 'project')->orderBy('created_at', 'desc')->paginate(15);
            return view('dashboard.project_types.index', compact('types'));
        } catch (\Exception $e) {
            Log::error('Error fetching project types: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء استرداد الأنواع.');
        }
    }
    
    public function maintenanceIndex(Request $request)
    {
        try {
            $types = ProjectType::where('type', 'maintenance')->orderBy('created_at', 'desc')->paginate(15);
            return view('dashboard.project_types.maintenance_index', compact('types'));
        } catch (\Exception $e) {
            Log::error('Error fetching project types: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء استرداد الأنواع.');
        }
    }

    public function create()
    {
        try {
            return view('dashboard.project_types.create');
        } catch (\Exception $e) {
            Log::error('Error loading project type create form: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل نموذج الإنشاء.');
        }
    }
    
    public function maintenanceCreate()
    {
        try {
            return view('dashboard.project_types.maintenance_create');
        } catch (\Exception $e) {
            Log::error('Error loading project type create form: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل نموذج الإنشاء.');
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:project,maintenance',
            ]);

            $type = ProjectType::create($data);
            notify('Created Type Successfully', 'success');
            if ($type->type == 'project') {
                return redirect()->route('project_types.index');
            } else {
                return redirect()->route('maintenance_types.index');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating project type: ' . $e->getMessage());
            notify('An error occurred during the creation.', 'error');
            return back();
        }
    }

    public function edit(ProjectType $type)
    {
        try {
            return view('dashboard.project_types.edit', compact('type'));
        } catch (\Exception $e) {
            Log::error('Error loading project type edit form: ' . $e->getMessage());
            notify('An error occurred during the editing.', 'error');
            return back();
        }
    }

    public function update(Request $request, ProjectType $type)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:project,maintenance',
            ]);

            $type->update($data);
            notify('The type has been successfully updated.', 'success');
            if ($type->type == 'project') {
                return redirect()->route('project_types.index');
            } else {
                return redirect()->route('maintenance_types.index');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating project type: ' . $e->getMessage());
            notify('An error occurred during the editing.', 'error');
            return back();
        }
    }

    public function destroy(ProjectType $type)
    {
        try {
            $type->delete();
            notify('The type was successfully deleted.', 'success');
            return redirect()->route('project_types.index');
        } catch (\Exception $e) {
            Log::error('Error deleting project type: ' . $e->getMessage());
            notify('An error occurred during the deleting.', 'error');
            return back();
        }
    }
}