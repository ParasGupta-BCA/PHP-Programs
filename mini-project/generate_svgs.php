<?php
// Script to generate SVG placeholders with icons locally
require_once 'products-data.php';

$saveDir = __DIR__ . '/images/';
if (!is_dir($saveDir)) {
    mkdir($saveDir);
}

// Brand Colors
$bg = "#ffffff";
$primary = "#0c831f"; // Blinkit Green
$secondary = "#fec500"; // Brand Yellow

function getIconPath($name) {
    // Return SVG path data based on product name keyword
    $n = strtolower($name);
    
    // Groceries
    if (strpos($n, 'rice') !== false || strpos($n, 'flour') !== false || strpos($n, 'sugar') !== false) {
        // Sack/Bag
        return '<path d="M20 30 Q20 10 50 10 Q80 10 80 30 L85 80 Q85 90 50 90 Q15 90 15 80 Z" fill="#e8f5e9" stroke="#0c831f" stroke-width="2"/>
                <path d="M35 40 L65 40" stroke="#0c831f" stroke-width="2"/>';
    }
    if (strpos($n, 'drinks') !== false) {
        // Bottle
        return '<path d="M40 30 L60 30 L60 40 L70 50 L70 90 L30 90 L30 50 L40 40 Z" fill="#e8f5e9" stroke="#0c831f" stroke-width="2"/>
                <rect x="30" y="60" width="40" height="20" fill="#0c831f" opacity="0.2"/>';
    }
    if (strpos($n, 'milk') !== false) {
        // Carton
        return '<path d="M30 40 L70 40 L70 90 L30 90 Z" fill="#e8f5e9" stroke="#0c831f" stroke-width="2"/>
                <path d="M30 40 L50 20 L70 40" fill="#e8f5e9" stroke="#0c831f" stroke-width="2"/>
                <text x="50" y="70" font-size="12" text-anchor="middle" fill="#0c831f">MILK</text>';
    }
    if (strpos($n, 'eggs') !== false) {
        // Eggs
        return '<ellipse cx="35" cy="50" rx="10" ry="14" fill="#fff" stroke="#fec500" stroke-width="2"/>
                <ellipse cx="65" cy="50" rx="10" ry="14" fill="#fff" stroke="#fec500" stroke-width="2"/>
                <ellipse cx="50" cy="65" rx="10" ry="14" fill="#fff" stroke="#fec500" stroke-width="2"/>';
    }

    // Tech
    if (strpos($n, 'macbook') !== false) {
        // Laptop
        return '<rect x="20" y="30" width="60" height="40" rx="2" fill="#e8f5e9" stroke="#0c831f" stroke-width="2"/>
                <path d="M15 75 L85 75 L80 70 L20 70 Z" fill="#0c831f"/>';
    }
    if (strpos($n, 'watch') !== false) {
        // Watch
        return '<rect x="40" y="20" width="20" height="60" rx="4" fill="#0c831f" opacity="0.2"/>
                <circle cx="50" cy="50" r="14" fill="#fff" stroke="#0c831f" stroke-width="2"/>';
    }
    if (strpos($n, 'phone') !== false || strpos($n, 'galaxy') !== false || strpos($n, 'pixel') !== false) {
        // Phone
        return '<rect x="35" y="20" width="30" height="60" rx="3" fill="#fff" stroke="#0c831f" stroke-width="2"/>
                <circle cx="50" cy="75" r="2" fill="#0c831f"/>';
    }
    if (strpos($n, 'dji') !== false) {
        // Drone-ish
        return '<circle cx="50" cy="50" r="10" fill="#0c831f"/>
                <path d="M20 20 L40 40 M80 20 L60 40 M20 80 L40 60 M80 80 L60 60" stroke="#0c831f" stroke-width="2"/>';
    }

    // Clothing
    if (strpos($n, 'jeans') !== false) {
        // Pants
        return '<path d="M30 20 L70 20 L75 80 L50 70 L25 80 Z" fill="#e8f5e9" stroke="#0c831f" stroke-width="2"/>';
    }
    if (strpos($n, 'shirt') !== false || strpos($n, 'oversized') !== false) {
        // Shirt
        return '<path d="M30 20 L70 20 L80 40 L70 40 L70 80 L30 80 L30 40 L20 40 Z" fill="#e8f5e9" stroke="#0c831f" stroke-width="2"/>';
    }
    if (strpos($n, 'shoe') !== false || strpos($n, 'nike') !== false) {
        // Shoe
        return '<path d="M20 50 Q20 70 40 70 L80 70 Q90 70 90 60 L80 40 Q40 40 20 50 Z" fill="#e8f5e9" stroke="#0c831f" stroke-width="2"/>';
    }
    if (strpos($n, 'belt') !== false) {
        // Belt
        return '<rect x="20" y="45" width="60" height="10" fill="none" stroke="#0c831f" stroke-width="2"/>
                <circle cx="30" cy="50" r="4" fill="#fec500"/>';
    }

    // Generic Box
    return '<rect x="25" y="25" width="50" height="50" rx="4" fill="#e8f5e9" stroke="#0c831f" stroke-width="2"/>
            <path d="M25 25 L75 75 M75 25 L25 75" stroke="#0c831f" stroke-width="1" opacity="0.2"/>';
}

$template = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 100 100">
  <rect width="100%" height="100%" fill="#ffffff"/>
  <!-- Icon Group -->
  <g transform="translate(0, -5)">
    {{ICON}}
  </g>
  <!-- Text Label -->
  <text x="50%" y="90" dominant-baseline="middle" text-anchor="middle" 
        font-family="sans-serif" font-size="6" font-weight="bold" fill="#333">
    {{NAME}}
  </text>
</svg>
SVG;

foreach ($malls as $categoryName => $catData) {
    foreach ($catData['products'] as $product) {
        $name = $product['item'];
        $filename = preg_replace('/[^a-z0-9]+/', '-', strtolower($name)) . '.svg';
        $filepath = $saveDir . $filename;
        
        $icon = getIconPath($name);
        $svg = str_replace(['{{ICON}}', '{{NAME}}'], [$icon, htmlspecialchars($name)], $template);
        
        file_put_contents($filepath, $svg);
        echo "Generated images/$filename\n";
    }
}
?>
