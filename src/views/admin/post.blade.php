@extends('laravel-cms::admin.layout')
@section('content')    
<div id="post">

	<div class="container">

		<header>
			<div class="row">
				<div class="col-sm-8">
					<h1>Éléments de "{{ $collection->title }}"</h1>
				</div>
				<div class="col-sm-4 text-right">
					<a href="{{ route('admin-collection') }}" class="btn btn-default">
						Revenir aux collections
						<i class="fa fa-arrow-right"></i> 
					</a>
				</div>
			</div>
		</header>

		<crud-wrapper
			:attributes="[
				{name: 'title', default: ''},
				{name: 'slug', default: ''}
			]"
			list-title=""
			create-title="Nouveau post"
			update-title="Modifier le post"
			delete-title="Supprimer le post"
			delete-text="Attention, cette opération est irréversible"
			no-results="Aucun post trouvé"
			:can-create="true"
			base-uri="/admin/api"
			uri="/collection/{{ $collection_id }}/post"
			create-form-component="form-post-create"
			update-form-component="form-post-create"
			list-component="tree-sortable"
			row-component="tree-post"
			:options="{
				'nested': false,
				'children-key': 'posts'
			}">

		</crud-wrapper>

    </div>

</div>
@endsection