<!DOCTYPE html>
<!-- prabhdeep mega mart is a leading multi store in hari nagar delhi
create an html page as home page and display the product details using multi dimentional associative array and display the details in table format using foreach loop -->
<html lang="en">
<head>    
    <title>Prabhdeep Mega Mart</title>
    </head>
<body">
    <h1 >Welcome to Prabhdeep Mega Mart</h1>
    <h2 >Product Details</h2>
    <table border="1">
        <tr>
            <th>Product Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
        </tr>
        <?php
        $products =array("Electronics" => array(array(
            "name" => "Laptop",
            "price" => 50000,
            "stock" => 10
        ), array(
            "name" => "Smartphone",
            "price" => 20000,
            "stock" => 20
        ), array(
            "name" => "Headphones",
            "price" => 2000,
            "stock" => 30
        )),
        "Clothing" => array(array(
            "name" => "T-Shirt",
            "price" => 500,
            "stock" => 50
        ), array(
            "name" => "Jeans",
            "price" => 1500,
            "stock" => 25
        ), array(
            "name" => "Jacket",
            "price" => 3000,
            "stock" => 10
        )),
        "Groceries" => array(array(
            "name" => "Rice",
            "price" => 100,
            "stock" => 100
        ), array(
            "name" => "Wheat",
            "price" => 80,
            "stock" => 150
        ), array(
            "name" => "Sugar",
            "price" => 60,
            "stock" => 200
        )),
        "Toys" => array(array(
            "name" => "Action Figure",
            "price" => 1500,
            "stock" => 15
        ), array(
            "name" => "Board Game",
            "price" => 800,
            "stock" => 20
        ), array(
            "name" => "Puzzle",
            "price" => 500,
            "stock" => 25
        ))
        );
        ksort($products);

        foreach ($products as $category => $items) {
            foreach ($items as $item) {
                echo "<tr>";
                echo "<td>" . $item['name'] . "</td>";
                echo "<td>" . $category . "</td>";
                echo "<td>" . $item['price'] . "</td>";
                echo "<td>" . $item['stock'] . "</td>";
                echo "</tr>";
            }
        }
        // average price of products in each category
        echo "<tr><th colspan='4'>Average Price of Products in Each Category</th
        </tr>";
        foreach ($products as $category => $items) {
            $totalPrice = 0;
            $count = count($items);
            foreach ($items as $item) {
                $totalPrice += $item['price'];
            }
            $averagePrice = $count > 0 ? $totalPrice / $count : 0;
            echo "<tr>";
            echo "<td colspan='3'>" . $category . "</td>";
            echo "<td>" . number_format($averagePrice, 2) . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>