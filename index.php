<?php
// Very simple router for Day 1 UI testing
// Usage example: /index.php?c=auth&a=login

$c = isset($_GET['c']) ? $_GET['c'] : 'auth';
$a = isset($_GET['a']) ? $_GET['a'] : 'login';

// Map controller/action to view files (UI only)
$routes = [
  'auth' => [
    'login'    => __DIR__ . '/../app/views/auth/login.php',
    'register' => __DIR__ . '/../app/views/auth/register.php',
  ],
  'manager' => [
    'slotsManage'   => __DIR__ . '/../app/views/manager/slots_manage.php',
    'requests'      => __DIR__ . '/../app/views/manager/requests.php',
    'dailySchedule' => __DIR__ . '/../app/views/manager/daily_schedule.php',
  ],
];

// Fallback if route not found
if (!isset($routes[$c]) || !isset($routes[$c][$a])) {
  echo "Page not found.";
  exit;
}

// Load the view
require $routes[$c][$a];
