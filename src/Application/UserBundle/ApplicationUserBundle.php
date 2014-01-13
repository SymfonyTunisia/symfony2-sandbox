<?php

namespace Application\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApplicationUserBundle extends Bundle
{
    /**
     * extends FOSUserBundle
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
