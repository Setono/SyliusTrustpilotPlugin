# SetonoSyliusTrustpilotPlugin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]

Send follow up emails to your customers to entice them to leave feedback for you. The plugin uses Trustpilots [AFS service](https://support.trustpilot.com/hc/en-us/articles/213703667-Automatic-Feedback-Service-AFS-2-0-setup-guide).

## Installation

* Install plugin using `composer`:

    ```bash
    $ composer require setono/sylius-trustpilot-plugin
    ```

* Add bundle to `config/bundles.php`:

    ```php
    <?php
    // config/bundles.php
    
    return [
        // ...
        Setono\SyliusTrustpilotPlugin\SetonoSyliusTrustpilotPlugin::class => ['all' => true],
    ];
    ```

* Add plugin routing to application:

    ```yaml
    # config/routes.yaml
    setono_sylius_trustpilot_admin:
        resource: "@SetonoSyliusTrustpilotPlugin/Resources/config/admin_routing.yaml"
        prefix: /admin
    ```

* Override `Customer` and `Order` entities:

    Read the docs regarding [customizing models](https://docs.sylius.com/en/latest/customization/model.html).
    
    Here are the plugin specific changes you need to make in order to make this plugin work:
    
    ```php
    <?php
    // src/Entity/Customer.php
    
    declare(strict_types=1);
    
    namespace App\Entity;
    
    use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
    use Setono\SyliusTrustpilotPlugin\Model\CustomerTrait;
    use Sylius\Component\Core\Model\Customer as BaseCustomer;
    
    class Customer extends BaseCustomer implements CustomerTrustpilotAwareInterface
    {
        use CustomerTrait;
    }
    ```
    
    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    <!-- src/AppBundle/Resources/config/doctrine/model/Customer.orm.yml -->
    <doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                      xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                          http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    
        <mapped-superclass name="App\Entity\Customer" table="sylius_customer">
            <field name="trustpilotEnabled" column="trustpilot_enabled" type="boolean">
                <options>
                    <option name="default">1</option>
                </options>
            </field>
        </mapped-superclass>
    
    </doctrine-mapping>
    ```
    
    ```php
    <?php
    // src/Entity/Order.php
    
    declare(strict_types=1);
    
    namespace App\Entity;
    
    use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
    use Setono\SyliusTrustpilotPlugin\Model\OrderTrait;
    use Sylius\Component\Core\Model\Order as BaseOrder;
    
    class Order extends BaseOrder implements OrderTrustpilotAwareInterface
    {
        use OrderTrait;
    }

    ```
    
    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    <!-- src/AppBundle/Resources/config/doctrine/model/Order.orm.yml -->
    <doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                      xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                          http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    
        <mapped-superclass name="App\Entity\Order" table="sylius_order">
            <field name="trustpilotEmailsSent" column="trustpilot_emails_sent" type="smallint">
                <options>
                    <option name="default">0</option>
                </options>
            </field>
        </mapped-superclass>
    
    </doctrine-mapping>
    ```
    
    ```php
    <?php
    // src/Kernel.php
  
    declare(strict_types=1);
    
    namespace AppBundle;
    
    use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\HttpKernel\Kernel as BaseKernel;
    
    final class Kernel extends BaseKernel
    {
        // ...
      
        public function build(ContainerBuilder $container)
        {
            parent::build($container);
    
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver(
                [
                    realpath(__DIR__ . '/Resources/config/doctrine/model') => 'App\Entity',
                ],
                ['doctrine.orm.entity_manager']
            ));
        }
        
        // ...
    }
    ```
    
* Add overrides configuration:

    ```yaml
    # config/packages/_sylius.yml
    imports:  
        - { resource: "@SetonoSyliusTrustpilotPlugin/Resources/config/app/_sylius.yaml" }
    
    sylius_customer:
        resources:
            customer:
                classes:
                    model: AppBundle\Model\Customer
                    # If you already have your own CustomerController - use TrustpilotCustomerTrait instead
                    controller: Setono\SyliusTrustpilotPlugin\Controller\CustomerController
                  
    sylius_order:
        resources:
            order:
                classes:
                    model: AppBundle\Model\Order
    ```
    
* Add plugin configuration:

    ```yaml
    # config/packages/setono_sylius_trustpilot.yml
  
    setono_sylius_trustpilot:
        # Mandatory.
        # Bcc Trustpilot email from https://businessapp.b2b.trustpilot.com/#/invitations/afs-settings
        trustpilot_email: "%env(APP_TRUSTPILOT_EMAIL)%"

        # Optional. Default value - 7.
        # How many days after the order was completed Customer's email should be sent to Trustpilot
        send_in_days: 3

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

* Put environment variable to `.env.dist`, `.env.*.dist` files:

    ```bash
    ###> setono/sylius-trustpilot-plugin ###
    APP_TRUSTPILOT_EMAIL=EDITME
    ###< setono/sylius-trustpilot-plugin ###
    ```

* Update your schema (for existing project)

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

*   Write your custom class implementing `OrderEligibilityCheckerInterface`:
    
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
    
*   Tag it with `setono_sylius_trustpilot.order_eligibility_checker` tag
 
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

## Prepare to run plugin test app

    ```bash
    cp tests/Application/.env.dist tests/Application/.env
    
    # Put actual values to environment variables
    nano tests/Application/.env
    
    set -a && source tests/Application/.env && set +a
    # OR (from tests/Application directory)
    # set -a && source .env && set +a
    ```

## Play with plugin test app

- Run application:
  (by default application have default config at `dev` environment
  and example config from `Configure plugin` step at `prod` environment)
  
    ```bash
    SYMFONY_ENV=dev
    cd tests/Application && \
        yarn install && \
        yarn run gulp && \
        bin/console assets:install public -e $SYMFONY_ENV && \
        bin/console doctrine:database:drop --force -e $SYMFONY_ENV && \
        bin/console doctrine:database:create -e $SYMFONY_ENV && \
        bin/console doctrine:schema:create -e $SYMFONY_ENV && \
        bin/console sylius:fixtures:load --no-interaction -e $SYMFONY_ENV && \
        bin/console server:run -d public -e $SYMFONY_ENV
    ```

- Log in at `http://localhost:8000/admin`
  with Sylius demo credentials:
  
  ```
  Login: sylius@example.com
  Password: sylius 
  ```

[ico-version]: https://img.shields.io/packagist/v/setono/sylius-trustpilot-plugin.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Setono/SyliusTrustpilotPlugin/master.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Setono/SyliusTrustpilotPlugin.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/setono/sylius-trustpilot-plugin
[link-travis]: https://travis-ci.org/Setono/SyliusTrustpilotPlugin
[link-code-quality]: https://scrutinizer-ci.com/g/Setono/SyliusTrustpilotPlugin
