@extends('laravel-cms::admin.layout')
@section('content')    
<div id="post-edit" class="edit">

	<div class="container">

		<header>
			<div class="row">
				<div class="col-sm-8">
					<h1>Modifier un élément de la collection : {{ $collection->title }}</h1>
				</div>
				<div class="col-sm-4 text-right">
					<a href="{{ route('admin-post', ['collectionId' => $collection->id]) }}" class="btn btn-default">
						Revenir à la collection : {{ $collection->title }}
						<i class="fa fa-arrow-right"></i> 
					</a>
				</div>
			</div>
		</header>

		

		<crud-form
            form-component="form-post-update"
            :id="{{ $id }}" 
            base-uri="/admin/api"
            uri="/collection/{{ $collection_id }}/post">
        </crud-form>

    </div>

</div>
@endsection