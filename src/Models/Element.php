<?php

namespace Helori\LaravelCms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Element extends Model
{
    //protected $table = 'pages';
    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true; // Model should be timestamped
    protected $guarded = []; // Not mass assignable

    protected $appends = ['medias'];
    protected $table_name = '';


    public function getMediasAttribute(){
        return DB::table('mediables')->where([
            'mediable_id' => $this->id,
            'mediable_type' => $this->table
        ])->leftJoin('medias', 'medias.id', '=', 'mediables.media_id')->get();
    }

    public function __construct($table_name = null, array $attributes = []){
        if($table_name){
            $this->table_name = $table_name;
            $this->setTable($table_name);
        }
        parent::__construct($attributes);
    }

    public function setTable($table_name) 
    {
        $this->table = $table_name;
        return $this;
    }
}
