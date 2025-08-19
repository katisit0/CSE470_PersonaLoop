<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PersonaCard extends Component
{
    /**
     * Public properties of PersonaCard.
     */
    public $name;
    public $description;
    public $image;
    public $xp;
    public $showActions;

    /**
     * Create a new component instance.
     */
    public function __construct($name, $description, $image, $xp = null, $showActions = true)
    {
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
        $this->xp = $xp;
        $this->showActions = $showActions;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.persona-card');
    }
}
