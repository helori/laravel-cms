@extends('laravel-cms::admin.layout')
@section('content')    
<div id="collection">

	<div class="container">

	<h1>Collections pouvant être associées aux pages</h1>

		<crud-wrapper
			:attributes="[
				{name: 'title', default: ''},
				{name: 'slug', default: ''}
			]"
			list-title=""
			create-title="Nouvelle collection"
			update-title="Modifier la collection"
			delete-title="Supprimer la collection"
			delete-text="Attention, cette opération est irréversible"
			no-results="Aucune collection trouvée"
			:can-create="true"
			base-uri="/admin/api"
			uri="/collection"
			create-form-component="form-collection"
			update-form-component="form-collection"
			list-component="crud-table"
			row-component="row-collection"
			:options="{
				'headers': [
					'Nom', 'Nombre d\'éléments'
				]
			}">

		</crud-wrapper>

    </div>

</div>
@endsection