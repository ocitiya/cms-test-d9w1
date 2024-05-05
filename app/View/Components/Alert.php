<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component {
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type,
        public string $message
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        if ($this->type == "error") return view('components.alert.error');
        else return view('components.alert.success'); 
    }
}
