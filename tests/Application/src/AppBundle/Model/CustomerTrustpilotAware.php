<?php

declare(strict_types=1);

namespace AppBundle\Model;

use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\CustomerTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

/**
 * Class Customer
 */
class CustomerTrustpilotAware extends BaseCustomer implements CustomerTrustpilotAwareInterface
{
    use CustomerTrait;
}
