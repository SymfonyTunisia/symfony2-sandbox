<?php

namespace Application\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\RegistrationController as BaseController;


class RegistrationController extends BaseController
{
    use ControllerFilters;

    public function registerAction(Request $request)
    {
        if (null !== ($response = $this->anonymousOnlyFilter()))
            return $response;

        return parent::registerAction($request);
    }

    public function checkEmailAction()
    {
        if (null !== ($response = $this->anonymousOnlyFilter()))
            return $response;

        return parent::checkEmailAction();
    }

    public function confirmAction(Request $request, $token)
    {
        if (null !== ($response = $this->anonymousOnlyFilter()))
            return $response;

        return parent::confirmAction($request, $token);
    }
}
