@extends('laravel-cms::admin.layout')
@section('content')    
<div id="post">

	<div class="container">

		<h1>Éléments de "{{ $collection->title }}"</h1>

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
			create-form-component="form-post"
			update-form-component="form-post"
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