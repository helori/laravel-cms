@extends('laravel-cms::admin.layout')
@section('content')    
<div id="table-fields">

	<div class="container-fluid">

		<crud-form
            form-component="form-table-fields"
            :id="{{ $tableId }}" 
            base-uri="/admin/api"
            uri="/table">
        </crud-form>

    </div>

</div>
@endsection