<?php

namespace Mastop\AdminBundle;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Mastop\AdminBundle\Twig\Extension\AdmiExtension;

class ControllerListener
{
    protected $extension;

    public function __construct(UserExtension $extension)
    {
        $this->extension = $extension;
    }

    public function onCoreController(FilterControllerEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            $this->extension->setController($event->getController());
        }
    }
}
