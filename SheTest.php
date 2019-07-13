<?php

namespace SheTest;

use Shopware\Components\Plugin;
use Symfony\Component\Console\ConsoleEvents as SymfonyConsoleEvents;
use Symfony\Component\Console\Event\ConsoleEvent;
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
            $event->getRequest()->attributes->set('_controller', [$controller, 'testAction']);
            // or $event->setResponse(new Response('Hello WORLD'));
        }
    }
}

class Controller
{
    public function testAction(Request $request)
    {
        return new Response('Hello ' . htmlspecialchars($request->get('name', 'World')));
    }
}