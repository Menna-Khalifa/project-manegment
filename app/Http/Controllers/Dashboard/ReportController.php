<?php
// app/Http/Controllers/Dashboard/ReportController.php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\ProjectAmer;
use App\Models\ProjectCapacity;
use App\Models\ProjectModel;
use App\Models\ProjectType;
use App\Models\ProjectVolt;
use App\Models\Report;
use App\Models\Store;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:reports_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_report', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:edit_report', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:show_report', ['only' => ['show']]);
        $this->middleware('check.permission:download_report', ['only' => ['downloadPdf']]);
        $this->middleware('check.permission:delete_report', ['only' => ['destroy']]);
    }

    public function index()
    {
        $reports = Report::with(['creator', 'store', 'projectAmer'])->orderBy('created_at', 'desc')->paginate(15);
        return view('dashboard.reports.index', compact('reports'));
    }

    public function create()
    {
        $stores = Store::orderBy('name')->get();
        $projects = ProjectAmer::orderBy('po_num')->get();
        $brands = Brand::orderBy('name')->get();
        $types = ProjectType::orderBy('name')->get();
        $capacities = ProjectCapacity::orderBy('name')->get();
        $models = ProjectModel::orderBy('name')->get();
        $volts = ProjectVolt::orderBy('value')->get();

        return view('dashboard.reports.create', compact(
            'stores',
            'projects',
            'brands',
            'types',
            'capacities',
            'models',
            'volts'
        ));
    }

    public function getReportTypeData(Request $request)
    {
        $reportType = $request->input('report_type');

        if (!$reportType) {
            return response()->json([
                'error' => 'Report type is required'
            ], 400);
        }

        return response()->json([
            'checklist_items' => Report::getChecklistItems($reportType),
            'custom_fields' => Report::getCustomFields($reportType),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:start_up_report,work_completed,sites_refer_report',
            'report_date' => 'required|date',
            'store_id' => 'nullable|exists:stores,id',
            'store_name' => 'nullable|string|max:255',
            'store_city' => 'nullable|string|max:255',
            'project_amer_id' => 'nullable|exists:project_amers,id',
            'checklist_items' => 'nullable|array',
            'custom_fields' => 'nullable|array',
            'units' => 'nullable|array',
            'units.*.brand_id' => 'nullable|exists:brands,id',
            'units.*.type_id' => 'nullable|exists:project_types,id',
            'units.*.capacity_id' => 'nullable|exists:project_capacities,id',
            'units.*.model_id' => 'nullable|exists:project_models,id',
            'units.*.volt_id' => 'nullable|exists:project_volts,id',
            'units.*.disconnect_switch_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'units.*.base_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'units.*.duct_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'project_items_used' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        // Handle project items used
        if ($request->has('project_items_used')) {
            if (!isset($validated['custom_fields'])) {
                $validated['custom_fields'] = [];
            }
            $validated['custom_fields']['project_items_used'] = $request->project_items_used;
        }

        // Handle units data and images for sites_refer_report
        if ($request->has('units') && is_array($request->units)) {
            $unitsData = [];

            foreach ($request->units as $index => $unit) {
                $unitData = [
                    'brand_id' => $unit['brand_id'] ?? null,
                    'type_id' => $unit['type_id'] ?? null,
                    'capacity_id' => $unit['capacity_id'] ?? null,
                    'model_id' => $unit['model_id'] ?? null,
                    'volt_id' => $unit['volt_id'] ?? null,
                    'disconnect_switch' => $unit['disconnect_switch'] ?? 'no',
                    'disconnect_switch_notes' => $unit['disconnect_switch_notes'] ?? null,
                    'cable_condition' => $unit['cable_condition'] ?? 'not_good',
                    'cable_capacity_id' => $unit['cable_capacity_id'] ?? null,
                    'base_condition' => $unit['base_condition'] ?? 'not_good',
                    'base_notes' => $unit['base_notes'] ?? null,
                    'duct_condition' => $unit['duct_condition'] ?? 'not_good',
                    'duct_notes' => $unit['duct_notes'] ?? null,
                    'duct_solution' => $unit['duct_solution'] ?? 'not_good',
                    'copper_pipe' => $unit['copper_pipe'] ?? 'no',
                    'copper_pipe_qty' => $unit['copper_pipe_qty'] ?? null,
                    'crane' => $unit['crane'] ?? 'no',
                    'crane_qty' => $unit['crane_qty'] ?? null,
                    'notes' => $unit['notes'] ?? null,
                ];

                // Handle unit images
                if ($request->hasFile("units.$index.disconnect_switch_image")) {
                    $path = $request->file("units.$index.disconnect_switch_image")->store('reports/units', 'public');
                    $unitData['disconnect_switch_image'] = $path;
                }

                if ($request->hasFile("units.$index.base_image")) {
                    $path = $request->file("units.$index.base_image")->store('reports/units', 'public');
                    $unitData['base_image'] = $path;
                }

                if ($request->hasFile("units.$index.duct_image")) {
                    $path = $request->file("units.$index.duct_image")->store('reports/units', 'public');
                    $unitData['duct_image'] = $path;
                }

                $unitsData[] = $unitData;
            }

            $validated['units'] = $unitsData;
        }

        // Handle main report images
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('reports', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        Report::create($validated);

        return redirect()->route('reports.index')
            ->with('success', 'Report created successfully');
    }

    public function show(Report $report)
    {
        $report->load(['creator', 'store', 'projectAmer']);
        $checklistItems = Report::getChecklistItems($report->report_type);
        $customFields = Report::getCustomFields($report->report_type);

        return view('dashboard.reports.show', compact('report', 'checklistItems', 'customFields'));
    }

    public function edit(Report $report)
    {
        $checklistItems = Report::getChecklistItems($report->report_type);
        $customFields = Report::getCustomFields($report->report_type);
        $stores = Store::orderBy('name')->get();
        $projects = ProjectAmer::orderBy('po_num')->get();
        $brands = Brand::orderBy('name')->get();
        $types = ProjectType::orderBy('name')->get();
        $capacities = ProjectCapacity::orderBy('name')->get();
        $models = ProjectModel::orderBy('name')->get();
        $volts = ProjectVolt::orderBy('value')->get();

        return view('dashboard.reports.edit', compact(
            'report',
            'checklistItems',
            'customFields',
            'stores',
            'projects',
            'brands',
            'types',
            'models',
            'volts',
            'capacities',
        ));
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:start_up_report,work_completed,sites_refer_report',
            'report_date' => 'required|date',
            'store_id' => 'nullable|exists:stores,id',
            'store_name' => 'nullable|string|max:255',
            'store_city' => 'nullable|string|max:255',
            'project_amer_id' => 'nullable|exists:project_amers,id',
            'checklist_items' => 'nullable|array',
            'custom_fields' => 'nullable|array',
            'units' => 'nullable|array',
            'units.*.brand_id' => 'nullable|exists:brands,id',
            'units.*.type_id' => 'nullable|exists:project_types,id',
            'units.*.capacity_id' => 'nullable|exists:project_capacities,id',
            'units.*.model_id' => 'nullable|exists:project_models,id',
            'units.*.volt_id' => 'nullable|exists:project_volts,id',
            'units.*.disconnect_switch_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'units.*.base_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'units.*.duct_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'project_items_used' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'delete_images' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        // Handle project items used
        if ($request->has('project_items_used')) {
            if (!isset($validated['custom_fields'])) {
                $validated['custom_fields'] = [];
            }
            $validated['custom_fields']['project_items_used'] = $request->project_items_used;
        }

        // Handle units data and images for sites_refer_report
        if ($request->has('units') && is_array($request->units)) {
            $unitsData = [];
            $existingUnits = $report->units ?? [];

            foreach ($request->units as $index => $unit) {
                $unitData = [
                    'brand_id' => $unit['brand_id'] ?? null,
                    'type_id' => $unit['type_id'] ?? null,
                    'capacity_id' => $unit['capacity_id'] ?? null,
                    'model_id' => $unit['model_id'] ?? null,
                    'volt_id' => $unit['volt_id'] ?? null,
                    'disconnect_switch' => $unit['disconnect_switch'] ?? 'no',
                    'disconnect_switch_notes' => $unit['disconnect_switch_notes'] ?? null,
                    'cable_condition' => $unit['cable_condition'] ?? 'not_good',
                    'cable_capacity_id' => $unit['cable_capacity_id'] ?? null,
                    'base_condition' => $unit['base_condition'] ?? 'not_good',
                    'base_notes' => $unit['base_notes'] ?? null,
                    'duct_condition' => $unit['duct_condition'] ?? 'not_good',
                    'duct_notes' => $unit['duct_notes'] ?? null,
                    'duct_solution' => $unit['duct_solution'] ?? 'not_good',
                    'copper_pipe' => $unit['copper_pipe'] ?? 'no',
                    'copper_pipe_qty' => $unit['copper_pipe_qty'] ?? null,
                    'crane' => $unit['crane'] ?? 'no',
                    'crane_qty' => $unit['crane_qty'] ?? null,
                    'notes' => $unit['notes'] ?? null,
                ];

                // Keep existing images if no new ones uploaded
                if (isset($existingUnits[$index])) {
                    $unitData['disconnect_switch_image'] = $existingUnits[$index]['disconnect_switch_image'] ?? null;
                    $unitData['base_image'] = $existingUnits[$index]['base_image'] ?? null;
                    $unitData['duct_image'] = $existingUnits[$index]['duct_image'] ?? null;
                }

                // Handle new unit images
                if ($request->hasFile("units.$index.disconnect_switch_image")) {
                    // Delete old image if exists
                    if (!empty($unitData['disconnect_switch_image'])) {
                        Storage::disk('public')->delete($unitData['disconnect_switch_image']);
                    }
                    $path = $request->file("units.$index.disconnect_switch_image")->store('reports/units', 'public');
                    $unitData['disconnect_switch_image'] = $path;
                }

                if ($request->hasFile("units.$index.base_image")) {
                    if (!empty($unitData['base_image'])) {
                        Storage::disk('public')->delete($unitData['base_image']);
                    }
                    $path = $request->file("units.$index.base_image")->store('reports/units', 'public');
                    $unitData['base_image'] = $path;
                }

                if ($request->hasFile("units.$index.duct_image")) {
                    if (!empty($unitData['duct_image'])) {
                        Storage::disk('public')->delete($unitData['duct_image']);
                    }
                    $path = $request->file("units.$index.duct_image")->store('reports/units', 'public');
                    $unitData['duct_image'] = $path;
                }

                $unitsData[] = $unitData;
            }

            // Delete images from removed units
            if (!empty($existingUnits) && count($existingUnits) > count($unitsData)) {
                for ($i = count($unitsData); $i < count($existingUnits); $i++) {
                    if (!empty($existingUnits[$i]['disconnect_switch_image'])) {
                        Storage::disk('public')->delete($existingUnits[$i]['disconnect_switch_image']);
                    }
                    if (!empty($existingUnits[$i]['base_image'])) {
                        Storage::disk('public')->delete($existingUnits[$i]['base_image']);
                    }
                    if (!empty($existingUnits[$i]['duct_image'])) {
                        Storage::disk('public')->delete($existingUnits[$i]['duct_image']);
                    }
                }
            }

            $validated['units'] = $unitsData;
        }

        // Handle main report image deletions
        if ($request->has('delete_images')) {
            $currentImages = $report->images ?? [];
            foreach ($request->delete_images as $imageToDelete) {
                if (in_array($imageToDelete, $currentImages)) {
                    Storage::disk('public')->delete($imageToDelete);
                    $currentImages = array_diff($currentImages, [$imageToDelete]);
                }
            }
            $validated['images'] = array_values($currentImages);
        } else {
            // Keep existing images if no deletion
            $validated['images'] = $report->images ?? [];
        }

        // Handle new main report image uploads
        if ($request->hasFile('images')) {
            $imagePaths = $validated['images'] ?? [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('reports', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        $report->update($validated);

        return redirect()->route('reports.index')
            ->with('success', 'Report updated successfully');
    }

    public function destroy(Report $report)
    {
        // Delete main report images
        if (!empty($report->images) && is_array($report->images)) {
            foreach ($report->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // Delete units images if exists
        if (!empty($report->units) && is_array($report->units)) {
            foreach ($report->units as $unit) {
                // Delete disconnect switch image
                if (!empty($unit['disconnect_switch_image'])) {
                    Storage::disk('public')->delete($unit['disconnect_switch_image']);
                }

                // Delete base image
                if (!empty($unit['base_image'])) {
                    Storage::disk('public')->delete($unit['base_image']);
                }

                // Delete duct image
                if (!empty($unit['duct_image'])) {
                    Storage::disk('public')->delete($unit['duct_image']);
                }
            }
        }

        // Delete the report record
        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Report deleted successfully');
    }

    public function downloadPdf(Report $report)
    {
        $report->load(['creator', 'store', 'projectAmer']);
        $checklistItems = Report::getChecklistItems($report->report_type);
        $customFields = Report::getCustomFields($report->report_type);

        $pdf = Pdf::loadView('dashboard.reports.pdf', compact('report', 'checklistItems', 'customFields'))
            ->setPaper('a4', 'portrait')
            ->setOption('enable-local-file-access', true);

        $filename = $report->report_type . '_' . $report->store->name . '_' . $report->report_date->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function deleteImage(Request $request, Report $report)
    {
        $imagePath = $request->input('image');

        if ($report->images && in_array($imagePath, $report->images)) {
            Storage::disk('public')->delete($imagePath);

            $images = $report->images;
            $images = array_diff($images, [$imagePath]);
            $report->images = array_values($images);
            $report->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function getProjectStore(Request $request)
    {
        $projectId = $request->input('project_id');

        if (!$projectId) {
            return response()->json(['error' => 'Project ID is required'], 400);
        }

        $project = ProjectAmer::with('store')->find($projectId);

        if (!$project || !$project->store) {
            return response()->json(['error' => 'Store not found for this project'], 404);
        }

        return response()->json([
            'store_id' => $project->store->id,
            'store_name' => $project->store->name,
            'store_uuid' => $project->store->uuid,
            'city' => $project->store->city,
        ]);
    }

    public function getProjectItems(Request $request)
    {
        $projectId = $request->input('project_id');

        if (!$projectId) {
            return response()->json(['error' => 'Project ID is required'], 400);
        }

        $project = ProjectAmer::with([
            'items.projectType',
            'items.projectModel',
            'items.projectCapacity',
            'items.projectVolt',
            'items.brand'
        ])->find($projectId);

        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $items = $project->items->map(function ($item) {
            return [
                'id' => $item->id,
                'project_dept' => $item->projectAmer->dept ?? 'N/A',
                'type' => $item->projectType->name ?? 'N/A',
                'model' => $item->projectModel->name ?? 'N/A',
                'capacity' => $item->projectCapacity->name ?? 'N/A',
                'volt' => $item->projectVolt->value ?? 'N/A',
                'brand' => $item->brand->name ?? 'N/A',
                'qty' => $item->qty,
            ];
        });

        return response()->json([
            'items' => $items
        ]);
    }
}
