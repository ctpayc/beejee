<?php

namespace Core;

/**
 * Base Controller
 */
class BaseController {

    protected function getFlashMessages() {
        $flash = null;

        if (isset($_SESSION["flash"]))
        {
            $flash = [
                'type' => $_SESSION["flash"]['type'],
                'message' => $_SESSION["flash"]['message'],
            ];
            unset($_SESSION["flash"]);
        }

        return $flash;
    }
}