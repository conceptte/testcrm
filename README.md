# MiniCRM - Test exersise

## Journey
During the development of this package, I focused on creating a clean and modular architecture that can be easily integrated into any Nette-based application. I wanted to demonstrate best practices in Nette development, such as using extensions, presenters, and repositories to keep the code organized and maintainable.

I went throwgh such opics as:
- Nette extensions
    - configuration
    - bootstrap and services registration
- Dependency injection
- Routing for clean URLs
    - route definitions
- Presenters
    - parameters injection
    - persistence parameters
    - forms
    - reusable components
    - redraw and snippets
- Form handling
- Latte basics
    - templates and layouts
    - blocks and snippets
- Database interactions with repositories
    - Explorer
- API design for JSON endpoints
- Security practices (CSRF, SQL injection prevention, XSS protection)

## About the app

This package is built as a Nette extension, not as code mixed into the host app.
That gives a few clear benefits:

- Host project only registers extension and routes.
- logic is in one place, not spread in many app files.
- host app can replace services by interface without editing core package code.

Main gap of extension in current version:
- Asset pipeline is limited: styles are kept in Latte layout blocks, not in fully separate built assets.




## Installation ([Fast dockerized setup:](https://github.com/conceptte/testcrm-host.git))
I recomend to use the dockerized setup for quick testing. It includes a simple host app with the extension installed and configured, so you can see it in action right away. You can find it here:
https://github.com/conceptte/testcrm-host.git

1. Install

```bash
composer require conceptte/minicrm
```


2. Register in config:

```yaml
extensions:
    minicrm: Mtr\MiniCRM\MiniCRMExtension
```

3. Add routes in `app/Core/RouterFactory.php`:

```php
$router->add(\Mtr\MiniCRM\Routing\RouterFactory::create());
```

4. Create and seed database:

```bash
php vendor/conceptte/minicrm/database/schema.php
#basically it: mysql < vendor/conceptte/minicrm/database/schema.sql
php vendor/conceptte/minicrm/database/seed.php  # optional - adds test data
```

### Basic simple structure:

```
├── config/                   # Extension configuration
├── database/                 # SQL schema and seed data
├── src/
    ├── Exception/            # Custom exceptions
    ├── Routing/              # Where routes are defined
    ├── Presentation/         # Pages and forms
    ├── Repository/           # Gets data from database
    ├── API/V1/               # JSON endpoints
    ├── MiniCRMExtension.php  # Main extension class
    ├── ConfigNodes.php       # Enum for config keys
```

## Configuration
Extension configuration is done in `config/minicrm.php`:
    
```php
return [
    'minicrm' => [
        'mapping' => [
            'MiniCRM' => 'Mtr\MiniCRM\Presentation\*\*Presenter',
            'MiniCRMAPI' => 'Mtr\MiniCRM\API\V1\Presentation\*\*Presenter',
        ],
        'services' => [
            'paginator' => Paginator::class,
            'paginationControl' => PaginationControl::class,
            'customersRepository' =>[
                'type' => CustomersRepositoryInterface::class,
                'create' => CustomersRepository::class,
            ],
            'activityRepository' => [
                'type' => ActivityRepositoryInterface::class,
                'create' => ActivityRepository::class,
            ],
            'commentsRepository' => [
                'type' => CommentsRepositoryInterface::class,
                'create' => CommentsRepository::class,
            ],
        ],
    ],
];
```

I decided to use PHP for configuration instead of NEON because it allows for more flexibility (and Nette supports it). You can easily swap out implementations or use real classes without needing a separate DI container configuration.


### Routes:

Router is defined in `src/Routing/RouterFactory.php`. It makkes group of routes for the app and API.
```php
public static function create(): RouteList
    {
        $group = new RouteList();

        $minicrm = (new RouteList('MiniCRM'))->add(CustomersRouteFactory::create());
        $api = (new RouteList('MiniCRMAPI'))->add(ApiRouteFactory::create());

        $group
            ->add($minicrm)
            ->add($api);

        return $group;
    }
```

