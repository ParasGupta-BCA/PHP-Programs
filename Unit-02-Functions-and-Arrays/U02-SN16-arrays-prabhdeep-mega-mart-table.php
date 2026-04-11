<!DOCTYPE html>
<!-- prabhdeep mega mart is a leading multi store in hari nagar delhi
create an html page as home page and display the product details using multi dimentional associative array and display the details in table format using foreach loop -->
<html lang="en">
<head>    
    <title>Prabhdeep Mega Mart</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #06adfa;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
            h1 {
                color: #5006fa;
                font-size: 36px;
                text-align: center;
            }
            h2 {
                font-size: 26px;
                text-align: center;
                color: #5006fa;
            }

    </style>
</head>
<body">
    <h1 >Welcome to Prabhdeep Mega Mart</h1>
    <h2 >Product Details</h2>
    <table>
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
        "Home Appliances" => array(array(
            "name" => "Refrigerator",
            "price" => 30000,
            "stock" => 5
        ), array(
            "name" => "Washing Machine",
            "price" => 25000,
            "stock" => 8
        ), array(
            "name" => "Microwave Oven",
            "price" => 10000,
            "stock" => 12
        )),
        "Books" => array(array(
            "name" => "Novel",
            "price" => 200,
            "stock" => 30
        ), array(
            "name" => "Science Fiction",
            "price" => 300,
            "stock" => 20
        ), array(
            "name" => "Biography",
            "price" => 250,
            "stock" => 15
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