@extends('laravel-cms::admin.layout')
@section('content')    
<div id="page-edit" class="edit">

	<div class="container">

		<h1>Modifier la page</h1>

		<crud-form
            form-component="form-page-update"
            :id="{{ $id }}" 
            base-uri="/admin/api"
            uri="/page">
        </crud-form>

    </div>

</div>
@endsection