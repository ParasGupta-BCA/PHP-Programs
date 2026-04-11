<?php
// Script to update specific product images from user URLs using CURL

$images = [
    // Rice Bag JPG (Pexels)
    'rice.jpg' => 'https://images.pexels.com/photos/7420845/pexels-photo-7420845.jpeg?auto=compress&cs=tinysrgb&w=600',
    // Coca Cola Bottle PNG
    'soft-drinks.png' => 'https://pngimg.com/uploads/cocacola/cocacola_PNG19.png'
];

$saveDir = __DIR__ . '/images/';
if (!is_dir($saveDir)) {
    mkdir($saveDir);
}

function downloadFile($url, $path) {
    echo "Attempting download of $url...\n";
    $fp = fopen($path, 'wb');
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'MyProjectBot/1.0 (paras.gupta@example.com) BasedOn/PHP');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FAILONERROR, true); // Report failure on 4xx/5xx
    
    // Add a delay to be polite
    sleep(1);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    fclose($fp);
    
    if ($code == 200) {
        return true;
    }

    echo "CURL Failed (Code: $code, Error: $error). Trying file_get_contents...\n";
    
    // Fallback
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: Mozilla/5.0\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $content = @file_get_contents($url, false, $context);
    
    if ($content !== false) {
        file_put_contents($path, $content);
        return true;
    }
    
    echo "file_get_contents failed.\n";
    return false;
}

foreach ($images as $filename => $url) {
    $filepath = $saveDir . $filename;
    echo "Downloading $filename... ";
    
    if (downloadFile($url, $filepath)) {
        echo "OK\n";
    } else {
        echo "FAILED\n";
    }
}
?>
