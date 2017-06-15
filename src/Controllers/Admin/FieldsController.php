<?php

namespace Helori\LaravelCms\Controllers\Admin;

use Illuminate\Http\Request;

use Helori\LaravelCms\Controllers\Controller;
use Helori\LaravelCms\Traits\ApiField;
use Helori\LaravelCms\Models\Field;


class FieldsController extends Controller
{
	use ApiField;

    public function __construct(Request $request)
    {
        $this->modelClass = Field::class;
        $this->where = [
            'fieldset_id' => $request->route()->parameter('fieldsetId')
        ];
        $this->read_with = [];
        $this->update_except = [];
    }

    // -------------------------------------------------------------
    //  Views
    // -------------------------------------------------------------
    public function field(Request $request, $fieldset_id)
    {
        $this->data['fieldset_id'] = $fieldset_id;
        return view('laravel-cms::admin.field', $this->data);
    }
}
