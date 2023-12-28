<?php

namespace App\Http\Resources;

use App\Http\Models\InterfaceModel;
use App\Http\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Check;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Fields\Upload;
use marcusvbda\vstack\Filters\FilterByOption;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Vstack;

class Interfaces extends Resource
{
    public $model = InterfaceModel::class;

    public function label()
    {
        return "Interfaces";
    }

    public function singularLabel()
    {
        return "Interface";
    }

    public function icon()
    {
        return "el-icon-picture-outline-round";
    }

    public function canImport()
    {
        return false;
    }

    public function canCreate()
    {
        return Auth::user()->hasPermissionTo('create-interfaces', "user");
    }

    public function canUpdate()
    {
        return Auth::user()->hasPermissionTo('edit-interfaces', "user");
    }

    public function canDelete()
    {
        return Auth::user()->hasPermissionTo('delete-interfaces', "user");
    }

    public function canViewList()
    {
        return Auth::user()->hasPermissionTo('viewlist-interfaces', "user");
    }

    public function canViewReport()
    {
        return false;
    }

    public function search()
    {
        return ["name"];
    }

    public function table()
    {
        return [
            "code" => ["label" => "#", "sortable_index" => "id", "width" => "80px"],
            "name" => ["label" => "Nome", "width" => "200px", "handler" => function ($row) {
                return Vstack::makeLinesHtmlAppend($row->name, $row->tenant->name);
            }],
            "f_created_at_badge" => ["label" => "Criado em", "sortable_index" => "created_at"],
        ];
    }

    public function fields()
    {
        $cards = [];
        $fields = [];
        $fields[] = new Text([
            "label" => "Nome",
            "field" => "name",
            "required" => true,
            "description" => "Digite o nome do grupo de acesso",
        ]);

        $cards[] = new Card("Informações básicas", $fields);

        if (Auth::user()->role === "root") {
            $fields = [];
            $fields[] = new BelongsTo([
                "label" => "Tenant",
                "field" => "tenant_id",
                "required" => true,
                "description" => "Selecione o tenant",
                "model" => Tenant::class,
            ]);
            $cards[] = new Card("Acesso", $fields);
        }


        $interface = request()->content;
        if ($interface) {
            $fields = [];
            foreach ($interface->fields as $field) {
                switch ($field->type) {
                    case "checkbox":
                        $fields[] = new Check([
                            "label" => $field->name,
                            "field" => "field_" . $field->id,
                            "description" => $field->description,
                            // "default" => @$field->boolValue ?? false
                        ]);
                        break;
                    case "text":
                        $fields[] = new Text([
                            "label" => $field->name,
                            "field" => "field_" . $field->id,
                            "description" => $field->description,
                            // "default" => @$field->value ?? ''
                        ]);
                        break;
                    case "file":
                        $fields[] = new Upload([
                            "label" => $field->name,
                            "field" => "field_" . $field->id,
                            "description" => $field->description,
                            // "default" => @$field->valueList ?? []
                        ]);
                        break;
                };
            }
            $cards[] = new Card("Conteúdo", $fields);
        }
        return $cards;
    }

    public function filters()
    {
        $filters = [];
        if (Auth::user()->role === "root") {
            $filters[] = new FilterByOption([
                "label" => "Tenant",
                "field" => "tenant_id",
                "model" => Tenant::class
            ]);
        }
        return $filters;
    }

    public function storeMethod($id, $data)
    {
        if ($id) {
            $fields = ["data" => [], "upload" => []];
            foreach ($data["data"] as $key => $value) {
                if (strpos($key, "field_") !== false) {
                    $fields["data"][] = [
                        "field_id" => str_replace("field_", "", $key),
                        "value" => $value
                    ];
                    unset($data["data"][$key]);
                }
            }
            foreach ($data["upload"] as $key => $value) {
                if (strpos($key, "field_") !== false) {
                    $fields["upload"][] = [

                        "field_id" => str_replace("field_", "", $key),
                        "value" => $value
                    ];
                    unset($data["upload"][$key]);
                }
            }
            $result = parent::storeMethod($id, $data);

            $model = data_get($result, "model");
            foreach ($fields["data"] as $field) {
                $modelField = $model->fields()->findOrFail($field["field_id"]);
                if ($modelField->type == "checkbox") $modelField->value = ["value" => $field["value"] ? true : false];
                if ($modelField->type == "text") $modelField->value = ["value" => $field["value"] ?? ''];
                $modelField->save();
            }

            foreach ($fields["upload"] as $field) {
                $modelField = $model->fields()->findOrFail($field["field_id"]);
                if ($modelField->type == "file")  $modelField->value = ["value" => $field["value"] ?? []];
                $modelField->save();
            }

            return $result;
        }
        return $data;
    }
}
