<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Models\Traits\hasCode;

class InterfaceModel extends DefaultModel
{
    use hasCode;
    protected $table = "interfaces";
    public $guarded = ["created_at"];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, "tenant_id");
    }

    public function fields()
    {
        return $this->hasMany(Field::class, "interface_id");
    }

    public function getAttribute($key)
    {
        if (strpos($key, "field_") === false) return  parent::getAttribute($key);
        $field = Field::where("interface_id", $this->getOriginal("id"))->where("id", str_replace("field_", "", $key))->first();
        $fallback = "";
        if ($field == null) return "";
        if ($field->type == "checkbox") $fallback = false;
        if ($field->type == "file") $fallback = [];
        return @$field->value->value ?? $fallback;
    }
}
