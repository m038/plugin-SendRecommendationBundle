<?php
/**
 * @package Newscoop\SendRecommendationBundle
 * @author Mischa Gorinskat <mischa.gorinskat@sourcefabric.org>
 * @copyright 2014 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\SendRecommendationBundle\EventListener;

use Newscoop\NewscoopBundle\Event\ConfigureMenuEvent;
use Symfony\Component\Translation\Translator;

class ConfigureMenuListener
{
    private $translator;

    /**
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu[$this->translator->trans('Plugins')]->addChild(
            $this->translator->trans('plugin.recommendation.label.menu'), 
            array('uri' => $event->getRouter()->generate('newscoop_sendrecommendation_admin_index'))
        );
    }
}
