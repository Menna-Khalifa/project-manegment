<!-- Title -->
<title> @yield('title') </title>
<!-- Favicon -->
<link rel="icon" href="{{ URL::asset('dashboard/assets/img/brand/desktop-logo.png') }}" type="image/x-icon" />
<!-- Icons css -->
<link href="{{ URL::asset('dashboard/assets/css/icons.css') }}" rel="stylesheet">
<!--  Custom Scroll bar-->
<link href="{{ URL::asset('dashboard/assets/plugins/mscrollbar/jquery.mCustomScrollbar.css') }}" rel="stylesheet" />
<!--  Sidebar css -->
<link href="{{ URL::asset('dashboard/assets/plugins/sidebar/sidebar.css') }}" rel="stylesheet">

<!-- Internal Data table css -->
<link href="{{ URL::asset('dashboard/assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('dashboard/assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('dashboard/assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}"
    rel="stylesheet" />
<link href="{{ URL::asset('dashboard/assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('dashboard/assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('dashboard/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<!--Internal  Datetimepicker-slider css -->
<link href="{{ URL::asset('dashboard/assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css') }}"
    rel="stylesheet">
<link href="{{ URL::asset('dashboard/assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css') }}"
    rel="stylesheet">

<link href="{{ URL::asset('dashboard/assets/plugins/pickerjs/picker.min.css') }}" rel="stylesheet">
<!-- Internal Spectrum-colorpicker css -->
<link href="{{ URL::asset('dashboard/assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">
<!--Internal   Notify -->
<link href="{{ URL::asset('dashboard/assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

<!--Internal  Font Awesome -->
<link href="{{ URL::asset('dashboard/assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
<!--Internal  treeview -->
<link href="{{ URL::asset('dashboard/assets/plugins/treeview/treeview.css') }}" rel="stylesheet" type="text/css" />

<!---Internal Fileupload css-->
<link href="{{ URL::asset('dashboard/assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet"
    type="text/css" />

<!-- Internal Nice-select css  -->
<link href="{{ URL::asset('dashboard/assets/plugins/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet" />

<!-- Internal Daterangepicker css -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- Sidemenu css -->
<link rel="stylesheet" href="{{ URL::asset('dashboard/assets/css/sidemenu.css') }}">
<!--- Style css -->
<link href="{{ URL::asset('dashboard/assets/css/style.css') }}" rel="stylesheet">
<!--- Dark-mode css -->
<link href="{{ URL::asset('dashboard/assets/css/style-dark.css') }}" rel="stylesheet">
<!---Skinmodes css-->
<link href="{{ URL::asset('dashboard/assets/css/skin-modes.css') }}" rel="stylesheet">

@yield('css')
