<?php
/**
 * Prabhdeep Mega Mart – Home (Blinkit Style)
 */
require_once __DIR__ . '/MP01-products-data.php';
require_once __DIR__ . '/MP01-orders-helper.php';

// Get latest order for default delivery address
$allOrders = load_orders();
$latestOrder = !empty($allOrders) ? $allOrders[0] : null;
$defaultAddr = "Jalandhar, PB";
$defaultLabel = "Home";

if ($latestOrder) {
    if (!empty($latestOrder['address'])) {
        $defaultAddr = $latestOrder['address'];
    }
    // Get full customer name and a label
    $fullName = !empty($latestOrder['customer_name']) ? $latestOrder['customer_name'] : "User";
    $defaultLabel = "Home"; // Or "Work" if we had that data, for now Home is the Blinkit default
    $displayLabel = "Delivering to " . $defaultLabel;
    $displaySub = $fullName . " — " . $defaultAddr;
} else {
    $displayLabel = "Delivery in 10 min";
    $displaySub = "Home — Jalandhar, PB";
}

// Get category data for category pills
$categories = [];
foreach ($malls as $catKey => $catData) {
    $categories[] = [
        'name'  => $catData['name'],
        'image' => $catData['products'][0]['image'] ?? '',
        'count' => count($catData['products']),
    ];
}

// Get 6 trending products
$trending = [];
foreach ($malls as $catData) {
    foreach (array_slice($catData['products'], 0, 2) as $p) {
        $trending[] = $p;
    }
}

