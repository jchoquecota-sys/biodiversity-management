<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

echo "Testing real user navigation scenario...\n\n";

$baseUrl = 'http://127.0.0.1:8000/';
$cookieJar = new CookieJar();
$client = new Client([
    'base_uri' => $baseUrl,
    'cookies' => $cookieJar,
    'allow_redirects' => [
        'max' => 10,
        'strict' => false,
        'referer' => true,
        'track_redirects' => true
    ]
]);

try {
    // Step 1: Get login page
    echo "1. Getting login page...\n";
    $loginPageResponse = $client->get('login');
    $loginPageContent = $loginPageResponse->getBody()->getContents();
    
    preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPageContent, $matches);
    $csrfToken = $matches[1] ?? null;
    
    if (!$csrfToken) {
        echo "❌ Could not extract CSRF token\n";
        exit(1);
    }
    
    echo "✅ Login page loaded, CSRF token: " . substr($csrfToken, 0, 10) . "...\n";
    
    // Step 2: Login
    echo "\n2. Logging in...\n";
    $loginResponse = $client->post('login', [
        'form_params' => [
            'email' => 'admin@example.com',
            'password' => 'password',
            '_token' => $csrfToken
        ]
    ]);
    
    $finalUrl = $loginResponse->getHeaderLine('Location') ?: 'No redirect';
    echo "Login Status: " . $loginResponse->getStatusCode() . "\n";
    echo "Redirect to: $finalUrl\n";
    
    // Step 3: Access dashboard
    echo "\n3. Accessing dashboard...\n";
    $dashboardResponse = $client->get('admin');
    $dashboardContent = $dashboardResponse->getBody()->getContents();
    $dashboardStatus = $dashboardResponse->getStatusCode();
    
    echo "Dashboard Status: $dashboardStatus\n";
    
    if ($dashboardStatus === 200) {
        echo "✅ Dashboard accessible\n";
        
        // Check if we can see admin content
        if (strpos($dashboardContent, 'Biodiversidad') !== false || 
            strpos($dashboardContent, 'Dashboard') !== false ||
            strpos($dashboardContent, 'admin') !== false) {
            echo "✅ Admin content visible\n";
        } else {
            echo "⚠️ Admin content not found\n";
        }
    } else {
        echo "❌ Dashboard not accessible\n";
        echo "Response preview: " . substr($dashboardContent, 0, 200) . "...\n";
    }
    
    // Step 4: Navigate to different admin pages
    $adminPages = [
        'admin/biodiversity' => 'Biodiversity Categories',
        'admin/publications' => 'Publications',
        'admin/reinos' => 'Reinos',
        'admin/clases' => 'Clases'
    ];
    
    echo "\n4. Testing navigation to admin pages...\n";
    
    foreach ($adminPages as $url => $name) {
        echo "Testing $name ($url): ";
        
        try {
            $pageResponse = $client->get($url);
            $pageStatus = $pageResponse->getStatusCode();
            $pageContent = $pageResponse->getBody()->getContents();
            
            if ($pageStatus === 200) {
                echo "✅ Accessible\n";
            } elseif ($pageStatus === 302) {
                $redirectLocation = $pageResponse->getHeaderLine('Location');
                if (strpos($redirectLocation, 'login') !== false) {
                    echo "❌ Redirected to login - Session lost!\n";
                } else {
                    echo "🔄 Redirected to: $redirectLocation\n";
                }
            } else {
                echo "⚠️ Status: $pageStatus\n";
            }
            
        } catch (Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
        
        // Small delay between requests
        usleep(500000); // 0.5 seconds
    }
    
    // Step 5: Wait and test again to simulate user behavior
    echo "\n5. Waiting 10 seconds and testing again...\n";
    sleep(10);
    
    $dashboardResponse2 = $client->get('admin');
    $dashboardStatus2 = $dashboardResponse2->getStatusCode();
    
    if ($dashboardStatus2 === 200) {
        echo "✅ Dashboard still accessible after wait\n";
    } elseif ($dashboardStatus2 === 302) {
        $redirectLocation = $dashboardResponse2->getHeaderLine('Location');
        if (strpos($redirectLocation, 'login') !== false) {
            echo "❌ Session expired - redirected to login\n";
        } else {
            echo "🔄 Redirected to: $redirectLocation\n";
        }
    } else {
        echo "⚠️ Unexpected status: $dashboardStatus2\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n";