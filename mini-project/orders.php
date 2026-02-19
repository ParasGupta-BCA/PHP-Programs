<?php
/**
 * Order Management – Prabhdeep Mega Mart
 * Shows all orders taken from the store with full details
 */
require_once __DIR__ . '/orders-helper.php';
require_once __DIR__ . '/products-data.php';

// Build product image lookup map
$productImages = [];
foreach ($malls as $cat) {
    foreach ($cat['products'] as $p) {
        $productImages[$p['item']] = $p['image'];
    }
}

$orders = load_orders();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prabhdeep Mega Mart - Order Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-page: #f5f5f5;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(0, 0, 0, 0.08);
            --text: #111;
            --text-muted: #525252;
            --accent: #111;
            --radius: 20px;
            --radius-sm: 12px;
            --shadow-glass: 0 8px 32px rgba(0, 0, 0, 0.06), inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', -apple-system, sans-serif;
            background: var(--bg-page);
            min-height: 100vh;
            color: var(--text);
            padding: clamp(1.25rem, 4vw, 2.5rem);
            line-height: 1.5;
        }
        .container { max-width: 900px; margin: 0 auto; }
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-glass);
        }
        .page-header h1 { font-size: 1.75rem; font-weight: 700; color: var(--text); }
        .page-header p { margin-top: 0.5rem; color: var(--text-muted); font-size: 0.95rem; }
        .page-header .order-count { margin-top: 0.5rem; font-size: 0.9rem; color: var(--text); }
        .nav-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--glass-border);
        }
        .nav-top a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
        }
        .nav-top a:hover { color: var(--text); }
        .nav-top .brand { color: var(--text); font-weight: 600; }
        .card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-glass);
        }
        .card h2 {
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            color: var(--accent);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--glass-border);
        }
        .order-meta {
            display: grid;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.9375rem;
        }
        .order-meta .oid { font-weight: 700; color: var(--accent); }
        .order-meta .label { color: var(--text-muted); }
        .customer-block {
            margin-bottom: 1rem;
            padding: 1rem;
            background: rgba(0,0,0,0.03);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
        }
        .customer-block p { margin-bottom: 0.35rem; }
        .customer-block strong { color: var(--text); }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .items-table th,
        .items-table td {
            padding: 0.5rem 0.75rem;
            text-align: left;
            border-bottom: 1px solid var(--glass-border);
        }
        .items-table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        .items-table .text-right { text-align: right; }
        .order-item-img { width: 40px; height: 40px; object-fit: cover; border-radius: 8px; vertical-align: middle; }
        .order-total {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent);
            padding-top: 0.75rem;
            border-top: 2px solid var(--glass-border);
            text-align: right;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--text-muted);
        }
        .empty-state p { margin-bottom: 1rem; }
        .btn {
            display: inline-block;
            padding: 0.6rem 1.25rem;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-weight: 600;
        }
        .btn:hover { background: #333; }
        .foot { text-align: center; color: var(--text-muted); font-size: 0.85rem; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--glass-border); }
    </style>
</head>
<body>
    <div class="container">
        <header class="page-header">
            <div class="nav-top">
                <a href="Assignment.php">← Home</a>
                <a href="Assignment.php" class="brand">Prabhdeep Mega Mart</a>
                <a href="Assignment2.php">Place Order</a>
            </div>
            <h1>Order Management</h1>
            <p>Orders you have placed — view all details here</p>
            <?php if (!empty($orders)): ?>
                <p class="order-count"><strong><?= count($orders) ?></strong> order(s) placed</p>
            <?php endif; ?>
        </header>

        <?php if (empty($orders)): ?>
            <div class="card empty-state">
                <p>No orders yet. Place an order from the Place Order page and it will show here.</p>
                <a href="Assignment2.php" class="btn">Go to Place Order</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order):
                $items = $order['items'] ?? [];
                $total = (float) ($order['total'] ?? 0);
            ?>
                <div class="card">
                    <h2>Order <?= htmlspecialchars($order['order_id'] ?? '') ?></h2>
                    <div class="order-meta">
                        <span class="oid"><?= htmlspecialchars($order['order_id'] ?? '') ?></span>
                        <span class="label"><?= htmlspecialchars($order['date'] ?? '') ?></span>
                    </div>
                    <div class="customer-block">
                        <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name'] ?? '') ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($order['email'] ?? '') ?></p>
                        <?php if (!empty($order['phone'])): ?>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                        <?php endif; ?>
                        <p><strong>Address:</strong> <?= htmlspecialchars($order['address'] ?? '') ?></p>
                    </div>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Product</th>
                                <th class="text-right">Price (₹)</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Subtotal (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item):
                                $img = $item['image'] ?? '';
                                if (empty($img)) {
                                    // Try to find image from catalog by name
                                    $name = $item['name'] ?? '';
                                    if (isset($productImages[$name])) {
                                        $img = $productImages[$name];
                                    }
                                }
                            ?>
                                <tr>
                                    <td><?php if ($img): ?><img src="<?= htmlspecialchars($img) ?>" alt="" class="order-item-img" width="40" height="40"><?php endif; ?></td>
                                    <td><?= htmlspecialchars($item['name'] ?? '') ?></td>
                                    <td class="text-right"><?= number_format((float)($item['price'] ?? 0)) ?></td>
                                    <td class="text-right"><?= (int)($item['qty'] ?? 0) ?></td>
                                    <td class="text-right"><?= number_format((float)($item['subtotal'] ?? 0)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="order-total">Grand Total: ₹ <?= number_format($total) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <p class="foot">Prabhdeep Mega Mart – Mini Project (Order Processing System)</p>
    </div>
</body>
</html>
