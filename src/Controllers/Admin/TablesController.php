<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Models\Table;
use Helori\LaravelCms\Models\Field;


class TablesController extends Controller
{
    public function tables(Request $request)
    {
        return view('laravel-cms::admin.tables', $this->data);
    }

    public function read(Request $request, $id = null)
    {
    	if($id){
    		return Table::with('fields')->findOrFail($id);
    	}else{
    		return Table::with('fields')->get();
    	}
    }

    public function delete(Request $request, $id)
    {
    	$item = Table::findOrFail($id);
    	Schema::dropIfExists($item->table);

        $item = Table::findOrFail($id);
        $item->delete();
    }

    public function create(Request $request)
    {
    	$name = $request->input('name', null);
    	$alias = $request->input('alias', null);
    	$table_name = $request->input('table', null);

    	if(!$name || !$alias || !$table_name){
    		return response()->json([
    			'message' => "Tous les champs sont requis"
    		], 422);
    	}

    	$alias = Str::slug($alias, '_');
    	$table_name = Str::slug($table_name, '_');

    	if(Schema::hasTable($table_name)){
    		return response()->json([
    			'message' => "La table ".$table_name." existe déjà dans la base de données"
    		], 422);
    	}

    	try{
    		Schema::create($table_name, function (Blueprint $table) {
	            $table->increments('id');
	            $table->timestamps();
	        });
    	}catch(\Exception $e){
    		return response()->json([
    			'message' => "La table ".$table_name." n'a pas pu être créée : ".$e->getMessage()
    		], 422);
    	}

		$item = new Table();
		$item->name = $name;
		$item->alias = $alias;
		$item->table = $table_name;
		$item->fields = [];
		$item->save();

		return $item;
    }

    public function update(Request $request, $id)
    {
    	$name = $request->input('name', null);
    	$alias = $request->input('alias', null);
    	$table_name = $request->input('table', null);
    	$in_admin = ($request->input('in_admin', false) == 'true');

    	if(!$name || !$alias || !$table_name){
    		return response()->json([
    			'message' => "Tous les champs sont requis"
    		], 422);
    	}

    	$alias = Str::slug($alias, '_');
    	$table_name = Str::slug($table_name, '_');

        $item = Table::findOrFail($id);

        if($item->table != $table_name){
        	Schema::rename($item->table, $table_name);
        }

        $item->name = $name;
		$item->alias = $alias;
		$item->table = $table_name;
		$item->in_admin = $in_admin;
		$item->save();

		if($request->has('fields'))
		{
			// Update fields
			$input_fields = $request->input('fields');

			// Delete useless fields
			foreach($item->fields as $field){
				if(!in_array($field->id, array_pluck($input_fields, 'id'))){
					Schema::table($item->table, function (Blueprint $table) use($field) {
						$table->dropColumn([$field->name]);
						$field->delete();
			        });
				}
			}

			foreach($input_fields as $input_field)
			{
				if(!isset($input_field['default'])){
					$input_field['default'] = null;
				}
				if(!isset($input_field['title'])){
					$input_field['title'] = '';
				}

				// Create missing fields
				if(!isset($input_field['id']))
				{
					// Create field in the "fields" table
					$field = new Field();
					$field->table_id = $item->id;
					$field->type = $input_field['type'];
					$field->name = $input_field['name'];
					$field->title = $input_field['title'];
					$field->default = $input_field['default'];
					$field->save();

					try{
			    		// Create field in target table
						Schema::table($item->table, function (Blueprint $table) use($field) {

							$type = $field->type;
							$name = $field->name;
							$default = $field->default;

							if(in_array($type, ['text', 'email', 'url', 'select'])){
								$table->string($name)->nullable()->default($default);
							}
							else if(in_array($type, ['textarea', 'editor'])){
								if($default){
									$table->text($name)->nullable()->default($default);
								}else{
									$table->text($name)->nullable();
								}
							}
							else if(in_array($type, ['checkbox'])){
								$table->boolean($name)->default($default ? true : false);
							}
							else if(in_array($type, ['date'])){
								$table->date($name)->nullable()->default($default);
							}
							else if(in_array($type, ['media', 'medias'])){
								// Nothing to do !
							}
				        });
			    	}catch(\Exception $e){
			    		return response()->json([
			    			'message' => "Le champ '".$field->name."' de type '".$field->type."' de la table '".$item->table."' n'a pas pu être créé : ".$e->getMessage()
			    		], 422);
			    	}
				}
				// Update existing fields
				else
				{
					$field = Field::findOrFail($input_field['id']);

					$type = $input_field['type'];
					$name = $input_field['name'];
					$title = $input_field['title'];
					$default = $input_field['default'];

					// Rename column if needed
					if($field->name != $name)
					{
						try{
							Schema::table($item->table, function (Blueprint $table) use($field, $name) {
								$table->renameColumn($field->name, $name);
							});
						}catch(\Exception $e){
				    		return response()->json([
				    			'message' => "Le champ '".$field->name."' de la table '".$item->table."' n'a pas pu être renommé en '".$name."' : ".$e->getMessage()
				    		], 422);
				    	}
					}

					// Update field in table (using old data from "fields")
					if($field->type != $type || $field->default != $default)
					{
						try{
							Schema::table($item->table, function (Blueprint $table) use($type, $name, $default) {
								if(in_array($type, ['text', 'email', 'url', 'select'])){
									$table->string($name)->nullable()->default($default)->change();
								}
								else if(in_array($type, ['textarea', 'editor'])){
									if($default){
										$table->text($name)->nullable()->default($default)->change();
									}else{
										$table->text($name)->nullable()->change();
									}
								}
								else if(in_array($type, ['checkbox'])){
									$table->boolean($name)->default($default ? true : false)->change();
								}
								else if(in_array($type, ['date'])){
									$table->date($name)->nullable()->default($default)->change();
								}
								else if(in_array($type, ['media', 'medias'])){
									// Nothing to do !
								}
					        });
					    }catch(\Exception $e){
				    		return response()->json([
				    			'message' => "Le champ '".$name."' de type '".$field->type."' de la table '".$item->table."' n'a pas pu être mis à jour vers le type '".$type."' avec la valeur par défaut '".$default."' : ".$e->getMessage()
				    		], 422);
				    	}
					}

					// Update "fields" table
					$field->type = $type;
					$field->name = $name;
					$field->title = $title;
					$field->default = $default;
					$field->save();
				}
			}
		}

        return $item;
    }
}
