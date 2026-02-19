<?php
/**
 * Order Processing System – Prabhdeep Mega Mart
 * Uses shared product catalog from products-data.php
 */
require_once __DIR__ . '/products-data.php';
require_once __DIR__ . '/orders-helper.php';

// Build flat product list (id => name, price) from $malls for order form
$products = [];
$id = 1;
foreach ($malls as $category) {
    foreach ($category['products'] as $p) {
        $products[$id] = [
            'name'  => $p['item'],
            'price' => parse_price($p['price']),
            'image' => $p['image'] ?? 'https://placehold.co/120x120/e5e7eb/9ca3af?text=Product',
        ];
        $id++;
    }
}

$orderPlaced = false;
$errors = [];
$orderData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and process order
    $customerName = trim($_POST['customer_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($customerName)) {
        $errors[] = 'Customer name is required.';
    }
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (empty($address)) {
        $errors[] = 'Delivery address is required.';
    }

    $orderItems = [];
    $grandTotal = 0;

    foreach ($products as $id => $product) {
        $qty = (int) ($_POST['qty'][$id] ?? 0);
        if ($qty > 0) {
            $subtotal = $qty * $product['price'];
            $orderItems[] = [
                'id' => $id,
                'name' => $product['name'],
                'price' => $product['price'],
                'qty' => $qty,
                'subtotal' => $subtotal,
                'image' => $product['image'] ?? '',
            ];
            $grandTotal += $subtotal;
        }
    }

    if (empty($orderItems)) {
        $errors[] = 'Please select at least one product.';
    }

    if (empty($errors)) {
        $orderData = [
            'order_id' => 'ORD-' . strtoupper(substr(uniqid(), -6)),
            'date' => date('d M Y, h:i A'),
            'customer_name' => $customerName,
            'email' => $email,
            'address' => $address,
            'phone' => $phone,
            'items' => $orderItems,
            'total' => $grandTotal,
        ];
        save_order($orderData);
        $orderPlaced = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prabhdeep Mega Mart - Place Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-green: #0c831f;
            --brand-green-dark: #096b19;
            --brand-yellow: #fec500;
            --bg-page: #f4f6fb;
            --bg-card: #ffffff;
            --text-main: #1c1c1c;
            --text-muted: #666666;
            --border-light: #e0e0e0;
            --shadow-card: 0 1px 4px rgba(0,0,0,0.04);
            --radius-card: 12px;
            --radius-btn: 8px;
            --font-main: 'DM Sans', sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font-main);
            background: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            padding-bottom: 80px; /* Space for fixed footer if needed */
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
        }

        /* Header */
        .page-header {
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 12px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-bottom: 24px;
        }
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
        }
        .brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--brand-green);
            text-decoration: none;
            letter-spacing: -0.03em;
        }
        .brand span { color: var(--brand-yellow); }
        .nav-link {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Layout Grid */
        .layout-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
        }

        /* Product Card */
        .product-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-card);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .product-img-wrap {
            width: 100%;
            padding-top: 100%; /* 1:1 Aspect Ratio */
            position: relative;
            background: #f9f9f9;
        }
        .product-img {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: contain;
            padding: 12px;
        }
        .product-info {
            padding: 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .product-name {
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 4px;
            color: var(--text-main);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .product-weight {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 12px;
        }
        .product-footer {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .product-price {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
        }

        /* Quantity Control (Blinkit Style) */
        .qty-control {
            display: flex;
            align-items: center;
            background: #fff;
            border: 1px solid var(--brand-green);
            border-radius: 6px;
            overflow: hidden;
            height: 32px;
            min-width: 70px;
        }
        .qty-btn {
            background: transparent;
            border: none;
            color: var(--brand-green);
            font-weight: 700;
            width: 24px;
            height: 100%;
            cursor: pointer;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qty-btn:hover { background: #f0fdf4; }
        .qty-input {
            width: 24px;
            border: none;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--brand-green);
            pointer-events: none; /* Read only visual */
            -moz-appearance: textfield;
        }
        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

        /* Checkout Sidebar */
        .checkout-sidebar {
            position: sticky;
            top: 100px;
        }
        .cart-card {
            background: var(--bg-card);
            border-radius: var(--radius-card);
            padding: 20px;
            box-shadow: var(--shadow-card);
        }
        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.9rem;
            transition: border 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--brand-green);
            box-shadow: 0 0 0 3px rgba(12, 131, 31, 0.1);
        }
        .btn-primary {
            width: 100%;
            background: var(--brand-green);
            color: white;
            border: none;
            padding: 14px;
            font-size: 1rem;
            font-weight: 700;
            border-radius: var(--radius-btn);
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-primary:hover { background: var(--brand-green-dark); }
        
        /* Mobile */
        @media (max-width: 900px) {
            .layout-grid { grid-template-columns: 1fr; }
            .checkout-sidebar { position: static; }
            .product-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
        }

        /* Success State */
        .success-state {
            text-align: center;
            padding: 40px 20px;
            background: #fff;
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            max-width: 600px;
            margin: 40px auto;
        }
        .success-icon {
            width: 64px; height: 64px;
            background: #e8f5e9;
            color: var(--brand-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 20px;
        }
        .order-summary-table { margin-top: 24px; width: 100%; border-collapse: collapse; text-align: left; }
        .order-summary-table th { font-size: 0.8rem; color: var(--text-muted); padding: 8px; border-bottom: 1px solid #eee; }
        .order-summary-table td { padding: 12px 8px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
    </style>
</head>
<body>
    <header class="page-header">
        <div class="header-content">
            <a href="Assignment.php" class="brand">Prabhdeep <span>Mega Mart</span></a>
            <nav>
                <a href="orders.php" class="nav-link">My Orders</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <?php if ($orderPlaced): ?>
            <div class="success-state">
                <div class="success-icon">✓</div>
                <h2>Order Placed Successfully!</h2>
                <p>Order ID: <strong style="color: var(--text-main)"><?php echo htmlspecialchars($orderData['order_id']); ?></strong></p>
                <p style="color: var(--text-muted); margin-bottom: 24px;">Thank you for shopping with us.</p>
                
                <table class="order-summary-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th style="text-align: right;">Qty</th>
                            <th style="text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderData['items'] as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td style="text-align: right;"><?php echo $item['qty']; ?></td>
                                <td style="text-align: right;">₹<?php echo number_format($item['subtotal']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="padding-top: 16px; font-weight: 700; border: none;">Grand Total</td>
                            <td style="padding-top: 16px; font-weight: 700; text-align: right; border: none; font-size: 1.1rem;">₹<?php echo number_format($orderData['total']); ?></td>
                        </tr>
                    </tfoot>
                </table>

                <div style="margin-top: 32px; display: grid; gap: 12px;">
                    <a href="Assignment2.php" class="btn-primary" style="text-decoration: none; display: inline-block; text-align: center;">Place Another Order</a>
                    <a href="orders.php" style="color: var(--brand-green); text-decoration: none; font-weight: 600;">View All Orders</a>
                </div>
            </div>
            
            <script>
            (function() {
                try {
                    var orderData = <?php echo json_encode($orderData); ?>;
                    var key = 'orderHistory';
                    var list = JSON.parse(localStorage.getItem(key) || '[]');
                    list.unshift(orderData);
                    if (list.length > 50) list = list.slice(0, 50);
                    localStorage.setItem(key, JSON.stringify(list));
                } catch (e) {}
            })();
            </script>
        <?php else: ?>

            <form method="POST" action="Assignment2.php">
                <div class="layout-grid">
                    <!-- Left: Products -->
                    <div class="main-content">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-error" style="margin-bottom: 24px; padding: 16px; border-radius: 8px; background: #fee2e2; color: #991b1b;">
                                <?php foreach ($errors as $err): ?>
                                    <p><?php echo htmlspecialchars($err); ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 16px;">Groceries & Essentials</h2>
                        
                        <div class="product-grid">
                            <?php foreach ($products as $id => $product): ?>
                                <div class="product-card">
                                    <div class="product-img-wrap">
                                        <img src="<?php echo htmlspecialchars($product['image'] ?? ''); ?>" alt="" class="product-img" loading="lazy">
                                    </div>
                                    <div class="product-info">
                                        <div class="product-name" title="<?php echo htmlspecialchars($product['name']); ?>"><?php echo htmlspecialchars($product['name']); ?></div>
                                        <div class="product-weight">1 unit</div>
                                        <div class="product-footer">
                                            <div class="product-price">₹<?php echo number_format($product['price']); ?></div>
                                            <div class="qty-control">
                                                <button type="button" class="qty-btn" onclick="adjustQty(this, -1)">−</button>
                                                <input type="number" name="qty[<?php echo $id; ?>]" class="qty-input" value="<?php echo (int)($_POST['qty'][$id] ?? 0); ?>" readonly>
                                                <button type="button" class="qty-btn" onclick="adjustQty(this, 1)">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Right: Checkout -->
                    <aside class="checkout-sidebar">
                        <div class="cart-card">
                            <h2 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;">Delivery Details</h2>
                            
                            <div class="form-group">
                                <label for="customer_name">Name</label>
                                <input type="text" id="customer_name" name="customer_name" class="form-control" 
                                       required placeholder="e.g. Paras Gupta"
                                       value="<?php echo htmlspecialchars($_POST['customer_name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                       required placeholder="name@example.com"
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone (Optional)</label>
                                <input type="tel" id="phone" name="phone" class="form-control"
                                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea id="address" name="address" class="form-control" required rows="3"
                                          placeholder="Enter full address"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                            </div>

                            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px dashed #e0e0e0;">
                                <button type="submit" class="btn-primary">Proceed to Pay</button>
                                <p style="text-align: center; font-size: 0.8rem; color: #666; margin-top: 12px;">Safe and secure payment</p>
                            </div>
                        </div>
                    </aside>
                </div>
            </form>

            <script>
                function adjustQty(btn, delta) {
                    const container = btn.parentElement;
                    const input = container.querySelector('input');
                    let val = parseInt(input.value) || 0;
                    val += delta;
                    if (val < 0) val = 0;
                    if (val > 99) val = 99;
                    input.value = val;
                }
            </script>
        <?php endif; ?>
    </div>
</body>
</html>
