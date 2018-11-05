<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Controller;

use Setono\SyliusTrustpilotPlugin\Model\CustomerInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait TrustpilotCustomerTrait
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function trustpilotEnableAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);

        /** @var CustomerInterface $resource */
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

        /** @var CustomerInterface $resource */
        $resource = $this->findOr404($configuration);

        $resource->setTrustpilotEnabled(false);
        $this->repository->add($resource);

        return $this->redirectHandler->redirectToReferer($configuration);
    }
}
