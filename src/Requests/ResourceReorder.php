<?php

namespace Helori\LaravelCms\Requests;


class ResourceReorder extends AdminBase
{
    public function rules()
    {
        return [
            'from_position' => 'required|integer',
            'to_position' => 'required|integer',
            'position_field' => 'required|string',
        ];
    }

    public function handle($resourceName)
    {
        $classname = $this->getResourceClass($resourceName);
        $positionField = $this->input('position_field', 'position');
        
        $itemStart = $classname::query()
            ->where($positionField, $this->from_position)
            ->firstOrFail();

        $itemStart->$positionField = $this->to_position;
        $itemStart->save();

        if($this->from_position < $this->to_position)
        {
            // Move down
            $classname::query()
                ->whereBetween($positionField, [$this->from_position + 1, $this->to_position])
                ->decrement($positionField);
        }
        else
        {
            // Move up
            $classname::query()
                ->whereBetween($positionField, [$this->to_position, $this->from_position - 1])
                ->increment($positionField);
        }
    }
}
