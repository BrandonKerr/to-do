<?php

namespace App\Models\Definitions;

/**
 * Class to define the attributes of a Todo model, so that we can ensure attributes are set
 */
class TodoDefinition {
    public function __construct(public string $title, public bool $is_complete) {
    }
}
