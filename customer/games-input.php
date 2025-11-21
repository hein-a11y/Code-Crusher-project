<?php require "../header.php" ?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EC Games - The Cosmos of Gaming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">

    <style>
        /* Set body and html to fill the viewport */
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            /* Hide scrollbars to prevent flashing during load, but allow scrolling */
            overflow-x: hidden;
        }

        /* Style for the 3D background canvas */
        #bg-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Place the canvas behind all other content */
            z-index: -1;
            background: #000; /* Fallback background color */
        }

        /* Wrapper for the page content */
        #content-wrap {
            position: relative;
            /* Ensure content is scrollable over the fixed background */
            z-index: 1;
            width: 100%;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            /* Add a very subtle dark overlay to help text pop */
            background-color: rgba(0, 0, 0, 0.2);
        }

        /* Glassmorphism effect for cards */
        .glass-card {
            background-color: rgba(17, 24, 39, 0.6); /* bg-gray-900 with 60% opacity */
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(55, 65, 81, 0.5); /* border-gray-700 with 50% opacity */
        }

        /* Custom Swiper Styles */
        /* Scoped styles ONLY for the hero slider arrows */
        .hero-slider .swiper-button-next,
        .hero-slider .swiper-button-prev {
            color: #c7d2fe; /* indigo-200 */
            transition: color 0.3s;
        }
        .hero-slider .swiper-button-next:hover,
        .hero-slider .swiper-button-prev:hover {
            color: #818cf8; /* indigo-400 */
        }
        .swiper-pagination-bullet {
            background: #c7d2fe; /* indigo-200 */
            opacity: 0.7;
        }
        .swiper-pagination-bullet-active {
            background: #6366f1; /* indigo-600 */
            opacity: 1;
        }
        .game-slider .swiper-slide {
            height: auto; /* Allow slides to grow with content */
        }
        
        /* Fix for game slider arrow clipping */
        .game-slider.swiper { /* Target the main Swiper container */
            padding: 0 40px; /* Add padding to make space for the buttons */
            overflow: visible; /* Allow content (buttons) to overflow */
        }
        .game-slider .swiper-button-prev {
            left: 0; /* Position the button at the start of the padding */
        }
        .game-slider .swiper-button-next {
            right: 0; /* Position the button at the end of the padding */
        }
    </style>
