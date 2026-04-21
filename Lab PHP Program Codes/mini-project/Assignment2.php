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
    $paymentMethod = $_POST['payment_method'] ?? '';

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
    if (empty($paymentMethod)) {
        $errors[] = 'Please select a payment method.';
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
            'payment_method' => $paymentMethod === 'upi' ? 'UPI' : 'Cash on Delivery',
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
            padding: 4px;
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
        /* Payment Options */
        .payment-options { display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; }
        .payment-label {
            display: flex; align-items: center; justify-content: flex-start; gap: 12px;
            padding: 14px 20px; border: 1px solid #e5e7eb; border-radius: 12px;
            cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600; font-size: 0.95rem; background: #fff; color: #374151;
            /* Text aligned left */
        }
        .payment-label svg {
            flex-shrink: 0; /* Don't let SVG shrink */
            vertical-align: middle; /* Align with text baseline */
        }
        .payment-label:hover { border-color: var(--brand-green); background: #f0fdf4; }
        .payment-input { display: none; }
        .payment-input:checked + .payment-label {
            border-color: var(--brand-green);
            background: #e8f5e9;
            color: var(--brand-green-dark);
            box-shadow: 0 0 0 1px var(--brand-green);
        }

        /* Payment Modal */
        .payment-modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 1000;
            display: none; align-items: center; justify-content: center;
            animation: fadeIn 0.2s ease-out;
        }
        .payment-modal-overlay.active { display: flex; }
        .payment-modal {
            background: #fff; border-radius: 20px; padding: 32px;
            max-width: 400px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .payment-modal h3 {
            font-size: 1.25rem; font-weight: 700; color: #1f2937;
            margin-bottom: 8px;
        }
        .payment-modal p {
            font-size: 0.85rem; color: #6b7280; margin-bottom: 24px;
        }
        .payment-modal .btn-confirm {
            width: 100%; padding: 14px; background: var(--brand-green);
            color: #fff; border: none; border-radius: 12px;
            font-size: 1rem; font-weight: 700; cursor: pointer;
            margin-top: 24px; transition: all 0.2s;
        }
        .payment-modal .btn-confirm:hover { background: var(--brand-green-dark); transform: translateY(-1px); }
        .payment-modal .btn-confirm:disabled {
            background: #d1d5db; cursor: not-allowed; transform: none;
        }
        .payment-modal-close {
            float: right; background: none; border: none;
            font-size: 1.5rem; color: #9ca3af; cursor: pointer;
            line-height: 1; padding: 0;
        }
        .payment-modal-close:hover { color: #374151; }

        /* Saved Delivery Details - Blinkit Style */
        .saved-details { display: none; }
        .saved-details.active { display: block; }
        .delivery-form-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 999;
            display: none; align-items: center; justify-content: center;
            animation: fadeIn 0.2s ease-out;
        }
        .delivery-form-overlay.active { display: flex; }
        .delivery-form-card {
            background: #fff; border-radius: 20px; padding: 28px;
            max-width: 420px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .delivery-form-card .form-close {
            float: right; background: none; border: none;
            font-size: 1.5rem; color: #9ca3af; cursor: pointer;
            line-height: 1; padding: 0;
        }
        .delivery-form-card .form-close:hover { color: #374151; }

        /* Blinkit Delivery Row */
        .bk-delivery-row {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 0;
        }
        .bk-delivery-row .bk-pin {
            width: 28px; height: 28px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .bk-delivery-row .bk-pin svg { color: #1f2937; }
        .bk-delivery-row .bk-info { flex: 1; min-width: 0; }
        .bk-delivery-row .bk-info .bk-label {
            font-size: 0.8rem; font-weight: 700; color: #1f2937;
        }
        .bk-delivery-row .bk-info .bk-value {
            font-size: 0.75rem; color: #9ca3af; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis;
        }
        .bk-change {
            background: none; border: none; color: var(--brand-green);
            font-size: 0.78rem; font-weight: 700; cursor: pointer;
            flex-shrink: 0;
        }

        /* Cart Summary */
        .cart-summary { margin-bottom: 12px; }
        .cart-summary-title {
            font-size: 0.85rem; font-weight: 700; color: #1f2937;
            margin-bottom: 10px; display: flex; align-items: center; gap: 8px;
        }
        .cart-summary-title .cart-count {
            background: var(--brand-green); color: #fff; font-size: 0.65rem;
            padding: 2px 7px; border-radius: 10px; font-weight: 600;
        }
        .cart-items { max-height: 200px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none; }
        .cart-items::-webkit-scrollbar { display: none; }
        .cart-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 8px 0; border-bottom: 1px solid #f3f4f6;
            font-size: 0.8rem; gap: 8px;
        }
        .cart-item:last-child { border-bottom: none; }
        .cart-item-img {
            width: 36px; height: 36px; border-radius: 6px;
            object-fit: cover; flex-shrink: 0; background: #f9fafb;
            border: 1px solid #f3f4f6;
        }
        .cart-item-name {
            flex: 1; color: #374151; font-weight: 500;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .cart-item-qty {
            color: #9ca3af; font-size: 0.75rem; margin-right: 10px;
            flex-shrink: 0;
        }
        .cart-item-price {
            font-weight: 600; color: #1f2937; flex-shrink: 0;
        }
        .cart-total-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 0 4px; border-top: 1px dashed #e5e7eb;
            margin-top: 4px;
        }
        .cart-total-label {
            font-size: 0.85rem; font-weight: 700; color: #1f2937;
        }
        .cart-total-value {
            font-size: 1rem; font-weight: 800; color: var(--brand-green);
        }
        .cart-empty {
            text-align: center; padding: 16px 0; color: #9ca3af;
            font-size: 0.82rem;
        }
        .cart-divider {
            border: none; border-top: 1px solid #f3f4f6; margin: 8px 0;
        }

        /* Blinkit CTA */
        .bk-cta {
            display: flex; align-items: center; justify-content: center;
            width: 100%; padding: 14px 20px; border: none; border-radius: 12px;
            font-size: 1rem; font-weight: 700; cursor: pointer;
            transition: all 0.2s; margin-top: 8px;
            background: #0c831f; color: #fff;
            gap: 8px; letter-spacing: 0.2px;
        }
        .bk-cta:hover { background: var(--brand-green-dark); }
        .bk-cta svg { flex-shrink: 0; }

        .btn-save-details {
            width: 100%; padding: 14px; background: #0c831f;
            color: #fff; border: none; border-radius: 12px;
            font-size: 1rem; font-weight: 700; cursor: pointer;
            margin-top: 16px; transition: all 0.2s;
        }
        .btn-save-details:hover { background: var(--brand-green-dark); }

        .delivery-form-header {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 20px;
        }
        .delivery-form-header svg { color: var(--brand-green); flex-shrink: 0; }
        .delivery-form-header h3 { font-size: 1.1rem; font-weight: 700; color: #1f2937; margin: 0; }

        /* Mobile sticky bottom bar */
        .mobile-bottom-bar {
            display: none;
        }
        @media (max-width: 900px) {
            .checkout-sidebar {
                position: static !important;
                margin-bottom: 16px;
            }
            .bk-cta { display: none !important; }
            .mobile-bottom-bar {
                display: flex; align-items: center; justify-content: space-between;
                position: fixed; bottom: 0; left: 0; right: 0;
                z-index: 100; padding: 12px 16px;
                background: #fff;
                box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
                border-top: 1px solid #f3f4f6;
                gap: 12px;
            }
            .mobile-bottom-bar .mob-total {
                font-size: 0.75rem; color: #9ca3af; line-height: 1.2;
            }
            .mobile-bottom-bar .mob-total strong {
                display: block; font-size: 1.05rem; color: #1f2937; font-weight: 800;
            }
            .mobile-bottom-bar .mob-cta {
                flex: 1; padding: 13px 16px; border: none; border-radius: 10px;
                background: #0c831f; color: #fff; font-size: 0.95rem;
                font-weight: 700; cursor: pointer; text-align: center;
            }
            body { padding-bottom: 80px; }
        }

        /* Animated Success State */
        .success-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255,255,255,0.95);
            z-index: 1000;
            display: flex; align-items: center; justify-content: center;
            animation: fadeIn 0.3s ease-out;
        }
        .success-card {
            background: #fff;
            padding: 40px;
            border-radius: 24px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            max-width: 400px; width: 90%;
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(40px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .checkmark-wrapper {
            width: 80px; height: 80px;
            margin: 0 auto 24px;
            position: relative;
        }
        .checkmark-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: var(--brand-green);
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        .checkmark-check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            stroke: #fff; /* White check on green fill? Or green check? Blinkit uses stylized green circle with white check usually, or green check. User asked for Green Tick Animation. Let's do Green Circle Fill + White Check or Green Stroke Check. Blinkit is Green Circle with White Check. */
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }
        /* Let's try a filled circle animation for Blinkit style */
        .success-anim-container {
            width: 80px; height: 80px; margin: 0 auto 20px;
        }
        .anim-circle {
            width: 100%; height: 100%; border-radius: 50%;
            background: var(--brand-green);
            transform: scale(0);
            animation: scaleUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            display: flex; align-items: center; justify-content: center;
        }
        .anim-check {
            width: 40px; height: 40px;
            color: #fff;
            opacity: 0;
            transform: translateY(5px);
            animation: checkAppear 0.3s ease-out 0.4s forwards;
        }
        @keyframes scaleUp { to { transform: scale(1); } }
        @keyframes checkAppear { to { opacity: 1; transform: translateY(0); } }

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
            <div class="success-overlay">
                <div class="success-card">
                    <div class="success-anim-container">
                        <div class="anim-circle">
                            <svg class="anim-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <h2 style="font-size: 1.5rem; margin-bottom: 8px; color: #111;">Payment Successful</h2>
                    <p style="color: #666; margin-bottom: 24px; font-size: 0.95rem;">
                        Your order <strong><?= htmlspecialchars($orderData['order_id']) ?></strong> has been placed successfully.
                    </p>
                    
                    <div style="background: #f9fafb; padding: 16px; border-radius: 12px; text-align: left; margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem; color: #666;">
                            <span>Payment Method</span>
                            <span style="font-weight: 600; color: #111;"><?= htmlspecialchars($orderData['payment_method']) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 700;">
                            <span>Total Amount</span>
                            <span>₹<?= number_format($orderData['total']) ?></span>
                        </div>
                    </div>

                    <div style="display: grid; gap: 12px;">
                        <a href="orders.php" class="btn-primary" style="text-decoration: none;">View Order Details</a>
                        <a href="Assignment2.php" style="color: #666; text-decoration: none; font-size: 0.9rem; padding: 8px;">Back to Home</a>
                    </div>
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

            <form id="orderForm" method="POST" action="Assignment2.php">
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

                        <?php 
                        $currentId = 1;
                        foreach ($malls as $catKey => $catData): 
                        ?>
                            <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 16px; margin-top: 32px; border-bottom: 1px solid #eee; padding-bottom: 8px;">
                                <?php echo htmlspecialchars($catData['name']); ?>
                            </h2>
                            
                            <div class="product-grid">
                                <?php foreach ($catData['products'] as $p): 
                                    // Retrieve the processed product data using the current ID to match form handling
                                    // The ID sequence MUST match the top loop where $products is built.
                                    $product = $products[$currentId];
                                    $id = $currentId;
                                    
                                    // Render card
                                ?>
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
                                <?php 
                                    $currentId++; 
                                endforeach; 
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Right: Checkout (Blinkit Style) -->
                    <aside class="checkout-sidebar">
                        <div class="cart-card">

                            <!-- Cart Summary -->
                            <div class="cart-summary" id="cartSummary">
                                <div class="cart-summary-title">
                                    🛒 Your Cart <span class="cart-count" id="cartCount">0 items</span>
                                </div>
                                <div id="cartEmpty" class="cart-empty">Add items to get started</div>
                                <div class="cart-items" id="cartItemsList"></div>
                                <div class="cart-total-row" id="cartTotalRow" style="display:none;">
                                    <span class="cart-total-label">Total</span>
                                    <span class="cart-total-value" id="cartTotalValue">₹0</span>
                                </div>
                            </div>

                            <hr class="cart-divider">

                            <!-- When address is saved -->
                            <div class="saved-details" id="savedDetailsView">
                                <div class="bk-delivery-row">
                                    <div class="bk-pin">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
                                    </div>
                                    <div class="bk-info">
                                        <div class="bk-label">Delivering to Home</div>
                                        <div class="bk-value" id="saved_address_short"></div>
                                    </div>
                                    <button type="button" class="bk-change" onclick="editDetails()">Change</button>
                                </div>
                            </div>

                            <!-- When no address saved -->
                            <div class="bk-no-address" id="noAddressView">
                                <div class="bk-delivery-row">
                                    <div class="bk-pin">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
                                    </div>
                                    <div class="bk-info">
                                        <div class="bk-label">Add delivery address</div>
                                        <div class="bk-value">Enter details to proceed</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden inputs for form submission -->
                            <input type="hidden" name="customer_name" id="hidden_name">
                            <input type="hidden" name="email" id="hidden_email">
                            <input type="hidden" name="phone" id="hidden_phone">
                            <input type="hidden" name="address" id="hidden_address">
                            <input type="hidden" name="payment_method" id="payment_method_hidden" value="">

                            <!-- CTA Button -->
                            <button type="button" class="bk-cta" id="mainCta" onclick="handleCta()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                <span id="ctaText">Select payment option</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </button>
                        </div>
                    </aside>

                    <!-- Mobile sticky bottom bar -->
                    <div class="mobile-bottom-bar" id="mobileBottomBar">
                        <div class="mob-total">
                            <span id="mobItemCount">0 items</span>
                            <strong id="mobTotalPrice">₹0</strong>
                        </div>
                        <button type="button" class="mob-cta" onclick="handleCta()">Select payment option ›</button>
                    </div>

            <!-- Delivery Form Overlay -->
            <div class="delivery-form-overlay" id="deliveryFormOverlay">
                <div class="delivery-form-card">
                    <button class="form-close" onclick="closeDeliveryForm()">&times;</button>
                    <div class="delivery-form-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
                        <h3>Delivery Details</h3>
                    </div>
                    <div class="form-group">
                        <label for="customer_name">Name</label>
                        <input type="text" id="customer_name" class="form-control" required placeholder="e.g. Paras Gupta">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control" required placeholder="name@example.com">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone (Optional)</label>
                        <input type="tel" id="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" class="form-control" required rows="3" placeholder="Enter full address"></textarea>
                    </div>
                    <button type="button" class="btn-save-details" onclick="saveDetails()">Save & Continue</button>
                </div>
            </div>
                </div>
            </form>

            <!-- Payment Modal -->
            <div class="payment-modal-overlay" id="paymentModal">
                <div class="payment-modal">
                    <button class="payment-modal-close" onclick="closePaymentModal()">&times;</button>
                    <h3>Choose Payment Method</h3>
                    <p>Select how you'd like to pay for your order</p>
                    <div class="payment-options">
                        <input type="radio" name="payment_modal_method" id="modal_upi" value="upi" class="payment-input">
                        <label for="modal_upi" class="payment-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            <span>UPI</span>
                        </label>

                        <input type="radio" name="payment_modal_method" id="modal_cod" value="cod" class="payment-input">
                        <label for="modal_cod" class="payment-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M2 10h2"/><path d="M20 10h2"/><path d="M2 14h2"/><path d="M20 14h2"/></svg>
                            <span>Cash on Delivery</span>
                        </label>
                    </div>
                    <button class="btn-confirm" id="confirmPayBtn" disabled onclick="confirmPayment()">Select a payment method</button>
                </div>
            </div>

            <script>
                // Product data from PHP
                const productData = <?php echo json_encode(array_map(function($p) {
                    return ['name' => $p['name'], 'price' => $p['price'], 'image' => $p['image']];
                }, $products)); ?>;

                function adjustQty(btn, delta) {
                    const container = btn.parentElement;
                    const input = container.querySelector('input');
                    let val = parseInt(input.value) || 0;
                    val += delta;
                    if (val < 0) val = 0;
                    if (val > 99) val = 99;
                    input.value = val;
                    updateCart();
                }

                function updateCart() {
                    const qtyInputs = document.querySelectorAll('input[name^="qty["]');
                    const cartList = document.getElementById('cartItemsList');
                    const cartEmpty = document.getElementById('cartEmpty');
                    const cartTotalRow = document.getElementById('cartTotalRow');
                    const cartTotalValue = document.getElementById('cartTotalValue');
                    const cartCount = document.getElementById('cartCount');

                    let html = '';
                    let total = 0;
                    let itemCount = 0;

                    qtyInputs.forEach(input => {
                        const qty = parseInt(input.value) || 0;
                        if (qty > 0) {
                            const match = input.name.match(/qty\[(\d+)\]/);
                            if (match) {
                                const id = match[1];
                                const product = productData[id];
                                if (product) {
                                    const subtotal = qty * product.price;
                                    total += subtotal;
                                    itemCount += qty;
                                    html += '<div class="cart-item">' +
                                        '<img class="cart-item-img" src="' + product.image + '" alt="">' +
                                        '<span class="cart-item-name">' + product.name + '</span>' +
                                        '<span class="cart-item-qty">x' + qty + '</span>' +
                                        '<span class="cart-item-price">₹' + subtotal.toLocaleString('en-IN') + '</span>' +
                                    '</div>';
                                }
                            }
                        }
                    });

                    cartList.innerHTML = html;
                    cartCount.textContent = itemCount + ' item' + (itemCount !== 1 ? 's' : '');
                    cartEmpty.style.display = itemCount === 0 ? 'block' : 'none';
                    cartTotalRow.style.display = itemCount > 0 ? 'flex' : 'none';
                    cartTotalValue.textContent = '₹' + total.toLocaleString('en-IN');

                    // Update mobile bottom bar
                    const mobCount = document.getElementById('mobItemCount');
                    const mobPrice = document.getElementById('mobTotalPrice');
                    if (mobCount) mobCount.textContent = itemCount + ' item' + (itemCount !== 1 ? 's' : '');
                    if (mobPrice) mobPrice.textContent = '₹' + total.toLocaleString('en-IN');
                }

                // Initial cart update
                updateCart();

                // ========== Delivery Details localStorage ==========
                const STORAGE_KEY = 'deliveryDetails';
                const savedView = document.getElementById('savedDetailsView');
                const noAddressView = document.getElementById('noAddressView');
                const deliveryOverlay = document.getElementById('deliveryFormOverlay');
                let hasAddress = false;

                function loadSavedDetails() {
                    const saved = JSON.parse(localStorage.getItem(STORAGE_KEY));
                    if (saved && saved.name && saved.email && saved.address) {
                        hasAddress = true;
                        // Show compact address in bottom bar
                        const shortAddr = saved.name + ' — ' + saved.address.substring(0, 35) + (saved.address.length > 35 ? '...' : '');
                        document.getElementById('saved_address_short').textContent = shortAddr;

                        // Set hidden inputs
                        document.getElementById('hidden_name').value = saved.name;
                        document.getElementById('hidden_email').value = saved.email;
                        document.getElementById('hidden_phone').value = saved.phone || '';
                        document.getElementById('hidden_address').value = saved.address;

                        savedView.classList.add('active');
                        noAddressView.style.display = 'none';
                        document.getElementById('ctaText').textContent = 'Select payment option';
                    } else {
                        hasAddress = false;
                        savedView.classList.remove('active');
                        noAddressView.style.display = 'block';
                        document.getElementById('ctaText').textContent = 'Add delivery details';
                    }
                }

                function openDeliveryForm() {
                    deliveryOverlay.classList.add('active');
                }

                function closeDeliveryForm() {
                    deliveryOverlay.classList.remove('active');
                }

                function saveDetails() {
                    const name = document.getElementById('customer_name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const phone = document.getElementById('phone').value.trim();
                    const address = document.getElementById('address').value.trim();

                    if (!name) { alert('Please enter your name.'); return; }
                    if (!email) { alert('Please enter your email.'); return; }
                    if (!address) { alert('Please enter your address.'); return; }

                    const details = { name, email, phone, address };
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(details));
                    closeDeliveryForm();
                    loadSavedDetails();
                }

                function editDetails() {
                    const saved = JSON.parse(localStorage.getItem(STORAGE_KEY));
                    if (saved) {
                        document.getElementById('customer_name').value = saved.name || '';
                        document.getElementById('email').value = saved.email || '';
                        document.getElementById('phone').value = saved.phone || '';
                        document.getElementById('address').value = saved.address || '';
                    }
                    openDeliveryForm();
                }

                function handleCta() {
                    if (!hasAddress) {
                        openDeliveryForm();
                        return;
                    }
                    // Check if at least one product is selected
                    const qtyInputs = document.querySelectorAll('input[name^="qty["]');
                    let hasProduct = false;
                    qtyInputs.forEach(input => { if (parseInt(input.value) > 0) hasProduct = true; });
                    if (!hasProduct) { alert('Please select at least one product.'); return; }
                    openPaymentModal();
                }

                // Load on page ready
                loadSavedDetails();

                // Close form overlay on outside click
                deliveryOverlay.addEventListener('click', (e) => {
                    if (e.target === deliveryOverlay) closeDeliveryForm();
                });

                // ========== Payment Modal Logic ==========
                const paymentModal = document.getElementById('paymentModal');
                const confirmBtn = document.getElementById('confirmPayBtn');
                const paymentRadios = document.querySelectorAll('input[name="payment_modal_method"]');

                function openPaymentModal() {
                    paymentModal.classList.add('active');
                }

                function closePaymentModal() {
                    paymentModal.classList.remove('active');
                }

                // Enable confirm button when payment is selected
                paymentRadios.forEach(radio => {
                    radio.addEventListener('change', () => {
                        confirmBtn.disabled = false;
                        confirmBtn.textContent = 'Confirm & Pay';
                    });
                });

                function confirmPayment() {
                    const selected = document.querySelector('input[name="payment_modal_method"]:checked');
                    if (!selected) return;
                    document.getElementById('payment_method_hidden').value = selected.value;
                    document.getElementById('orderForm').submit();
                }

                // Close on outside click
                paymentModal.addEventListener('click', (e) => {
                    if (e.target === paymentModal) closePaymentModal();
                });
            </script>
        <?php endif; ?>
    </div>
</body>
</html>
