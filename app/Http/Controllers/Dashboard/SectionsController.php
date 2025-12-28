<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\SectionRequest;
use Illuminate\Support\Facades\Validator;

class SectionsController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:sections_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_section', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:edit_section', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:delete_section', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sections = Section::orderBy('created_at', 'desc')->paginate('50');
            return view('dashboard.sections.index', compact('sections'));
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
        return view('dashboard.sections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionRequest $request)
    {
        try {
            Section::create($request->validated());
            notify(__('sections.add_section_success'), 'success');
            return redirect()->route('sections.index');
        } catch (\Exception $e) {
            Log::error('Error in SectionsController@store: ' . $e->getMessage());
            notify(__('sections.add_section_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('sections.add_section_error')])->withInput();
        }
    }


    public function ajaxStore(SectionRequest $request)
{
    try {
        $section = Section::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Add Section Success',
            'section' => [
                'id' => $section->id,
                'name' => $section->name,
                'description' => $section->description
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error in SectionController@ajaxStore: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Add Section Error'
        ], 500);
    }
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        return view('dashboard.sections.edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionRequest $request, Section $section)
    {
        try {
            if (!$section) {
                notify(__('sections.section_not_found'), 'error');
                return redirect()->back()->withErrors(['error' => __('sections.section_not_found')]);
            }
            $section->update($request->validated());
            notify(__('sections.update_section_success'), 'success');
            return redirect()->route('sections.index');
        } catch (\Exception $e) {
            Log::error('Error in SectionsController@update: ' . $e->getMessage());
            notify(__('sections.update_section_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('sections.update_section_error')])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        try {
            if (!$section) {
                notify(__('sections.section_not_found'), 'error');
                return redirect()->back()->withErrors(['error' => __('sections.section_not_found')]);
            }
            if ($section->sectionItems()->count() > 0) {
                notify(__('sections.delete_section_error_items_exist'), 'error');
                return redirect()->back()->withErrors(['error' => __('sections.delete_section_error_items_exist')]);
            }

            $section->delete();
            notify(__('sections.delete_section_success'), 'success');
            return redirect()->route('sections.index');
        } catch (\Exception $e) {
            Log::error('Error in SectionsController@destroy: ' . $e->getMessage());
            notify(__('sections.delete_section_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('sections.delete_section_error')]);
        }
    }
}
