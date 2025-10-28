<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{

    // function __construct()
    // {
    //     $this->middleware('check.permission:users_list', ['only' => ['index']]);
    //     $this->middleware('check.permission:show_user', ['only' => ['show']]);
    //     $this->middleware('check.permission:add_user', ['only' => ['create', 'store']]);
    //     $this->middleware('check.permission:edit_user', ['only' => ['edit', 'update']]);
    //     $this->middleware('check.permission:edit_status_user', ['only' => ['edit_status']]);
    //     $this->middleware('check.permission:delete_user', ['only' => ['delete']]);
    //     $this->middleware('check.permission:assign_role_user', ['only' => ['assign_role']]);
    // }

    public function profile($id)
    {
        $admin = User::find($id);
        $groups = Group::all();

        if (!$admin) {
            notify(__('users.not_found_user'), 'error');
            return redirect()->back();
        }
        return view('dashboard.profile', compact('admin', 'groups'));
    }

    public function update_profile(ProfileUpdateRequest $request, $id)
    {
        try {

            $user = User::find($id);

            if (!$user) {
                notify(__('users.not_found_user'), 'error');
                return redirect()->back();
            }

            // تحديث البيانات
            $data = $request->only(['name', 'email', 'phone', 'status', 'group_id']);

            // تحديث كلمة المرور فقط إذا تم إدخالها
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
                $data['new_password'] = $request->password;
            }

            $user->update($data);

            // إشعار نجاح العملية
            notify(__('users.edit_user_success'), 'success');
            return redirect()->route('profile', $user->id);
        } catch (\Exception $e) {
            // إشعار خطأ في حالة فشل العملية
            notify(__('users.edit_user_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('users.edit_user_error')])->withInput();
        }
    }

    // update profile photo
    public function update_profile_photo(Request $request, $id)
    {
        // validation rules
        $request->validate(
            [
                'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            ],
            [
                'photo.image' => __('validation.image'),
                'photo.mimes' => __('validation.mimes'),
            ]
        );

        try {
            $user = User::find($id);

            if ($user == null) {
                notify(__('users.not_found_user'), 'error');
                return redirect()->back();
            }

            // حفظ الصورة
            if ($request->hasFile('photo')) {
                $user->clearMediaCollection('avatars');
                $user->addMediaFromRequest('photo')
                    ->toMediaCollection('avatars');
            }

            // إشعار نجاح العملية
            notify(__('users.edit_user_success'), 'success');
            return redirect()->route('profile', $user->id);
        } catch (\Exception $e) {
            // إشعار خطأ في حالة فشل العملية
            notify(__('users.edit_user_error'), 'error');
            return redirect()->back()->withErrors(['error' => __('users.edit_user_error')])->withInput();
        }
    }
}
