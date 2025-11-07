<?php require_once '../header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Catalog</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="./css/game.css">
    <script src="./js/game.js" defer></script>

</head>
<body class="theme-dark">

    <div class="page-container">

        <section class="hero-section">
            
            <div class="hero-slider-container">
                
                <div class="hero-slide active">
                    <img src="./photos/GOW.jpg" alt="God of War Ragnarok" class="hero-image">
                    <div class="hero-content">
                        <h1 class="hero-title">God of War</h1>
                        <button class="hero-button button-glow-purple">
                            Learn More
                        </button>
                    </div>
                </div>

                <div class="hero-slide">
                    <img src="./photos/READ DEAD.jfif" alt="Red Dead Redemption 2" class="hero-image">
                    <div class="hero-content">
                        <h1 class="hero-title">Red Dead Redemption 2</h1>
                        <button class="hero-button button-glow-purple">
                            Learn More
                        </button>
                    </div>
                </div>
                
                <div class="hero-slide">
                    <img src="./photos/ELDEN RING.jfif" alt="Elden Ring" class="hero-image">
                    <div class="hero-content">
                        <h1 class="hero-title">Elden Ring</h1>
                        <button class="hero-button button-glow-purple">
                            Learn More
                        </button>
                    </div>
                </div>

                <div class="hero-slide">
                    <img src="./photos/Assassin’s Creed.jfif" alt="Assassin's Creed" class="hero-image">
                    <div class="hero-content">
                        <h1 class="hero-title">Assassin's Creed</h1>
                        <button class="hero-button button-glow-purple">
                            Learn More
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="hero-thumbnails">
                <button class="hero-thumb-button active">God of War</button>
                <button class="hero-thumb-button">Red Dead 2</button>
                <button class="hero-thumb-button">Elden Ring</button>
                <button class="hero-thumb-button">Assassin's Creed</button>
            </div>
            </section>

        <section class="games-section">
            <h2 class="section-title">Our Games</h2>
            
            <div class="slider-wrapper">
                <div id="our-games-slider" class="slider-container">
                    
                    <div class="game-card">
                        <img src="./photos/READ DEAD.jfif" alt="Game 1" class="game-card-image">
                        <div class="game-card-info">
                            <h3 class="game-card-title">Red Dead Redemption 2</h3>
                            <button class="game-card-button">
                                Details
                            </button>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="./photos/ELDEN RING.jfif" alt="Game 2" class="game-card-image">
                        <div class="game-card-info">
                            <h3 class="game-card-title">Elden Ring</h3>
                            <button class="game-card-button">
                                Details
                            </button>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="./photos/witcher 3.jfif" alt="Game 3" class="game-card-image">
                        <div class="game-card-info">
                            <h3 class="game-card-title">The Witcher 3</h3>
                            <button class="game-card-button">
                                Details
                            </button>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="./photos/Assassin’s Creed.jfif" alt="Game 4" class="game-card-image">
                        <div class="game-card-info">
                            <h3 class="game-card-title">Assassin's Creed</h3>
                            <button class="game-card-button">
                                Details
                            </button>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="./photos/Dark Souls III .jfif" alt="Game 5" class="game-card-image">
                        <div class="game-card-info">
                            <h3 class="game-card-title">Dark Souls III</h3>
                            <button class="game-card-button">
                                Details
                            </button>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="./photos/RE VILLAGE.jfif" alt="Game 6" class="game-card-image">
                        <div class="game-card-info">
                            <h3 class="game-card-title">Resident Evil Village</h3>
                            <button class="game-card-button">
                                Details
                            </button>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="https://placehold.co/300x450/2a2a2a/f0f0f0?text=Game+Title+7" alt="Game 7" class="game-card-image">
                        <div class="game-card-info">
                            <h3 class="game-card-title">Cyberpunk 2077</h3>
                            <button class="game-card-button">
                                Details
                            </button>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="https://placehold.co/300x450/2a2a2a/f0f0f0?text=Game+Title+8" alt="Game 8" class="game-card-image">
                        <div class="game-card-info">
                            <h3 class="game-card-title">Starfield</h3>
                            <button class="game-card-button">
                                Details
                            </button>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="https://placehold.co/300x450/2a2a2a/f0f0f0?text=Game+Title+9" alt="Game 9" class="game-card-image">
                        <div class="game-card-info">
                            <h3 class="game-card-title">Zelda: TotK</h3>
                            <button class="game-card-button">
                                Details
                            </button>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="https://placehold.co/300x450/2a2a2a/f0f0f0?text=Game+Title+10" alt="Game 10" class="game-card-image">
                        <div class="game-card-info">
                            <h3 class="game-card-title">Hogwarts Legacy</h3>
                            <button class="game-card-button">
                                Details
                            </button>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="https://placehold.co/300x450/2a2a2a/f0f0f0?text=Game+Title+11" alt="Game 11" class="game-card-image">
                        <div class="game-card-info">
                            <h3 class="game-card-title">Diablo IV</h3>
                            <button class="game-card-button">
                                Details
                            </button>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="https://placehold.co/300x450/2a2a2a/f0f0f0?text=Game+Title+12" alt="Game 12" class="game-card-image">
                        <div class="game-card-info">
                            <h3 class="game-card-title">Baldur's Gate 3</h3>
                            <button class="game-card-button">
                                Details
                            </button>
                        </div>
                    </div>

                </div>

                <button id="slider-prev" class="slider-control-prev">
                    <svg xmlns="http://www.w3.org/2000/svg" class="slider-control-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button id="slider-next" class="slider-control-next">
                    <svg xmlns="http://www.w3.org/2000/svg" class="slider-control-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

            </div>
        </section>
        
        <footer class="page-footer">
            <p class="footer-copyright">&copy; 2025 GameStore. All rights reserved.</p>
            <div class="footer-links">
                <a href="#" class="footer-link">Twitter</a>
                <a href="#" class="footer-link">Instagram</a>
                <a href="#" classs="footer-link">Twitch</a>
                <a href="#" class="footer-link">YouTube</a>
            </div>
        </footer>

    </div>
</body>
</html>