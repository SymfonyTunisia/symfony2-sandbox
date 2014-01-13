<?php

namespace Application\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Application\SiteBundle\Entity\Application;
use Application\UserBundle\Form\Type\ApplicationType;

/**
 * Application controller.
 *
 * @Route("/user/Application")
 */
class ApplicationController extends Controller
{
    /**
     * Lists all Application entities.
     *
     * @Route("/", name="user_Application")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationSiteBundle:Application')->findBy(
            array('user' => $user->getId()),
            array('title' => 'ASC')
        );

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * Lists all starred Application entities.
     *
     * @Route("/starred", name="user_application_starred")
     * @Template()
     */
    public function starredAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $entities = $user->getStarredApplication();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Application entity.
     *
     * @Route("/new", name="user_application_new", options={"expose"=true})
     * @Template("ApplicationUserBundle:Application:edit.html.twig")
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Application();

        if ($this->getRequest()->getMethod() == 'POST') {
            $c = $this->getRequest()->get('content');

            $c = html_entity_decode($c);

            $entity->setContent($c);
        }

        $form   = $this->createForm(new ApplicationType($em), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Application entity.
     *
     * @Route("/create", name="user_application_create")
     * @Method("POST")
     * @Template("ApplicationUserBundle:Application:edit.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity  = new Application();
        $form = $this->createForm(new ApplicationType($em), $entity);
        
        $formHandler = $this->container->get('application.form.handler');
        
        if ($formHandler->process($form)) {
            // set the user
            $user = $this->container->get('security.context')->getToken()->getUser();
            $entity->setUser($user);

            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'alert.application.create.success');

            return $this->redirect($this->generateUrl('user_application_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('danger', 'alert.application.create.error');

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Application entity.
     *
     * @Route("/{id}/edit", name="user_application_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationSiteBundle:Application')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Application entity.');
        }

        // check user permissions on this Application
        $this->checkUserCanEditApplication($entity);

        $editForm = $this->createForm(new ApplicationType($em), $entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Application entity.
     *
     * @Route("/{id}/update", name="user_application_update")
     * @Method("POST")
     * @Template("ApplicationUserBundle:Application:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationSiteBundle:Application')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Application entity.');
        }

        // check user permissions on this Application
        $this->checkUserCanEditApplication($entity);

        $form = $this->createForm(new ApplicationType($em), $entity);
        $formHandler = $this->container->get('application.form.handler');

        if ($formHandler->process($form)) {
            $this->get('session')->getFlashBag()->add('success', 'alert.application.update.success');

            return $this->redirect($this->generateUrl('user_application_edit', array('id' => $id)));
        }

        $this->get('session')->getFlashBag()->add('error', 'alert.application.update.error');

        return array(
            'entity'  => $entity,
            'form'    => $form->createView(),
        );
    }
    
    /**
     * Return the markdown formattation of an input text
     * 
     * \@TODO: refactor this function to some general utility class
     * 
     * @Route("/_render-markdown", name="_user_application_renderMarkdown")
     * @Template()
     * @method("POST")
     * 
     * @param unknown_type $content
     */
    public function _renderMarkdownAction()
    {
        $content = $this->getRequest()->get('content');
        
        return array('content' => $content);
    }

    /**
     * Deletes a Application entity.
     *
     * @Route("/{id}/delete", name="user_application_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handle($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationSiteBundle:Application')->find($id);
            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Application entity.');
            }

            // check user permissions on this Application
            $this->checkUserCanEditApplication($entity);
            
            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'alert.application.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'alert.application.delete.error');
        }

        return $this->redirect($this->generateUrl('user_Application'));
    }
    
    /**
     * returns a partial template to delete a Application
     * 
     * @Template
     */
    public function _deleteFormAction($id)
    {
        return array(
            'form' =>$this->createDeleteForm($id)->createView(),
            'id' => $id
        );
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * check if a uer can edit a Application
     * 
     * @param unknown_type $Application
     * @throws AccessDeniedException
     */
    private function checkUserCanEditApplication(Application $Application)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        if (!$Application->getUser() || $Application->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('Yo are not allowed to access this content');
        }
    }
    
    /**
     * Toggle the "published" state of a Application
     * 
     * @Route("/toggle-published/{id}", name="user_application_toggle_published")
     * 
     * @param unknown_type $id
     */
    public function togglePublishedAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationSiteBundle:Application')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Application entity.');
        }
        
        // check user permissions on this Application
        $this->checkUserCanEditApplication($entity);

        // toggle "published"
        $entity->setPublished(!$entity->getPublished());
        
        // saves the entity
        $em->persist($entity);
        $em->flush();
        
        if ($entity->getPublished()) {
            $this->get('session')->getFlashBag()->add('success', 'alert.application.togglePublished.published');
        } else {
            $this->get('session')->getFlashBag()->add('success', 'alert.application.togglePublished.unpublished');
        }
        
        return $this->redirect($this->generateUrl('user_Application'));
    }
}
