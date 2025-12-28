<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProjectModel;
use App\Models\ProjectType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectModelController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:project_models_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_project_model', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:edit_project_model', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:delete_project_model', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $models = ProjectModel::with('projectType')->orderBy('created_at', 'desc')->paginate(50);
            return view('dashboard.project_models.index', compact('models'));
        } catch (\Exception $e) {
            Log::error('Error fetching models: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء الاسترداد.');
        }
    }

    public function create()
    {
        try {
            $types = ProjectType::where('type', 'maintenance')->orderBy('name')->get();
            return view('dashboard.project_models.create', compact('types'));
        } catch (\Exception $e) {
            Log::error('Error loading model create form: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل نموذج الإنشاء.');
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'project_type_id' => 'required|exists:project_types,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $model = ProjectModel::create($data);
            notify('Created Model Successfully', 'success');
            return redirect()->route('project_models.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating model: ' . $e->getMessage());
            notify('An error occurred during the creation.', 'error');
            return back();
        }
    }


    public function edit(ProjectModel $model)
    {
        try {
            $types = ProjectType::where('type', 'maintenance')->orderBy('name')->get();
            return view('dashboard.project_models.edit', compact('model', 'types'));
        } catch (\Exception $e) {
            Log::error('Error loading model edit form: ' . $e->getMessage());
            notify('An error occurred during the editing.', 'error');
            return back();
        }
    }

    public function update(Request $request, ProjectModel $model)
    {
        try {
            $data = $request->validate([
                'project_type_id' => 'required|exists:project_types,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $model->update($data);
            notify('The model has been successfully updated.', 'success');
            return redirect()->route('project_models.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating model: ' . $e->getMessage());
            notify('An error occurred during the editing.', 'error');
            return back();
        }
    }

    public function destroy(ProjectModel $model)
    {
        try {
            $model->delete();
            notify('The model was successfully deleted.', 'success');
            return redirect()->route('project_models.index');
        } catch (\Exception $e) {
            Log::error('Error deleting model: ' . $e->getMessage());
            notify('An error occurred during the deleting.', 'error');
            return back();
        }
    }
}
