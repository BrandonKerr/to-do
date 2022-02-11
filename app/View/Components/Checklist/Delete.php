<?php

namespace App\View\Components\Checklist;

use Illuminate\View\Component;

class Delete extends Component
{

    public $checklist;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($checklist)
    {
        $this->checklist = $checklist;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.checklist.delete');
    }
}
