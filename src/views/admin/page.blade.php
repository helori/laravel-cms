@extends('laravel-cms::admin.layout')
@section('content')    
<div id="page">

	<div class="container">

		<h1>Pages du site</h1>

		<crud-wrapper
			:attributes="[
				{name: 'title', default: ''},
				{name: 'slug', default: ''}
			]"
			list-title=""
			create-title="Nouvelle page"
			update-title="Modifier la page"
			delete-title="Supprimer la page"
			delete-text="Attention, cette opération est irréversible"
			no-results="Aucune page trouvée"
			:can-create="true"
			base-uri="/admin/api"
			uri="/page"
			create-form-component="form-page-create"
			update-form-component="form-page-create"
			list-component="tree-sortable"
			row-component="tree-page"
			:options="{
				'nested': true,
				'children-key': 'pages'
			}">

		</crud-wrapper>

    </div>

</div>
@endsection