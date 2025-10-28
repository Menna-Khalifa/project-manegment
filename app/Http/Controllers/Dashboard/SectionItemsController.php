<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Section;
use App\Models\SectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\SectionItemRequest;
use Illuminate\Support\Facades\Validator;

class SectionItemsController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:section_items_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_section_item', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:edit_section_item', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:delete_section_item', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $section_items = SectionItem::all();
            return view('dashboard.section_items.index', compact('section_items'));
        } catch (\Exception $e) {
            Log::error('Error in SectionsController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء عرض الأقسام');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        return view('dashboard.section_items.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionItemRequest $request)
    {
        try {
            SectionItem::create($request->validated());
            notify(__('section_items.add_section_item_success'), 'success');
            return redirect()->route('section_items.index');
        } catch (\Exception $e) {
            Log::error('Error in SectionsController@store: ' . $e->getMessage());
            notify(__('section_items.add_section_item_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('section_items.add_section_item_error')])->withInput();
        }
    }

    public function ajaxStore(SectionItemRequest $request)
    {
        try {

            // Check for duplicate name within the same section
            $existingItem = SectionItem::where('name', $request->name)
                ->where('section_id', $request->section_id)
                ->first();

            if ($existingItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'اسم العنصر موجود بالفعل في هذا القسم'
                ], 422);
            }

            $sectionItem = SectionItem::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Add Section Item Success',
                'section_item' => [
                    'id' => $sectionItem->id,
                    'name' => $sectionItem->name,
                    'description' => $sectionItem->description,
                    'section_id' => $sectionItem->section_id
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in SectionItemController@ajaxStore: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('section_items.add_section_item_error')
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SectionItem $section_item)
    {
        $sections = Section::all();
        return view('dashboard.section_items.edit', compact('section_item', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionItemRequest $request, SectionItem $section_item)
    {
        try {
            if (!$section_item) {
                notify(__('section_items.section_item_not_found'), 'error');
                return redirect()->back()->withErrors(['error' => __('section_items.section_item_not_found')]);
            }
            $section_item->update($request->validated());
            notify(__('section_items.update_section_item_success'), 'success');
            return redirect()->route('section_items.index');
        } catch (\Exception $e) {
            Log::error('Error in SectionItemsController@update: ' . $e->getMessage());
            notify(__('section_items.update_section_item_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('section_items.update_section_item_error')])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SectionItem $section_item)
    {
        try {
            if (!$section_item) {
                notify(__('section_items.section_item_not_found'), 'error');
                return redirect()->back()->withErrors(['error' => __('section_items.section_item_not_found')]);
            }
            // if ($section_item->sectionItems()->count() > 0) {
            //     notify(__('section_items.delete_section_item_error_items_exist'), 'error');
            //     return redirect()->back()->withErrors(['error' => __('section_items.delete_section_item_error_items_exist')]);
            // }

            $section_item->delete();
            notify(__('section_items.delete_section_item_success'), 'success');
            return redirect()->route('section_items.index');
        } catch (\Exception $e) {
            Log::error('Error in SectionItemsController@destroy: ' . $e->getMessage());
            notify(__('section_items.delete_section_item_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('section_items.delete_section_item_error')]);
        }
    }
}
