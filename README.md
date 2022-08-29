# Trustpilot plugin for your Sylius store

[![Latest Version][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]

Send review invitations to your customers to entice them to leave feedback for you.
The plugin uses Trustpilots [AFS service](https://support.trustpilot.com/hc/en-us/articles/213703667-Automatic-Feedback-Service-AFS-2-0-setup-guide).

## Installation

```bash
$ composer require setono/sylius-trustpilot-plugin
```

### Add bundle to `config/bundles.php`:

```php
<?php
// config/bundles.php

return [
    // ...
    Setono\SyliusTrustpilotPlugin\SetonoSyliusTrustpilotPlugin::class => ['all' => true],
];
```

### Configure plugin

```yaml
# config/packages/setono_sylius_trustpilot.yaml
imports:
    - { resource: "@SetonoSyliusTrustpilotPlugin/Resources/config/app/config.yaml" }
```

### Configure routes

```yaml
# config/routes/setono_sylius_trustpilot.yaml
setono_sylius_trustpilot:
    resource: "@SetonoSyliusTrustpilotPlugin/Resources/config/routes.yaml"
```

### Install assets

```bash
bin/console assets:install
```

### Update your schema

```bash
# Generate and edit migration
bin/console doctrine:migrations:diff

# Then apply migration
bin/console doctrine:migrations:migrate
```

### Add cronjob

The following command should be run on a regular basis:

```bash
bin/console setono:sylius-trustpilot:process
```

[ico-version]: https://poser.pugx.org/setono/sylius-trustpilot-plugin/v/stable
[ico-license]: https://poser.pugx.org/setono/sylius-trustpilot-plugin/license
[ico-github-actions]: https://github.com/Setono/SyliusTrustpilotPlugin/workflows/build/badge.svg

[link-packagist]: https://packagist.org/packages/setono/sylius-trustpilot-plugin
[link-github-actions]: https://github.com/Setono/SyliusTrustpilotPlugin/actions
