@extends('laravel-cms::admin.layout')
@section('content')
<div id="tables-manager">

	<tables-manager
		assets-base-url="{{ asset('/') }}"
		queries-base-url="{{ url('/admin') }}/">
	</tables-manager>

</div>
@endsection
