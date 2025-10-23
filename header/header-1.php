<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>
    <body>
        <style>
            /* General Theme & Body Styling */
            :root {
                --primary-color: #00bfff; /* Neon blue accent */
                --background-color: #121212; /* Deep black */
                --surface-color: #1e1e1e;   /* Slightly lighter for cards/headers */
                --text-color: #e0e0e0;      /* Light grey for readability */
                --text-secondary-color: #a0a0a0; /* Dimmer text */
            }

            body {
                background-color: var(--background-color);
                color: var(--text-color);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                line-height: 1.6;
                overflow-x: hidden; /* Prevent horizontal scroll from slider adjustments */
            }

            /* Header & Navigation */
            header {
                background-color: var(--surface-color);
                padding: 1rem 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #333;
            }

            .logo {
                font-size: 1.8rem;
                font-weight: bold;
                color: var(--primary-color);
                text-shadow: 0 0 5px var(--primary-color);
            }

            nav ul {
                list-style: none;
                margin: 0;postal-code
                padding: 0;
                display: flex;
                gap: 1.5rem;
            }

            nav a {
                color: var(--text-color);
                text-decoration: none;
                font-weight: 500;
                transition: color 0.3s;
            }

            nav a:hover {
                color: var(--primary-color);
            }

            .header-actions {
                display: flex;
                align-items: center;
                gap: 1.5rem;
            }

            .search-bar {
                background-color: #333;
                border: 1px solid #555;
                color: var(--text-color);
                padding: 0.5rem;
                border-radius: 5px;
            }
        </style>
        <header>
            <div class="logo">GG STORE</div>
            <nav>
                <ul>
                    <li><a href="./index.php">HOME</a></li>
                    <li><a href="./game-page.php">GAMES</a></li>
                    <li><a href="./GADGETS.php">GADGETS</a></li>
                    <li><a href="./review.php">REVIEWS</a></li>
                    <li><a href="./contact.php">CONTACT</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <input type="search" placeholder="Search..." class="search-bar">
                <span>🛒</span> <span><a href="./login.php">👤</a></span> </div>
        </header>
    </body>

