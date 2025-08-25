<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

echo "Testing session persistence...\n\n";

$baseUrl = 'http://127.0.0.1:8000/';
$cookieJar = new CookieJar();
$client = new Client([
    'base_uri' => $baseUrl,
    'cookies' => $cookieJar,
    'allow_redirects' => true
]);

try {
    // Step 1: Get login page and extract CSRF token
    echo "1. Getting login page...\n";
    $loginPageResponse = $client->get('login');
    $loginPageContent = $loginPageResponse->getBody()->getContents();
    
    preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPageContent, $matches);
    $csrfToken = $matches[1] ?? null;
    
    if (!$csrfToken) {
        echo "❌ Could not extract CSRF token\n";
        exit(1);
    }
    
    echo "✅ CSRF Token extracted: " . substr($csrfToken, 0, 10) . "...\n";
    
    // Step 2: Login
    echo "\n2. Logging in...\n";
    $loginResponse = $client->post('login', [
        'form_params' => [
            'email' => 'admin@example.com',
            'password' => 'password',
            '_token' => $csrfToken
        ],
        'headers' => [
            'X-CSRF-TOKEN' => $csrfToken,
            'X-Requested-With' => 'XMLHttpRequest'
        ]
    ]);
    
    echo "Login Status: " . $loginResponse->getStatusCode() . "\n";
    
    // Step 3: Access dashboard multiple times to test session persistence
    echo "\n3. Testing session persistence...\n";
    
    for ($i = 1; $i <= 5; $i++) {
        echo "Request $i: ";
        
        try {
            $dashboardResponse = $client->get('admin', [
                'headers' => [
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                ]
            ]);
            
            $statusCode = $dashboardResponse->getStatusCode();
            $content = $dashboardResponse->getBody()->getContents();
            
            if ($statusCode === 200) {
                // Check if we're still authenticated by looking for admin content
                if (strpos($content, 'dashboard') !== false || strpos($content, 'admin') !== false) {
                    echo "✅ Authenticated (Status: $statusCode)\n";
                } else {
                    echo "⚠️ Possible redirect or login required (Status: $statusCode)\n";
                }
            } else {
                echo "❌ Failed (Status: $statusCode)\n";
            }
            
        } catch (Exception $e) {
            echo "❌ Exception: " . $e->getMessage() . "\n";
        }
        
        // Wait 1 second between requests
        sleep(1);
    }
    
    // Step 4: Check session data in database
    echo "\n4. Checking session data in database...\n";
    
    // Get cookies to see session ID
    $cookies = $cookieJar->toArray();
    $sessionCookie = null;
    
    foreach ($cookies as $cookie) {
        if (strpos($cookie['Name'], 'session') !== false) {
            $sessionCookie = $cookie;
            break;
        }
    }
    
    if ($sessionCookie) {
        echo "✅ Session cookie found: " . $sessionCookie['Name'] . " = " . substr($sessionCookie['Value'], 0, 20) . "...\n";
        echo "Cookie expires: " . ($sessionCookie['Expires'] ? date('Y-m-d H:i:s', $sessionCookie['Expires']) : 'Session') . "\n";
    } else {
        echo "❌ No session cookie found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n";