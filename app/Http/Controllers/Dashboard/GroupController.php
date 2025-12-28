<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:groups_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_group', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:edit_group', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:delete_group', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $groups = Group::orderBy('created_at', 'desc')->paginate('50');
            return view('dashboard.groups.index', compact('groups'));
        } catch (\Exception $e) {
            Log::error('Error in GroupController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء عرض المجموعات');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupRequest $request)
    {
        try {
            Group::create($request->validated());
            notify(__('groups.add_group_success'), 'success');
            return redirect()->route('groups.index');
        } catch (\Exception $e) {
            Log::error('Error in GroupController@store: ' . $e->getMessage());
            notify(__('groups.add_group_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('groups.add_group_error')])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        return view('dashboard.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupRequest $request, Group $group)
    {
        try {
            if (!$group) {
                notify(__('groups.group_not_found'), 'error');
                return redirect()->back()->withErrors(['error' => __('groups.group_not_found')]);
            }
            $group->update($request->validated());
            notify(__('groups.update_group_success'), 'success');
            return redirect()->route('groups.index');
        } catch (\Exception $e) {
            Log::error('Error in GroupController@update: ' . $e->getMessage());
            notify(__('groups.update_group_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('groups.update_group_error')])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        try {
            if (!$group) {
                notify(__('groups.group_not_found'), 'error');
                return redirect()->back()->withErrors(['error' => __('groups.group_not_found')]);
            }
            if ($group->users()->count() > 0) {
                notify(__('groups.delete_group_error_admins_exist'), 'error');
                return redirect()->back()->withErrors(['error' => __('groups.delete_group_error_admins_exist')]);
            }

            $group->delete();
            notify(__('groups.delete_group_success'), 'success');
            return redirect()->route('groups.index');
        } catch (\Exception $e) {
            Log::error('Error in GroupController@destroy: ' . $e->getMessage());
            notify(__('groups.delete_group_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('groups.delete_group_error')]);
        }
    }
}
