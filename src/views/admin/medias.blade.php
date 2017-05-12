@extends('laravel-cms::admin.layout')
@section('content')
<div id="medias-manager">

	<medias-manager
		assets-base-url="{{ asset('/') }}"
		queries-base-url="{{ url('/admin') }}/">
	</medias-manager>

</div>
@endsection
