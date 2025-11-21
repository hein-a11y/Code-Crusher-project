<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELDEN RING - Game Store Page</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles to match the Steam-like theme */
        body {
            font-family: 'Inter', 'Arial', sans-serif;
            background-color: #1b2838; /* Steam dark blue */
            color: #c7d5e0; /* Light text color */
        }
        .main-container {
            background-color: #171a21; /* Slightly darker content background */
            max-width: 940px;
            margin: 30px auto;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        .header-gradient {
            background: linear-gradient(to right, #06b6d4, #3b82f6);
            padding: 2px;
            border-radius: 6px 6px 0 0;
        }
        .header-title {
            background-color: #1a1a1a;
            color: #ffffff;
            padding: 16px 24px;
            font-size: 28px;
            font-weight: 700;
            border-radius: 4px 4px 0 0;
        }
        .content-wrapper {
            display: flex;
            flex-direction: column;
            lg:flex-direction: row;
            padding: 24px;
            gap: 24px;
        }
        .main-column {
            flex: 3;
        }
        .sidebar-column {
            flex: 1;
            min-width: 300px;
        }
        .media-carousel .main-image {
            width: 100%;
            border-radius: 6px;
        }
        .media-carousel .thumbnails {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-top: 10px;
        }
        .media-carousel .thumbnail {
            width: 100%;
            border: 2px solid transparent;
            border-radius: 4px;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .media-carousel .thumbnail:hover,
        .media-carousel .thumbnail.active {
            border-color: #06b6d4; /* Highlight color */
        }
        
        .purchase-box {
            background-color: #2a475e; /* Lighter blue box */
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .purchase-box-deluxe {
            background: linear-gradient(to right, #4a5a6b, #2a3a4b);
        }

        .buy-button {
            background: linear-gradient(to bottom, #79a100, #537a00);
            color: #ffffff;
            padding: 8px 20px;
            border-radius: 4px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .buy-button:hover {
            background: linear-gradient(to bottom, #8bb800, #648f00);
        }
        .section-header {
            color: #ffffff;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
            border-bottom: 1px solid #2a475e;
            padding-bottom: 8px;
        }
        .sidebar-box {
            background-color: #2a475e;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .sidebar-box .sidebar-content {
            padding: 16px;
        }
        .sidebar-box .sidebar-title {
            background-color: #203a4c;
            padding: 8px 16px;
            color: #ffffff;
            font-weight: 500;
        }
        .game-feature {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
            font-size: 14px;
        }
        .game-feature svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        .language-table {
            width: 100%;
            font-size: 13px;
        }
        .language-table th {
            text-align: left;
            color: #8f9ba6;
            font-weight: normal;
            padding: 2px 4px;
        }
        .language-table td {
            text-align: center;
            padding: 2px 4px;
        }
        .checkmark {
            color: #79a100; /* Green check */
            font-weight: bold;
        }
        .sys-req {
            background-color: #20242a;
            padding: 16px;
            border-radius: 6px;
            font-size: 14px;
        }
        .sys-req ul {
            list-style: none;
            padding-left: 0;
        }
        .sys-req li {
            margin-bottom: 8px;
            border-bottom: 1px solid #3a3f4c;
            padding-bottom: 8px;
        }
        .sys-req li strong {
            color: #ffffff;
            display: block;
        }
        .sys-req li span {
            color: #c7d5e0;
        }

    </style>
</head>
<body class="bg-gray-900 text-gray-300">

    <div class="main-container">
        <!-- Header -->
        <div class="header-gradient">
            <div class="header-title">ELDEN RING</div>
        </div>

        <!-- Content -->
        <div class="content-wrapper">

            <!-- Main Column -->
            <div class="main-column">
                
                <!-- Media Carousel -->
                <div class="media-carousel mb-6">
                    <img id="mainImage" src="./photos/Wallpaper de Elder Ring.jfif" alt="Main game media" class="main-image">
                    <div class="thumbnails">
                        <!-- Placeholder images. In a real app, these would be game screenshots -->
                        <img src="https://placehold.co/120x68/1b2838/c7d5e0?text=Gameplay+1" alt="Thumbnail 1" class="thumbnail active" data-src="https://placehold.co/600x338/1b2838/c7d5e0?text=ELDEN+RING+Gameplay+1">
                        <img src="https://placehold.co/120x68/1b2838/c7d5e0?text=Scenery" alt="Thumbnail 2" class="thumbnail" data-src="https://placehold.co/600x338/1b2838/c7d5e0?text=Beautiful+Scenery">
                        <img src="https://placehold.co/120x68/1b2838/c7d5e0?text=Combat" alt="Thumbnail 3" class="thumbnail" data-src="https://placehold.co/600x338/1b2838/c7d5e0?text=Intense+Combat">
                        <img src="https://placehold.co/120x68/1b2838/c7d5e0?text=Boss" alt="Thumbnail 4" class="thumbnail" data-src="https://placehold.co/600x338/1b2838/c7d5e0?text=Epic+Boss+Fight">
                        <img src="https://placehold.co/120x68/1b2838/c7d5e0?text=World" alt="Thumbnail 5" class="thumbnail" data-src="https://placehold.co/600x338/1b2838/c7d5e0?text=Vast+World">
                    </div>
                </div>

                <!-- Purchase Boxes -->
                <div class="purchase-box purchase-box-deluxe mb-4">
                    <h2 class="text-xl text-white font-semibold mb-3">Buy ELDEN RING Shadow of the Erdtree Deluxe Edition</h2>
                    <p class="text-sm mb-4">Includes ELDEN RING, Shadow of the Erdtree expansion, and Digital Artbook & Original Soundtrack.</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl text-white font-bold">¥9,900</span>
                        <button class="buy-button">Add to Cart</button>
                    </div>
                </div>
                
                <div class="purchase-box mb-4">
                    <h2 class="text-xl text-white font-semibold mb-3">Buy ELDEN RING</h2>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl text-white font-bold">¥9,240</span>
                        <button class="buy-button">Add to Cart</button>
                    </div>
                </div>

                <!-- About this game -->
                <div class="about-game mb-6">
                    <h2 class="section-header">About This Game</h2>
                    <p class="text-sm mb-4">
                        THE NEW FANTASY ACTION RPG.
                        <br>
                        Rise, Tarnished, and be guided by grace to brandish the power of the Elden Ring and become an Elden Lord in the Lands Between.
                    </p>
                    <p class="text-sm mb-4">
                        <strong>• A Vast World Full of Excitement</strong><br>
                        A vast world where open fields with a variety of situations and huge dungeons with complex and three-dimensional designs are seamlessly connected. As you explore, the joy of discovering unknown and overwhelming threats awaits you, leading to a high sense of accomplishment.
                    </p>
                    <p class="text-sm mb-4">
                        <strong>• Create your Own Character</strong><br>
                        In addition to customizing the appearance of your character, you can freely combine the weapons, armor, and magic that you equip. You can develop your character according to your play style, such as increasing your muscle strength to become a strong warrior, or mastering magic.
                    </p>
                </div>

                <!-- System Requirements -->
                <div>
                    <h2 class="section-header">System Requirements</h2>
                    <div class="sys-req grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Minimum -->
                        <div>
                            <h3 class="text-gray-400 mb-3 font-medium">MINIMUM:</h3>
                            <ul>
                                <li><strong>OS:</strong> <span>Windows 10</span></li>
                                <li><strong>Processor:</strong> <span>INTEL CORE I5-8400 or AMD RYZEN 3 3300X</span></li>
                                <li><strong>Memory:</strong> <span>12 GB RAM</span></li>
                                <li><strong>Graphics:</strong> <span>NVIDIA GEFORCE GTX 1060 3GB or AMD RADEON RX 580 4GB</span></li>
                                <li><strong>DirectX:</strong> <span>Version 12</span></li>
                                <li><strong>Storage:</strong> <span>60 GB available space</span></li>
                            </ul>
                        </div>
                        <!-- Recommended -->
                        <div>
                            <h3 class="text-gray-400 mb-3 font-medium">RECOMMENDED:</h3>
                            <ul>
                                <li><strong>OS:</strong> <span>Windows 10/11</span></li>
                                <li><strong>Processor:</strong> <span>INTEL CORE I7-8700K or AMD RYZEN 5 3600X</span></li>
                                <li><strong>Memory:</strong> <span>16 GB RAM</span></li>
                                <li><strong>Graphics:</strong> <span>NVIDIA GEFORCE GTX 1070 8GB or AMD RADEON RX VEGA 56 8GB</span></li>
                                <li><strong>DirectX:</strong> <span>Version 12</span></li>
                                <li><strong>Storage:</strong> <span>60 GB available space</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar Column -->
            <div class="sidebar-column">
                
                <!-- Promo Image -->
                <img src="https://placehold.co/300x169/1b2838/c7d5e0?text=ELDEN+RING+Logo" alt="Game Logo" class="w-full rounded-md mb-4">

                <!-- Game Features -->
                <div class="sidebar-box">
                    <div class="sidebar-content">
                        <div class="game-feature">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                            <span>Single-player</span>
                        </div>
                        <div class="game-feature">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479c.998-.264 1.953-.55 2.916-.864M13.5 13.5h.008v.008h-.008v-.008Zm0 4.5h.008v.008h-.008v-.008Zm0 4.5h.008v.008h-.008v-.008Zm3-4.5h.008v.008h-.008v-.008Zm0 4.5h.008v.008h-.008v-.008Zm0-4.5h.008v.008h-.008v-.008Zm3-4.5h.008v.008h-.008v-.008Zm0 4.5h.008v.008h-.008v-.008Zm0-4.5h.008v.008h-.008v-.008Zm-3 4.5h.008v.008h-.008v-.008Zm0-4.5h.008v.008h-.008v-.008Zm0 4.5h.008v.008h-.008v-.008Zm-3 4.5h.008v.008h-.008v-.008Zm0-4.5h.008v.008h-.008v-.008Zm-3 4.5h.008v.008h-.008v-.008Zm-3 4.5h.008v.008h-.008v-.008Zm0-4.5h.008v.008h-.008v-.008Z" /></svg>
                            <span>Online PvP</span>
                        </div>
                        <div class="game-feature">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479c.998-.264 1.953-.55 2.916-.864M13.5 13.5h.008v.008h-.008v-.008Zm0 4.5h.008v.008h-.008v-.008Zm0 4.5h.008v.008h-.008v-.008Zm3-4.5h.008v.008h-.008v-.008Zm0 4.5h.008v.008h-.008v-.008Zm0-4.5h.008v.008h-.008v-.008Zm3-4.5h.008v.008h-.008v-.008Zm0 4.5h.008v.008h-.008v-.008Zm0-4.5h.008v.008h-.008v-.008Zm-3 4.5h.008v.008h-.008v-.008Zm0-4.5h.008v.008h-.008v-.008Zm0 4.5h.008v.008h-.008v-.008Zm-3 4.5h.008v.008h-.008v-.008Zm0-4.5h.008v.008h-.008v-.008Zm-3 4.5h.008v.008h-.008v-.008Zm-3 4.5h.008v.008h-.008v-.008Zm0-4.S5h.008v.008h-.008v-.008Z" /></svg>
                            <span>Online Co-op</span>
                        </div>
                        <div class="game-feature">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h3.75" /></svg>
                            <span>Full controller support</span>
                        </div>
                    </div>
                </div>

                <!-- Language Support -->
                <div class="sidebar-box">
                    <div class="sidebar-title">Languages</div>
                    <div class="sidebar-content">
                        <table class="language-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Interface</th>
                                    <th>Full Audio</th>
                                    <th>Subtitles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>日本語 (Japanese)</td>
                                    <td><span class="checkmark">✔</span></td>
                                    <td><span class="checkmark">✔</span></td>
                                    <td><span class="checkmark">✔</span></td>
                                </tr>
                                <tr>
                                    <td>English</td>
                                    <td><span class="checkmark">✔</span></td>
                                    <td><span class="checkmark">✔</span></td>
                                    <td><span class="checkmark">✔</span></td>
                                </tr>
                                <tr>
                                    <td>Français</td>
                                    <td><span class="checkmark">✔</span></td>
                                    <td></td>
                                    <td><span class="checkmark">✔</span></td>
                                </tr>
                                <tr>
                                    <td>Italiano</td>
                                    <td><span class="checkmark">✔</span></td>
                                    <td></td>
                                    <td><span class="checkmark">✔</span></td>
                                <h3>...and 8 more</h3>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Ratings -->
                <div class="sidebar-box">
                    <div class="sidebar-title">Ratings</div>
                    <div class="sidebar-content">
                        <div class="flex items-center gap-4">
                            <div class="bg-green-500 text-white text-3xl font-bold p-3 rounded-md">94</div>
                            <div>
                                <strong class="text-white">Metacritic</strong>
                                <p class="text-sm">Based on 85 Critic Reviews</p>
                            </div>
                        </div>
                        <hr class="border-t border-gray-600 my-4">
                        <div>
                            <strong class="text-white">CERO D</strong>
                            <p class="text-sm">(17+)</p>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>

    <script>
        // JavaScript for the media carousel
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.getElementById('mainImage');
            const thumbnails = document.querySelectorAll('.thumbnail');

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Set the main image source to the clicked thumbnail's data-src
                    mainImage.src = this.dataset.src;

                    // Update the 'active' class
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>

</body>
</html>