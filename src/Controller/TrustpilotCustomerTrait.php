<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Controller;

use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

trait TrustpilotCustomerTrait
{
    /**
     * @param string $view
     * @param array $parameters
     * @param Response|null $response
     * @return Response
     */
    abstract protected function render(string $view, array $parameters = array(), Response $response = null): Response;

    /**
     * @param RequestConfiguration $configuration
     * @param string $permission
     */
    abstract protected function isGrantedOr403(RequestConfiguration $configuration, string $permission): void;

    /**
     * @param RequestConfiguration $configuration
     * @return ResourceInterface
     */
    abstract protected function findOr404(RequestConfiguration $configuration): ResourceInterface;

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
                'invites_limit' => $this->container->getParameter('setono_sylius_trustpilot.invites_limit'),
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