// Flatten all products for search
$allProducts = [];
foreach ($malls as $catData) {
    foreach ($catData['products'] as $p) {
        $allProducts[] = [
            'item'  => $p['item'],
            'price' => $p['price'],
            'image' => $p['image'],
            'category' => $catData['name']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prabhdeep Mega Mart — Groceries & More in 10 Minutes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #0c831f;
            --green-dark: #066b16;
            --green-light: #e8f5e9;
            --yellow: #fec500;
            --bg: #f0f1f5;
            --white: #ffffff;
            --dark: #1a1a2e;
            --text: #1c1c1c;
            --muted: #73767a;
            --border: #e8e8e8;
            --font: 'DM Sans', system-ui, sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }
        a { text-decoration: none; color: inherit; }
        img { display: block; max-width: 100%; }

        .wrap { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        /* ─── TOP BAR ─── */
        .topbar {
            background: var(--green);
            color: #fff;
            text-align: center;
            padding: 8px 16px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .topbar strong { color: var(--yellow); }

        /* ─── HEADER ─── */
        .header {
            background: var(--white);
            position: sticky; top: 0; z-index: 50;
            box-shadow: 0 1px 6px rgba(0,0,0,0.06);
        }
        .header-inner {
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            gap: 16px;
            flex-wrap: nowrap;
        }
        .logo {
            font-size: 1.4rem; font-weight: 800;
            color: var(--green);
            letter-spacing: -0.04em;
            white-space: nowrap;
        }
        .logo b { color: var(--yellow); font-weight: 800; }

        /* Delivery pill */
        .deliver-pill {
            display: flex; align-items: center; gap: 8px;
            background: var(--green-light); border-radius: 30px;
            padding: 6px 14px 6px 10px;
            font-size: 0.78rem; line-height: 1.25;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }
        .deliver-pill:hover { background: #d0efd5; transform: translateY(-1px); }
        .deliver-pill svg { flex-shrink: 0; color: var(--green); }
        .deliver-pill .dp-time { font-weight: 700; color: var(--green); }
        .deliver-pill .dp-addr { color: var(--muted); font-weight: 500; }

        /* Search */
        .search {
            flex: 1; max-width: 420px; position: relative;
        }
        .search input {
            width: 100%; padding: 10px 14px 10px 40px;
            border: 1.5px solid var(--border); border-radius: 10px;
            font-family: var(--font); font-size: 0.85rem;
            background: #fafafa; color: var(--text);
            transition: border 0.2s, box-shadow 0.2s;
        }
        .search input::placeholder { color: #b0b3b8; }
        .search input:focus {
            outline: none; border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(12,131,31,0.08);
        }
        .search svg {
            position: absolute; top: 50%; left: 12px;
            transform: translateY(-50%); color: #b0b3b8;
        }

        /* Location Modal */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .modal-overlay.active { display: flex; opacity: 1; }
        
        .modal-content {
            background: #fff; border-radius: 20px;
            width: 100%; max-width: 440px;
            padding: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.2);
            transform: scale(0.9); transition: transform 0.3s ease;
        }
        .modal-overlay.active .modal-content { transform: scale(1); }
        
        .modal-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px;
        }
        .modal-header h3 { font-size: 1.2rem; font-weight: 800; }
        .modal-close { 
            cursor: pointer; border: none; background: #f5f5f5; width: 32px; height: 32px;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: var(--muted); transition: background 0.2s;
        }
        .modal-close:hover { background: #eee; color: var(--text); }
        
        .loc-search { position: relative; margin-bottom: 20px; }
        .loc-search input {
            width: 100%; padding: 12px 14px 12px 40px;
            border: 1.5px solid var(--border); border-radius: 12px;
            font-family: var(--font); font-size: 0.9rem;
        }
        .loc-search svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); }
        
        .popular-loc { margin-bottom: 10px; font-size: 0.75rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .loc-list { list-style: none; }
        .loc-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px; border-radius: 10px; cursor: pointer;
            transition: background 0.2s;
        }
        .loc-item:hover { background: var(--green-light); }
        .loc-item svg { color: var(--green); }
        .loc-item-text b { display: block; font-size: 0.9rem; margin-bottom: 2px; }
        .loc-item-text span { font-size: 0.75rem; color: var(--muted); }

        /* Search Results Overlay */
        .search-results {
            position: absolute;
            top: 100%; left: 0; right: 0;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            margin-top: 8px;
            max-height: 480px;
            overflow-y: auto;
            z-index: 100;
            display: none;
            padding: 8px;
            border: 1px solid var(--border);
            /* Hide scrollbar but keep functionality */
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .search-results::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }
        .search-results.active { display: block; }
        
        .result-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px; border-radius: 8px;
            transition: background 0.2s;
            cursor: pointer;
            border-bottom: 1px solid #f5f5f5;
        }
        .result-item:last-child { border-bottom: none; }
        .result-item:hover { background: #f9f9f9; }
        
        .result-img {
            width: 48px; height: 48px; object-fit: contain;
            background: #f8f8f8; border-radius: 6px; padding: 4px;
        }
        .result-info { flex: 1; }
        .result-name { font-size: 0.9rem; font-weight: 700; margin-bottom: 2px; }
        .result-cat { font-size: 0.72rem; color: var(--muted); }
        .result-price { font-size: 0.88rem; font-weight: 800; color: var(--green); margin-top: 2px; }
        
        .result-add {
            padding: 6px 12px; border: 1.5px solid var(--green);
            border-radius: 6px; background: #fff; color: var(--green);
            font-size: 0.75rem; font-weight: 700; cursor: pointer;
            transition: all 0.2s;
        }
        .result-add:hover { background: var(--green); color: #fff; }

        .search-no-results {
            padding: 32px 16px; text-align: center; color: var(--muted);
        }
        .search-no-results b { color: var(--text); }

        /* Nav */
        .nav { display: flex; align-items: center; gap: 4px; }
        .nav-item {
            display: flex; align-items: center; gap: 5px;
            padding: 8px 14px; border-radius: 8px;
            font-size: 0.82rem; font-weight: 600;
            color: var(--text); background: none;
            border: none; cursor: pointer;
            font-family: var(--font);
            transition: background 0.2s;
        }
        .nav-item:hover { background: #f5f5f5; }
        .nav-item svg { color: var(--muted); }
        .nav-cart {
            background: var(--green); color: #fff;
            border-radius: 10px; padding: 9px 16px;
        }
        .nav-cart:hover { background: var(--green-dark); }
        .nav-cart svg { color: #fff; }

        /* ─── HERO ─── */
        .hero {
            background: linear-gradient(135deg, #0a6e1a, #0c831f 40%, #18a832 80%, #28c744);
            padding: 56px 0 64px;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 600px 400px at 80% 20%, rgba(255,203,5,0.12), transparent),
                radial-gradient(ellipse 400px 400px at 10% 80%, rgba(255,255,255,0.05), transparent);
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px; align-items: center;
            position: relative; z-index: 2;
        }
        .hero-left { color: #fff; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 14px; margin-bottom: 18px;
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 30px;
            font-size: 0.72rem; font-weight: 600;
            color: var(--yellow);
            backdrop-filter: blur(6px);
        }
        .hero-title {
            font-size: 2.8rem; font-weight: 800;
            line-height: 1.12; letter-spacing: -0.03em;
            margin-bottom: 14px;
        }
        .hero-title em {
            font-style: normal;
            color: var(--yellow);
            position: relative;
        }
        .hero-sub {
            font-size: 1rem; color: rgba(255,255,255,0.82);
            line-height: 1.55; margin-bottom: 28px;
            max-width: 400px;
        }
        .hero-btns { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn-shop {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 26px; border-radius: 10px;
            font-family: var(--font); font-size: 0.95rem;
            font-weight: 700; cursor: pointer; border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-shop-primary {
            background: #fff; color: var(--green);
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }
        .btn-shop-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,0.16); }
        .btn-shop-ghost {
            background: rgba(255,255,255,0.12); color: #fff;
            border: 1.5px solid rgba(255,255,255,0.25);
            backdrop-filter: blur(4px);
        }
        .btn-shop-ghost:hover { background: rgba(255,255,255,0.22); }

        /* Hero stats */
        .hero-stats {
            display: flex; gap: 28px; margin-top: 36px;
        }
        .hs-num { font-size: 1.5rem; font-weight: 800; color: var(--yellow); line-height: 1; }
        .hs-label { font-size: 0.7rem; color: rgba(255,255,255,0.6); margin-top: 3px; font-weight: 500; }

        /* Hero product cards */
        .hero-right { display: flex; justify-content: center; }
        .hero-products {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 12px; max-width: 340px;
        }
        .hp-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 14px; padding: 14px;
            text-align: center;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            min-height: 150px;
            transition: transform 0.3s, background 0.3s;
            animation: hpFloat 0.5s ease both;
        }
        .hp-card:nth-child(1) { animation-delay: .05s; }
        .hp-card:nth-child(2) { animation-delay: .12s; }
        .hp-card:nth-child(3) { animation-delay: .2s; }
        .hp-card:nth-child(4) { animation-delay: .28s; }
        .hp-card:hover { transform: translateY(-6px); background: rgba(255,255,255,0.17); }
        @keyframes hpFloat {
            from { opacity: 0; transform: translateY(18px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .hp-card img {
            width: 70px; height: 70px; object-fit: contain;
            margin: 0 auto 8px; border-radius: 8px;
        }
        .hp-card .hp-name {
            font-size: 0.72rem; font-weight: 600; color: #fff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 100%;
        }
        .hp-card .hp-price {
            font-size: 0.78rem; font-weight: 800; color: var(--yellow); margin-top: 2px;
        }

        /* ─── CATEGORIES ─── */
        .section { padding: 40px 0; }
        .sec-head {
            display: flex; align-items: center;
            justify-content: space-between; margin-bottom: 20px;
        }
        .sec-title { font-size: 1.3rem; font-weight: 800; letter-spacing: -0.02em; }
        .sec-link {
            font-size: 0.8rem; font-weight: 600; color: var(--green);
            display: flex; align-items: center; gap: 3px;
            transition: gap 0.2s;
        }
        .sec-link:hover { gap: 6px; }

        .cat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 16px;
        }
        .cat-card {
            background: var(--white); border-radius: 14px;
            padding: 20px; display: flex; align-items: center; gap: 16px;
            border: 1px solid var(--border);
            transition: transform 0.25s, box-shadow 0.25s;
            cursor: pointer;
        }
        .cat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.07);
        }
        .cat-card:nth-child(1) { border-left: 3px solid #22c55e; }
        .cat-card:nth-child(2) { border-left: 3px solid #3b82f6; }
        .cat-card:nth-child(3) { border-left: 3px solid #a855f7; }
        .cat-img {
            width: 60px; height: 60px; border-radius: 10px;
            object-fit: contain; background: #f9fafb; padding: 6px;
        }
        .cat-name { font-size: 1rem; font-weight: 700; }
        .cat-sub { font-size: 0.75rem; color: var(--muted); margin-top: 2px; }
        .cat-arrow {
            margin-left: auto;
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--green-light); color: var(--green);
            display: flex; align-items: center; justify-content: center;
            transition: background 0.2s, color 0.2s;
            flex-shrink: 0;
        }
        .cat-card:hover .cat-arrow { background: var(--green); color: #fff; }

        /* ─── TRENDING ─── */
        .trend-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 14px;
        }
        .t-card {
            background: var(--white); border: 1px solid var(--border);
            border-radius: 12px; overflow: hidden;
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .t-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.07);
        }
        .t-img {
            width: 100%; aspect-ratio: 1; background: #f8f8f8;
            display: flex; align-items: center; justify-content: center;
            padding: 10px;
        }
        .t-img img {
            max-width: 100%; max-height: 100%; object-fit: contain;
            transition: transform 0.3s;
        }
        .t-card:hover .t-img img { transform: scale(1.06); }
        .t-body { padding: 12px; }
        .t-name {
            font-size: 0.82rem; font-weight: 600;
            display: -webkit-box; -webkit-line-clamp: 2;
            -webkit-box-orient: vertical; overflow: hidden;
            line-height: 1.3; margin-bottom: 8px;
        }
        .t-foot {
            display: flex; align-items: center; justify-content: space-between;
        }
        .t-price { font-size: 0.92rem; font-weight: 800; }
        .t-add {
            padding: 5px 14px; border: 1.5px solid var(--green);
            border-radius: 7px; background: #fff;
            color: var(--green); font-size: 0.76rem; font-weight: 700;
            font-family: var(--font); cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        .t-add:hover { background: var(--green); color: #fff; }

        /* ─── PROMO STRIP ─── */
        .promo-row {
            display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
        }
        .promo {
            border-radius: 16px; padding: 32px 28px;
            color: #fff; position: relative; overflow: hidden;
        }
        .promo::after {
            content: ''; position: absolute;
            top: -50%; right: -25%;
            width: 180px; height: 180px;
            background: rgba(255,255,255,0.07); border-radius: 50%;
        }
        .promo-a { background: linear-gradient(135deg, #0c831f, #22c55e); }
        .promo-b { background: linear-gradient(135deg, #1a1a2e, #2d3561); }
        .promo-emoji { font-size: 2rem; margin-bottom: 12px; }
        .promo h3 { font-size: 1.2rem; font-weight: 800; margin-bottom: 6px; position: relative; z-index: 2; }
        .promo p { font-size: 0.82rem; opacity: 0.85; line-height: 1.45; position: relative; z-index: 2; }

        /* ─── TRUST ─── */
        .trust-row {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px;
        }
        .trust-card {
            background: var(--white); border: 1px solid var(--border);
            border-radius: 12px; padding: 22px 16px;
            text-align: center;
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .trust-card:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(0,0,0,0.06); }
        .trust-emoji {
            width: 46px; height: 46px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px; font-size: 1.3rem;
        }
        .trust-card:nth-child(1) .trust-emoji { background: #fef3c7; }
        .trust-card:nth-child(2) .trust-emoji { background: #dbeafe; }
        .trust-card:nth-child(3) .trust-emoji { background: #dcfce7; }
        .trust-card:nth-child(4) .trust-emoji { background: #fce7f3; }
        .trust-card h4 { font-size: 0.88rem; font-weight: 700; margin-bottom: 4px; }
        .trust-card p { font-size: 0.72rem; color: var(--muted); line-height: 1.35; }

        /* ─── APP BANNER ─── */
        .app-banner {
            background: #fdfdfd; padding: 60px 0;
            overflow: hidden;
        }
        .app-flex {
            display: flex; align-items: center; gap: 60px;
            background: #f3f4f6; border-radius: 24px;
            padding: 40px 60px; border: 1px solid #e5e7eb;
        }
        .app-left { flex: 1; }
        .app-left h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: 12px; }
        .app-left p { font-size: 1rem; color: var(--muted); margin-bottom: 24px; }
        .app-stores { display: flex; gap: 12px; }
        .store-btn {
            background: #000; color: #fff; padding: 10px 20px;
            border-radius: 8px; display: flex; align-items: center; gap: 10px;
            font-size: 0.8rem; cursor: pointer;
        }
        .store-btn b { display: block; font-size: 1rem; }
        .app-right { position: relative; width: 300px; height: 300px; }
        .app-mock {
            width: 100%; height: 100%; object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.1));
            transform: rotate(-10deg);
        }

        /* ─── FOOTER ─── */
        .footer {
            background: #14532d; color: rgba(255,255,255,0.75);
            padding: 40px 0 20px; margin-top: 20px;
        }
        .footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr;
            gap: 32px; padding-bottom: 24px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .footer-brand { font-size: 1.3rem; font-weight: 800; color: #fff; margin-bottom: 10px; }
        .footer-brand b { color: var(--yellow); font-weight: 800; }
        .footer p { font-size: 0.82rem; line-height: 1.55; }
        .footer h5 {
            font-size: 0.75rem; font-weight: 700; color: #fff;
            text-transform: uppercase; letter-spacing: 0.06em;
            margin-bottom: 12px;
        }
        .footer ul { list-style: none; }
        .footer ul li { margin-bottom: 8px; }
        .footer ul a { font-size: 0.82rem; color: rgba(255,255,255,0.55); transition: color 0.2s; }
        .footer ul a:hover { color: var(--yellow); }
        .footer-copy {
            padding-top: 20px; text-align: center;
            font-size: 0.75rem;
        }

        /* ─── SCROLL ANIMATIONS ─── */
        .anim {
            opacity: 0; transform: translateY(16px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .anim.show { opacity: 1; transform: translateY(0); }

        /* ══════ RESPONSIVE — Large Tablet ≤ 1024px ══════ */
        @media (max-width: 1024px) {
            .hero-title { font-size: 2.2rem; }
            .hero-grid { gap: 30px; }
            .hero-products { max-width: 300px; }
        }

        /* ══════ RESPONSIVE — Tablet ≤ 768px ══════ */
        @media (max-width: 768px) {
            .wrap { padding: 0 20px; }
            .deliver-pill { display: none; }
            .header-inner { gap: 12px; }
            .search { max-width: 300px; }
            
            .hero { padding: 48px 0 56px; }
            .hero-grid { grid-template-columns: 1.2fr 1fr; gap: 24px; text-align: left; }
            .hero-title { font-size: 1.8rem; }
            .hero-sub { font-size: 0.9rem; margin-bottom: 24px; }
            .hero-btns { justify-content: flex-start; }
            .hero-stats { justify-content: flex-start; gap: 20px; }
            .hs-num { font-size: 1.3rem; }
            
            .hero-right { margin-top: 0; }
            .hero-products { max-width: 280px; gap: 10px; }
            .hp-card { min-height: 130px; paddding: 10px; }
            .hp-card img { width: 50px; height: 50px; }
            
            .cat-grid { grid-template-columns: repeat(2, 1fr); }
            .promo-row { grid-template-columns: 1fr; }
            .trust-row { grid-template-columns: repeat(2, 1fr); }
            .trend-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
            .footer-grid { grid-template-columns: 1fr; gap: 20px; }
            .app-flex { flex-direction: column; padding: 40px 20px; text-align: center; gap: 30px; }
            .app-right { width: 220px; height: 220px; }
        }

        /* ══════ RESPONSIVE — Mobile ≤ 480px ══════ */
        @media (max-width: 480px) {
            .topbar { font-size: 0.7rem; padding: 6px 20px; }
            .wrap { padding: 0 20px; } /* Increased side margins for modern feel */
            
            .header-inner { 
                flex-wrap: wrap; 
                padding: 12px 0; 
                gap: 12px; 
                align-items: center; /* Ensure vertical center alignment */
            }
            .logo { font-size: 1.15rem; flex: 1; }
            .nav { gap: 6px; display: flex; align-items: center; }
            .nav-item { padding: 4px; display: flex; align-items: center; }
            .nav-item span { display: none; }
            .nav-cart { 
                background: var(--green); 
                color: #fff; 
                padding: 8px 14px; 
                border-radius: 10px;
                gap: 6px;
            }
            .nav-cart span { display: inline; font-size: 0.85rem; }
            
            /* Mobile Search Row */
            .search { 
                display: block; 
                order: 3; 
                flex-basis: 100%; 
                max-width: 100%; 
                margin-top: 2px;
            }
            .search input { 
                padding: 10px 14px 10px 40px; 
                font-size: 0.85rem; 
                border-radius: 10px; 
                background: #f5f5f5;
                border-color: #eee;
            }
            .search svg { left: 14px; }
            
            .hero { padding: 24px 0 40px; }
            .hero-grid { grid-template-columns: 1fr; text-align: center; gap: 28px; }
            .hero-badge { margin-bottom: 12px; padding: 4px 12px; font-size: 0.68rem; }
            .hero-title { font-size: 1.55rem; margin-bottom: 10px; line-height: 1.2; }
            .hero-sub { font-size: 0.85rem; line-height: 1.5; margin: 0 auto 24px; padding: 0 10px; }
            .hero-btns { justify-content: center; gap: 8px; transform: none; }
            .btn-shop { padding: 11px 20px; font-size: 0.88rem; }
            
            .hero-stats { justify-content: center; gap: 20px; margin-top: 24px; padding: 0 10px; }
            .hs-num { font-size: 1.25rem; }
            .hs-label { font-size: 0.65rem; }
            
            .hero-right { margin-top: 10px; }
            .hero-products { max-width: 280px; margin: 0 auto; gap: 10px; }
            .hp-card { min-height: 135px; padding: 12px; border-radius: 12px; }
            .hp-card img { width: 52px; height: 52px; margin-bottom: 6px; }
            .hp-card .hp-name { font-size: 0.68rem; white-space: normal; line-height: 1.2; height: 1.5rem; display: flex; align-items: center; justify-content: center; }
            .hp-card .hp-price { font-size: 0.8rem; margin-top: 4px; }
            
            .sec-title { font-size: 1.15rem; }
            .cat-grid { grid-template-columns: 1fr; gap: 12px; }
            .cat-card { padding: 16px; gap: 12px; }
            .cat-img { width: 50px; height: 50px; }
            
            .trend-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .trust-row { grid-template-columns: 1fr 1fr; gap: 12px; }
            .promo { padding: 28px 20px; }
            .promo h3 { font-size: 1.1rem; }
        }
    </style>
</head>
<body>

<!-- Top Bar -->
<div class="topbar">
    ⚡ Free delivery on your first order — use code <strong>FRESH10</strong>
</div>

<!-- Header -->
<header class="header">
  <div class="wrap header-inner">
    <a href="MP01-home.php" class="logo">Prabhdeep <b>Mega Mart</b></a>

    <div class="deliver-pill" id="locationBtn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/></svg>
        <div>
            <div class="dp-time" id="dpLabel"><?= htmlspecialchars($displayLabel) ?></div>
            <div class="dp-addr" id="currentAddr"><?= htmlspecialchars($displaySub) ?></div>
        </div>
    </div>

    <div class="search">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="searchInput" placeholder="Search for groceries, electronics, fashion..." autocomplete="off">
        <div id="searchResults" class="search-results"></div>
    </div>

    <nav class="nav">
        <a href="MP01-orders-view.php" class="nav-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <span>Orders</span>
        </a>
        <a href="MP01-shop-checkout.php" class="nav-item nav-cart">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
            <span>Cart</span>
        </a>
    </nav>
  </div>
</header>

<!-- Hero -->
<section class="hero">
  <div class="wrap hero-grid">
    <div class="hero-left">
        <div class="hero-badge">⚡ India's Fastest Delivery</div>
        <h1 class="hero-title">Everything delivered<br>in <em>10 minutes</em></h1>
        <p class="hero-sub">Groceries, fresh produce, tech gadgets & daily essentials — right to your doorstep.</p>
        <div class="hero-btns">
            <a href="MP01-shop-checkout.php" class="btn-shop btn-shop-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                Start Shopping
            </a>
            <a href="MP01-orders-view.php" class="btn-shop btn-shop-ghost">View Past Orders</a>
        </div>
        <div class="hero-stats">
            <div><div class="hs-num">18+</div><div class="hs-label">Products</div></div>
            <div><div class="hs-num">10 min</div><div class="hs-label">Delivery</div></div>
            <div><div class="hs-num">4.8★</div><div class="hs-label">Rated</div></div>
        </div>
    </div>
    <div class="hero-right">
        <div class="hero-products">
            <?php foreach (array_slice($trending, 0, 4) as $p): ?>
            <div class="hp-card">
                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['item']) ?>" loading="lazy">
                <div class="hp-name"><?= htmlspecialchars($p['item']) ?></div>
                <div class="hp-price"><?= htmlspecialchars($p['price']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
  </div>
</section>

<!-- Categories -->
<section class="section anim">
  <div class="wrap">
    <div class="sec-head">
        <h2 class="sec-title">Shop by Category</h2>
        <a href="MP01-shop-checkout.php" class="sec-link">See all <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg></a>
    </div>
    <div class="cat-grid">
        <?php foreach ($categories as $cat): ?>
        <a href="MP01-shop-checkout.php" class="cat-card">
            <img class="cat-img" src="<?= htmlspecialchars($cat['image']) ?>" alt="<?= htmlspecialchars($cat['name']) ?>" loading="lazy">
            <div>
                <div class="cat-name"><?= htmlspecialchars($cat['name']) ?></div>
                <div class="cat-sub"><?= $cat['count'] ?> products</div>
            </div>
            <div class="cat-arrow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Trending -->
<section class="section anim" style="padding-top:0">
  <div class="wrap">
    <div class="sec-head">
        <h2 class="sec-title">🔥 Trending Now</h2>
        <a href="MP01-shop-checkout.php" class="sec-link">View all <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg></a>
    </div>
    <div class="trend-grid">
        <?php foreach ($trending as $tp): ?>
        <a href="MP01-shop-checkout.php" class="t-card">
            <div class="t-img">
                <img src="<?= htmlspecialchars($tp['image']) ?>" alt="<?= htmlspecialchars($tp['item']) ?>" loading="lazy">
            </div>
            <div class="t-body">
                <div class="t-name"><?= htmlspecialchars($tp['item']) ?></div>
                <div class="t-foot">
                    <div class="t-price"><?= htmlspecialchars($tp['price']) ?></div>
                    <div class="t-add">ADD</div>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Promo -->
<section class="section anim" style="padding-top:0">
  <div class="wrap">
    <div class="promo-row">
        <div class="promo promo-a">
            <div class="promo-emoji">🚚</div>
            <h3>Free Delivery on First Order</h3>
            <p>No minimum cart value. Sign up and start saving today!</p>
        </div>
        <div class="promo promo-b">
            <div class="promo-emoji">🛡️</div>
            <h3>100% Quality Guaranteed</h3>
            <p>Not satisfied? Get a full refund — no questions asked.</p>
        </div>
    </div>
  </div>
</section>

<!-- Trust -->
<section class="section anim" style="padding-top:0">
  <div class="wrap">
    <div class="trust-row">
        <div class="trust-card">
            <div class="trust-emoji">⚡</div>
            <h4>10-Min Delivery</h4>
            <p>Lightning-fast to your door</p>
        </div>
        <div class="trust-card">
            <div class="trust-emoji">🔒</div>
            <h4>Secure Payments</h4>
            <p>UPI & Cash on Delivery</p>
        </div>
        <div class="trust-card">
            <div class="trust-emoji">🥬</div>
            <h4>Farm Fresh</h4>
            <p>Direct from farms daily</p>
        </div>
        <div class="trust-card">
            <div class="trust-emoji">💰</div>
            <h4>Best Prices</h4>
            <p>Daily deals & offers</p>
        </div>
    </div>
  </div>
</section>

<!-- App Banner -->
<section class="app-banner anim">
  <div class="wrap">
    <div class="app-flex">
      <div class="app-left">
        <h2>Get the Prabhdeep Mart App</h2>
        <p>Experience the fastest delivery in your city. Download the app to track orders, discover exclusive deals, and more.</p>
        <div class="app-stores">
          <div class="store-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 1h-11a3 3 0 00-3 3v16a3 3 0 003 3h11a3 3 0 003-3V4a3 3 0 00-3-3zm1 19a1 1 0 01-1 1h-11a1 1 0 01-1-1v-2h13v2zm0-4h-13V6h13v10zm0-12h-13V4a1 1 0 011-1h11a1 1 0 011 1v2z"/></svg>
            <div>Get it on <b>Google Play</b></div>
          </div>
          <div class="store-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.1 2.48-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .76-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83zM13 3.5c.73-.89 1.22-2.11 1.08-3.33-1.04.04-2.31.7-3.05 1.57-.67.77-1.26 2.03-1.1 3.2 1.16.09 2.34-.55 3.07-1.44z"/></svg>
            <div>Download on <b>App Store</b></div>
          </div>
        </div>
      </div>
      <div class="app-right">
        <img class="app-mock" src="images/M4-MacBook-Pro-product.jpg" alt="App Mockup" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="footer">
  <div class="wrap">
    <div class="footer-grid">
        <div>
            <div class="footer-brand">Prabhdeep <b>Mega Mart</b></div>
            <p>Your one-stop shop for groceries, tech & fashion. Delivering happiness at lightning speed.</p>
        </div>
        <div>
            <h5>Quick Links</h5>
            <ul>
                <li><a href="MP01-home.php">Home</a></li>
                <li><a href="MP01-shop-checkout.php">Shop Now</a></li>
                <li><a href="MP01-orders-view.php">My Orders</a></li>
            </ul>
        </div>
        <div>
            <h5>Categories</h5>
            <ul>
                <?php foreach ($categories as $c): ?>
                <li><a href="MP01-shop-checkout.php"><?= htmlspecialchars($c['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="footer-copy">
        &copy; <?= date('Y') ?> Prabhdeep Mega Mart. All rights reserved.
    </div>
  </div>
</footer>

<!-- Location Modal -->
<div class="modal-overlay" id="locationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Change Location</h3>
            <button class="modal-close" id="closeModal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="loc-search">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search for area, street name...">
        </div>
        <div class="popular-loc">Popular Localities</div>
        <ul class="loc-list">
            <li class="loc-item" data-loc="Jalandhar, PB">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/></svg>
                <div class="loc-item-text"><b>Jalandhar City</b><span>Home — Jalandhar, Punjab</span></div>
            </li>
            <li class="loc-item" data-loc="Ludhiana, PB">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/></svg>
                <div class="loc-item-text"><b>Ludhiana</b><span>Business Center — Ludhiana</span></div>
            </li>
            <li class="loc-item" data-loc="Amritsar, PB">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/></svg>
                <div class="loc-item-text"><b>Amritsar</b><span>Holy City — Amritsar</span></div>
            </li>
        </ul>
    </div>
</div>

<script>
// Location Modal Logic
const locationBtn = document.getElementById('locationBtn');
const locationModal = document.getElementById('locationModal');
const closeModal = document.getElementById('closeModal');
const currentAddr = document.getElementById('currentAddr');

// Sync with latest order from localStorage if available
try {
    const history = JSON.parse(localStorage.getItem('orderHistory') || '[]');
    if (history.length > 0) {
        const last = history[0];
        const fullName = last.customer_name || 'User';
        const addr = last.address || 'Jalandhar, PB';
        document.getElementById('dpLabel').innerText = "Delivering to Home";
        currentAddr.innerText = `${fullName} — ${addr}`;
    }
} catch (e) {
    console.error("Local storage error:", e);
}

locationBtn.addEventListener('click', () => {
    locationModal.classList.add('active');
    document.body.style.overflow = 'hidden';
});

const closeLocationModal = () => {
    locationModal.classList.remove('active');
    document.body.style.overflow = 'auto';
};

closeModal.addEventListener('click', closeLocationModal);
locationModal.addEventListener('click', (e) => {
    if (e.target === locationModal) closeLocationModal();
});

document.querySelectorAll('.loc-item').forEach(item => {
    item.addEventListener('click', () => {
        const loc = item.getAttribute('data-loc');
        currentAddr.innerText = `Home — ${loc}`;
        closeLocationModal();
    });
});

// Search Data
const products = <?= json_encode($allProducts) ?>;

const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');

searchInput.addEventListener('input', (e) => {
    const query = e.target.value.toLowerCase().trim();
    
    if (query.length < 1) {
        searchResults.classList.remove('active');
        return;
    }

    const filtered = products.filter(p => 
        p.item.toLowerCase().includes(query) || 
        p.category.toLowerCase().includes(query)
    );

    renderResults(filtered, query);
    searchResults.classList.add('active');
});

function renderResults(list, query) {
    if (list.length === 0) {
        searchResults.innerHTML = `<div class="search-no-results">No results found for "<b>${query}</b>"</div>`;
        return;
    }

    let html = '';
    list.forEach(p => {
        html += `
            <div class="result-item" onclick="location.href='MP01-shop-checkout.php'">
                <img class="result-img" src="${p.image}" alt="${p.item}">
                <div class="result-info">
                    <div class="result-name">${p.item}</div>
                    <div class="result-cat">${p.category}</div>
                    <div class="result-price">${p.price}</div>
                </div>
                <button class="result-add">ADD</button>
            </div>
        `;
    });
    searchResults.innerHTML = html;
}

// Close search on click outside
document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.remove('active');
    }
});

// Scroll-triggered fade-in
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('show'); obs.unobserve(e.target); } });
}, { threshold: 0.12 });
document.querySelectorAll('.anim').forEach(el => obs.observe(el));
</script>
</body>
</html>
