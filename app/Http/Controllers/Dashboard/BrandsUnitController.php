<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\SectionRequest;
use App\Models\Brand;
use App\Models\Section;
use Illuminate\Support\Facades\Log;

class BrandsUnitController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:brands_unit_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_brand_unit', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:edit_brand_unit', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:delete_brand_unit', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $brands = Brand::typeUnit()
                ->latest()
                ->paginate(50);

            return view('dashboard.brand_units.index', compact('brands'));
        } catch (\Exception $e) {
            Log::error('Error in BrandsUnitController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء عرض العلامات');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.brand_units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        try {
            Brand::create($request->validated());
            notify(__('brands.add_brand_success'), 'success');
            return redirect()->route('brand_units.index');
        } catch (\Exception $e) {
            Log::error('Error in BrandsUnitController@store: ' . $e->getMessage());
            notify(__('brands.add_brand_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('brands.add_brand_error')])->withInput();
        }
    }


    public function ajaxStore(BrandRequest $request)
    {
        try {
            $brand = Brand::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Add Brand Success',
                'brand' => [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'description' => $brand->description
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BrandsUnitController@ajaxStore: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Add Brand Error'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('dashboard.brand_units.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brand)
    {
        try {
            if (!$brand) {
                notify(__('brands.brand_not_found'), 'error');
                return redirect()->back()->withErrors(['error' => __('brands.brand_not_found')]);
            }
            $brand->update($request->validated());
            notify(__('brands.update_brand_success'), 'success');
            return redirect()->route('brand_units.index');
        } catch (\Exception $e) {
            Log::error('Error in BrandsUnitController@update: ' . $e->getMessage());
            notify(__('brands.update_brand_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('brands.update_brand_error')])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        try {
            if (!$brand) {
                notify(__('brands.brand_not_found'), 'error');
                return redirect()->back()->withErrors(['error' => __('brands.brand_not_found')]);
            }

            $brand->delete();
            notify(__('brands.delete_brand_success'), 'success');
            return redirect()->route('brand_units.index');
        } catch (\Exception $e) {
            Log::error('Error in BrandsUnitController@destroy: ' . $e->getMessage());
            notify(__('brands.delete_brand_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('brands.delete_brand_error')]);
        }
    }
}
