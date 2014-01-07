<?php

namespace Application\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController
{
    use ControllerFilters;

    public function loginAction(Request $request)
    {
        if (null !== ($response = $this->anonymousOnlyFilter()))
            return $response;

        return parent::loginAction($request);
    }
}
