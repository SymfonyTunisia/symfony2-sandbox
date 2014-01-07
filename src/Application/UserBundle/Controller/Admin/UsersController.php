<?php

namespace Application\UserBundle\Controller\Admin;

use Application\UserBundle\Entity\User;
use Application\UserBundle\Form\Type\EditUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Application\UserBundle\Form\Type\AddUserType;

/**
 * Class UsersController
 *
 * @package Application\UserBundle\Controller\Admin
 *
 * @Route("/admin/users")
 */
class UsersController extends Controller
{
    /**
     * @Route("/", name="application_user_admin_users")
     * @Template()
     */
    public function listAction()
    {
        return array(
            'users' => $this->getDoctrine()->getRepository('ApplicationUserBundle:User')->findAllWithGroups(),
        );
    }

    /**
     * @Route("/create", name="application_user_admin_users_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $form = $this->createForm(new AddUserType(), $user, array(
            'action' => $this->generateUrl('application_user_admin_users_create'),
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $translator = $this->get('translator');
            $flashbag = $this->get('session')->getFlashBag();

            try {
                $userManager->updateUser($user);

                $flashbag->add('success', $translator->trans('user.admin.users.create.flash_success'));
                return $this->redirect($this->generateUrl('application_user_admin_users'));

            } catch (\Exception $e) {
                $flashbag->add('error', $translator->trans('user.admin.users.create.flash_error'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/edit/{id}", name="application_user_admin_users_edit")
     * @Template()
     */
    public function editAction(Request $request, User $user)
    {
        $userManager = $this->get('fos_user.user_manager');

        $form = $this->createForm(new EditUserType(), $user, array(
            'action' => $this->generateUrl('application_user_admin_users_edit', array(
                'id' => $user->getId()
            )),
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $translator = $this->get('translator');
            $flashbag = $this->get('session')->getFlashBag();

            try {
                $userManager->updateUser($user);

                $flashbag->add('success', $translator->trans('user.admin.users.edit.flash_success'));
                return $this->redirect($this->generateUrl('application_user_admin_users_edit', array(
                    'id' => $user->getId()
                )));

            } catch (\Exception $e) {
                $flashbag->add('error', $translator->trans('user.admin.users.edit.flash_error'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/delete/{id}", name="application_user_admin_users_delete")
     * @Template()
     */
    public function deleteAction(Request $request, User $user)
    {
        $userManager = $this->get('fos_user.user_manager');

        $form = $this->createDeleteForm($user->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $translator = $this->get('translator');
            $flashbag = $this->get('session')->getFlashBag();

            try {
                if ($form->get('id')->getData() == $user->getId()) {
                    $userManager->deleteUser($user);
                    $flashbag->add('success', $translator->trans('user.admin.users.delete.flash_success'));
                } else {
                    throw new \Exception;
                }
            } catch (\Exception $e) {
                $flashbag->add('error', $translator->trans('user.admin.users.delete.flash_error'));
            }

            return $this->redirect($this->generateUrl('application_user_admin_users'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param integer $id
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->add('submit', 'submit')
            ->setMethod('POST')
            ->setAction($this->generateUrl('application_user_admin_users_delete', array(
                'id' => $id
            )))
            ->getForm();
    }
}
