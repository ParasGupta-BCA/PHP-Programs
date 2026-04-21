<?php
require_once 'orders-helper.php';
require_once 'products-data.php';

$orders = load_orders();

// Build a map of Item Name -> Local Image URL
$productImages = [];
foreach ($malls as $cat) {
    foreach ($cat['products'] as $p) {
        $productImages[$p['item']] = $p['image'];
    }
}

$count = 0;
foreach ($orders as &$order) {
    if (isset($order['items']) && is_array($order['items'])) {
        foreach ($order['items'] as &$item) {
            $name = $item['name'] ?? '';
            // Always update image if we have a match in catalog
            if (isset($productImages[$name])) {
                $item['image'] = $productImages[$name];
                $count++;
            }
        }
    }
}

if ($count > 0) {
    if (file_put_contents(get_orders_file(), json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        echo "Updated $count items in orders.json with local images.\n";
    } else {
        echo "Failed to write orders.json.\n";
    }
} else {
    echo "No items needed updating.\n";
}
?>
