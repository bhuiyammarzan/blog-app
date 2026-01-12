'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],

protected $routeMiddleware = [
    'valid.token' => \App\Http\Middleware\EnsureValidToken::class,
];
