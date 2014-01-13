<?php

namespace Application\MessageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApplicationMessageBundle extends Bundle
{
    /**
     * extends FOSMessageBundle
     */
    public function getParent()
    {
        return 'FOSMessageBundle';
    }
}
