<?php

namespace App\View\Components\Fields;

use Illuminate\Console\View\Components\Component;

class ListFields extends Component
{
    private $props = [];

    public function __construct($props)
    {
        $this->props = $props;
    }

    public function render()
    {
        $this->makeSlot();
        return view('vStack::resources.field.default-field',  $this->props);
    }

    private function makeSlot()
    {
        $field = $this->props["field"];
        $defaultValue = json_encode($this->props["value"] ?? []);

        $this->props["slot"] = <<<HTML
            <list-custom-fields field="$field" :default_value='$defaultValue' :form="form" />
        HTML;
    }
}
