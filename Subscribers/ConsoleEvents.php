<?php

namespace SheTest\Subscribers;

use Symfony\Component\Console\ConsoleEvents as SymfonyConsoleEvents;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConsoleEvents implements EventSubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            SymfonyConsoleEvents::TERMINATE => 'onTerminate',
            //SymfonyConsoleEvents::COMMAND => 'onCommand',
            //SymfonyConsoleEvents::ERROR => 'onError',
            //SymfonyConsoleEvents::EXCEPTION => 'onException',
            strtolower('Shopware_Command_Before_Run') => 'onRun'
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
}