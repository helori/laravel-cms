<?php

namespace Helori\LaravelCms\Relations;

use Illuminate\Database\Eloquent\Relations\MorphToMany as MorphToManyBase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class MorphToMany extends MorphToManyBase
{
    public function __construct(Builder $query, Model $parent, $name, $table, $foreignKey, $relatedKey, $relationName = null, $inverse = false, $morphClass = null)
    {
        $this->inverse = $inverse;
        $this->morphType = $name.'_type';
        if($morphClass){
            $this->morphClass = $morphClass;
        }else{
            $this->morphClass = $inverse ? $query->getModel()->getMorphClass() : $parent->getMorphClass();
        }

        BelongsToMany::__construct($query, $parent, $table, $foreignKey, $relatedKey, $relationName);
    }
}
