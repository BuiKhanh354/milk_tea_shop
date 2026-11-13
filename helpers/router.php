<?php
// helpers/router.php — Điều hướng URL theo cấu trúc minishop
function app_config()
{
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/../config/app.php';
    }
    return $config;
}

function base_url($path = '')
{
    $base = rtrim(app_config()['base_url'], '/');
    if ($path === '' || $path === '/') {
        return $base;
    }
    return $base . '/' . ltrim($path, '/');
}

function url($page = 'home', $params = array())
{
    $queryParams = array_merge(array('page' => $page), $params);
    return base_url('index.php?' . http_build_query($queryParams));
}

function admin_url($page = 'dashboard', $params = array())
{
    $queryParams = array_merge(array('page' => $page), $params);
    return base_url('admin/index.php?' . http_build_query($queryParams));
}

function asset($path)
{
    return base_url('public/' . ltrim($path, '/'));
}

function get_param($name, $default = '')
{
    if (!isset($_GET[$name]) || !is_string($_GET[$name])) {
        return $default;
    }
    return trim($_GET[$name]);
}

