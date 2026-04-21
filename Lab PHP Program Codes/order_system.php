<?php
session_start();

if (!isset($_SESSION['orders'])) $_SESSION['orders'] = [];

$page = $_GET['page'] ?? 'order';

function h($x) { return htmlspecialchars($x, ENT_QUOTES); }

function get_products() {
    return [
        ["Cable Wire", 5],
        ["Switch", 3],
        ["Plug", 2]
    ];
}

$name = $email = $product = $quantity = '';
$errors = [];
if ($page === 'order' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $product = $_POST['product'] ?? '';
    $quantity = intval($_POST['quantity'] ?? 0);
    $found = false;
    foreach (get_products() as $p) if ($product === $p[0]) {$price = $p[1]; $found = true;}
    if ($name === '') $errors['name'] = "Name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Valid email required";
    if (!$found) $errors['product'] = "Choose a product";
    if ($quantity < 1) $errors['quantity'] = "Quantity must be at least 1";
    if (!$errors) {
        $_SESSION['orders'][] = [
            'id' => uniqid(),
            'name' => $name,
            'email' => $email,
            'product' => $product,
            'price' => $price,
            'quantity' => $quantity,
            'total' => $price * $quantity,
            'date' => date('Y-m-d H:i:s')
        ];
        header("Location: ?page=success"); exit;
    }
}
if ($page === 'delete' && isset($_GET['id'])) {
    $_SESSION['orders'] = array_values(array_filter($_SESSION['orders'], fn($o)=>$o['id']!==$_GET['id']));
    header("Location: ?page=admin");
    exit;
}
$selected_order = null;
if ($page === 'details' && isset($_GET['id'])) {
    foreach($_SESSION['orders'] as $o) if ($o['id'] === $_GET['id']) {$selected_order = $o; break;}
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Processing System</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Inter',Arial,sans-serif; background:#f4f6fb; margin:0; }
        .navbar { background:#262964; display:flex; justify-content:center; align-items:center; gap:30px; height:58px; box-shadow:0 2px 12px #dbe3fb66; }
        .navbar a { color:#fff; text-decoration:none; font-weight:600; font-size:1.09em; letter-spacing:.5px; padding:6px 18px; border-radius:7px; transition:background .15s; }
        .navbar a.active,.navbar a:hover { background:#4251ec22; color:#dedffd; }
        .container { max-width: 545px; margin: 44px auto; background: #fff; border-radius: 15px; box-shadow: 0 3px 22px #c4cdfc33; padding:35px 35px 30px;}
        h1 { text-align:center; font-weight:700; color:#223060; font-size:2.1em; margin-bottom:12px; }
        h2 { color:#2c3177; margin-bottom:7px;font-weight:700;}
        label { font-weight:500; color:#36396e; display:block; margin-bottom:3px;}
        input, select { width:100%; border:1.5px solid #dde2ff; border-radius:8px; padding:12px; font-size:1.07em; margin-bottom:18px; background:#f7f8fc; transition:border .15s;}
        input:focus,select:focus { border-color:#4251ec; outline:none;}
        .form-actions { display:flex; gap:15px; justify-content:space-between;}
        .btn, button { background:linear-gradient(90deg,#4251ec 60%,#53aeff 100%); border:none; color:#fff; font-size:1.09em; font-weight:600; border-radius:8px;
            padding:10px 0; cursor:pointer; box-shadow:0 3px 13px #d1e1ff33; transition:box-shadow .15s,background .2s;}
        .btn:hover,button:hover { background:linear-gradient(90deg,#4251ec 70%,#209cff 100%); box-shadow:0 6px 16px #94c3ff33;}
        table { width:100%; border-collapse:separate; border-spacing:0 7px; margin-top:16px;}
        th,td { padding:13px 11px; background:#f7f8fc; text-align:left;}
        th { background:#e3ebff; font-weight:700; color:#2d3070;}
        td { color:#2d314b; font-weight:500; border-top-left-radius:8px; border-bottom-left-radius:8px;}
        tr { border-radius:14px;}
        .actions a { margin-right:7px; color:#466afb; text-decoration:underline; font-size:0.98em;}
        .actions a:last-child {color:#e66363; }
        .success { background: #dafee1; padding:17px 17px 10px; border-radius:9px; color: #1e8e4b; text-align: center; margin-bottom:15px; box-shadow:0 2px 10px #b6fed733;}
        .error { background:#ffe8e8; color:#b02828; padding:12px; border-radius:7px; margin-bottom:10px;}
        .card { background:#f7f9fd; box-shadow:0 1px 7px #abc7ed22; border-radius:12px; padding:20px 22px; margin-bottom:10px;}
        .order-detail strong { display:inline-block; width:130px; color:#23205b;}
        @media (max-width:600px) {.container {padding:13px;} .order-detail strong{width:auto;}}
        ::selection {background:#4251ec11;}
        input[type="number"]::-webkit-inner-spin-button { accent-color: #4251ec;}
        .form-title {margin-bottom:19px;}
    </style>
    <script>
    function onProductChange(sel) {
        var prices = {"Cable Wire":5,"Switch":3,"Plug":2};
        let p = sel.value;
        document.getElementById('unitSpan').innerText = (prices[p] ? "₹"+prices[p] : "");
    }
    </script>
</head>
<body>
<div class="navbar">
    <a href="?page=order" <?php if($page=='order')echo'class="active"'; ?>>Order</a>
    <a href="?page=admin" <?php if($page=='admin')echo'class="active"'; ?>>Admin Panel</a>
</div>
<div class="container">

<?php if ($page === 'order'): ?>
    <h1 class="form-title">Place a New Order</h1>
    <?php if ($errors) foreach($errors as $err): ?>
        <div class="error"><?php echo h($err); ?></div>
    <?php endforeach;?>
    <form method="post" autocomplete="off">
        <label for="name">Your Name</label>
        <input id="name" type="text" name="name" value="<?php echo h($name); ?>" placeholder="Enter your name">
        <label for="email">Email Address</label>
        <input id="email" type="email" name="email" value="<?php echo h($email); ?>" placeholder="you@email.com">
        <label for="product">Product <span id="unitSpan" style="font-weight:normal;color:#6276ef;">
            <?php if($product) foreach(get_products() as $p) if($product==$p[0]) echo "₹".$p[1]; ?>
        </span></label>
        <select id="product" name="product" onchange="onProductChange(this)">
            <option value="">-- Select Product --</option>
            <?php foreach(get_products() as $p): ?>
                <option value="<?php echo h($p[0]); ?>" <?php if($product==$p[0])echo"selected";?>>
                    <?php echo h($p[0]); ?> (₹<?php echo $p[1]; ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <label for="quantity">Quantity</label>
        <input id="quantity" type="number" min="1" name="quantity" value="<?php echo h($quantity); ?>" placeholder="1 or more">
        <div class="form-actions">
            <button type="submit" class="btn" style="width:100%;">Place Order</button>
        </div>
        <div style="margin-top:14px;text-align:center;">
            <a href="?page=admin" style="text-decoration:underline; color:#4251ec;font-weight:500;">View Admin Panel</a>
        </div>
    </form>
<?php endif; ?>

<?php if ($page === 'admin'): ?>
    <h1>Orders Admin Panel</h1>
    <div style="text-align:right; margin-bottom:10px;">
        <a class="btn" href="?page=order" style="background:linear-gradient(90deg,#5acec9 10%,#5f6cf0 88%); min-width:140px;">+ Add Order</a>
    </div>
    <?php if (count($_SESSION['orders'])): ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
            <?php foreach(array_reverse($_SESSION['orders']) as $o): ?>
            <tr>
                <td><?php echo h($o['date']); ?></td>
                <td><?php echo h($o['name']); ?><br>
                    <small style="color:#888;"><?php echo h($o['email']); ?></small>
                </td>
                <td><?php echo h($o['product']); ?></td>
                <td><?php echo h($o['quantity']); ?></td>
                <td>₹<?php echo number_format($o['total'],2); ?></td>
                <td class="actions">
                    <a href="?page=details&id=<?php echo $o['id']; ?>">View</a>
                    <a href="?page=delete&id=<?php echo $o['id']; ?>"
                       onclick="return confirm('Delete this order?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <div class="success">No orders found.</div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($page === 'details' && $selected_order): ?>
    <h2>Order Summary</h2>
    <div class="card order-detail">
        <p><strong>Date:</strong> <?php echo h($selected_order['date']); ?></p>
        <p><strong>Name:</strong> <?php echo h($selected_order['name']); ?></p>
        <p><strong>Email:</strong> <?php echo h($selected_order['email']); ?></p>
        <p><strong>Product:</strong> <?php echo h($selected_order['product']); ?></p>
        <p><strong>Unit Price:</strong> ₹<?php echo number_format($selected_order['price'],2); ?></p>
        <p><strong>Quantity:</strong> <?php echo $selected_order['quantity']; ?></p>
        <p><strong>Total:</strong> <b>₹<?php echo number_format($selected_order['total'],2); ?></b></p>
    </div>
    <div class="form-actions" style="gap:10px;">
        <a class="btn" href="?page=admin" style="width:48%;">Back</a>
    </div>
<?php elseif ($page === 'details'): ?>
    <div class="error">Order not found.</div>
    <div class="form-actions"><a class="btn" href="?page=admin">Back</a></div>
<?php endif; ?>

<?php if ($page === 'success'): ?>
    <div class="success">
        <h2 style="margin-top:0">Order Placed!</h2>
        Thank you, your order was received.<br>
    </div>
    <div class="form-actions" style="margin-top:20px;">
        <a class="btn" href="?page=order" style="width:48%;">New Order</a>
        <a class="btn" href="?page=admin" style="width:48%;background:linear-gradient(90deg,#f77c6a 30%,#574b7c 85%);">Admin Panel</a>
    </div>
<?php endif; ?>

</div>
</body>
</html>
