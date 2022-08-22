<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Controller\Action;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

// todo
final class BlacklistCustomerAction
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function __invoke(Request $request): Response
    {
        /** @var mixed $customerId */
        $customerId = $request->query->get('customerId');
        if (!is_string($customerId) || '' === $customerId) {
            // todo should not be this exception, but it works for now
            throw new NotFoundHttpException('You need to provide a customer id to blacklist');
        }

        $customer = $this->customerRepository->find($customerId);
        Assert::nullOrIsInstanceOf($customer, CustomerInterface::class);

        if (null === $customer) {
            throw new NotFoundHttpException(sprintf('The customer with id %s does not exist', $customerId));
        }

        return new Response();
    }
}
