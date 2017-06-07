@extends('laravel-cms::admin.layout')
@section('content')    
<div id="admin">

	<div class="container">

		<crud-table
			:headers="[
				'Nom', 'Email'
			]"
			:attributes="[
				{name: 'name', default: ''},
				{name: 'email', default: ''},
				{name: 'password', default: ''}
			]"
			list-title="Administrateurs"
			create-title="Nouvel administrateur"
			update-title="Modifier l'administrateur"
			delete-title="Supprimer l'administrateur'"
			delete-text="Attention, cette opération est irréversible"
			no-results="Aucun administrateur trouvé"
			:can-create="true"
			base-uri="/admin/api"
			uri="/admin"
			create-form-component="form-admin"
			update-form-component="form-admin"
			table-row-component="row-admin"
			:options="{}">

		</crud-table>

    </div>

</div>
@endsection