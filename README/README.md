
## Response Strategy
<details>
<summary>
This is an optional solution to handle the type of output that will be implemented for a crud. Make sure to respect the naming standards or you will need to modify the AdditionalDataRequest inputs for (setMethod, setView, setRoute).
</summary>

## How it works:
1- From a middleware or similar logic set the Additional Data Request and identify the required Response Strategy

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use SebastianBergmann\Type\Exception;
use Symfony\Component\HttpFoundation\Response;
use Anasa\ResponseStrategy\{AdditionalDataRequest,ResponseStrategyFactory,ResponseContextInterface};

class ApiOrWebMiddleware
{
    public function __construct(protected ResponseContextInterface $responseContext)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * Set additional data request:
         * this will add the controller, method, view and resource. It's
         * some dinamic data to be used in the strategy to identify and build
         * the response. A facade will be used.
         */
        $service = AdditionalDataRequest::getInstance();
        $this->setAdditionalDataRequest($request, $service);

        $this->defineResponseStrategy($service);

        return $next($request);
    }

    private function setAdditionalDataRequest(Request $request, $service): void
    {
        $action = $request->route()->getAction();
        $controller = class_basename($action['controller']);
        [, $methodName] = explode('@', $controller);
        
        $service->setMethod($request->expectsJson() || $request->is('api/*') ? 'API' : $methodName);
        $service->setView($request->route()->getName());
        $service->setRoute($request->route()->getName());
    }
    
    public function defineResponseStrategy()
    {
        try {
            $strategy = ResponseStrategyFactory::createStrategy($service->getMethod());
        } catch (Exception $e) {
            throw new Exception('Unknown method');
        }

        $this->responseContext->setStrategy($strategy);
    }
}

```
**Notes:**
- setMethod will set as API for all input json output.
- If your project uses a custom prefix for API inputs, make sure to add the Accept: application/json Header to identify if a json output.
```php
$service->setMethod($request->expectsJson() || $request->is('api/*') ? 'API' : $methodName);
```

2- Set Service Provider and Response Service provider,

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Anasa\ResponseStrategy\AdditionalDataRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //...
        $this->app->singleton('additionalDataRequest', function ($app) {
            return new AdditionalDataRequest;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       //
    }
}

```

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Anasa\ResponseStrategy\{ResponseContext,ResponseContextInterface};
use Anasa\ResponseStrategy\Output\{ApiResponseStrategy, ViewResponseStrategy, RedirectResponseStrategy};
use Anasa\ResponseStrategy\OutputDataFormat\{StrategyData,StrategyDataInterface};

class ResponseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ApiResponseStrategy::class, function ($app) {
            return new ApiResponseStrategy();
        });

        $this->app->bind(ViewResponseStrategy::class, function ($app) {
            return new ViewResponseStrategy();
        });

        $this->app->bind(RedirectResponseStrategy::class, function ($app) {
            return new RedirectResponseStrategy();
        });
        $this->app->bind(StrategyDataInterface::class, function ($app) {
            return new StrategyData();
        });

        $this->app->singleton(ResponseContextInterface::class, function ($app) {
            return new ResponseContext();
        });
    }
}

```

3- Using it in your controller. ***No checks nor conditionalities, just input and output of requests with a single way.*** 

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Repositories\YourRepository;
use Anasa\ResponseStrategy\ResponseContextInterface;
use Anasa\ResponseStrategy\OutputDataFormat\StrategyDataInterface;

class YourController extends Controller
{
    public function __construct(protected YourRepository $repository, protected ResponseContextInterface $responseContext, protected StrategyDataInterface $strategyData)
    {
    }
    
    public function index(): View|JsonResponse
    {
        $data = $this->repository->all();
        $strategy = $this->strategyData->setStrategyData(YourResource::collection($data));

        return $this->responseContext->executeStrategy($strategy);
    }

    /**
     * No strategy needed
    */
    public function create(): View
    {
        return View('yourResource.create');
    }

    public function store(YourRequest $request): JsonResponse|RedirectResponse
    {
        
        $data = $this->repository->create($request->validated());
        $strategy = $this->strategyData->setStrategyData(new YourResource($data), 'YourResource created successfully', Response::HTTP_CREATED);

        return $this->responseContext->executeStrategy($strategy);
    }

    public function show($id): JsonResponse|View
    {
        $data = $this->repository->find($id); //it uses findOrFail from the repository
        $strategy = $this->strategyData->setStrategyData(new YourResource($data));

        return $this->responseContext->executeStrategy($strategy);
    }

    public function edit(string $id): View
    {
        $data = $this->repository->find($id); //it uses findOrFail from the repository
        return View('gateway.edit', ['YourData' => $data]);
    }

    public function update(YourRequest $request, string $id): JsonResponse|RedirectResponse
    {
        $updated_data = $this->repository->update($id, $request->validated()); //it uses findOrFail
        $strategy = $this->strategyData->setStrategyData(new YourResource($updated_data), 'YourResource updated successfully', Response::HTTP_OK);

        return $this->responseContext->executeStrategy($strategy);
    }

    public function destroy($id): JsonResponse|RedirectResponse
    {
        $this->repository->delete($id); //it uses findOrFail from the repository

        return $this->responseContext->executeStrategy($this->strategyData->setStrategyData([], 'YourResource deleted successfully', Response::HTTP_OK));
    }
```
4- For testing, you can add: *$service->setMethod('API');*
```php
namespace Tests\Feature;

use Tests\TestCase;
use Anasa\ResponseStrategy\Facades\AdditionalDataRequest;

class GatewayTest extends TestCase
{
  

    protected function setUp(): void
    {
        parent::setUp();

        $service->setMethod('API');
    }
```
</details>


