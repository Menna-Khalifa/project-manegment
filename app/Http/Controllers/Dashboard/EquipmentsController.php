<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EquipmentsController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:equipments_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_equipment', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:edit_equipment', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:delete_equipment', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $equipments = Equipment::orderBy('created_at', 'desc')->paginate('50');
            return view('dashboard.equipments.index', compact('equipments'));
        } catch (\Exception $e) {
            Log::error('Error in equipmentsController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء عرض المعدات');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.equipments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipmentRequest $request)
    {
        try {
            Equipment::create($request->validated());
            notify(__('equipments.add_equipment_success'), 'success');
            return redirect()->route('equipments.index');
        } catch (\Exception $e) {
            Log::error('Error in equipmentsController@store: ' . $e->getMessage());
            notify(__('equipments.add_equipment_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('equipments.Edd_equipment_error')])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        return view('dashboard.equipments.edit', compact('equipment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EquipmentRequest $request, Equipment $equipment)
    {
        try {
            if (!$equipment) {
                notify(__('equipments.equipment_not_found'), 'error');
                return redirect()->back()->withErrors(['error' => __('equipments.equipment_not_found')]);
            }
            $equipment->update($request->validated());
            notify(__('equipments.update_equipment_success'), 'success');
            return redirect()->route('equipments.index');
        } catch (\Exception $e) {
            Log::error('Error in equipmentsController@update: ' . $e->getMessage());
            notify(__('equipments.update_equipment_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('equipments.update_equipment_error')])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        try {
            if (!$equipment) {
                notify(__('equipments.equipment_not_found'), 'error');
                return redirect()->back()->withErrors(['error' => __('equipments.equipment_not_found')]);
            }
            // if ($equipment->EquipmentItems()->count() > 0) {
            //     notify(__('equipments.Eelete_equipment_error_items_exist'), 'error');
            //     return redirect()->back()->withErrors(['error' => __('equipments.Eelete_equipment_error_items_exist')]);
            // }

            $equipment->delete();
            notify(__('equipments.delete_equipment_success'), 'success');
            return redirect()->route('equipments.index');
        } catch (\Exception $e) {
            Log::error('Error in equipmentsController@destroy: ' . $e->getMessage());
            notify(__('equipments.delete_equipment_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('equipments.delete_equipment_error')]);
        }
    }
}
