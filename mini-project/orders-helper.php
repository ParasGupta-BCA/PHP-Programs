<?php
/**
 * Order storage for Prabhdeep Mega Mart – save/load orders from JSON file
 */
function get_orders_file() {
    return __DIR__ . DIRECTORY_SEPARATOR . 'orders.json';
}

function load_orders() {
    $file = get_orders_file();
    if (!is_file($file)) {
        return [];
    }
    $json = @file_get_contents($file);
    if ($json === false) {
        return [];
    }
    $data = @json_decode($json, true);
    return is_array($data) ? $data : [];
}

function save_order(array $order) {
    $orders = load_orders();
    array_unshift($orders, $order); // newest first
    $file = get_orders_file();
    return @file_put_contents($file, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}
