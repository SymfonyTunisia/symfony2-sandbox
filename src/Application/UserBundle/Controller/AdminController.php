<?php

namespace Application\UserBundle\Controller;

use Application\AdminBundle\Controller\DashboardInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller implements DashboardInterface
{
    public function dashboardAction()
    {
        $translator = $this->get('translator');

        return array(
            'module_title' => $translator->trans('user.admin.dashboard.title'),
            'module_links' => array(
                $translator->trans('user.admin.dashboard.users_management') => array(
                    'url' => $this->generateUrl('application_user_admin_users'),
                    'role' => 'ROLE_USER_ADMIN_USERS'
                ),
                $translator->trans('user.admin.dashboard.groups_management') => array(
                    'url' => $this->generateUrl('application_user_admin_groups'),
                    'role' => 'ROLE_USER_ADMIN_GROUPS'
                ),
            )
        );
    }
}
