<?php

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ChannelConfigurationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('afsEmail', EmailType::class, [
                'label' => 'setono_sylius_trustpilot.form.channel_configuration.afs_email',
            ])
            ->add('sendDelay', DateIntervalType::class, [
                'label' => 'setono_sylius_trustpilot.form.channel_configuration.send_delay',
                'with_minutes' => true,
                'with_hours' => true,
                'with_days' => true,
                'with_weeks' => false,
                'with_months' => false,
                'with_years' => false,
                'input' => 'dateinterval',
            ])
            ->add('preferredSendTime', TextType::class, [
                'label' => 'setono_sylius_trustpilot.form.channel_configuration.preferred_send_time',
            ])
            ->add('templateId', TextType::class, [
                'label' => 'setono_sylius_trustpilot.form.channel_configuration.template_id',
                'required' => false,
            ])
            ->add('channel', ChannelChoiceType::class, [
                'label' => 'sylius.ui.channel',
                'expanded' => false,
                'multiple' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'setono_sylius_trustpilot_channel_configuration';
    }
}