</head>
<body class="bg-black text-white">

    <canvas id="bg-canvas"></canvas>

    <div id="content-wrap">
        
       

        <main class="flex-grow">
            <section class="relative h-[70vh] md:h-[90vh] w-full text-white overflow-hidden">
                <div class="swiper hero-slider h-full">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide relative">
                            <img src="./photos/GOW.jpg" alt="Hero Game 1" class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-60"></div>
                            <div class="relative z-10 h-full flex flex-col justify-end pb-20 md:pb-32 items-center text-center px-4">
                                <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter mb-4">GOD OF WAR 8</h1>
                                <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto mb-8">
                                    A new adventure awaits. Pre-order the deluxe edition now.
                                </p>
                                <a href="#" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-10 rounded-lg text-lg shadow-xl hover:shadow-indigo-500/50 transition duration-300 transform hover:scale-105">
                                    Pre-Order
                                </a>
                            </div>
                        </div>
                        <div class="swiper-slide relative">
                            <img src="./photos/RDR.jfif" alt="Hero Game 2" class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-60"></div>
                            <div class="relative z-10 h-full flex flex-col justify-end pb-20 md:pb-32 items-center text-center px-4">
                                <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter mb-4">RED Raiders</h1>
                                <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto mb-8">
                                    Assemble your crew. Conquer the stars. Now 20% off.
                                </p>
                                <a href="#" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-10 rounded-lg text-lg shadow-xl hover:shadow-indigo-500/50 transition duration-300 transform hover:scale-105">
                                    Shop Sale
                                </a>
                            </div>
                        </div>
                        <div class="swiper-slide relative">
                            <img src="./photos/mario.jfif" alt="Hero Game 3" class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-60"></div>
                            <div class="relative z-10 h-full flex flex-col justify-end pb-20 md:pb-32 items-center text-center px-4">
                                <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter mb-4">LUIGIO</h1>
                                <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto mb-8">
                                    The future of humanity is in your hands. Available now.
                                </p>
                                <a href="#" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-10 rounded-lg text-lg shadow-xl hover:shadow-indigo-500/50 transition duration-300 transform hover:scale-105">
                                    Buy Now
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </section>

            <section class="py-20 px-4 container mx-auto space-y-16">

                <div>
                    <h2 class="text-3xl font-bold mb-8">New Releases</h2>
                    <div class="swiper game-slider relative group">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/93c5fd?text=Cybernetic+Dawn" alt="Game 1" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Cybernetic Dawn</h3>
                                    <p class="text-gray-300 mb-4 text-sm">A sprawling open-world RPG set in a neon-drenched future.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$59.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/93c5fd?text=Star+Drifter" alt="Game 2" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Star Drifter</h3>
                                    <p class="text-gray-300 mb-4 text-sm">Command your starship and explore a procedurally generated galaxy.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$39.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/93c5fd?text=Void+Echoes" alt="Game 3" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Void Echoes</h3>
                                    <p class="text-gray-300 mb-4 text-sm">A psychological horror game set on an abandoned space station.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$29.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/93c5fd?text=Chrono+Breach" alt="Game 4" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Chrono Breach</h3>
                                    <p class="text-gray-300 mb-4 text-sm">A fast-paced roguelite where you manipulate time.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$19.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/93c5fd?text=Quantum+Rift" alt="Game 5" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Quantum Rift</h3>
                                    <p class="text-gray-300 mb-4 text-sm">Bend reality in this mind-bending puzzle-shooter.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$44.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/93c5fd?text=Aetherium+Wars" alt="Game 6" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Aetherium Wars</h3>
                                    <p class="text-gray-300 mb-4 text-sm">A 4X strategy game set in a unique fantasy cosmos.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$39.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/93c5fd?text=Project+Nova" alt="Game 7" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Project Nova</h3>
                                    <p class="text-gray-300 mb-4 text-sm">Survive hostile alien worlds in this co-op survival hit.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$29.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/93c5fd?text=Hexforge" alt="Game 8" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Hexforge</h3>
                                    <p class="text-gray-300 mb-4 text-sm">A tactical turn-based RPG with deep card-based mechanics.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$24.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="swiper-button-prev absolute top-1/2 -translate-y-1/2 left-0 z-10 glass-card p-2 rounded-full text-indigo-200 hover:text-white hover:bg-indigo-600/50 transition-all duration-300 opacity-0 group-hover:opacity-100 disabled:opacity-0 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button class="swiper-button-next absolute top-1/2 -translate-y-1/2 right-0 z-10 glass-card p-2 rounded-full text-indigo-200 hover:text-white hover:bg-indigo-600/50 transition-all duration-300 opacity-0 group-hover:opacity-100 disabled:opacity-0 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <h2 class="text-3xl font-bold mb-8">Top Sellers</h2>
                    <div class="swiper game-slider relative group">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/a5b4fc?text=Galactic+Empires" alt="Game 1" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Galactic Empires</h3>
                                    <p class="text-gray-300 mb-4 text-sm">Build your empire from a single planet to a star-spanning dominion.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$49.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/a5b4fc?text=Star+Drifter" alt="Game 2" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Star Drifter</h3>
                                    <p class="text-gray-300 mb-4 text-sm">Command your starship and explore a procedurally generated galaxy.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$39.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/a5b4fc?text=Quantum+Leap" alt="Game 3" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Quantum Leap</h3>
                                    <p class="text-gray-300 mb-4 text-sm">First-person puzzle platformer that bends the laws of physics.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$24.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/a5b4fc?text=Cybernetic+Dawn" alt="Game 4" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Cybernetic Dawn</h3>
                                    <p class="text-gray-300 mb-4 text-sm">A sprawling open-world RPG set in a neon-drenched future.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$59.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/a5b4fc?text=Void+Echoes" alt="Game 5" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Void Echoes</h3>
                                    <p class="text-gray-300 mb-4 text-sm">A psychological horror game set on an abandoned space station.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$29.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/a5b4fc?text=Chrono+Breach" alt="Game 6" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Chrono Breach</h3>
                                    <p class="text-gray-300 mb-4 text-sm">A fast-paced roguelite where you manipulate time.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$19.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/a5b4fc?text=Solara+Prime" alt="Game 7" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Solara Prime</h3>
                                    <p class="text-gray-300 mb-4 text-sm">A vibrant open-world adventure game full of mystery.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$59.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="swiper-slide glass-card rounded-xl overflow-hidden shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-2">
                                <img src="https://placehold.co/600x400/1e1b4b/a5b4fc?text=Mecha+Assault" alt="Game 8" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">Mecha Assault</h3>
                                    <p class="text-gray-300 mb-4 text-sm">Customize your giant mech and battle for supremacy.</p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-2xl font-bold text-indigo-400">$49.99</span>
                                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2 px-4 rounded-lg transition duration-300 text-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <button class="swiper-button-prev absolute top-1/2 -translate-y-1/2 left-0 z-10 glass-card p-2 rounded-full text-indigo-200 hover:text-white hover:bg-indigo-600/50 transition-all duration-300 opacity-0 group-hover:opacity-100 disabled:opacity-0 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button class="swiper-button-next absolute top-1/2 -translate-y-1/2 right-0 z-10 glass-card p-2 rounded-full text-indigo-200 hover:text-white hover:bg-indigo-600/50 transition-all duration-300 opacity-0 group-hover:opacity-100 disabled:opacity-0 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

            </section>
        </main>

        <footer class="glass-card mt-20">
            <div class="container mx-auto px-6 py-8 text-center text-gray-400">
                <p>&copy; 2025 EC GAMES. All rights reserved.</p>
                <p class="mt-2 text-sm">Navigating the cosmos of gaming, one click at a time.</p>
            </div>
        </footer>

    </div>

    <script>
        // Hero Slider
        const heroSwiper = new Swiper('.hero-slider', {
            // Optional parameters
            direction: 'horizontal',
            loop: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },

            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

        // Game Sliders
        const gameSwiper = new Swiper('.game-slider', {
            // Optional parameters
            direction: 'horizontal',
            loop: false,
            slidesPerView: 1,
            spaceBetween: 30,
            
            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // Responsive breakpoints
            breakpoints: {
                // when window width is >= 640px
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                // when window width is >= 1024px
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30
                }
            }
        });
    </script>

    <script>
        let scene, camera, renderer, stars;

        function init() {
            // Scene
            scene = new THREE.Scene();

            // Camera
            camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 1, 1000);
            camera.position.z = 1;

            // Renderer
            const canvas = document.getElementById('bg-canvas');
            renderer = new THREE.WebGLRenderer({
                canvas: canvas,
                antialias: true
            });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

            // Create Stars
            createStars();

            // Event Listeners
            window.addEventListener('resize', onWindowResize, false);
            // Add mouse move listener for parallax effect
            document.addEventListener('mousemove', onMouseMove, false);

            // Start animation loop
            animate();
        }

        function createStars() {
            const starGeometry = new THREE.BufferGeometry();
            const starCount = 10000;
            const positions = new Float32Array(starCount * 3);

            for (let i = 0; i < starCount; i++) {
                const i3 = i * 3;
                positions[i3] = (Math.random() - 0.5) * 1000; // x
                positions[i3 + 1] = (Math.random() - 0.5) * 1000; // y
                positions[i3 + 2] = (Math.random() - 0.5) * 1000; // z
            }

            starGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));

            const starMaterial = new THREE.PointsMaterial({
                color: 0xffffff,
                size: 0.5,
                transparent: true,
                blending: THREE.AdditiveBlending
            });

            stars = new THREE.Points(starGeometry, starMaterial);
            scene.add(stars);
        }

        // Animation Loop
        function animate() {
            requestAnimationFrame(animate);

            // Slowly rotate the starfield
            if (stars) {
                stars.rotation.x += 0.00005;
                stars.rotation.y += 0.0001;
            }

            renderer.render(scene, camera);
        }

        // Handle Window Resize
        function onWindowResize() {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        }

        // Handle Mouse Move for Parallax
        function onMouseMove(event) {
            // Normalize mouse position (-1 to 1)
            const mouseX = (event.clientX / window.innerWidth) * 2 - 1;
            const mouseY = -(event.clientY / window.innerHeight) * 2 + 1;

            // Animate camera position based on mouse for a subtle parallax effect
            // We use a small factor to keep it subtle
            gsap.to(camera.position, {
                duration: 1,
                x: mouseX * 0.5, // Max 0.5 units left/right
                y: mouseY * 0.5, // Max 0.5 units up/down
                ease: "power2.out"
            });
        }
        
        // We need GSAP for the smooth mouse-move effect. Let's load it.
        // We will add the script tag for GSAP.
        const gsapScript = document.createElement('script');
        gsapScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js';
        gsapScript.onload = () => {
            // Once GSAP is loaded, initialize everything
            init();
        };
        document.head.appendChild(gsapScript);

    </script>
</body>
</html>