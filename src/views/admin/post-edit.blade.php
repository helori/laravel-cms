@extends('laravel-cms::admin.layout')
@section('content')    
<div id="post-edit" class="edit">

	<div class="container">

		<h1>Modifier l'élément de la collection "{{ $collection->title }}"</h1>

		<crud-form
            form-component="form-post-update"
            :id="{{ $id }}" 
            base-uri="/admin/api"
            uri="/collection/{{ $collection_id }}/post">
        </crud-form>

    </div>

</div>
@endsection