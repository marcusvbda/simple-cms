<?php

namespace App\Http\Resources;

use App\Http\Models\Integrator;
use App\Http\Models\InterfaceModel;
use App\Http\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Filters\FilterByOption;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Vstack;

class Integrators extends Resource
{
    public $model = Integrator::class;

    public function label()
    {
        return "Integradores";
    }

    public function singularLabel()
    {
        return "Integrador";
    }

    public function icon()
    {
        return "el-icon-s-help";
    }

    public function canImport()
    {
        return false;
    }

    public function canCreate()
    {
        return Auth::user()->hasPermissionTo('create-integrators', "user");
    }

    public function canUpdate()
    {
        return Auth::user()->hasPermissionTo('edit-integrators', "user");
    }

    public function canDelete()
    {
        return Auth::user()->hasPermissionTo('delete-integrators', "user");
    }

    public function canViewList()
    {
        return Auth::user()->hasPermissionTo('viewlist-access-groups', "user");
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
                return Vstack::makeLinesHtmlAppend($row->name, "********", $row->interface->name, $row->tenant->name);
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
            "placeholder" => "Digite o nome do integrador",
            "rules" => "required|min:3|max:255",
        ]);

        $fields[] = new BelongsTo([
            "label" => "Interface",
            "field" => "interface_id",
            "required" => true,
            "description" => "Selecione a interface",
            "model" => InterfaceModel::class,
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

        $fields[] = new Text([
            "label" => "Senha",
            "field" => "password",
            "type" => "password",
            "required" => true,
            "description" => "Digite a senha do integrador",
            "rules" => "required|min:3|max:255",
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
        return $filters;
    }
}
