<?php

namespace App\Actions;

abstract class BaseAction
{
    /**
     * Execute the action.
     *
     * Concrete actions should define a stricter method signature that matches
     * their specific use case.
     */
    abstract public function execute(...$arguments): mixed;
}
