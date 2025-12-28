<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Group;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminsRequest;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:admins_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_admin', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:show_admin', ['only' => ['show']]);
        $this->middleware('check.permission:edit_admin', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:edit_status_admin', ['only' => ['edit_status']]);
        $this->middleware('check.permission:assign_role_admin', ['only' => ['assign_role']]);
        $this->middleware('check.permission:delete_admin', ['only' => ['delete']]);
    }

    public function index()
    {
        $admins = User::admin()->where('id', '!=', auth()->user()->id)->orderBy('created_at', 'desc')->paginate('50');
        $roles = Role::pluck('name', 'name')->all();
        return view('dashboard.admins.index', compact('admins', 'roles'));
    }

    public function show($id)
    {
        $admin = User::find($id);
        if (!$admin) {
            notify(__('admins.not_found_user'), 'error');
            return redirect()->route('admin.index');
        }

        $projects = Project::all();
        return view('dashboard.admins.show', compact('admin', 'projects'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        $groups = Group::all();
        return view('dashboard.admins.create', compact('roles', 'groups'));
    }

    public function store(AdminsRequest $request)
    {
        try {
            // محاولة إنشاء المستخدم
            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'type' => 'admin',
                'status' => $request->status,
            ]);

            // تعيين دور المستخدم
            $admin->assignRole($request->input('roles_name'));

            // حفظ الصورة
            if ($request->hasFile('photo')) {
                $admin->addMediaFromRequest('photo')
                    ->toMediaCollection('avatars');
            }

            // إشعار نجاح العملية
            notify(__('admins.add_admin_success'), 'success');
            return redirect()->route('admin.index');
        } catch (\Exception $e) {
            // إشعار خطأ في حالة فشل العملية
            notify(__('admins.add_admin_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('admins.edit_admin_error')])->withInput();
        }
    }

    public function edit($id)
    {
        $roles = Role::pluck('name', 'name')->all();
        $admin = User::find($id);
        $groups = Group::all();

        if (!$admin) {
            notify(__('admins.not_found_user'), 'error');
            return redirect()->route('admin.index');
        }

        return view('dashboard.admins.edit', compact('admin', 'roles', 'groups'));
    }

    public function update(AdminsRequest $request, $id)
    {
        try {

            $admin = User::find($id);

            if (!$admin) {
                notify(__('admins.not_found_user'), 'error');
                return redirect()->route('admin.index');
            }

            // تحديث البيانات
            $data = $request->only(['name', 'email', 'phone', 'status']);

            // تحديث كلمة المرور فقط إذا تم إدخالها
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
                $data['new_password'] = $request->password;
            }

            $admin->update($data);

            // تعيين دور المستخدم
            $admin->syncRoles($request->input('roles_name'));

            // حفظ الصورة
            if ($request->hasFile('photo')) {

                $admin->clearMediaCollection('avatars');

                $admin->addMediaFromRequest('photo')
                    ->toMediaCollection('avatars');
            }

            // إشعار نجاح العملية
            notify(__('admins.edit_admin_success'), 'success');
            return redirect()->route('admin.index');
        } catch (\Exception $e) {
            // إشعار خطأ في حالة فشل العملية
            notify(__('admins.edit_admin_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('admins.edit_admin_error')])->withInput();
        }
    }

    public function edit_status(Request $request, $id)
    {
        try {
            // البحث عن المستخدم بالـ ID
            $admin = User::find($id);

            if (!$admin) {
                notify(__('admins.not_found_user'), 'error');
                return redirect()->route('admin.index');
            }

            // تحديث البيانات
            $data = $request->only(['status']);
            $admin->update($data);

            // إذا تم تغيير الحالة وكان المستخدم مسجل دخول، قم بتسجيل خروجه
            if ($data['status'] == 0) {
                // حذف جلسات المستخدم من قاعدة البيانات
                DB::table('sessions')
                    ->where('user_id', $admin->id)
                    ->delete();

                // إذا كان المستخدم الحالي هو نفسه المستخدم الذي تم تغيير حالته
                if (Auth::id() == $admin->id) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                }
            }

            // إشعار نجاح العملية
            notify(__('admins.edit_status_admin_success'), 'success');
            return redirect()->route('admin.index');
        } catch (\Exception $e) {
            // إشعار خطأ في حالة فشل العملية
            notify(__('admins.edit_status_admin_error'), 'error');
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function delete($id)
    {
        try {
            // محاولة العثور على المستخدم
            $admin = User::find($id);

            // التحقق مما إذا كان المستخدم موجودًا
            if (!$admin) {
                notify(__('admins.not_found_user'), 'error');
                return redirect()->route('admin.index');
            }

            // حذف المستخدم
            $admin->delete();

            // إشعار نجاح العملية
            notify(__('admins.delete_admin_success'), 'success');
            return redirect()->route('admin.index');
        } catch (\Exception $e) {
            // إشعار خطأ في حالة فشل العملية
            notify(__('admins.delete_admin_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('admins.delete_admin_error')]);
        }
    }

    public function assign_role(Request $request, $id)
    {
        $this->validate($request, [
            'roles_name' => 'required|string|exists:roles,name'
        ], [
            'roles_name.required' => 'الدور مطلوب',
            'roles_name.exists' => 'الدور غير موجود في النظام',
        ]);

        $admin = User::find($id);

        if (!$admin) {
            notify(__('admins.not_found_user'), 'error');
            return redirect()->route('admin.index');
        }

        $admin->syncRoles($request->input('roles_name'));

        notify('Assign Role Success', 'success');

        return redirect()->route('admin.index');
    }
}
