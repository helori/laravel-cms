@extends('laravel-cms::admin.layout')
@section('content')    
<div id="table">

	<div class="container">

		<crud-table
			:headers="[
				'Tables', 'Infos'
			]"
			:attributes="[
				{name: 'name', default: ''},
				{name: 'table', default: ''},
				{name: 'alias', default: ''}
			]"
			list-title="Base de données"
			create-title="Nouvelle table"
			update-title="Modifier la table"
			delete-title="Supprimer la table'"
			delete-text="Attention, cette opération est irréversible"
			no-results="Aucune table trouvée"
			:can-create="true"
			base-uri="/admin/api"
			uri="/table"
			create-form-component="form-table"
			update-form-component="form-table"
			table-row-component="row-table"
			:options="{}">

		</crud-table>

    </div>

</div>
@endsection