<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class Field extends DefaultModel
{
	use hasCode;
	protected $table = "fields";
	public $guarded = ["created_at"];
	public $casts = [
		"value" => "object"
	];

	public function tenant()
	{
		return $this->belongsTo(Tenant::class, "tenant_id");
	}
}
