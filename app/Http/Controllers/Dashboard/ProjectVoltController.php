<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProjectVolt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectVoltController extends Controller
{
    public function index(Request $request)
    {
        try {
            $volts = ProjectVolt::orderBy('created_at', 'desc')->paginate(15);
            return view('dashboard.project_volts.index', compact('volts'));
        } catch (\Exception $e) {
            Log::error('Error fetching volts: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء الاسترداد.');
        }
    }

    public function create()
    {
        try {
            return view('dashboard.project_volts.create');
        } catch (\Exception $e) {
            Log::error('Error loading volt create form: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل نموذج الإنشاء.');
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'value' => 'required|string|max:255',
            ]);

            $volt = ProjectVolt::create($data);
            notify('Created Volt Successfully', 'success');
            return redirect()->route('project_volts.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating volt: ' . $e->getMessage());
            notify('An error occurred during the creation.', 'error');
            return back();
        }
    }

    public function edit(ProjectVolt $volt)
    {
        try {
            return view('dashboard.project_volts.edit', compact('volt'));
        } catch (\Exception $e) {
            Log::error('Error loading volt edit form: ' . $e->getMessage());
            notify('An error occurred during the editing.', 'error');
            return back();
        }
    }

    public function update(Request $request, ProjectVolt $volt)
    {
        try {
            $data = $request->validate([
                'value' => 'required|string|max:255',
            ]);

            $volt->update($data);
            notify('The volt has been successfully updated.', 'success');
            return redirect()->route('project_volts.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating volt: ' . $e->getMessage());
            notify('An error occurred during the editing.', 'error');
            return back();
        }
    }

    public function destroy(ProjectVolt $volt)
    {
        try {
            $volt->delete();
            notify('The volt was successfully deleted.', 'success');
            return redirect()->route('project_volts.index');
        } catch (\Exception $e) {
            Log::error('Error deleting volt: ' . $e->getMessage());
            notify('An error occurred during the deleting.', 'error');
            return back();
        }
    }
}