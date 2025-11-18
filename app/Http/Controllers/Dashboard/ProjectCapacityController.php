<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProjectCapacity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectCapacityController extends Controller
{
    public function index(Request $request)
    {
        try {
            $capacities = ProjectCapacity::orderBy('created_at', 'desc')->paginate(15);
            return view('dashboard.project_capacities.index', compact('capacities'));
        } catch (\Exception $e) {
            Log::error('Error fetching capacities: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء الاسترداد.');
        }
    }

    public function create()
    {
        try {
            return view('dashboard.project_capacities.create');
        } catch (\Exception $e) {
            Log::error('Error loading capacity create form: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل نموذج الإنشاء.');
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $capacity = ProjectCapacity::create($data);
            notify('Created Capacity Successfully', 'success');
            return redirect()->route('project_capacities.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating capacity: ' . $e->getMessage());
            notify('An error occurred during the creation.', 'error');
            return back();
        }
    }


    public function edit(ProjectCapacity $capacity)
    {
        try {
            return view('dashboard.project_capacities.edit', compact('capacity'));
        } catch (\Exception $e) {
            Log::error('Error loading capacity edit form: ' . $e->getMessage());
            notify('An error occurred during the editing.', 'error');
            return back();
        }
    }

    public function update(Request $request, ProjectCapacity $capacity)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $capacity->update($data);
            notify('The capacity has been successfully updated.', 'success');
            return redirect()->route('project_capacities.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating capacity: ' . $e->getMessage());
            notify('An error occurred during the editing.', 'error');
            return back();
        }
    }

    public function destroy(ProjectCapacity $capacity)
    {
        try {
            $capacity->delete();
            notify('The capacity was successfully deleted.', 'success');
            return redirect()->route('project_capacities.index');
        } catch (\Exception $e) {
            Log::error('Error deleting capacity: ' . $e->getMessage());
            notify('An error occurred during the deleting.', 'error');
            return back();
        }
    }
}