<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class Integrator extends DefaultModel
{
	use hasCode;
	protected $table = "integrators";
	public $guarded = ["created_at"];

	public function interface()
	{
		return $this->belongsTo(InterfaceModel::class, "interface_id");
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class, "tenant_id");
	}

	public function fields()
	{
		return $this->hasMany(Field::class, "integrator_id");
	}
}
