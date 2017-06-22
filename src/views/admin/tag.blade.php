@extends('laravel-cms::admin.layout')
@section('content')    
<div id="post">

	<div class="container">

		<header>
			<h1>Tags</h1>
		</header>

		<crud-wrapper
			:attributes="[
				{name: 'title', default: ''},
				{name: 'slug', default: ''}
			]"
			list-title=""
			create-title="Nouveau tag"
			update-title="Modifier le tag"
			delete-title="Supprimer le tag"
			delete-text="Attention, cette opération est irréversible"
			no-results="Aucun tag trouvé"
			:can-create="true"
			base-uri="/admin/api"
			uri="/tag"
			create-form-component="form-tag"
			update-form-component="form-tag"
			list-component="crud-table"
			row-component="row-tag"
			:options="{
				headers: ['Nom']
			}">

		</crud-wrapper>

    </div>

</div>
@endsection