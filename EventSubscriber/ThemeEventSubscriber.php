<?php

/*
 * This file is part of the CustomTimesheetFormBundle for Kimai 2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\CustomTimesheetFormBundle\EventSubscriber;

use App\Event\ThemeEvent;
use Symfony\Component\Asset\Packages;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class ThemeEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * @var Packages
     */
    private $packages;

    public function __construct(\Twig\Environment $twig, Packages $packages)
    {
        $this->twig = $twig;

        $this->packages = $packages;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ThemeEvent::JAVASCRIPT => ['renderContentAfter', 100],
        ];
    }

    /**
     * @param ThemeEvent $event
     */
    public function renderContentAfter(ThemeEvent $event)
    {
        $html = '';

        // timesheets can be edited/created from all pages via toolbar.
        // add timepicker scripts/css to all html pages

        $html .='
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css"
          integrity="sha256-zV9aQFg2u+n7xs0FTQEhY0zGHSFlwgIu7pivQiwJ38E=" crossorigin="anonymous"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"
            integrity="sha256-xoE/2szqaiaaZh7goVyF5p9C/qBu9dM3V5utrQaiJMc=" crossorigin="anonymous"></script>
';


        $url = $this->packages->getUrl('bundles/customtimesheetform/js/visible-media-query.js');
        $html .= '
    <script src="' . $url . '"></script>
    <div class="device-xs visible-xs-block"></div>
    <div class="device-sm visible-sm-block"></div>
    <div class="device-md visible-md-block"></div>
    <div class="device-lg visible-lg-block"></div>
';

        $event->addContent($html);
    }
}
