<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\SectionRequest;
use App\Http\Requests\StoreRequest;
use App\Models\Brand;
use App\Models\Section;
use App\Models\Store;
use Illuminate\Support\Facades\Log;

class StoresController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:stores_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_store', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:edit_store', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:delete_store', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $stores = Store::orderBy('created_at', 'desc')->paginate('50');
            return view('dashboard.stores.index', compact('stores'));
        } catch (\Exception $e) {
            Log::error('Error in StoresController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء عرض المتاجر');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        return view('dashboard.stores.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            Store::create($request->validated());
            notify(__('stores.add_store_success'), 'success');
            return redirect()->route('stores.index');
        } catch (\Exception $e) {
            Log::error('Error in StoresController@store: ' . $e->getMessage());
            notify(__('stores.add_store_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('stores.add_store_error')])->withInput();
        }
    }


    public function ajaxStore(StoreRequest $request)
    {
        try {
            $store = Store::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Add Store Success',
                'store' => [
                    'id' => $store->id,
                    'name' => $store->name,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in StoresController@ajaxStore: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Add Store Error'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        $brands = Brand::all();
        return view('dashboard.stores.edit', compact('store', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, Store $store)
    {
        try {
            if (!$store) {
                notify(__('stores.store_not_found'), 'error');
                return redirect()->back()->withErrors(['error' => __('stores.store_not_found')]);
            }
            $store->update($request->validated());
            notify(__('stores.update_store_success'), 'success');
            return redirect()->route('stores.index');
        } catch (\Exception $e) {
            Log::error('Error in StoresController@update: ' . $e->getMessage());
            notify(__('stores.update_store_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('stores.update_store_error')])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        try {
            if (!$store) {
                notify(__('stores.store_not_found'), 'error');
                return redirect()->back()->withErrors(['error' => __('stores.store_not_found')]);
            }

            $store->delete();
            notify(__('stores.delete_store_success'), 'success');
            return redirect()->route('stores.index');
        } catch (\Exception $e) {
            Log::error('Error in StoresController@destroy: ' . $e->getMessage());
            notify(__('stores.delete_store_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('stores.delete_store_error')]);
        }
    }
}
