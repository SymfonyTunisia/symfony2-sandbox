<?php
namespace Application\UserBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Application\SiteBundle\Entity\Application;
use Application\SiteBundle\Entity\Tag;
use Application\SiteBundle\Entity\ApplicationHasTag;

/**
 * 
 */
class TagsToIdsTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;
    
    private $Application;

    /**
     * @param ObjectManager $om
     */
    public function __construct($om, Application $Application)
    {
        $this->om = $om;
        $this->Application = $Application;
    }

    /**
     * Transform a collection of Tag objects into a string
     *
     * @param  DoctrineCollection|null $collection
     * @return string $output a string of Tag titles separated by ","
     */
    public function transform($collection)
    {
        if (null === $collection) {
            return "";
        }
        
        $output = '';
        
        foreach ($collection as $k => $item) {
            // add separator
            if ($k != 0) {
                $output .= ',';
            }
             // add tag title
            $output .= $item->getTag()->getTitle();
        }

        return $output;
    }

    /**
     * Transform a string (Tag ids separated by ",") to an array of ApplicationHasTag objects
     *
     * @param  string $tags
     * @return array|null
     */
    public function reverseTransform($tags)
    {
        if (!$tags) {
            return null;
        }
        
        // array of ApplicationHasTag objects
        $output = array();
        
        // explode the $tags string; tag titles separated by ","
        $array = explode(',', $tags);
        
        // iterate on tag titles
        foreach ($array as $tagTitle) {
            
            // title of the tag (lowered and trimmed)
            $tagTitle = strtolower(trim($tagTitle));
            
            // check if tag title is empty
            if ($tagTitle == '') {
                continue;
            }
            
            // try to find existing Tag; if not found, create a new one
            $tag = $this->om->getRepository('ApplicationSiteBundle:Tag')->findOneBy(array('title' => $tagTitle));
            
            // if not found, create a new Tag
            if (!$tag) {
                $tag = new Tag();
                $tag->setTitle($tagTitle);
            }
            
            // try to find existing ApplicationHasTag
            $ApplicationHasTag = $this->om->getRepository('ApplicationSiteBundle:ApplicationHasTag')->findOneBy(array(
                'tag'    => $tag->getId(),
                'Application'  => $this->Application->getId()
            ));
            
            // if $ApplicationHasTag is not found, create a new one
            if (!$ApplicationHasTag) {
                $ApplicationHasTag = new ApplicationHasTag();
                $ApplicationHasTag->setTag($tag);
                $ApplicationHasTag->setApplication($this->Application);
            }
            
            // add $ApplicationHasTag to returning array
            $output[] = $ApplicationHasTag;
        }
        
        return $output;
    }
}
