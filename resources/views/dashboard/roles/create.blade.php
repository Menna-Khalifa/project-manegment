@extends('dashboard.layouts.master')

@section('title')
    {{ __('roles.add_role') }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <li class="breadcrumb-item" style="font-size: 1rem !important;">
        <a href="{{ route('roles.index') }}">{{ __('layouts/main-sidebar.admins_and_permissions') }}</a>
    </li>
    <li class="breadcrumb-item active" style="font-size: 1rem !important;">
        {{ __('roles.add_role') }}
    </li>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mg-b-20">
                <div class="card-body">
                    <div class="main-content-label mg-b-5 mb-5">
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('roles.index') }}">{{ __('roles.back') }}</a>
                        </div>
                    </div>
                    <form action="{{ route('roles.store') }}" method="post">
                        @csrf
                        <div class="main-content-label mg-b-5">
                            <div class="col-xs-7 col-sm-7 col-md-7">
                                <div class="form-group">
                                    <p>{{ __('roles.name_role') }} :</p>
                                    <input type="text" name="name" placeholder="{{ __('roles.name_role') }}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- col -->
                            <div class="col-lg-4">
                                <ul id="treeview1">
                                    <li>
                                        <a href="#">{{ __('roles.permissions') }}</a>
                                        <ul>
                                            @foreach ($groupedPermissionsArray as $teamId => $permissions)
                                                <!-- عنوان الفريق -->
                                                <h4 class="mt-4 mb-4 mr-4">{{ __('role_seeder.' . $teamId) }}</h4>

                                                <!-- خانة اختيار الكل -->
                                                <li>
                                                    <div class="custom-checkbox custom-control mr-4">
                                                        <input type="checkbox" id="select-all-{{ $teamId }}"
                                                            class="custom-control-input select-all">
                                                        <label for="select-all-{{ $teamId }}"
                                                            class="custom-control-label mt-1">
                                                            {{ __('roles.select_all') }}
                                                        </label>
                                                    </div>
                                                </li>

                                                <!-- الصلاحيات -->
                                                <li>
                                                    <div class="d-flex flex-wrap mr-4" style="width:250% !important">
                                                        @foreach ($permissions as $permissionId => $permissionName)
                                                            <div class="custom-checkbox custom-control mb-3 ml-3"
                                                        style="min-width: 100px;">
                                                                <input type="checkbox" name="permission[]"
                                                                    data-checkboxes="mygroup"
                                                                    class="custom-control-input permission-checkbox team-{{ $teamId }}"
                                                                    id="{{ $permissionId }}" value="{{ $permissionId }}">
                                                                <label for="{{ $permissionId }}"
                                                                    class="custom-control-label mt-1">{{ __('role_seeder.' . $permissionName) }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /col -->
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-5 text-center">
                            <button type="submit" class="btn btn-main-primary">{{ __('roles.sure') }}</button>
                        </div>

                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تحديد جميع خانات "اختيار الكل"
            const selectAllCheckboxes = document.querySelectorAll('.select-all');

            // دالة لتحديث حالة خانة "اختيار الكل"
            function updateSelectAllState(teamId) {
                // الحصول على جميع الصلاحيات الخاصة بالفريق
                const permissionCheckboxes = document.querySelectorAll(`.team-${teamId}`);

                // التحقق مما إذا كانت جميع الصلاحيات محددة
                const allChecked = Array.from(permissionCheckboxes).every(checkbox => checkbox.checked);

                // تحديث حالة خانة "اختيار الكل"
                const selectAllCheckbox = document.querySelector(`#select-all-${teamId}`);
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                }
            }

            // تحديث حالة خانات "اختيار الكل" عند تحميل الصفحة
            selectAllCheckboxes.forEach(selectAllCheckbox => {
                const teamId = selectAllCheckbox.id.split('-').pop();
                updateSelectAllState(teamId);
            });

            // عند تغيير حالة خانة "اختيار الكل"
            selectAllCheckboxes.forEach(selectAllCheckbox => {
                selectAllCheckbox.addEventListener('change', function() {
                    const teamId = this.id.split('-').pop();

                    // تحديد جميع الصلاحيات الخاصة بهذا الفريق
                    const permissionCheckboxes = document.querySelectorAll(`.team-${teamId}`);
                    permissionCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            });

            // عند تغيير حالة أي صلاحية
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
            permissionCheckboxes.forEach(permissionCheckbox => {
                permissionCheckbox.addEventListener('change', function() {
                    const teamId = this.classList[1].split('-').pop();
                    updateSelectAllState(teamId);
                });
            });
        });
    </script>
@endsection
