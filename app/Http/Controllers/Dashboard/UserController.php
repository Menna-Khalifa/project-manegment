<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use App\Models\Group;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\ProfileUpdateRequest;

class UserController extends Controller
{

    function __construct()
    {
        $this->middleware('check.permission:users_list', ['only' => ['index']]);
        $this->middleware('check.permission:add_user', ['only' => ['create', 'store']]);
        $this->middleware('check.permission:show_user', ['only' => ['show']]);
        $this->middleware('check.permission:edit_user', ['only' => ['edit', 'update']]);
        $this->middleware('check.permission:edit_status_user', ['only' => ['edit_status']]);
        $this->middleware('check.permission:assign_role_user', ['only' => ['assign_role']]);
        $this->middleware('check.permission:delete_user', ['only' => ['delete']]);
    }

    public function index()
    {
        $users = User::user()->where('id', '!=', auth()->user()->id)->get();
        $roles = Role::pluck('name', 'name')->all();
        return view('dashboard.users.index', compact('users', 'roles'));
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            notify(__('users.not_found_user'), 'error');
            return redirect()->route('user.index');
        }

        $projects = Project::all();
        return view('dashboard.users.show', compact('user', 'projects'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        $groups = Group::all();
        return view('dashboard.users.create', compact('roles', 'groups'));
    }

    public function store(UserRequest $request)
    {
        try {
            // محاولة إنشاء المستخدم
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'group_id' => $request->group_id,
                'type' => $request->type,
                'status' => $request->status,
            ]);

            // تعيين دور المستخدم
            $user->assignRole($request->input('roles_name'));

            // حفظ الصورة
            if ($request->hasFile('photo')) {
                $user->addMediaFromRequest('photo')
                    ->toMediaCollection('avatars');
            }

            // إشعار نجاح العملية
            notify(__('users.add_user_success'), 'success');
            return redirect()->route('user.index');
        } catch (\Exception $e) {
            // إشعار خطأ في حالة فشل العملية
            notify(__('users.add_user_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('users.add_user_error')])->withInput();
        }
    }

    public function edit($id)
    {
        $roles = Role::pluck('name', 'name')->all();
        $user = User::find($id);
        $groups = Group::all();

        if (!$user) {
            notify(__('users.not_found_user'), 'error');
            return redirect()->route('user.index');
        }

        return view('dashboard.users.edit', compact('user', 'roles', 'groups'));
    }

    public function update(UserRequest $request, $id)
    {
        try {

            $user = User::find($id);

            if (!$user) {
                notify(__('users.not_found_user'), 'error');
                return redirect()->route('user.index');
            }

            // تحديث البيانات
            $data = $request->only(['name', 'email', 'phone', 'status', 'group_id']);

            // تحديث كلمة المرور فقط إذا تم إدخالها
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
                $data['new_password'] = $request->password;
            }

            $user->update($data);

            // تعيين دور المستخدم
            $user->syncRoles($request->input('roles_name'));

            // حفظ الصورة
            if ($request->hasFile('photo')) {

                $user->clearMediaCollection('avatars');

                $user->addMediaFromRequest('photo')
                    ->toMediaCollection('avatars');
            }

            // إشعار نجاح العملية
            notify(__('users.edit_user_success'), 'success');
            return redirect()->route('user.index');
        } catch (\Exception $e) {
            // إشعار خطأ في حالة فشل العملية
            notify(__('users.edit_user_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('users.edit_user_error')])->withInput();
        }
    }

    public function edit_status(Request $request, $id)
    {
        try {
            // البحث عن المستخدم بالـ ID
            $user = User::find($id);

            if (!$user) {
                notify(__('users.not_found_user'), 'error');
                return redirect()->route('user.index');
            }

            // تحديث البيانات
            $data = $request->only(['status']);
            $user->update($data);

            // إذا تم تغيير الحالة وكان المستخدم مسجل دخول، قم بتسجيل خروجه
            if ($data['status'] == 0) {
                // حذف جلسات المستخدم من قاعدة البيانات
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->delete();

                // إذا كان المستخدم الحالي هو نفسه المستخدم الذي تم تغيير حالته
                if (Auth::id() == $user->id) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                }
            }

            // إشعار نجاح العملية
            notify(__('users.edit_status_user_success'), 'success');
            return redirect()->route('user.index');
        } catch (\Exception $e) {
            // إشعار خطأ في حالة فشل العملية
            notify(__('users.edit_status_user_error'), 'error');
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function delete($id)
    {
        try {
            // محاولة العثور على المستخدم
            $user = User::find($id);

            // التحقق مما إذا كان المستخدم موجودًا
            if (!$user) {
                notify(__('users.not_found_user'), 'error');
                return redirect()->route('user.index');
            }

            // حذف المستخدم
            $user->delete();

            // إشعار نجاح العملية
            notify(__('users.delete_user_success'), 'success');
            return redirect()->route('user.index');
        } catch (\Exception $e) {
            // إشعار خطأ في حالة فشل العملية
            notify(__('users.delete_user_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('users.delete_user_error')]);
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

        $user = User::find($id);

        if (!$user) {
            notify(__('users.not_found_user'), 'error');
            return redirect()->route('user.index');
        }

        $user->syncRoles($request->input('roles_name'));

        notify('Assign Role Success', 'success');
        return redirect()->route('user.index', $user->id);
    }
}
