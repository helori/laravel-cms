@extends('laravel-cms::admin.layout')
@section('content')
<div id="media">

	<div class="container">

		<medias-manager
			assets-base-url="{{ asset('/') }}"
			queries-base-url="{{ url('/admin/api') }}/">
		</medias-manager>

	</div>

</div>
@endsection
