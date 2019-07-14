<?php

namespace SheTest;

use Shopware\Components\Plugin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\ConsoleEvents as SymfonyConsoleEvents;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SheTest extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            SymfonyConsoleEvents::TERMINATE => 'onTerminate',
            //SymfonyConsoleEvents::COMMAND => 'onCommand',
            //SymfonyConsoleEvents::ERROR => 'onError',
            //SymfonyConsoleEvents::EXCEPTION => 'onException',
            strtolower('Shopware_Command_Before_Run') => 'onRun',
            KernelEvents::REQUEST => 'onRequest'
        ];
    }

    public function onTerminate(ConsoleEvent $event)
    {
        var_dump('On terminate:');
        var_dump($event->getCommand()->getName());
    }

    public function onRun(\Enlight_Event_EventArgs $args)
    {
        var_dump('On run:');
        var_dump($args->get('command')->getName());
    }

    public function onRequest(GetResponseEvent $event)
    {
        if($event->getRequest()->getPathInfo() === '/hello/') {
            $controller = new Controller();
            $controller->setContainer($this->container);
            $event->getRequest()->attributes->set('_controller', [$controller, 'testAction']);
        }
        if($event->getRequest()->get('hello')) {
            $event->setResponse(new Response('WORLD'));
        }
    }
}

class Controller
{
    use ContainerAwareTrait;

    public function testAction(Request $request)
    {
        $template = $this->container->get('template');
        $template->assign('name', $request->get('name'));
        return new Response($template->fetch('string: Hello {$name|escape} {action}'));
    }
}