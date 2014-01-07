<?php

namespace Application\UserBundle\Controller\Admin;

use Doctrine\ORM\EntityRepository;
use Application\UserBundle\Entity\Group;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class GroupsController
 *
 * @package Application\UserBundle\Controller\Admin
 *
 * @Route("/admin/groups")
 */
class GroupsController extends Controller
{
    /**
     * @Route("/", name="application_user_admin_groups")
     * @Template()
     */
    public function listAction()
    {
        return array(
            'groups' => $this->getDoctrine()->getRepository('ApplicationUserBundle:Group')->findAllWithCount(),
        );
    }

    /**
     * @Route("/create", name="application_user_admin_groups_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $groupManager = $this->get('fos_user.group_manager');
        $groupType = $this->get('application_user.groups.group_type');
        $group = $groupManager->createGroup("");

        $form = $this->createForm($groupType, $group, array(
            'action' => $this->generateUrl('application_user_admin_groups_create'),
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $translator = $this->get('translator');
            $flashbag = $this->get('session')->getFlashBag();

            try {
                $groupManager->updateGroup($group);

                $flashbag->add('success', $translator->trans('user.admin.groups.create.flash_success'));
                return $this->redirect($this->generateUrl('application_user_admin_groups'));

            } catch (\Exception $e) {
                $flashbag->add('error', $translator->trans('user.admin.groups.create.flash_error'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/edit/{id}", name="application_user_admin_groups_edit")
     * @Template()
     */
    public function editAction(Request $request, Group $group)
    {
        $groupManager = $this->get('fos_user.group_manager');
        $groupType = $this->get('application_user.groups.group_type');

        $form = $this->createForm($groupType, $group, array(
            'action' => $this->generateUrl('application_user_admin_groups_edit', array(
                'id' => $group->getId()
            )),
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $translator = $this->get('translator');
            $flashbag = $this->get('session')->getFlashBag();

            try {
                $groupManager->updateGroup($group);

                $flashbag->add('success', $translator->trans('user.admin.groups.edit.flash_success'));
                return $this->redirect($this->generateUrl('application_user_admin_groups_edit', array(
                    'id' => $group->getId()
                )));

            } catch (\Exception $e) {
                $flashbag->add('error', $translator->trans('user.admin.groups.edit.flash_error'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/delete/{id}", name="application_user_admin_groups_delete")
     * @Template()
     */
    public function deleteAction(Request $request, Group $group)
    {
        if ($group->isDefault())
            throw new AccessDeniedException("Not allowed to delete default group");

        $form = $this->createDeleteForm($group->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $translator = $this->get('translator');
            $flashbag = $this->get('session')->getFlashBag();

            try {
                if ($form->get('id')->getData() == $group->getId()) {
                    $groupManager = $this->get('fos_user.group_manager');

                    $moveTo = $form->get('newgroup')->getData();
                    $groupManager->deleteGroupAndMoveUsers($group, $moveTo);

                    $flashbag->add('success', $translator->trans('user.admin.groups.delete.flash_success'));
                } else {
                    throw new \Exception;
                }
            } catch (\Exception $e) {
                $flashbag->add('error', $translator->trans('user.admin.groups.delete.flash_error'));
            }

            return $this->redirect($this->generateUrl('application_user_admin_groups'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to delete a Group entity by id.
     *
     * @param integer $id
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->add('newgroup', 'entity', array(
                'class' => 'ApplicationUserBundle:Group',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) use ($id) {
                    return $er->createQueryBuilder('g')
                        ->where('g.id <> :id')
                        ->orderBy('g.default', 'DESC')
                        ->setParameter('id', $id);
                }
            ))
            ->add('submit', 'submit')
            ->setMethod('POST')
            ->setAction($this->generateUrl('application_user_admin_groups_delete', array(
                'id' => $id
            )))
            ->getForm();
    }
}
