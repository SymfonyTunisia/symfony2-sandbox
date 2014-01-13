<?php
namespace Application\UserBundle\Form\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;

use Application\SiteBundle\Entity\Application;

class ApplicationFormHandler
{
    protected $form_factory;
    protected $request;
    protected $em;
    
    protected $originalApplicationHasTags = array();

    public function __construct(FormFactory $form_factory, Request $request, $em)
    {
        $this->form_factory = $form_factory;
        $this->request = $request;
        $this->em = $em;
    }

    public function process($form)
    {
        if ('POST' === $this->request->getMethod()) {

            $Application = $form->getData();
            
            // ApplicationHasTag (array) before binding request
            $this->originalApplicationHasTags = $Application->getApplicationHasTags()->toArray();
            
            $form->handleRequest($this->request);

            if ($form->isValid()) {
                $this->onSuccess($Application);

                return true;
            }
        }

        return false;
    }

    protected function onSuccess(Application $Application)
    {
        $this->manageTags($Application);
        
        $this->em->persist($Application);
        $this->em->flush();
    }
    
    protected function manageTags(Application $Application) {
        
        // ApplicationHasTag array related to $Application
        $ApplicationHasTags = $Application->getApplicationHasTags();
        
        // array of ApplicationHasTag id after associating request
        $ApplicationHasTagsArrayIds = (count($ApplicationHasTags) > 0) ? array_map(function($value) {return $value->getId();}, $ApplicationHasTags->toArray()) : array();
        
        // filter $this->originalApplicationHasTags to contain ApplicationHasTag no longer present
        foreach ($this->originalApplicationHasTags as $k => $ApplicationHasTag) {
            if (in_array($ApplicationHasTag->getId(), $ApplicationHasTagsArrayIds)) {
                unset($this->originalApplicationHasTags[$k]);
            }
        }
        
        // remove no longer associated ApplicationHasTags
        foreach ($this->originalApplicationHasTags as $ApplicationHasTag) {
            $this->em->remove($ApplicationHasTag);
        }
        
        /*
        // save new ApplicationHasTags
        foreach ($Application->getApplicationHasTags() as $ApplicationHasTag) {
            
            if (is_null($ApplicationHasTag->getId())) {
                
                // title of the tag (lowered and trimmed)
                $tagTitle = strtolower(trim($ApplicationHasTag->getTag()->getTitle()));
                
                // try to find existing tag; if not found, create a new one
                $tag = $this->em->getRepository('ApplicationSiteBundle:Tag')->findOneBy(array(
                    'title' => $tagTitle
                ));
                
                // existing tag found: associate it to $Application
                if ($tag) {
                    $ApplicationHasTag->setTag($tag);
                
                // create a new tag
                } else {
                    $tag = new Tag();
                    $tag->setTitle($tagTitle);
                }
            }
        }
        */
        
    }
}