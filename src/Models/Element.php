<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;
use Helori\LaravelCms\Models\Fieldset;
use Helori\LaravelCms\Traits\HasMedia;


class Element extends Model
{
    use HasMedia;

	protected $table = '';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true;
    protected $hidden = [];
    protected $guarded = [];

    protected $appends = [];

    public function newInstance($attributes = [], $exists = false)
    {
        // Overridden in order to allow for late table binding.

        $model = parent::newInstance($attributes, $exists);
        $model->setTable($this->table);
        $model->initRelations();

        return $model;
    }

    public function initRelations(){

    	$fieldset = Fieldset::where('table', $this->table)->firstOrFail();
    	foreach($fieldset->fields as $field){
            if($field->type == 'media'){
            	$this->appends[] = $field->name;
            }
        }
    }

    function __call($method, $args){

    	foreach($this->appends as $append){
    		$functionName = 'get'.ucfirst(strtolower($append)).'Attribute';
    		if($functionName == $method){
    			return $this->medias($append)->get();
    		}
    	}

    	return parent::__call($method, $args);
    }

    public function medias($collection = null){
        $relationship = $this->morphToMany(Media::class, 'mediable')->wherePivot('table', $this->table);
        if(!is_null($collection)){
            $relationship->wherePivot('collection', $collection);
        }
        return $relationship;
    }

    public function syncMedias($medias, $collection){
        $medias_data = [];
        foreach($medias as $media){
            $medias_data[$media['id']] = [
                'collection' => $collection,
                'table' => $this->table
            ];
        }
        $this->medias($collection)->sync($medias_data);
    }
}
