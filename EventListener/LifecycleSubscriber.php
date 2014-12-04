<?php
/**
 * @package Newscoop\SendRecommendationBundle
 * @author Mischa Gorinskat <mischa.gorinskat@sourcefabric.org>
 * @copyright 2014 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\SendRecommendationBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newscoop\EventDispatcher\Events\GenericEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Event lifecycle management
 */
class LifecycleSubscriber implements EventSubscriberInterface
{
    private $container;

    private $em;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->em = $this->container->get('em');
    }

    public function install(GenericEvent $event)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->updateSchema($this->getClasses(), true);

        // Generate proxies for entities
        $this->em->getProxyFactory()->generateProxyClasses($this->getClasses(), __DIR__ . '/../../../../library/Proxy');

        $preferencesService = $this->container->get('system_preferences_service');
        $preferencesService->set('SendRecommendationEmail', 'email@example.com');
    }

    public function update(GenericEvent $event)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->updateSchema($this->getClasses(), true);

        // Generate proxies for entities
        $this->em->getProxyFactory()->generateProxyClasses($this->getClasses(), __DIR__ . '/../../../../library/Proxy');
    }

    public function remove(GenericEvent $event)
    {   
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->dropSchema($this->getClasses(), true);

        $removeEmail = $this->em->getRepository('Newscoop\NewscoopBundle\Entity\SystemPreferences')->findOneBy(array(
            'option' => 'SendRecommendationEmail'
        ));

        $this->em->remove($removeEmail);
        $this->em->remove($removeNonUserPref);
        $this->em->flush();
    }

    public static function getSubscribedEvents()
    {
        return array(
            'plugin.install.newscoop_send_recommendation_plugin' => array('install', 1),
            'plugin.update.newscoop_send_recommendation_plugin' => array('update', 1),
            'plugin.remove.newscoop_send_recommendation_plugin' => array('remove', 1),
        );
    }

    private function getClasses(){
        return array();
    }
}
