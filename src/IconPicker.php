<?php

namespace Adeliom\Acf\Fields;

use Extended\ACF\Fields\Settings\DefaultValue;
use Extended\ACF\Fields\Settings\Instructions;
use Extended\ACF\Fields\Settings\Required;
use Extended\ACF\Fields\Field;

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
