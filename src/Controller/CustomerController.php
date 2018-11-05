<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;

class CustomerController extends ResourceController
{
    use TrustpilotCustomerTrait;
}
