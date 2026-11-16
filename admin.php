<?php
// admin.php — Forward tương thích ngược về admin/index.php
$params = $_GET;
if (isset($params['route'])) {
    $params['page'] = $params['route'];
    unset($params['route']);
}
$queryString = !empty($params) ? ('?' . http_build_query($params)) : '';
header('Location: admin/index.php' . $queryString);
exit;
