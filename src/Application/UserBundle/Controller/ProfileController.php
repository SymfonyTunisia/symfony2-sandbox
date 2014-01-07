<?php

namespace Application\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Controller\ProfileController as BaseController;

/**
 * Class ProfileController
 *
 * @package Application\UserBundle\Controller
 *
 * @Route("/profile")
 */
class ProfileController extends BaseController
{
    /**
     * @Route("/", name="application_user_profile")
     * @template()
     */
    public function indexAction()
    {
        return array();
    }
}
