<?php
/**
 * Shared product catalog for Prabhdeep Mega Mart
 * Used by Assignment.php (Home) and Assignment2.php (Order Processing)
 */

// Helper to get local image path
$local = function ($name, $ext = 'svg') {
    $filename = preg_replace('/[^a-z0-9]+/', '-', strtolower($name)) . '.' . $ext;
    return "images/" . $filename;
};

$malls = [
    'Groceries' => [
        'name' => 'Groceries',
        'products' => [
            ['item' => 'Rice',             'price' => '₹200',  'image' => 'images/rice.png'],
            ['item' => 'Soft Drinks',      'price' => '₹150',  'image' => $local('Soft Drinks', 'png')],
            ['item' => 'Milk',             'price' => '₹70',   'image' => 'images/milk-product.webp'],
            ['item' => 'Eggs (Dozen)',     'price' => '₹250',  'image' => 'images/eggs-product.jpg'],
            ['item' => 'Flour (1 kg)',     'price' => '₹90',   'image' => 'images/Flour-product.webp'],
            ['item' => 'Sugar (1 kg)',     'price' => '₹80',   'image' => 'images/suger-product.webp'],
            ['item' => 'Tata Salt (1 kg)', 'price' => '₹25',   'image' => 'images/tata salt-product.webp'],
            ['item' => 'Amul Butter (500g)','price' => '₹275',  'image' => 'images/amul butter-product.webp'],
        ]
    ],
    'Tech Products' => [
        'name' => 'Tech Products',
        'products' => [
            ['item' => 'MacBook Pro M4',      'price' => '₹1,69,900', 'image' => 'images/M4-MacBook-Pro-product.jpg'],
            ['item' => 'Apple Watch Ultra',   'price' => '₹89,900',   'image' => 'images/apple watch ultra-product.webp'],
            ['item' => 'Samsung Galaxy S25',  'price' => '₹1,29,999', 'image' => 'images/samsung-galaxy-s25-product.webp'],
            ['item' => 'DJI Air 2S',          'price' => '₹99,900',   'image' => 'images/dji air 2s-product.jpg'],
            ['item' => 'Google Pixel 8',      'price' => '₹79,900',   'image' => 'images/Google-Pixel-8-Pro-product.webp'],
            ['item' => 'Sony WH-1000XM5',     'price' => '₹29,990',   'image' => 'images/Sony WH-1000XM5-product.webp'],
        ]
    ],
    'Clothing' => [
        'name' => 'Clothing',
        'products' => [
            ['item' => 'Levi\'s Jeans',    'price' => '₹3,499',  'image' => 'images/Levi\'s Jeans-product.webp'],
            ['item' => 'H&M Sweatshirt',   'price' => '₹2,499',  'image' => 'images/H&M Sweatshirt-product.webp'],
            ['item' => 'Zara Oversized',   'price' => '₹5,990',  'image' => 'images/Zara Oversized-product.webp'],
            ['item' => 'Nike Air Max',     'price' => '₹12,995', 'image' => 'images/AIR+MAX+DN-product.webp'],

        ]
    ]
];

/**
 * Parse price string (e.g. '₹1,69,900') to integer
 */
function parse_price($priceStr) {
    $n = preg_replace('/[^0-9]/', '', $priceStr);
    return (int) $n;
}
