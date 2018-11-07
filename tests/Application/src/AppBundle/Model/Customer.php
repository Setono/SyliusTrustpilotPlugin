<?php

declare(strict_types=1);

namespace AppBundle\Model;

use Setono\SyliusTrustpilotPlugin\Model\CustomerTrait;
use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

/**
 * Class Customer
 */
class Customer extends BaseCustomer implements CustomerTrustpilotAwareInterface
{
    use CustomerTrait;
}