### Group of Frontend routes:
- `/minicrm/customers` - Customer list page (search, filter, sort)
- `/minicrm/customers/{public_id}` - Customer details page (activities and history)
- `/minicrm/activities/{id}` - Activity page (view details and comments)
```php
public static function create(): RouteList
{
    $router = new RouteList();

    $router->addRoute('minicrm[/customers]', [
        'presenter' => 'Customers',
        'action' => 'index',
    ]);

    $router->addRoute('minicrm/customers/<id>', [
        'presenter' => 'Customers:Details',
        'action' => 'view',
    ]);

    $router->addRoute('minicrm/customers/<id>/activity/<activity>', [
        'presenter' => 'Customers:Activity',
        'action' => 'view',
    ]);

    return $router;
}

```


### API routes group:
- `/minicrm/api/v1/ping` - API to check if the service is alive
- `/minicrm/api/v1/customers` - API to get customer data as JSON
- `/minicrm/api/v1/customers/{public_id}` - API to get single customer details as JSON

```php
public static function create(): RouteList
{
    $router = new RouteList();
    
    $router->addRoute('minicrm/api/<version>/ping', [
        'presenter' => 'Ping',
        'action' => 'pong',
    ]);

    $router->addRoute('minicrm/api/<version>/customers', [
        'presenter' => 'Customers',
        'action' => 'index',
    ]);

    $router->addRoute('minicrm/api/<version>/customers/<id>', [
        'presenter' => 'Customers:Details',
        'action' => 'index',
    ]);

    return $router;
}
```

## Database tables
[schema.sql](database/schema.sql) defines tables:
- `customers` - stores customer info
- `customer_activities` - stores customer activities 
- `activity_comments` - stores comments on activities

Foreign keys and indexes are set for performance and data integrity.

## Presenters 

I investigated and used native Nette functionality like:

- forms for comment add
- snippets for small page updates
- pagination for long lists
- AJAX with Naja for no full page reload

Presenters use repository services, pagination control as dependencies injected by Nette DI container.

## Repository layer
Repositories are defined as interfaces and implemented in `src/Repository/`. 
They provide methods to get data from the database and are used by presenters and API endpoints.
Registered as services in extension config, so they can be injected and swapped if needed.

## API

Get customers as JSON:

```
GET /minicrm/api/v1/customers?q=<name_or_email>&status=<active|inactive>&page=<page_number>&limit=<page_size>
```

Response includes customer list and meta info:
```json
{
  "data": [
    {
      "id": "c6a2ffb454ac1c",
      "name": "Sadie Block",
      "email": "name.borer@example.net",
      "is_active": true,
      "totals": {
        "total_activities": 47,
        "total_comments": 680
      },
      "links": {
        "self": "/minicrm/api/v1/customers/c6a2ffb454ac1c"
      }
    },
    {
      "id": "c6a2ffb4689703",
      "name": "Dr. Kaia Prohaska I",
      "email": "dthompson@example.net",
      "is_active": true,
      "totals": {
        "total_activities": 20,
        "total_comments": 312
      },
      "links": {
        "self": "/minicrm/api/v1/customers/c6a2ffb4689703"
      }
    }
  ],
  "meta": {
    "params": {
      "q": "",
      "status": "active",
      "page": 2,
      "limit": 2
    },
    "pagination": {
      "total": 54,
      "current": 2,
      "per_page": 10,
      "total_pages": 6
    }
  }
}
```

Get single customer details:

```
GET /minicrm/api/v1/customers/{public_id}
```
Response includes customer info and some metadata:
```json
{
  "id": "c6a2ffb454ac1c",
  "name": "Sadie Block",
  "email": "name.borer@example.net",
  "is_active": true,
  "totals": {
    "total_activities": 47,
    "total_comments": 680
  }
}
```

## Safety

Used Nette's built-in features to protect against common web vulnerabilities:

- CSRF attacks - forms have protection tokens
- SQL injection - database uses safe queries
- XSS - text is automatically escaped
- Rate limiting - N/A (not implemented yet, but can be added)



