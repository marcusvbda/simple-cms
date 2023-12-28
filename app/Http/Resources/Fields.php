<?php

namespace App\Http\Resources;

use App\Http\Models\Field;
use App\Http\Models\InterfaceModel;
use App\Http\Models\Permission;
use App\Http\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Filters\FilterByOption;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Vstack;

class Fields extends Resource
{
    public $model = Field::class;

    public function label()
    {
        return "Campos";
    }

    public function singularLabel()
    {
        return "Campo";
    }

    public function icon()
    {
        return "el-icon-s-operation";
    }

    public function canImport()
    {
        return false;
    }

    public function canCreate()
    {
        return Auth::user()->hasPermissionTo('create-fields', "user");
    }

    public function canUpdate()
    {
        return Auth::user()->hasPermissionTo('edit-fields', "user");
    }

    public function canDelete()
    {
        return Auth::user()->hasPermissionTo('delete-fields', "user");
    }

    public function canViewList()
    {
        return Auth::user()->hasPermissionTo('viewlist-fields', "user");
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
                return Vstack::makeLinesHtmlAppend($row->name, $row->slug, $row->type, $row->tenant->name);
            }],
            "f_created_at_badge" => ["label" => "Criado em", "sortable_index" => "created_at"],
        ];
    }

    public function fields()
    {
        $tenant_id = request()->tenant_id ?? Auth::user()->tenant_id;
        $id = request()->id;

        $cards = [];
        $fields = [];
        $fields[] = new Text([
            "label" => "Nome",
            "field" => "name",
            "required" => true,
            "placeholder" => "Digite o nome do campo",
            "rules" => "required|min:3|max:255",
        ]);
        $fields[] = new Text([
            "label" => "Slug",
            "field" => "slug",
            "required" => true,
            "placeholder" => "Digite o slug do campo",
            "rules" => ["required", function ($attribute, $value, $fail) use ($id, $tenant_id) {
                $slug = Field::where("slug", $value)->where("tenant_id", $tenant_id)->where("id", "!=", $id)->first();
                if ($slug && $slug->id != request()->id) {
                    $fail('Slug em uso por outro campo desta interface');
                }
            }],

        ]);
        $fields[] = new Text([
            "label" => "Descrição",
            "field" => "description",
            "type" => "textarea",
            "rows" => 3,
            "placeholder" => "Digite a descrição do campo",
            "rules" => "max:255",
        ]);

        $fields[] = new BelongsTo([
            "label" => "Tipo",
            "field" => "type",
            "required" => true,
            "description" => "Selecione o tipo do campo",
            "options" => [
                ["value" => "Texto", "id" => "text"],
                ["value" => "Arquivo", "id" => "file"],
                ["value" => "Booleano", "id" => "checkbox"]
            ],
        ]);

        $cards[] = new Card("Informações", $fields);

        $fields = [];
        if (Auth::user()->role === "root") {
            $fields[] = new BelongsTo([
                "label" => "Tenant",
                "field" => "tenant_id",
                "required" => true,
                "description" => "Selecione o tenant",
                "model" => Tenant::class,
            ]);
        }
        $fields[] = new BelongsTo([
            "label" => "Interface",
            "field" => "interface_id",
            "required" => true,
            "description" => "Selecione a interface",
            "model" => InterfaceModel::class,
        ]);
        $cards[] = new Card("Acesso", $fields);

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

        $filters[] = new FilterByOption([
            "label" => "Interface",
            "field" => "interface_id",
            "model" => InterfaceModel::class
        ]);
        return $filters;
    }
}
