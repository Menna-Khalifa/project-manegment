@extends('dashboard.layouts.master2')
@section('css')
<!--- Internal Fontawesome css-->
<link href="{{URL::asset('dashboard/assets/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
<!---Ionicons css-->
<link href="{{URL::asset('dashboard/assets/plugins/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
<!---Internal Typicons css-->
<link href="{{URL::asset('dashboard/assets/plugins/typicons.font/typicons.css')}}" rel="stylesheet">
<!---Internal Feather css-->
<link href="{{URL::asset('dashboard/assets/plugins/feather/feather.css')}}" rel="stylesheet">
<!---Internal Falg-icons css-->
<link href="{{URL::asset('dashboard/assets/plugins/flag-icon-css/css/flag-icon.min.css')}}" rel="stylesheet">
@endsection
@section('content')
		<!-- Main-error-wrapper -->
		<div class="main-error-wrapper  page page-h ">
			<img src="{{URL::asset('dashboard/assets/img/media/403.png')}}" class="error-page" alt="error">
			<h2>Forbidden</h2>
			<h6>The Forbidden response code indicates that the server understands the request, but refuses to authorize it.</h6><a class="btn btn-outline-danger" href="{{ route('dashboard') }}">Back to Home</a>
		</div>
		<!-- /Main-error-wrapper -->
@endsection
@section('js')
@endsection
