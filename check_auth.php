<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Checking authentication status:\n";
echo "Is authenticated: " . (auth()->check() ? 'Yes' : 'No') . "\n";

if (auth()->check()) {
    echo "User: " . auth()->user()->name . " (" . auth()->user()->email . ")\n";
} else {
    echo "No authenticated user found.\n";
}

echo "\nChecking route for /admin:\n";
$routes = Route::getRoutes();
foreach ($routes as $route) {
    if ($route->uri() === 'admin') {
        echo "Route found: " . $route->uri() . "\n";
        echo "Controller: " . $route->getActionName() . "\n";
        echo "Middleware: " . implode(', ', $route->middleware()) . "\n";
    }
}

echo "\nChecking if dashboard view exists:\n";
echo "admin.dashboard exists: " . (view()->exists('admin.dashboard') ? 'Yes' : 'No') . "\n";

echo "\nChecking AdminLTE configuration:\n";
echo "AdminLTE package installed: " . (class_exists('JeroenNoten\\LaravelAdminLte\\AdminLte') ? 'Yes' : 'No') . "\n";