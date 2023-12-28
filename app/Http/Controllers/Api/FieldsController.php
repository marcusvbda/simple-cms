<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FieldsController extends Controller
{
	public function getFields(Request $request, $field = null)
	{
		$integrator = $request->integrator;
		$interface = $integrator->interface;

		$fieldsQuery = $interface->fields();
		if (!$field) return response()->json($fieldsQuery->get());
		return response()->json($fieldsQuery->where("slug", $field)->first());
	}
}
