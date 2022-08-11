<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Controller\Action;

use Setono\SyliusTrustpilotPlugin\Repository\ChannelConfigurationRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class RenderMissingChannelConfigurationsAction
{
    private ChannelConfigurationRepositoryInterface $channelConfigurationRepository;

    private Environment $twig;

    public function __construct(ChannelConfigurationRepositoryInterface $channelConfigurationRepository, Environment $twig)
    {
        $this->channelConfigurationRepository = $channelConfigurationRepository;
        $this->twig = $twig;
    }

    public function __invoke(Request $request): Response
    {
        return new Response(
            $this->twig->render('@SetonoSyliusTrustpilotPlugin/admin/layout/missing_channel_configurations.html.twig', [
                'hasAny' => $this->channelConfigurationRepository->hasAny(),
            ])
        );
    }
}
