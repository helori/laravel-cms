<?php

namespace Helori\LaravelCms\Requests;


class ResourceMediaReorder extends AdminBase
{
    public function rules()
    {
        return [
            'collection' => 'sometimes|string',
            'from_position' => 'required|integer',
            'to_position' => 'required|integer',
        ];
    }

    public function handle($resourceName, $resourceId)
    {
        $query = $this->queryForResource($resourceName);
        $resource = $query->findOrFail($resourceId);

        $collection = $this->input('collection', null);
        $positionField = 'position';
        
        $queryMedias = $resource->medias()
            ->where('collection', $collection);

        $mediaStart = (clone $queryMedias)
            ->where($positionField, $this->from_position)
            ->firstOrFail();

        if($this->from_position < $this->to_position)
        {
            // Move down
            (clone $queryMedias)
                ->whereBetween($positionField, [$this->from_position + 1, $this->to_position])
                ->decrement($positionField);
        }
        else
        {
            // Move up
            (clone $queryMedias)
                ->whereBetween($positionField, [$this->to_position, $this->from_position - 1])
                ->increment($positionField);
        }

        $mediaStart->{$positionField} = $this->to_position;
        $mediaStart->save();
    }
}
