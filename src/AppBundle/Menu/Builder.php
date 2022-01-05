<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\User;

class Builder implements ContainerAwareInterface
{
    private $container;

    /**
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');
        $menu->addChild('Home', ['route' => 'homepage']);

        $em = $this->container->get('doctrine')->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if (is_object($user)) {
            $menu->addChild('Logout', ['route' => 'logout']);
            $menu->addChild('Add new contact', ['route' => 'contact_new']);
        } else {
            $menu->addChild('Login', ['route' => 'login']);
            $menu->addChild('Register', ['route' => 'register']);
        }

        return $menu;
    }

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}