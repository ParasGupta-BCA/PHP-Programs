<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Loading orders-helper.php...\n";
require_once 'orders-helper.php';
echo "Loading products-data.php...\n";
require_once 'products-data.php';

echo "Malls loaded. Categories: " . isset($malls) . "\n";
if (isset($malls)) {
    echo "Count: " . count($malls) . "\n";
}

$orders = load_orders();
echo "Orders loaded. Count: " . count($orders) . "\n";

$productImages = [];
foreach ($malls as $cat) {
    foreach ($cat['products'] as $p) {
        $productImages[$p['item']] = $p['image'];
    }
}

echo "Lookup map built. Rice image: " . ($productImages['Rice'] ?? 'NOT FOUND') . "\n";

foreach ($orders as $i => $order) {
    echo "Order #$i items:\n";
    $items = $order['items'] ?? [];
    foreach ($items as $item) {
        $name = $item['name'] ?? 'Unknown';
        $img = $item['image'] ?? '';
        echo " - $name: Image stored: [" . ($img ? 'YES' : 'NO') . "]";
        
        if (empty($img)) {
            if (isset($productImages[$name])) {
                 echo " -> FALLBACK FOUND: " . substr($productImages[$name], 0, 20) . "...\n";
            } else {
                 echo " -> FALLBACK FAILED\n";
            }
        } else {
            echo "\n";
        }
    }
}
?>
