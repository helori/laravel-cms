<?php

namespace Helori\LaravelCms\Requests;


class ResourceReorder extends AdminBase
{
    public function rules()
    {
        return [
            'from_position' => 'required|integer',
            'to_position' => 'required|integer',
        ];
    }

    public function handle($resourceName)
    {
        $query = $this->queryForResource($resourceName, true);
        $config = $this->getResourceConfig($resourceName);
        $positionField = $config['position'] ?? null;

        if(!$positionField)
        {
            throw new \Exception("Resource '{$resourceName}' does not support reordering (position field must be set in resource config)");
        }

        $itemStart = (clone $query)
            ->where($positionField, $this->from_position)
            ->firstOrFail();

        if($this->from_position < $this->to_position)
        {
            // Move down
            (clone $query)
                ->whereBetween($positionField, [$this->from_position + 1, $this->to_position])
                ->decrement($positionField);
        }
        else
        {
            // Move up
            (clone $query)
                ->whereBetween($positionField, [$this->to_position, $this->from_position - 1])
                ->increment($positionField);
        }

        $itemStart->{$positionField} = $this->to_position;
        $itemStart->save();
    }
}
