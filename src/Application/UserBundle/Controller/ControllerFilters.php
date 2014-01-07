<?php

namespace Application\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

Trait ControllerFilters
{
    public function anonymousOnlyFilter()
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return new RedirectResponse($this->container->get('router')->generate('application_site_home'));
        }

        return null;
    }
}
