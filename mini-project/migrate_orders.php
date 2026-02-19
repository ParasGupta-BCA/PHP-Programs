<?php
require_once 'orders-helper.php';
require_once 'products-data.php';

$orders = load_orders();
$productImages = [];
foreach ($malls as $cat) {
    foreach ($cat['products'] as $p) {
        $productImages[$p['item']] = $p['image'];
    }
}

$updated = false;
foreach ($orders as &$order) {
    if (isset($order['items']) && is_array($order['items'])) {
        foreach ($order['items'] as &$item) {
            if (empty($item['image'])) {
                $name = $item['name'] ?? '';
                if (isset($productImages[$name])) {
                    $item['image'] = $productImages[$name];
                    $updated = true;
                    echo "Added image for $name in order " . ($order['order_id'] ?? 'Unknown') . "\n";
                }
            }
        }
    }
}

if ($updated) {
    if (file_put_contents(get_orders_file(), json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        echo "orders.json updated successfully.\n";
    } else {
        echo "Failed to write orders.json.\n";
    }
} else {
    echo "No updates needed.\n";
}
?>
