<?php 
namespace GLZeist\Bundle\ProgrammaBundle\Listener;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class BreadcrumbListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) 
        {
            
        }
    }
}