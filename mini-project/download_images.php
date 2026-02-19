<?php
// Script to download images locally
// It extracts IDs from the $unsplash calls or just uses the data if we include the file.

// We need to define $unsplash first so when we include the file it doesn't crash or we capture the IDs.
// Actually, products-data.php defines $unsplash.
// Let's just include it and use the URLs it generates to download.

require_once 'products-data.php';

$saveDir = __DIR__ . '/images/';
if (!is_dir($saveDir)) {
    mkdir($saveDir);
}

// Function to extract Unsplash ID from URL
function getUnsplashId($url) {
    // URL format: https://images.unsplash.com/photo-ID?w=...
    if (preg_match('/photo-([A-Za-z0-9_-]+)/', $url, $matches)) {
        return $matches[1];
    }
    return md5($url); // Fallback
}

foreach ($malls as $categoryName => $catData) {
    echo "Processing category: $categoryName\n";
    foreach ($catData['products'] as $product) {
        $url = $product['image'];
        $name = $product['item'];
        $id = getUnsplashId($url);
        $filename = $id . ".jpg";
        $filepath = $saveDir . $filename;

        echo "Downloading $name ($id)...\n";
        
        // Check if already exists
        if (file_exists($filepath) && filesize($filepath) > 0) {
            echo "  - Already exists, skipping.\n";
            continue;
        }

        $content = @file_get_contents($url);
        if ($content) {
            file_put_contents($filepath, $content);
            echo "  - Saved to images/$filename\n";
        } else {
            echo "  - FAILED to download.\n";
        }
    }
}

echo "Done.\n";
?>
