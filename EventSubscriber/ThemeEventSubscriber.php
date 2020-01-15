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

final class ThemeEventSubscriber implements EventSubscriberInterface
{
    private $twig;

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
        $url = $this->packages->getUrl('bundles/customtimesheetform/js/visible-media-query.js');

        $html = '
    <script src="' . $url . '"></script>
    <div class="device-xs visible-xs-block"></div>
    <div class="device-sm visible-sm-block"></div>
    <div class="device-md visible-md-block"></div>
    <div class="device-lg visible-lg-block"></div>
';

        $event->addContent($html);
    }
}
