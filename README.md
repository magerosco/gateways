
<p align="center">
    <a href="https://laravel.com/"><img src="https://img.shields.io/badge/Laravel-11-FF2D20.svg?style=flat&logo=laravel" alt="Laravel 11"/></a>
    <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.2-777BB4.svg?style=flat&logo=php" alt="PHP 8.2"/></a>
    <a href="https://github.com/magerosco/gateways/actions/workflows/ci.yml"><img src="https://github.com/magerosco/gateways/actions/workflows/ci.yml/badge.svg" alt="GithubActions"/></a>
</p>


## This project is a compilation of exercises covering different aspects of Laravel, and applying concepts to use clean, decoupled code aligned with SOLID principles.**  

**✅ Features:**
1. **Setup enviroment with Docker. (Apache, Redis, RabbitMQ, Mongo, MySql)**
2. **Define routes with prefixes**
3. **Sanitize data by middleware to ensure data integrity before validations (XSS, SQL Injection)**
4. **Handle roles by own middleware or by the spatie/laravel-permission package**
5. **Authentication with Sanctum and tests**
6. **Authentication with OAuth+JWT and use public and private keys for OAuth authentication with Laravel Passport. and tests**
7. **Write tests and setUp method for each feature**
8. **Implement event handling**
9. **Implemented custom log channels and validated log structure in tests**
10. **Use Illuminate\Http\Response defining responses with appropriate status codes.**
11. **Use Laravel Resources to define the structure of outgoing data with custom fields.**
12. **Protect endpoints with rate limiting (throttle middleware)**
13. **Validate inputs using Form Requests to handle the responsibility of validation in a single place.**
14. **Configured CORS and used the database to dynamically manage allowed origins.**
15. **Defined relationships (hasOne, hasMany, belongsTo, belongsToMany, morphTo, morphToMany, morphOne, morphMany, morphedByMany, hasOneThrough, hasManyThrough).**
16. **Used advanced queries (whereHas, withCount, subqueries, mutators & accessors) and implemented query scopes**
17. **Implemented caching using Redis, Memcached, Database. Apply tags to group cache keys by a trait to manage the cache globally. Use the model events from the observer as an option to clear the cache.** [Details](README/README.md#L377):
18. **Model events (Use observer to handle the model events)**
19. **Optimized queries using chunk(), lazy(), cursor()**
20. **Handled transactions and lockings (DB::transaction(), lockForUpdate()).**
21. **Used Gates & Policies.**
22. **Used factories and seeders in tests**
23. **Develop tests with/without database persistence (ModelName::factory()->create(), ModelName::factory()->make())**
24. **Mocked dependencies with Mockery/Laravel Mock.**
25. **Scheduled tasks with Task Scheduling.**
26. **Monitored queues with Horizon.**
27. **Swagger OpenAPI to align work between the backend and frontend.**
28. **API Versioning.**
29. **Used the lint to check the scripts. I consider it important, for example, to quickly review scripts in production.**
 ```bash
 #GitBash:
 find . -name "*.php" -exec php -l {} \;

#PowerShell
Get-ChildItem -Path . -Filter "*.php" -Recurse | ForEach-Object { php -l $_.FullName }
```
30. **Built custom commands.**
31. **Configured GitHub Actions to run tests using Dockers containers.**
32. **Use RabbitMQ. Implemented a simple RabbitMQ service and external library as examples.**
33. ***(Handling failed connection to RabbitMQ.)*** **RabbitQM is an external service that allows you to send and receive messages between apps/microservices in different ecosystems. Regardless of a failed connection and sent notifications to administrators, the system must be able to keep working.**




✅ **Design Patterns, SOLID principles** *Click to read more:*
<details> <summary><b>1. Repository Layer Design Pattern:<b></summary>

***Note: Dependency injection by interface and handling it  from the provider as part of multiple dependency classes that need to be injected into the same class***
[CrudRepositoryInterface](app/Repositories/CrudRepositoryInterface.php)<br>
[GatewayRepository](app/Repositories/GatewayRepository.php)<br>
[InterfaceServiceProvider](app/Providers/InterfaceServiceProvider.php#L56)<br>
</details>


<details> 
<summary><b>2. Service Layer Design Pattern:<b></summary>

***Note: Basic example using inheritance between interfaces and handling  the multiple dependency classes that need to be injected into the same class.***

[GatewayService](app/Services/Gateway/GatewayService.php) <br>
[GatewayServiceInterface](app/Services/Gateway/GatewayServiceInterface.php)<br>
[GatewayServiceDestroyV2Interface](app/Services/Gateway/GatewayServiceDestroyV2Interface.php)<br>
[InterfaceServiceProvider](app/Providers/InterfaceServiceProvider.php#L38)<br>
[GatewayController](app/Http/Controllers/Api/V2/GatewayController.php#L88)
</details>

<details>
<summary><b>3. Observer Design Pattern<b></summary>

***Note: This app use cache (DB, Redis, etc..), and the example attempts to make use of the observer for clear the cache when a resource is created, updated or deleted.***
[GatewayObserver](app/Observers/GatewayObserver.php)<br>
</details>

<details>
<summary><b>4. Decorator Design Pattern<b></summary>

***Note: Dispatching events for a specific function from a decorated repository to avoid coupling the code logic.***

[GatewayRepository](app/Repositories/GatewayRepository.php#L49)<br>
[GatewayRepositoryDecorator](app/Repositories/Decorators/GatewayRepositoryDecorator.php#L18)<br>
</details>
<details>
<summary><b>5. Event-Driven Pattern<b></summary>

***Note: This example works in combination with the Decorator Design Pattern to decouple the code logic.***

[GatewayUpdated](app/Events/GatewayUpdated.php)<br>
[GatewayUpdatedListener](app/Listeners/GatewayUpdatedListener.php)<br>
</details>

<details>
<summary><b>6. Strategy Pattern.<b></summary>

***Note: This example combines middleware, a vendor package, factory and the strategy pattern as an optional solution to handle the type of output that will be implemented for a crud. With middleware as a starting point, this only works for endpoints that apply it.👉🏻 [Details:](README/README.md)***

[ApiOrWebMiddleware](app/Http/Middleware/ApiOrWebMiddleware.php)<br>
[GatewayController](app/Http/Controllers/GatewayController.php#L34)<br>
[Vendor/ResponseStrategy](vendor/anasa/response-strategy/src/)

</details>

<details>
<summary><b>7. Facade Pattern<b></summary>

[RabbitMQ](app/Facades/RabbitMQ.php)<br> 
[bootstrap/app.php](bootstrap/app.php#L22)<br>

</details>

<details>
<summary><b>8. DTO (Data Transfer Object) Pattern<b></summary>

***Notes: Basic example, just to show the pattern***
[DTO](app/DTO/)<br>
</details>

<details>
<summary><b>9. Chain of Responsibility Pattern.<b></summary>

[Pipelines/Order](app/Pipelines/Order)<br>
[OrderController/processOrder](app/Http/Controllers/Api/OrderController.php#L20)<br>
</details>

<details>
<summary><b>10. Builder Pattern<b></summary>

***Note: Example in combination with Factory Pattern to generate different report formats***

[ReportController](app/Http/Controllers/Api/ReportController.php#L12)<br>
[ReportDirector](app/Services/Report/ReportDirector.php)<br>
</details>

<details>
<summary><b>11. Factory Pattern<b></summary>

***Note: Example in combination with Builder Pattern to generate different report formats.***
[ReportFactory](app/Factories/ReportFactory.php)<br>
</details>

<details>
<summary><b>12. Command Pattern in Laravel<b></summary>

***Note: Using Illuminate\Console\Command as extension, it responds to the command line php artisan rabbit:consume {queues=default}***

[ConsumeRabbitMessages](app/Console/ConsumeRabbitMessages.php)<br>
</details>








## Installation

First steps:

```bash
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate
    php artisan db:seed
    php artisan serve
``` 

## Testing 
 
 ## Check the available endpoints to test with postman 

```
  php artisan route:list
```
**Note: You must use the admin credentials to delete path: *api/peripheral/{peripheral}* or *api/gateway/{gateway}.***
1. **Login to get the token.**

```
POST            api/login

Body raw:
{
  "email": "admin@admin.com",
  "password": "admin"
}
```




