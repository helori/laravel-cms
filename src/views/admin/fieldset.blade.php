@extends('laravel-cms::admin.layout')
@section('content')    
<div id="fieldset">

	<div class="container">

		<crud-wrapper
			:attributes="[
				{name: 'title', default: ''}
			]"
			list-title="Groupes de champs"
			create-title="Nouveau groupe"
			update-title="Modifier le groupe"
			delete-title="Supprimer le groupe"
			delete-text="Attention, cette opération est irréversible"
			no-results="Aucun groupe trouvé"
			:can-create="true"
			base-uri="/admin/api"
			uri="/fieldset"
			create-form-component="form-fieldset"
			update-form-component="form-fieldset"
			list-component="crud-table"
			row-component="row-fieldset"
			:options="{
				'headers': [
					'Groupe de champs', 'Nombre de champs'
				]
			}">

		</crud-wrapper>

    </div>

</div>
@endsection