## Policy Example
<details>
<summary>
This is a solution that seeks to maintain the sole responsibility of the classes. Therefore, managing security and/or accessibility to the functionalities from the controllers would fail to comply desire to have decoupled code.
</summary>

```php
 public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }
```
In this case, ***$this->authorize('update', $post);***  the accessibility from the controller,  as an alternative, a solution built with a middleware and a policy, it handles the accessibility isolate from the controller.

In addition, the route is loading the resource.

![alt text](image/{2CC2EFFC-C5C4-4A55-895F-4B2164FA2C4B}.png)

Check also:
```php	
 use App\Http\Middleware\GatewayActionMiddleware;
 use App\Policies\GatewayPolicy;
```

**USE** *Illuminate\Foundation\Support\Providers\AuthServiceProvider* FROM **AppServiceProvider.php**
```php
namespace App\Providers;
/*...code */
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Gateway::class => GatewayPolicy::class,
    ];
    
    public function register(): void{/*...code*/}
    
    public function boot(): void
    {
        $this->registerPolicies();
       /*...code*/
    }
}
```
</details>

## Handling the *Roles/Permissions* with [spatie/laravel-permission](https://github.com/spatie/laravel-permission) package.
<details>
<summary>
The previous example handles accessibility correctly, but it's just for a case of you don't use a security package.
</summary>
For the example, a seeder was created to add roles and permissions:

```php
public function run(): void
{
    /*...code*/
    Permission::create(['name' => 'gateway.update']);
    Permission::create(['name' => 'gateway.destroy']);

    /*...code*/
    Permission::create(['name' => 'peripheral.update']);
    Permission::create(['name' => 'peripheral.destroy']);

    $admin = Role::create(['name' => 'admin']);
    $admin->givePermissionTo(Permission::all());

    $user = \App\Models\User::where('name', 'admin')->first();
    $user->assignRole('admin');
}
```
A middleware was created to handle the roles and permissions, It's not necessary, but will allow to personalize the access to the resources, and it will work for any input, whether it is an API or a Web input. This will not take into account the guard_name used by the package.
```php
class RoleOrPermissionMiddleware
{
    
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        //The route name is used to name the permission (like as the seeders)
        $route = $request->route()->getName();
        $user = $request->user();

        if ($user->hasRole($role) || $user->can($route)) {
            return $next($request);
        }

        abort(Response::HTTP_FORBIDDEN, 'You are not authorized.');
    }
}
```
```php
 Route::delete('/peripheral/{peripheral}', [PeripheralController::class, 'destroy'])->name('peripheral.destroy')
    ->middleware('role_or_permission:admin');
```
</details>

## An even example for an specific function from the repository
<details>
<summary>
We can trigger an event on the repository, but we don't want coupled code.
This solution uses a decorator to intercept method calls to the GatewayRepository and add the event for the needed function.
</summary>

**The most of the logic happens in the decorator, the rest is the provider to intercept the method calls.**

