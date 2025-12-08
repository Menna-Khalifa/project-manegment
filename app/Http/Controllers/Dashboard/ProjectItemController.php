<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Project;
use App\Models\Section;
use App\Models\SectionItem;
use App\Models\ProjectItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ProjectItemController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:project_items_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_project_item', ['only' => ['create', 'store', 'bulkAssign']]);
        $this->middleware('check.permission:edit_project_item', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:edit_received_project_item', ['only' => ['updateReceivedQty']]);
        $this->middleware('check.permission:edit_executed_project_item', ['only' => ['updateExecutedQty']]);
        $this->middleware('check.permission:show_project_item', ['only' => ['show']]);
        $this->middleware('check.permission:delete_project_item', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = ProjectItems::with(['project', 'section', 'sectionItem']);

            // Filter by project
            if ($request->filled('project_id')) {
                $query->byProject($request->project_id);
            }

            // Filter by section
            if ($request->filled('section_id')) {
                $query->bySection($request->section_id);
            }

            // Filter pending delivery
            if ($request->filled('pending_delivery') && $request->pending_delivery == '1') {
                $query->pendingDelivery();
            }

            // Filter pending execution
            if ($request->filled('pending_execution') && $request->pending_execution == '1') {
                $query->pendingExecution();
            }

            // Filter by expected arrival date range
            if ($request->filled(['arrival_from', 'arrival_to'])) {
                $query->whereBetween('expected_arrival', [$request->arrival_from, $request->arrival_to]);
            }

            // Search
            if ($request->filled('search')) {
                $query->whereHas('project', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            }

            // Sort
            $sortBy = $request->get('sort_by', 'expected_arrival');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            $projectItems = $query->paginate('15');
            $projects = Project::all(['id', 'name']);
            $sections = Section::all(['id', 'name']);

            return view('dashboard.project_items.index', compact('projectItems', 'projects', 'sections'));
        } catch (\Exception $e) {
            Log::error('Error fetching project items: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء استرداد عناصر المشروع.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $projects = Project::active()->get(['id', 'name']);
            $sections = Section::all(['id', 'name']);
            $sectionItems = SectionItem::all(['id', 'name', 'section_id']);

            return view('dashboard.project_items.create', compact('projects', 'sections', 'sectionItems'));
        } catch (\Exception $e) {
            Log::error('Error loading project item create form: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل نموذج إنشاء عنصر المشروع.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'items' => 'required|array|min:1',
            'items.*.section_id' => 'required|exists:sections,id',
            'items.*.section_item_id' => 'required|exists:section_items,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.received_qty' => 'nullable|integer|min:0|lte:items.*.qty',
            'items.*.executed_qty' => 'nullable|integer|min:0|lte:items.*.received_qty',
            'items.*.custom_expected_arrival' => 'nullable|date|after_or_equal:today',
        ], [
            'project_id.required' => 'Please select a project.',
            'items.required' => 'At least one item is required.',
            'items.min' => 'At least one item is required.',
            'items.*.section_id.required' => 'Section is required for all items.',
            'items.*.section_item_id.required' => 'Section item is required for all items.',
            'items.*.qty.required' => 'Quantity is required for all items.',
            'items.*.qty.min' => 'Quantity must be at least 1.',
            'items.*.received_qty.min' => 'Received quantity cannot be negative.',
            'items.*.executed_qty.min' => 'Executed quantity cannot be negative.',
        ]);

        try {
            DB::beginTransaction();

            $projectItems = [];

            foreach ($request->items as $itemData) {
                // Skip empty items
                if (empty($itemData['section_id']) || empty($itemData['section_item_id']) || empty($itemData['qty'])) {
                    continue;
                }

                // Validate quantities
                $qty = (int) $itemData['qty'];
                $receivedQty = (int) ($itemData['received_qty'] ?? 0);
                $executedQty = (int) ($itemData['executed_qty'] ?? 0);

                if ($receivedQty > $qty) {
                    notify('Received quantity cannot exceed total quantity.', 'error');

                    throw new \Exception('Received quantity cannot exceed total quantity.');
                }

                if ($executedQty > $receivedQty) {

                    notify('Executed quantity cannot exceed received quantity.', 'error');

                    throw new \Exception('Executed quantity cannot exceed received quantity.');
                }

                // Check if this combination already exists
                $existingItem = ProjectItems::where([
                    'project_id' => $request->project_id,
                    'section_id' => $itemData['section_id'],
                    'section_item_id' => $itemData['section_item_id'],
                ])->first();

                if ($existingItem) {
                    // Update existing item by adding quantities
                    $existingItem->qty += $qty;
                    $existingItem->received_qty += $receivedQty;
                    $existingItem->executed_qty += $executedQty;
                    $existingItem->save();

                    $projectItems[] = $existingItem;
                } else {
                    // Create new item
                    $projectItem = ProjectItems::create([
                        'project_id' => $request->project_id,
                        'section_id' => $itemData['section_id'],
                        'section_item_id' => $itemData['section_item_id'],
                        'qty' => $qty,
                        'received_qty' => $receivedQty,
                        'executed_qty' => $executedQty,
                        'expected_arrival' => $itemData['custom_expected_arrival'],
                    ]);

                    $projectItems[] = $projectItem;
                }
            }

            DB::commit();

            $message = count($projectItems) . ' project item(s) have been added successfully.';

            notify($message, 'success');

            return redirect()->route('project-items.index');
        } catch (\Exception $e) {
            DB::rollback();

            notify('Error storing project items: ' . $e->getMessage(), 'error');
            Log::error('Error storing project items: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectItems $projectItem)
    {
        try {
            $projectItem->load(['project', 'section', 'sectionItem']);

            return view('dashboard.project_items.show', compact('projectItem'));
        } catch (\Exception $e) {
            Log::error('Error loading project item details: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل تفاصيل عنصر المشروع.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectItems $projectItem)
    {
        try {
            $projects = Project::all(['id', 'name']);
            $sections = Section::all(['id', 'name']);
            $sectionItems = SectionItem::all(['id', 'name', 'section_id']);

            return view('dashboard.project_items.edit', compact('projectItem', 'projects', 'sections', 'sectionItems'));
        } catch (\Exception $e) {
            Log::error('Error loading project item edit form: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل نموذج تحرير عنصر المشروع.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectItems $projectItem)
    {
        try {
            $validatedData = $request->validate([
                'project_id' => 'required|exists:projects,id',
                'section_id' => 'required|exists:sections,id',
                'section_item_id' => 'required|exists:section_items,id',
                'qty' => 'required|integer|min:1',
                'received_qty' => 'required|integer|min:0|lte:qty',
                'executed_qty' => 'required|integer|min:0|lte:received_qty',
                'expected_arrival' => 'required|date',
            ]);

            $projectItem->update($validatedData);

            notify('The project item has been successfully updated.', 'success');

            return redirect()->route('project-items.show', $projectItem);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating project item: ' . $e->getMessage());

            notify('An error occurred while updating the project item.', 'error');

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectItems $projectItem)
    {
        try {
            $projectItem->delete();

            notify('The project item was successfully deleted.', 'success');

            return redirect()->route('project-items.index');
        } catch (\Exception $e) {
            Log::error('Error deleting project item: ' . $e->getMessage());

            notify('An error occurred while deleting the project item.', 'error');

            return back();
        }
    }

    /**
     * Update received quantity
     */
    public function updateReceivedQty(Request $request, ProjectItems $projectItem)
    {
        try {
            $validatedData = $request->validate([
                'received_qty' => 'required|integer|min:0|max:' . $projectItem->qty,
            ]);

            $projectItem->update($validatedData);

            notify('The received quantity has been successfully updated.', 'success');

            return back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error updating received quantity: ' . $e->getMessage());

            notify('An error occurred while updating the received quantity.', 'error');

            return back();
        }
    }

    /**
     * Update executed quantity
     */
    public function updateExecutedQty(Request $request, ProjectItems $projectItem)
    {
        try {
            $validatedData = $request->validate([
                'executed_qty' => 'required|integer|min:0|max:' . $projectItem->received_qty,
            ]);

            $projectItem->update($validatedData);

            notify('The executed quantity has been successfully updated.', 'success');

            return back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error updating executed quantity: ' . $e->getMessage());

            notify('An error occurred while updating the executed quantity.', 'error');

            return back();
        }
    }
}
