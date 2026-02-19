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
            ['item' => 'Rice',             'price' => '₹200',  'image' => $local('Rice', 'jpg')],
            ['item' => 'Soft Drinks',      'price' => '₹150',  'image' => $local('Soft Drinks', 'png')],
            ['item' => 'Milk',             'price' => '₹70',   'image' => $local('Milk')],
            ['item' => 'Eggs (Dozen)',     'price' => '₹250',  'image' => $local('Eggs (Dozen)')],
            ['item' => 'Flour (1 kg)',     'price' => '₹90',   'image' => $local('Flour (1 kg)')],
            ['item' => 'Sugar (1 kg)',     'price' => '₹80',   'image' => $local('Sugar (1 kg)')],
        ]
    ],
    'Tech Products' => [
        'name' => 'Tech Products',
        'products' => [
            ['item' => 'MacBook Pro M4',      'price' => '₹1,69,900', 'image' => $local('MacBook Pro M4')],
            ['item' => 'Apple Watch Ultra',   'price' => '₹89,900',   'image' => $local('Apple Watch Ultra')],
            ['item' => 'Samsung Galaxy S25',  'price' => '₹1,29,999', 'image' => $local('Samsung Galaxy S25')],
            ['item' => 'DJI Air 2S',          'price' => '₹99,900',   'image' => $local('DJI Air 2S')],
            ['item' => 'Google Pixel 8',      'price' => '₹79,900',   'image' => $local('Google Pixel 8')],
        ]
    ],
    'Clothing' => [
        'name' => 'Clothing',
        'products' => [
            ['item' => 'Levi\'s Jeans',    'price' => '₹3,499',  'image' => $local('Levi\'s Jeans')],
            ['item' => 'H&M Sweatshirt',   'price' => '₹2,499',  'image' => $local('H&M Sweatshirt')],
            ['item' => 'Zara Oversized',   'price' => '₹5,990',  'image' => $local('Zara Oversized')],
            ['item' => 'Nike Air Max',     'price' => '₹12,995', 'image' => $local('Nike Air Max')],
            ['item' => 'Gucci Belt',       'price' => '₹38,000',  'image' => $local('Gucci Belt')],
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
