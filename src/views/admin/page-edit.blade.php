@extends('laravel-cms::admin.layout')
@section('content')    
<div id="page-edit" class="edit">

	<div class="container">

		<header>
			<div class="row">
				<div class="col-sm-8">
					<h1>Modifier la page</h1>
				</div>
				<div class="col-sm-4 text-right">
					<a href="{{ route('admin-page') }}" class="btn btn-default">
						Revenir aux pages
						<i class="fa fa-arrow-right"></i> 
					</a>
				</div>
			</div>
		</header>

		<crud-form
            form-component="form-page-update"
            :id="{{ $id }}" 
            base-uri="/admin/api"
            uri="/page">
        </crud-form>

    </div>

</div>
@endsection