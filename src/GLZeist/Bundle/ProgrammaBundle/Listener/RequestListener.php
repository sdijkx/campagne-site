<?php
namespace GLZeist\Bundle\ProgrammaBundle\Listener;
use Symfony\Component\Security\Core\SecurityContextInterface;
class RequestListener 
{
    private $reader;
    private $securityContext;
    
    public function __construct($reader,SecurityContextInterface $securityContext)
    {
        $this->reader = $reader;
        $this->securityContext=$securityContext;
    }    
    public function onKernelController(\Symfony\Component\HttpKernel\Event\FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController()))
        { //return if no controller
            return;
        }

        $object = new \ReflectionObject($controller[0]); // get controller
        $method = $object->getMethod($controller[1]); // get method
        $granted=true;
        
        foreach ($this->reader->getClassAnnotations($object) as $configuration)
        {
            if ($configuration instanceof \GLZeist\Bundle\ProgrammaBundle\Annotation\Granted)
            {
                $granted=$this->isGranted($configuration->role);
            }
        }

        foreach ($this->reader->getMethodAnnotations($method) as $configuration)
        { //Start of annotations reading

            if ($configuration instanceof \GLZeist\Bundle\ProgrammaBundle\Annotation\Granted)
            {
                $granted=$this->isGranted($configuration->role);
            }
        }
        if(!$granted)
        {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
        
    }
    
    private function isGranted($role)
    {
        return $this->securityContext->isGranted($role);
    }
    
}
