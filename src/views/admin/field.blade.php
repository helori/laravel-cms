@extends('laravel-cms::admin.layout')
@section('content')    
<div id="field">

	<div class="container">

		<crud-wrapper
			:attributes="[
				{name: 'title', default: ''},
				{name: 'name', default: ''},
				{name: 'type', default: 'text'},
			]"
			list-title="Champs"
			create-title="Nouveau champ"
			update-title="Modifier le champ"
			delete-title="Supprimer le champ"
			delete-text="Attention, cette opération est irréversible"
			no-results="Aucun champ trouvé"
			:can-create="true"
			base-uri="/admin/api"
			uri="/fieldset/{{ $fieldset_id }}/field"
			create-form-component="form-field"
			update-form-component="form-field"
			list-component="crud-table"
			row-component="row-field"
			:options="{
				'headers': [
					'Titre', 'Nom', 'Type'
				]
			}">

		</crud-wrapper>

    </div>

</div>
@endsection