<?php
/**
 * Prabhdeep Mega Mart – Home (Blinkit Style)
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prabhdeep Mega Mart - Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-green: #0c831f;
            --brand-green-dark: #096b19;
            --brand-yellow: #fec500;
            --bg-page: #f4f6fb;
            --bg-card: #ffffff;
            --text-main: #1c1c1c;
            --text-muted: #666666;
            --border-light: #e0e0e0;
            --shadow-card: 0 1px 4px rgba(0,0,0,0.04);
            --radius-card: 16px; 
            --radius-btn: 8px;
            --font-main: 'DM Sans', sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font-main);
            background: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
            width: 100%;
        }

        /* Header */
        .page-header {
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 12px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--brand-green);
            text-decoration: none;
            letter-spacing: -0.03em;
        }
        .brand span { color: var(--brand-yellow); }
        .nav-link {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Hero Section */
        .main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }
        .hero-card {
            background: var(--bg-card);
            border-radius: var(--radius-card);
            padding: 48px 32px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            text-align: center;
            max-width: 500px;
            width: 100%;
            border: 1px solid var(--border-light);
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 12px;
            line-height: 1.2;
            color: var(--text-main);
        }
        .hero-title span { color: var(--brand-green); }
        
        .hero-subtitle {
            font-size: 1rem;
            color: var(--text-muted);
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .cta-group {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            border-radius: var(--radius-btn);
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:active { transform: scale(0.98); }

        .btn-primary {
            background: var(--brand-green);
            color: white;
            box-shadow: 0 4px 12px rgba(12, 131, 31, 0.2);
        }
        .btn-primary:hover { background: var(--brand-green-dark); }

        .btn-secondary {
            background: #fff;
            color: var(--text-main);
            border: 1px solid var(--border-light);
        }
        .btn-secondary:hover {
            border-color: var(--brand-green);
            color: var(--brand-green);
            background: #fcfcfc;
        }

        .features {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 32px;
            padding-top: 32px;
            border-top: 1px dashed var(--border-light);
        }
        .feature-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 500;
        }
        .feature-icon {
            width: 40px; height: 40px;
            background: #f0fdf4;
            color: var(--brand-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        footer {
            text-align: center;
            padding: 24px;
            color: var(--text-muted);
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <header class="page-header">
        <div class="container header-content">
            <a href="Assignment.php" class="brand">Prabhdeep <span>Mega Mart</span></a>
            <nav>
                <a href="orders.php" class="nav-link">My Orders</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="hero-card">
            <div style="font-size: 3rem; margin-bottom: 24px;">⚡️</div>
            <h1 class="hero-title">Everything delivered in <span>10 minutes</span></h1>
            <p class="hero-subtitle">
                Groceries, fresh produce, and daily essentials delivered right to your doorstep.
            </p>
            
            <div class="cta-group">
                <a href="Assignment2.php" class="btn btn-primary">Start Shopping</a>
                <a href="orders.php" class="btn btn-secondary">View Past Orders</a>
            </div>

            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">🚀</div>
                    <span>Super Fast</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🛡️</div>
                    <span>Secure</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🥗</div>
                    <span>Fresh</span>
                </div>
            </div>
        </div>
    </main>

    <footer>
        &copy; <?php echo date('Y'); ?> Prabhdeep Mega Mart • Order Processing System
    </footer>
</body>
</html>
