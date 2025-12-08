<?php

namespace App\Http\Controllers\Dashboard;


use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Requests\RolesRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('check.permission:roles_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_role', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:show_role', ['only' => ['show']]);
        $this->middleware('check.permission:edit_role', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:delete_role', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('created_at', 'desc')->paginate('15');
        return view('dashboard.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::with('team')->get();

        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return $permission->team?->name ?? 'No Team';
        })->map(function ($group) {
            return $group->mapWithKeys(function ($permission) {
                return [$permission->id => $permission->name];
            });
        });
        $groupedPermissionsArray = $groupedPermissions->toArray();

        return view('dashboard.roles.create', compact('groupedPermissionsArray'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(RolesRequest $request)
    {
        DB::BeginTransaction();

        try {
            $role = Role::create(['name' => $request->input('name')]);

            $permissions = Permission::whereIn('id', $request->input('permission'))->pluck('name')->toArray();

            $role->syncPermissions($permissions);

            DB::commit();

            notify(__('roles.add_role_success'), 'success');
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creating role: ' . $e->getMessage());

            notify(__('roles.add_role_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('roles.add_role_error')])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);

        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->with('team')
            ->get();

        $groupedPermissions = $rolePermissions->groupBy(function ($permission) {
            return $permission->team?->name ?? 'No Team';
        })->map(function ($group) {
            return $group->mapWithKeys(function ($permission) {
                return [
                    $permission->id => $permission->name,
                ];
            });
        });

        $groupedPermissionsArray = $groupedPermissions->toArray();

        return view('dashboard.roles.show', compact('role', 'groupedPermissionsArray'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);

        $permissions = Permission::with('team')->get();

        $groupedPermissionsArray = $permissions->groupBy(function ($permission) {
            return $permission->team?->name ?? 'No Team';
        })->map(function ($group) {
            return $group->mapWithKeys(function ($permission) {
                return [$permission->id => $permission->name];
            });
        })->toArray();

        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id')
            ->toArray();

        return view('dashboard.roles.edit', compact('role', 'rolePermissions', 'groupedPermissionsArray'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RolesRequest $request, $id)
    {
        // بدء المعاملة
        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);

            $role->update(['name' => $request->input('name')]);

            $permissions = Permission::whereIn('id', $request->input('permission'))->pluck('name')->toArray();

            $role->syncPermissions($permissions);

            DB::commit();

            notify(__('roles.edit_role_success'), 'success');
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating role: ' . $e->getMessage());

            notify(__('roles.edit_role_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('roles.edit_role_error')])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        DB::beginTransaction();

        try {
            DB::table("roles")->where('id', $id)->delete();

            DB::commit();

            notify(__('roles.deleted_role_success'), 'success');
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error deleting role: ' . $e->getMessage());

            notify(__('roles.deleted_role_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('roles.deleted_role_error')]);
        }
    }
}