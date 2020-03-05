<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusTrustpilotPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Setono\SyliusTrustpilotPlugin\Model\CustomerTrait;
use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

/**
 * @ORM\Table(name="sylius_customer")
 * @ORM\Entity()
 */
class Customer extends BaseCustomer implements CustomerTrustpilotAwareInterface
{
    use CustomerTrait;
}