```php
namespace App\Repositories\Decorators;
/*...code*/
class GatewayRepositoryDecorator extends GatewayRepository
{
   /*...code*/
    public function updateGateway($id, $data)
    {
        // Call the original updateGateway method
        $result = $this->repository->updateGateway($id, $data);
        $gateway = $this->find($id);

        event(new GatewayUpdated($gateway));

        return $result;
    }
}
```
```php
namespace App\Providers;
/*...code*/
class GatewayInterceptorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //Using the decorator to intercept method calls to the GatewayRepository.
        $this->app->extend(GatewayRepository::class, function ($repository) {
            return new GatewayRepositoryDecorator($repository);
        });
    }
}
```

***It's necessary highlight that implemented event doesn't use the interface ShouldQueue. so, slowness is experienced during the testing. We could add it to a queue and dispatch it as a scheduled job to ensure the asynchrony but implementing the queue will require many steps to test it.***

```php
namespace App\Listeners;
/*...code*/
class GatewayUpdatedListener
{
    /*...code*/
    public function handle(GatewayUpdated $event): void
    {
        $gateway = $event->gateway;        
        Log::info('GatewayUpdatedListener triggered: ', ['gateway' => $gateway]);
    }
}
```
Note: No big changes in the repository, just duplicated the update function now named updateGateway
```php
public function updateGateway($id, array $data)
{
    $gateway = $this->find($id);
    $gateway->update($data);
    return $gateway;
}
```
</details>


## Swagger OpenAPI to align work between the backend and frontend
<details>
<summary>
During the development process, it is common to find communication problems, or simply a different interpretation of the user stories. One solution to keep the Frontend and Backend aligned is to use Swagger to generate the documentation for each endpoint. This allows several developers to work at the same time, following the same previously defined format.
</summary>

<br>

**This is a proposal on how to use Swagger OpenAPI without overloading the system with D that affects the readability of the code.**


This is what we want to achieve http://127.0.0.1:8000/api/documentation 👇🏻
![alt text](image/SwaggerDoc.png)

<hr>
⚠️**What we want to avoid:** This would be the basic solution, but this would add long lines of annotations in each class

![alt text](image/SwaggerAnotationInController.png)

![alt text](image/SwaggerAnnotationEndpoint.png)
<hr>

## An option to isolate Swagger OpenAPI from classes:

/config/l5-swagger.php
```php
//the standard option must be removed.
 'annotations' => array_merge(
    // base_path('app'), <<<DELETE/COMMENT LINE>>>
    glob(base_path('app/OpenApi/Endpoints/*.php')),
    glob(base_path('app/OpenApi/Schemas/*.php')),
),
```	
**The next step would be to create the app/OpenApi/ directory. This way, you will have all the annotation-related classes in this directory and isolated from the code.**


/GatewayEndpoints.php
```php
namespace App\OpenApi\Endpoints;

use OpenApi\Annotations as OA;

class GatewayEndpoints
{
    /**
     * @OA\Get(
     *     path="/api/gateway",
     *     tags={"Gateway"},
     *     summary="Gateway index",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *           type="array",
     *           @OA\Items(ref="#/components/schemas/Gateway"))
     *         )
     *    )
     */
    public function index()
    {
        //
    }
    
    //... more annotations

    /**
     * @OA\Post(
     *     path="/api/gateway",
     *     tags={"Gateway"},
     *     summary="Gateway store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "serial_number", "IPv4_address"},
     *             @OA\Property(property="serial_number", type="string", example="123456"),
     *             @OA\Property(property="name", type="string", example="Gateway 1"),
     *             @OA\Property(property="IPv4_address", type="string", example="127.0.0.1"),
     *             @OA\Property(
     *                 property="peripheral",
     *                 type="array",
     *                 @OA\Items(type="object", ref="#/components/schemas/Peripheral")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Gateway created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Gateway")
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Gateway created successfully",
     *         @OA\Header(
     *             header="Location",
     *             description="/api/gateway",
     *             @OA\Schema(type="string", example="GET /api/gateway")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function store()
    {
        //
    }
    
    //.. more annotations
}
```

/GatewayResourceSchema.php
```php
namespace App\OpenApi\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Documentation")
 *
 * @OA\Tag(name="Gateway", description="Gateway crud")
 * @OA\Schema(
 *       schema="Gateway",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="serial_number", type="string", example="1234567"),
 *     @OA\Property(property="name", type="string", example="Gateway 1"),
 *     @OA\Property(property="IPv4_address", type="string", example="127.0.0.1"),
 *     @OA\Property(
 *         property="peripheral",
 *         type="array",
 *         @OA\Items(type="object", ref="#/components/schemas/Peripheral")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2022-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2022-01-01T00:00:00.000000Z")
 * )
 */
class GatewayResourceSchema
{
}
```
</details>
