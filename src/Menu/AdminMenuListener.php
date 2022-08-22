<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $this->addChild($menu);
    }

    private function addChild(ItemInterface $menu): void
    {
        $submenu = $menu->getChild('marketing');
        $item = $submenu instanceof ItemInterface ? $submenu : $menu->getFirstChild();
        $item
            ->addChild('trustpilot', [
                'route' => 'setono_sylius_trustpilot_admin_invitation_index',
            ])
            ->setLabel('setono_sylius_trustpilot.ui.trustpilot')
            ->setLabelAttribute('icon', 'star')
        ;
    }
}
