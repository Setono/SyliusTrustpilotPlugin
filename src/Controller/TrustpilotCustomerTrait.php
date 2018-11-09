<?php

/** @noinspection PhpUndefinedMethodInspection */
/** @noinspection PhpInternalEntityUsedInspection */

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Controller;

use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

trait TrustpilotCustomerTrait
{
    use ControllerTrait;
    use ContainerAwareTrait;

    abstract protected function getParameter(string $name);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function trustpilotBlockAction(Request $request): Response
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->container->get('sylius.repository.order');

        /** @var OrderTrustpilotAwareInterface $order */
        $order = $orderRepository->find(
            $request->attributes->get('id')
        );

        $options = $request->attributes->get('_sylius');
        Assert::notNull($options['template'], 'Template is not configured.');

        return $this->render(
            $options['template'],
            [
                'order' => $order,
                'invites_limit' => $this->getParameter('setono_sylius_trustpilot.invites_limit'),
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function trustpilotEnableAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);

        /** @var CustomerTrustpilotAwareInterface $resource */
        $resource = $this->findOr404($configuration);

        $resource->setTrustpilotEnabled(true);
        $this->repository->add($resource);

        return $this->redirectHandler->redirectToReferer($configuration);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function trustpilotDisableAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);

        /** @var CustomerTrustpilotAwareInterface $resource */
        $resource = $this->findOr404($configuration);

        $resource->setTrustpilotEnabled(false);
        $this->repository->add($resource);

        return $this->redirectHandler->redirectToReferer($configuration);
    }
}
