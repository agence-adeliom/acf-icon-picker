<?php

namespace Plugins\AcfIconPicker;

use WordPlate\Acf\Fields\Attributes\DefaultValue;
use WordPlate\Acf\Fields\Attributes\Instructions;
use WordPlate\Acf\Fields\Attributes\Required;
use WordPlate\Acf\Fields\Field;

class IconPicker extends Field
{

    use DefaultValue;
    use Instructions;
    use Required;

    protected $type = 'icon-picker';

    /**
     * @param array $value
     */
    public function icons($value): self
    {
        $this->config->set('icons', $value);
        return $this;
    }

}
