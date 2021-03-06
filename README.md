# SetonoSyliusTrustpilotPlugin

[![Latest Version][ico-version]][link-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]
[![Quality Score][ico-code-quality]][link-code-quality]

Send follow up emails to your customers to entice them to leave feedback for you. The plugin uses Trustpilots [AFS service](https://support.trustpilot.com/hc/en-us/articles/213703667-Automatic-Feedback-Service-AFS-2-0-setup-guide).

## Installation

### Install plugin using `composer`:

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

### Add plugin routing to application:

```yaml
# config/routes/setono_sylius_trustpilot.yaml
setono_sylius_trustpilot_admin:
    resource: "@SetonoSyliusTrustpilotPlugin/Resources/config/admin_routing.yaml"
    prefix: /admin
```

### Override `Customer` and `Order` entities:

Read the docs regarding [customizing models](https://docs.sylius.com/en/latest/customization/model.html).

Here are the plugin specific changes you need to make in order to make this plugin work:

```php
<?php
// src/Entity/Customer.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\CustomerTrait as TrustpilotCustomerTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

/**
 * @ORM\Table(name="sylius_customer")
 * @ORM\Entity()
 */
class Customer extends BaseCustomer implements CustomerTrustpilotAwareInterface
{
    use TrustpilotCustomerTrait;
}
```

```php
<?php
// src/Entity/Order.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrait as TrustpilotOrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

/**
 * @ORM\Table(name="sylius_order")
 * @ORM\Entity()
 */
class Order extends BaseOrder implements OrderTrustpilotAwareInterface
{
    use TrustpilotOrderTrait;
}

```

### Add overrides configuration:

```yaml
# config/packages/setono_sylius_trustpilot.yml
imports:  
    - { resource: "@SetonoSyliusTrustpilotPlugin/Resources/config/app/config.yaml" }

sylius_customer:
    resources:
        customer:
            classes:
                model: App\Entity\Customer
                # If you already have your own CustomerController - use TrustpilotCustomerTrait instead
                controller: Setono\SyliusTrustpilotPlugin\Controller\CustomerController
              
sylius_order:
    resources:
        order:
            classes:
                model: App\Entity\Order
```
    
### Add plugin configuration:

```yaml
# config/packages/setono_sylius_trustpilot.yml

setono_sylius_trustpilot:
    # Mandatory.
    # Bcc Trustpilot email from https://businessapp.b2b.trustpilot.com/#/invitations/afs-settings
    trustpilot_email: "%env(APP_TRUSTPILOT_EMAIL)%"

    # Optional. Default value - 7.
    # How many days after the order was completed Customer's email should be sent to Trustpilot
    send_in_days: 7

    # Optional. Default value - send_in_days + 2.
    # If you decrease send_in_days on live project, you should keep
    # process_latest_days old value for more than that amount of days.
    # For example, if you had send_in_days: 7 and changed to
    # send_in_days: 3, then you should keep process_latest_days: 9 (7+2)
    # for at least next 10 days after this change
    # After 10 days gone, you can remove this parameter from your configuration
    process_latest_days: 9

    # Optional. Default value - 0
    # (meaning that the customer will receive an invite every time he makes an order)
    # How many invites a Customer should receive before never asking him again
    invites_limit: 0
```

### Put environment variable to `.env`

```bash
###> setono/sylius-trustpilot-plugin ###
APP_TRUSTPILOT_EMAIL=EDITME
###< setono/sylius-trustpilot-plugin ###
```

### Update your schema (for existing project)

```bash
# Generate and edit migration
bin/console doctrine:migrations:diff

# Then apply migration
bin/console doctrine:migrations:migrate
```

## Production notes

Make sure you configured next command to be executed on daily basis (with CRON):

```bash
bin/console setono:sylius-trustpilot:process --env=prod
```

Keep in mind this command probably should be executed in most
active time of day for your customers, e.g. not at 3:00.

# Extending

## Add custom `OrderEligibilityChecker` 

### Write your custom class implementing `OrderEligibilityCheckerInterface`:
    
Lets assume we don't want feedback for order less than $100. 

```php
class CustomOrderEligibilityChecker implements OrderEligibilityCheckerInterface
{
    public function isEligible(OrderInterface $order): bool
    {
        return $order->getTotal() >= 10000;
    }
}
```
    
### Tag it with `setono_sylius_trustpilot.order_eligibility_checker` tag

```xml
    <service id="App\Trustpilot\Order\EligibilityChecker\CustomOrderEligibilityChecker">
        <tag name="setono_sylius_trustpilot.order_eligibility_checker" />
    </service>
```

# Contribution

Please run `composer all` to run all checks and tests before making PR or pushing changes to repo.

## Running plugin tests

- PHPSpec
    ```bash
    $ composer phpspec
    ```

- Behat
    ```bash
    $ composer behat
    ```

- All tests (phpspec & behat)
    ```bash
    $ composer test
    ```

[ico-version]: https://poser.pugx.org/setono/sylius-trustpilot-plugin/v/stable
[ico-unstable-version]: https://poser.pugx.org/setono/sylius-trustpilot-plugin/v/unstable
[ico-license]: https://poser.pugx.org/setono/sylius-trustpilot-plugin/license
[ico-github-actions]: https://github.com/Setono/SyliusTrustpilotPlugin/workflows/build/badge.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Setono/SyliusTrustpilotPlugin.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/setono/sylius-trustpilot-plugin
[link-github-actions]: https://github.com/Setono/SyliusTrustpilotPlugin/actions
[link-code-quality]: https://scrutinizer-ci.com/g/Setono/SyliusTrustpilotPlugin