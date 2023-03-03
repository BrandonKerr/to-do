<?php

namespace App\View\Components\Checklist;

use Illuminate\View\Component;

class Show extends Component {
    public $checklist;

    public $todos;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($checklist, $all = false) {
        $this->checklist = $checklist;
        if ($all) {
            $this->todos = $checklist->todos()->withTrashed()->get();
        } else {
            $this->todos = $checklist->todos;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view("components.checklist.show");
    }
}
