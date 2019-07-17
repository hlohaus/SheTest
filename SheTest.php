<?php

namespace SheTest;

use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use Shopware\Components\Controller\AbstractController;
use Shopware\Components\Plugin;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SheTest extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::TERMINATE => 'onTerminate',
            //SymfonyConsoleEvents::COMMAND => 'onCommand',
            //SymfonyConsoleEvents::ERROR => 'onError',
            //SymfonyConsoleEvents::EXCEPTION => 'onException',
            'Shopware_Command_Before_Run' => 'onRun',
            KernelEvents::REQUEST => ['onRequest', -100],
            'Shopware_Controllers_Frontend_Index::indexAction::before' => 'onIndex'
        ];
    }

    public function onIndex(\Enlight_Hook_HookArgs $args)
    {
        /** @var \Shopware_Controllers_Frontend_Index $subject */
        $subject = $args->getSubject();

        if ($subject->Request()->get('hello')) {
            $subject->View()->loadTemplate('string: Hello');
        }
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
            $event->getRequest()->attributes->set('_controller', Controller::class);
        }
        if($event->getRequest()->get('hello')) {
            $event->setResponse(new Response('WORLD'));
        }
    }
}


class Controller extends AbstractController
{
    public function __invoke(ContainerInterface $container, $name = 'World')
    {
        return $this->render('string:Hello {$name} {url}', ['name' => $name]);
    }
}