<?php

/** app/Support/ViewHelpers.php **/

function include_action($controller, $action)
{
    if (is_callable(array($controller, $action))) {
        $c = new $controller;
        return $c->$action();
    }
}
