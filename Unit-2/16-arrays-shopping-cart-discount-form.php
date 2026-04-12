<!-- basic shopping cart system with discount functionality user defined discount 
     and we have multiple items as products options and can select multiple items -->

<?php
$products = [
    "Laptop" => 50000,
    "Mobile" => 20000,
    "Headphones" => 2000,
    "Keyboard" => 1500,
    "Mouse" => 800
];

$total = 0;
?>

<html>
    <head>
        <title>Shopping Cart</title>
    </head>
<body>

<h2>Shopping Cart System</h2>

<form method="post">
    <table border="1" cellpadding="10">
        <tr>
            <th>Select</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>

        <?php foreach ($products as $name => $price) { ?>
        <tr>
            <td>
                <input type="checkbox" name="product[]" value="<?php echo $name; ?>">
            </td>
            <td><?php echo $name; ?></td>
            <td><?php echo $price; ?></td>
            <td>
                <input type="number" name="qty[<?php echo $name; ?>]" min="1" value="1">
            </td>
        </tr>
        <?php } ?>
    </table>

    <br>

    <!-- 🔽 Discount Dropdown -->
    Discount:
    <select name="discount">
        <option value="0">No Discount</option>
        <option value="5">5%</option>
        <option value="10">10%</option>
        <option value="15">15%</option>
        <option value="20">20%</option>
        <option value="25">25%</option>
    </select>

    <br><br>
    <input type="submit" name="calculate" value="Calculate">
</form>

<?php
if (isset($_POST['calculate'])) {
    if (!empty($_POST['product'])) {

        echo "<h3>Bill Details:</h3>";

        echo "<table border='1'>";
        echo "<tr><th>Product</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Subtotal</th>
              </tr>";

        foreach ($_POST['product'] as $selected) {
            $price = $products[$selected];
            $qty = $_POST['qty'][$selected];
            $subtotal = $price * $qty;
            $total += $subtotal;

            echo "<tr>
                    <td>$selected</td>
                    <td>$qty</td>
                    <td>$price</td>
                    <td>$subtotal</td>
                  </tr>";
        }

        echo "</table>";

        // Discount logic
        $discount = $_POST['discount'];
        $discountAmount = ($total * $discount) / 100;
        $finalTotal = $total - $discountAmount;

        echo "<br>Total Amount: ₹$total";
        echo "<br>Discount: $discount% (₹$discountAmount)";
        echo "<br><strong>Final Amount: ₹$finalTotal</strong>";

    } else {
        echo "<p style='color:red;'>Please select at least one product.</p>";
    }
}
?>

</body>
</html>
<!-- 🧠 1. Concepts Used (Theory)
🔹 1. Associative Array (Products List)
$products = [
    "Laptop" => 50000,
    "Mobile" => 20000,
    "Headphones" => 2000,
    "Keyboard" => 1500,
    "Mouse" => 800
];

👉 Stores:

Product name → price
🔹 2. Dynamic Form Generation
foreach ($products as $name => $price)

👉 Automatically creates:

Product rows
Checkbox + quantity field
🔹 3. Multiple Selection (Checkbox Array)
<input type="checkbox" name="product[]" value="Laptop">

👉 product[]:

Allows selecting multiple products
Stored as array in $_POST
🔹 4. Quantity Handling (Associative Input)
<input type="number" name="qty[Laptop]">

👉 Creates:

$_POST['qty']['Laptop']

👉 Quantity linked with product name

🔹 5. Form Handling (POST)
isset($_POST['calculate'])

👉 Runs logic when "Calculate" button is clicked

🔹 6. Discount Logic
Discount selected from dropdown
Applied on total amount
🔹 7. Table Display
Bill generated using HTML table
Shows product-wise calculation
🔄 2. Flow of Execution
Step 1: Initialize Products
Products and prices stored in array
Step 2: Display Form
Table Columns:
Select (checkbox)
Product name
Price
Quantity
Step 3: User Input
Selects multiple products
Enters quantity
Chooses discount
Step 4: Form Submission
if (isset($_POST['calculate']))
Step 5: Check Product Selection
if (!empty($_POST['product']))

👉 If no product selected → show error

Step 6: Loop Through Selected Products
foreach ($_POST['product'] as $selected)
🔹 Get Price
$price = $products[$selected];
🔹 Get Quantity
$qty = $_POST['qty'][$selected];
🔹 Calculate Subtotal
$subtotal = $price * $qty;
🔹 Add to Total
$total += $subtotal;
🔹 Display Row
echo "<tr>
<td>$selected</td>
<td>$qty</td>
<td>$price</td>
<td>$subtotal</td>
</tr>";
Step 7: Apply Discount
$discount = $_POST['discount'];
🔹 Discount Amount
$discountAmount = ($total * $discount) / 100;
🔹 Final Total
$finalTotal = $total - $discountAmount;
Step 8: Display Final Bill
echo "Total Amount: ₹$total";
echo "Discount: $discount% (₹$discountAmount)";
echo "Final Amount: ₹$finalTotal";
🔑 3. Key Functions / Features
Element	Purpose
foreach	Loop through products
$_POST	Get form data
isset()	Check submission
empty()	Validate selection
Associative arrays	Map product → price
Checkbox array	Multiple selection
📊 4. Calculation Summary
Subtotal = Price × Quantity
Total = Sum of all subtotals
Discount = (Total × Discount%) / 100
Final Amount = Total − Discount
📌 5. Special Logic
✅ Multiple Product Selection
Uses product[] array
✅ Dynamic Quantity Mapping
Uses qty[product_name]
✅ Discount Dropdown
User selects percentage
📌 6. Overall Working (In One Line)

👉 This program allows users to select multiple products, enter quantities, apply a discount, and generate a final bill dynamically. -->