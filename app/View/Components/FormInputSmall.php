<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormInputSmall extends Component
{
    public $name;
    public $type;
    public $placeholder;

    public function __construct($name, $type = 'text', $placeholder = 'Type here...')
    {
        $this->name = $name;
        $this->type = $type;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.form-input-small');
    }
}
