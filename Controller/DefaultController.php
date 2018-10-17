<?php

namespace App\Controller;

use App\Response\View;

class DefaultController
{
    /**
     * @return View
     */
    function DefaultAction()
    {

        return new View('default');

    }
}