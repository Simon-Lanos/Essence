<?php

namespace App\Controller;

use App\Response\View;

class DefaultController
{
    /**
     * @return View
     */
    function defaultAction()
    {

        return new View('default');

    }
}
