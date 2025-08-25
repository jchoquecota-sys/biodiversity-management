<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Checking route for /admin:\n";

// Simulate a request to /admin
$request = Illuminate\Http\Request::create('/admin', 'GET');

// Get the route
$routes = app('router')->getRoutes();
$route = $routes->match($request);

echo "Route found: " . $route->uri() . "\n";
echo "Controller: " . $route->getActionName() . "\n";
echo "Middleware: " . implode(', ', $route->gatherMiddleware()) . "\n";

// Check if the controller exists
echo "\nChecking controller:\n";
$controller = $route->getController();
echo "Controller class exists: " . (class_exists(get_class($controller)) ? 'Yes' : 'No') . "\n";

// Check if the method exists
echo "\nChecking method:\n";
$method = $route->getActionMethod();
echo "Method exists: " . (method_exists($controller, $method) ? 'Yes' : 'No') . "\n";

// Check if the view exists
echo "\nChecking view:\n";
echo "admin.dashboard view exists: " . (view()->exists('admin.dashboard') ? 'Yes' : 'No') . "\n";

// Check if the view file exists
echo "\nChecking view file:\n";
$viewPath = resource_path('views/admin/dashboard.blade.php');
echo "View file exists: " . (file_exists($viewPath) ? 'Yes' : 'No') . "\n";
echo "View file path: " . $viewPath . "\n";