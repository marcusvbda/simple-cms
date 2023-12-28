<?php

namespace App\View\Components\Fields;

use Illuminate\Console\View\Components\Component;

class PricingCrud extends Component
{
    private $props = [];

    public function __construct($props)
    {
        $this->props = $props;
    }

    public function render()
    {
        $field = @$this->props["field"] ?? '';
        $defaultValue = json_encode(@$this->props["value"]);
        $penultimatePrice = json_encode(@$this->props["penultimatePrice"]);
        $productCode = @$this->props["productCode"] ?? '';
        $onlyView = @$this->props["onlyView"] ? 'true' : 'false';
        $history = @$this->props["history"] === false ? 'false' : 'true';

        return <<<HTML
            <pricing-crud field="$field" product_code="$productCode" :default_value='$defaultValue' :only_view='$onlyView' :history='$history' :penultimate_price='$penultimatePrice' :form="$onlyView ? {} : form" />
        HTML;
    }
}
