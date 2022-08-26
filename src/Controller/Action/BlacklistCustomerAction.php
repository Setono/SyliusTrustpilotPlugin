<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Controller\Action;

use InvalidArgumentException;
use Setono\SyliusTrustpilotPlugin\Factory\BlacklistedCustomerFactoryInterface;
use Setono\SyliusTrustpilotPlugin\Repository\BlacklistedCustomerRepositoryInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

final class BlacklistCustomerAction
{
    private CustomerRepositoryInterface $customerRepository;

    private BlacklistedCustomerRepositoryInterface $blacklistedCustomerRepository;

    private BlacklistedCustomerFactoryInterface $blacklistedCustomerFactory;

    private TranslatorInterface $translator;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        BlacklistedCustomerRepositoryInterface $blacklistedCustomerRepository,
        BlacklistedCustomerFactoryInterface $blacklistedCustomerFactory,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->customerRepository = $customerRepository;
        $this->blacklistedCustomerRepository = $blacklistedCustomerRepository;
        $this->blacklistedCustomerFactory = $blacklistedCustomerFactory;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $email = $this->resolveEmail($request);
        } catch (InvalidArgumentException $e) {
            $this->addFlash($request, 'error', $e->getMessage());

            return new RedirectResponse($this->resolveRedirectUrl($request));
        }

        $blacklistedCustomer = $this->blacklistedCustomerRepository->findOneByEmail($email);
        if (null === $blacklistedCustomer) {
            $blacklistedCustomer = $this->blacklistedCustomerFactory->createWithEmail($email);
            $this->blacklistedCustomerRepository->add($blacklistedCustomer);
        }

        $this->addFlash(
            $request,
            'success',
            $this->translator->trans('setono_sylius_trustpilot.blacklisted_customer.blacklisted', ['%email%' => $email], 'flashes')
        );

        return new RedirectResponse($this->resolveRedirectUrl($request));
    }

    private function resolveEmail(Request $request): string
    {
        /** @var mixed $email */
        $email = $request->query->get('email') ?? $request->request->get('email');
        if (is_string($email) && '' !== $email) {
            return $email;
        }

        /** @var mixed $customerId */
        $customerId = $request->query->get('customerId') ?? $request->request->get('customerId');
        if (!is_string($customerId) || '' === $customerId) {
            throw new InvalidArgumentException(sprintf(
                'To blacklist an email you either need to add a customerId parameter or an email parameter, i.e. %s',
                $this->urlGenerator->generate('setono_sylius_trustpilot_admin_blacklist_customer', ['email' => 'johndoe@example.com'], UrlGeneratorInterface::ABSOLUTE_URL)
            ));
        }

        /** @var CustomerInterface|object|null $customer */
        $customer = $this->customerRepository->find($customerId);
        Assert::nullOrIsInstanceOf($customer, CustomerInterface::class);

        if (null === $customer) {
            throw new InvalidArgumentException(sprintf('The customer with id %s does not exist', $customerId));
        }

        $email = $customer->getEmailCanonical();
        Assert::notNull($email);

        return $email;
    }

    private function addFlash(Request $request, string $type, string $message): void
    {
        $session = $request->getSession();

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        if ($session instanceof Session) {
            $session->getFlashBag()->add($type, $message);
        }
    }

    private function resolveRedirectUrl(Request $request): string
    {
        $referrer = $request->headers->get('referer');
        if (is_string($referrer) && '' !== $referrer) {
            return $referrer;
        }

        return $this->urlGenerator->generate('setono_sylius_trustpilot_admin_invitation_index');
    }
}
