<?php 
    session_start();

    require "../functions.php";

    $pdo = null;

    if($pdo == null){
        $pdo = getPDO();
    }

    if(isset($_SESSION['customer'])){
        $user_id = $_SESSION['customer']['user_id'];
        $login = $_SESSION['customer']['login'];
        $firstname = $_SESSION['customer']['firstname'];
        $lastname = $_SESSION['customer']['lastname'];
        $address = $_SESSION['customer']['address'];

        $fullname = $lastname . " " . $firstname ;

        $sql = "SELECT * FROM gg_users WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $profile = $stmt->fetch();

        $firstkana = $profile['firstname_kana'];
        $lastkana = $profile['lastname_kana'];
        $email = $profile['mailaddress'];
        $phone = $profile['phone_number'];
        $gender = $profile['gender'];
        $birthday = datetime_to_date($profile['birthday']);
        $created_date = datetime_to_date($profile['creation_date']);

        

    }else{
        header("location: login-input.php");
        exit;
    }

    
    
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザーダッシュボード - マイページ</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', 'Noto Sans JP', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Neon Text Effect */
        .neon-text {
            transition: text-shadow 0.3s ease-in-out;
        }
        
        .neon-text:hover {
            text-shadow: 0 0 5px #3b82f6, 0 0 10px #3b82f6, 0 0 20px #3b82f6, 0 0 40px #2563eb;
        }

        /* Transition for tab switching */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }
        .tab-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-100">

    <div class="flex h-screen overflow-hidden">

        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-slate-800 border-r border-slate-700 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col h-full">
            <!-- Logo Area -->
            <div class="h-16 flex items-center px-6 border-b border-slate-700">
                <div class="flex items-center gap-2 text-blue-400 font-bold text-xl neon-text">
                    <i data-lucide="layout-dashboard"></i>
                    <span>GG store</span>
                </div>
            </div>

            <!-- User Brief -->
            <div class="p-6 border-b border-slate-700 flex items-center gap-3">
                <img src="https://api.dicebear.com/9.x/avataaars/svg?seed=Felix" alt="User" class="w-10 h-10 rounded-full bg-slate-700">
                <div class="overflow-hidden">
                    <h4 class="font-semibold text-sm truncate text-slate-100"><?php echo $fullname; ?></h4>
                    <?php 
                        if(isActiveMember($pdo, $user_id)){
                            echo "<p class='text-xs text-slate-400 truncate'>プレミアム会員</p>";
                        }else{
                            echo "<p class='text-xs text-slate-400 truncate'>無料会員</p>";
                        }
                    ?> 
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <button onclick="switchTab('dashboard')" class="nav-item w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-700 hover:text-blue-400 transition-colors active-nav" data-target="dashboard">
                    <i data-lucide="home" class="w-5 h-5"></i>
                    ダッシュボード
                </button>
                
                <button onclick="switchTab('profile')" class="nav-item w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-700 hover:text-blue-400 transition-colors" data-target="profile">
                    <i data-lucide="user" class="w-5 h-5"></i>
                    プロフィール
                </button>

                <button onclick="switchTab('edit-profile')" class="nav-item w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-700 hover:text-blue-400 transition-colors" data-target="edit-profile">
                    <i data-lucide="settings-2" class="w-5 h-5"></i>
                    プロフィール変更
                </button>

                <button onclick="switchTab('purchases')" class="nav-item w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-700 hover:text-blue-400 transition-colors" data-target="purchases">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    購入履歴
                </button>

                <button onclick="switchTab('reviews')" class="nav-item w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-700 hover:text-blue-400 transition-colors" data-target="reviews">
                    <i data-lucide="star" class="w-5 h-5"></i>
                    マイレビュー
                </button>

                <button onclick="switchTab('wishlist')" class="nav-item w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-700 hover:text-blue-400 transition-colors" data-target="wishlist">
                    <i data-lucide="heart" class="w-5 h-5"></i>
                    ウィッシュリスト (推奨)
                </button>

                <button onclick="switchTab('premium')" class="nav-item w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-blue-400 bg-slate-700 hover:bg-slate-600 transition-colors mt-4" data-target="premium">
                    <i data-lucide="crown" class="w-5 h-5"></i>
                    プレミアム会員
                </button>
            </nav>

            <!-- Bottom Links -->
            <div class="p-4 border-t border-slate-700 space-y-1">
                <button onclick="switchTab('settings')" class="nav-item w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-700 transition-colors" data-target="settings">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                    設定・セキュリティ
                </button>
                <button onclick="openLogoutModal()" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-red-500 hover:bg-slate-700 transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    ログアウト
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
            
            <!-- Mobile Header -->
            <header class="h-16 bg-slate-800 border-b border-slate-700 flex items-center justify-between px-4 lg:hidden z-10">
                <button onclick="toggleSidebar()" class="p-2 text-slate-300 rounded-md hover:bg-slate-700">
                    <i data-lucide="menu"></i>
                </button>
                <span class="font-bold text-slate-100">マイページ</span>
                <img src="https://api.dicebear.com/9.x/avataaars/svg?seed=Felix" alt="User" class="w-8 h-8 rounded-full bg-slate-700">
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-4 lg:p-8 bg-slate-900">
                <div class="max-w-5xl mx-auto">
                    
                    <!-- 1. DASHBOARD OVERVIEW -->
                    <section id="dashboard" class="tab-content active">
                        <h2 class="text-2xl font-bold text-slate-100 mb-6">ダッシュボード概要</h2>
                        
                        <!-- Stats Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-700">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-blue-500/20 text-blue-400 rounded-lg">
                                        <i data-lucide="shopping-bag"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-400">注文総数</p>
                                        <p class="text-2xl font-bold text-slate-100">12</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-700">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-yellow-500/20 text-yellow-400 rounded-lg">
                                        <i data-lucide="star"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-400">投稿レビュー数</p>
                                        <p class="text-2xl font-bold text-slate-100">8</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-700">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-green-500/20 text-green-400 rounded-lg">
                                        <i data-lucide="wallet"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-400">総支出額</p>
                                        <p class="text-2xl font-bold text-slate-100">¥186,000</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity / Notice -->
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-6 text-white mb-8 shadow-lg">
                            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-bold">プレミアムへアップグレード</h3>
                                    <p class="text-blue-100 opacity-90">送料無料や全注文10%キャッシュバックなど特典満載。</p>
                                </div>
                                <button onclick="switchTab('premium')" class="px-6 py-2 bg-white text-blue-600 font-semibold rounded-lg hover:bg-opacity-90 transition">
                                    プランを見る
                                </button>
                            </div>
                        </div>
                    </section>


                    <!-- 2. MY PROFILE -->

                    <section id="profile" class="tab-content">
                        <h2 class="text-2xl font-bold text-slate-100 mb-6">プロフィール</h2>
                        <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 overflow-hidden">
                            <div class="h-32 bg-slate-700 w-full relative">
                                <div class="absolute -bottom-12 left-8">
                                    <img src="https://api.dicebear.com/9.x/avataaars/svg?seed=Felix" class="w-24 h-24 rounded-full border-4 border-slate-800 bg-slate-700">
                                </div>
                            </div>
                            <div class="pt-16 pb-8 px-8">
                                <div class="flex justify-between items-start mb-6">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-100"><?php echo $fullname; ?></h3>
                                        <p class="text-slate-400"><?php echo $created_date; ?></p>
                                    </div>
                                    <button onclick="switchTab('edit-profile')" class="text-blue-400 hover:text-blue-500 font-medium text-sm">
                                        プロフィール編集
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                                    <div>
                                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">メールアドレス</label>
                                        <p class="mt-1 text-slate-300 font-medium"><?php echo $email; ?></p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">電話番号</label>
                                        <p class="mt-1 text-slate-300 font-medium"><?php echo $phone; ?></p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">お届け先住所</label>
                                        <p class="mt-1 text-slate-300 font-medium"><?php echo $address; ?></p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">性別</label>
                                        <p class="mt-1 text-slate-300 font-medium"><?php echo $gender; ?></p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">生年月日</label>
                                        <p class="mt-1 text-slate-300 font-medium"><?php echo $birthday; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>


                    <!-- 3. CHANGE PROFILE (EDIT) -->
                    <section id="edit-profile" class="tab-content">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-slate-100">プロフィール編集</h2>
                            <button onclick="switchTab('profile')" class="text-sm text-slate-400 hover:text-slate-300">キャンセル</button>
                        </div>
                        
                        <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 p-8">
                            <form onsubmit="event.preventDefault(); alert('実際のアプリではPHP経由でデータベースを更新します。');">
                                <div class="flex items-center gap-6 mb-8">
                                    <img src="https://api.dicebear.com/9.x/avataaars/svg?seed=Felix" class="w-20 h-20 rounded-full bg-slate-700">
                                    <button class="px-4 py-2 border border-slate-600 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-700">アバター変更</button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-400 mb-1">名</label>
                                        <input type="text" value="フェリックス" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-slate-100">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-400 mb-1">姓</label>
                                        <input type="text" value="田中" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-slate-100">
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-slate-400 mb-1">メールアドレス</label>
                                    <input type="email" value="felix.anderson@example.com" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-slate-100">
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-slate-400 mb-1">住所</label>
                                    <textarea rows="3" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-slate-100">〒100-0001 東京都千代田区千代田1-1</textarea>
                                </div>

                                <div class="flex justify-end gap-3">
                                    <button type="button" onclick="switchTab('profile')" class="px-6 py-2 rounded-lg text-slate-400 font-medium hover:bg-slate-700 transition">キャンセル</button>
                                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 shadow-md transition">保存する</button>
                                </div>
                            </form>
                        </div>
                    </section>


                    <!-- 4. MY PURCHASES -->
                    <section id="purchases" class="tab-content">
                        <h2 class="text-2xl font-bold text-slate-100 mb-6">購入履歴</h2>
                        
                        <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-slate-700 border-b border-slate-700">
                                        <tr>
                                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase">注文ID</th>
                                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase">日付</th>
                                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase">ステータス</th>
                                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase">合計</th>
                                            <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase text-right">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-700">
                                        <!-- Row 1 -->
                                        <tr class="hover:bg-slate-700/50 transition">
                                            <td class="px-6 py-4 font-medium text-slate-100">#ORD-7782</td>
                                            <td class="px-6 py-4 text-slate-400">2023年10月24日</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/50 text-green-400">
                                                    配達完了
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-slate-100">¥18,000</td>
                                            <td class="px-6 py-4 text-right">
                                                <button class="text-blue-400 hover:text-blue-500 text-sm font-medium">詳細を見る</button>
                                            </td>
                                        </tr>
                                        <!-- Row 2 -->
                                        <tr class="hover:bg-slate-700/50 transition">
                                            <td class="px-6 py-4 font-medium text-slate-100">#ORD-7781</td>
                                            <td class="px-6 py-4 text-slate-400">2023年10月12日</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900/50 text-yellow-400">
                                                    処理中
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-slate-100">¥6,500</td>
                                            <td class="px-6 py-4 text-right">
                                                <button class="text-blue-400 hover:text-blue-500 text-sm font-medium">詳細を見る</button>
                                            </td>
                                        </tr>
                                        <!-- Row 3 -->
                                        <tr class="hover:bg-slate-700/50 transition">
                                            <td class="px-6 py-4 font-medium text-slate-100">#ORD-7500</td>
                                            <td class="px-6 py-4 text-slate-400">2023年9月5日</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/50 text-green-400">
                                                    配達完了
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-slate-100">¥48,000</td>
                                            <td class="px-6 py-4 text-right">
                                                <button class="text-blue-400 hover:text-blue-500 text-sm font-medium">詳細を見る</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>


                    <!-- 5. MY REVIEWS -->
                    <section id="reviews" class="tab-content">
                        <h2 class="text-2xl font-bold text-slate-100 mb-6">マイレビュー</h2>
                        
                        <div class="space-y-4">
                            <!-- Review Item -->
                            <div class="bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-700 flex gap-4 flex-col md:flex-row">
                                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-1.2.1&auto=format&fit=crop&w=150&q=80" alt="Product" class="w-20 h-20 object-cover rounded-md flex-shrink-0">
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-bold text-slate-100">ナイキ エアマックス 270</h3>
                                        <span class="text-sm text-slate-400">2日前</span>
                                    </div>
                                    <div class="flex text-yellow-400 my-1">
                                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                        <i data-lucide="star" class="w-4 h-4 fill-current text-slate-600"></i>
                                    </div>
                                    <p class="text-slate-300 text-sm mt-2">素晴らしい靴です。とても快適ですが、サイズが少し小さめです。ハーフサイズアップをお勧めします。</p>
                                </div>
                                <div class="flex gap-2 self-start">
                                    <button class="text-slate-400 hover:text-blue-400"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                                    <button class="text-slate-400 hover:text-red-400"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </div>
                            </div>
                            
                            <!-- Review Item -->
                            <div class="bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-700 flex gap-4 flex-col md:flex-row">
                                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-1.2.1&auto=format&fit=crop&w=150&q=80" alt="Product" class="w-20 h-20 object-cover rounded-md flex-shrink-0">
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-bold text-slate-100">クラシックレザーウォッチ</h3>
                                        <span class="text-sm text-slate-400">1ヶ月前</span>
                                    </div>
                                    <div class="flex text-yellow-400 my-1">
                                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                    </div>
                                    <p class="text-slate-300 text-sm mt-2">価格に対して品質が素晴らしいです。革の質感がプレミアムで、時間も正確です。</p>
                                </div>
                                <div class="flex gap-2 self-start">
                                    <button class="text-slate-400 hover:text-blue-400"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                                    <button class="text-slate-400 hover:text-red-400"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </div>
                            </div>
                        </div>
                    </section>


                    <!-- 6. PREMIUM MEMBER -->
                    <section id="premium" class="tab-content">
                        <div class="text-center mb-10">
                            <h2 class="text-3xl font-bold text-slate-100">プレミアム会員にアップグレード</h2>
                            <p class="text-slate-400 mt-2">限定機能を解除して、お買い物をもっとお得に。</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                            <!-- Free Plan -->
                            <div class="bg-slate-800 p-8 rounded-2xl border border-slate-700">
                                <h3 class="text-lg font-bold text-slate-100">無料会員</h3>
                                <p class="text-slate-400 text-sm mb-6">基本機能のご利用</p>
                                <div class="text-4xl font-bold text-slate-100 mb-6">¥0<span class="text-lg font-normal text-slate-400">/月</span></div>
                                <ul class="space-y-3 mb-8">
                                    <li class="flex items-center gap-3 text-slate-300"><i data-lucide="check" class="w-5 h-5 text-green-400"></i> 通常配送</li>
                                    <li class="flex items-center gap-3 text-slate-300"><i data-lucide="check" class="w-5 h-5 text-green-400"></i> 基本カスタマーサポート</li>
                                    <li class="flex items-center gap-3 text-slate-300"><i data-lucide="check" class="w-5 h-5 text-green-400"></i> セールへのアクセス</li>
                                </ul>
                                <button class="w-full py-3 px-4 border border-slate-600 rounded-xl font-semibold text-slate-400 bg-slate-800 cursor-not-allowed">現在のプラン</button>
                            </div>

                            <!-- Premium Plan -->
                            <div class="bg-blue-600 p-8 rounded-2xl shadow-xl text-white relative overflow-hidden">
                                <div class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-bl-lg">おすすめ</div>
                                <h3 class="text-lg font-bold">プレミアム会員</h3>
                                <p class="text-blue-200 text-sm mb-6">ヘビーユーザー向け</p>
                                <div class="text-4xl font-bold mb-6">¥1,200<span class="text-lg font-normal text-blue-200">/月</span></div>
                                <ul class="space-y-3 mb-8">
                                    <li class="flex items-center gap-3"><i data-lucide="check-circle" class="w-5 h-5 text-yellow-400"></i> お急ぎ便無料</li>
                                    <li class="flex items-center gap-3"><i data-lucide="check-circle" class="w-5 h-5 text-yellow-400"></i> 10%キャッシュバック</li>
                                    <li class="flex items-center gap-3"><i data-lucide="check-circle" class="w-5 h-5 text-yellow-400"></i> 24時間優先サポート</li>
                                    <li class="flex items-center gap-3"><i data-lucide="check-circle" class="w-5 h-5 text-yellow-400"></i> 新商品の先行アクセス</li>
                                </ul>
                                <button class="w-full py-3 px-4 bg-white text-blue-600 rounded-xl font-bold hover:bg-slate-100 transition shadow-lg">今すぐアップグレード</button>
                            </div>
                        </div>
                    </section>


                    <!-- 7. WISHLIST (Recommended Section) -->
                    <section id="wishlist" class="tab-content">
                        <h2 class="text-2xl font-bold text-slate-100 mb-6">ウィッシュリスト</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Wishlist Item -->
                            <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 overflow-hidden group">
                                <div class="relative h-48 bg-slate-700 overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <button class="absolute top-3 right-3 p-2 bg-slate-800/80 rounded-full text-red-400 shadow-sm hover:bg-slate-700"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-slate-100 truncate">Sony ワイヤレスヘッドホン</h3>
                                    <p class="text-blue-400 font-bold mt-1">¥32,800</p>
                                    <button class="w-full mt-4 py-2 border border-blue-400 text-blue-400 rounded-lg text-sm font-medium hover:bg-blue-400 hover:text-slate-900 transition">カートに追加</button>
                                </div>
                            </div>
                            
                            <!-- Wishlist Item -->
                            <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 overflow-hidden group">
                                <div class="relative h-48 bg-slate-700 overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <button class="absolute top-3 right-3 p-2 bg-slate-800/80 rounded-full text-red-400 shadow-sm hover:bg-slate-700"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-slate-100 truncate">ポラロイドカメラ</h3>
                                    <p class="text-blue-400 font-bold mt-1">¥18,000</p>
                                    <button class="w-full mt-4 py-2 border border-blue-400 text-blue-400 rounded-lg text-sm font-medium hover:bg-blue-400 hover:text-slate-900 transition">カートに追加</button>
                                </div>
                            </div>
                        </div>
                    </section>


                    <!-- 8. SETTINGS (Recommended Section) -->
                    <section id="settings" class="tab-content">
                        <h2 class="text-2xl font-bold text-slate-100 mb-6">設定・セキュリティ</h2>
                        
                        <!-- Change Password -->
                        <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 p-6 mb-6">
                            <h3 class="text-lg font-bold text-slate-100 mb-4 border-b border-slate-700 pb-2">パスワード変更</h3>
                            <div class="space-y-4 max-w-md">
                                <div>
                                    <label class="block text-sm text-slate-400 mb-1">現在のパスワード</label>
                                    <input type="password" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg outline-none focus:border-blue-500 text-slate-100">
                                </div>
                                <div>
                                    <label class="block text-sm text-slate-400 mb-1">新しいパスワード</label>
                                    <input type="password" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg outline-none focus:border-blue-500 text-slate-100">
                                </div>
                                <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">パスワードを更新</button>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 p-6">
                            <h3 class="text-lg font-bold text-slate-100 mb-4 border-b border-slate-700 pb-2">通知設定</h3>
                            <div class="space-y-4">
                                <label class="flex items-center justify-between cursor-pointer">
                                    <span class="text-slate-300">注文状況（メール）</span>
                                    <input type="checkbox" checked class="accent-blue-600 w-5 h-5">
                                </label>
                                <label class="flex items-center justify-between cursor-pointer">
                                    <span class="text-slate-300">キャンペーン情報</span>
                                    <input type="checkbox" class="accent-blue-600 w-5 h-5">
                                </label>
                                <label class="flex items-center justify-between cursor-pointer">
                                    <span class="text-slate-300">新着メッセージ通知</span>
                                    <input type="checkbox" checked class="accent-blue-600 w-5 h-5">
                                </label>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </main>
    </div>

    <!-- Logout Modal -->
    <div id="logout-modal" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeLogoutModal()"></div>
        <!-- Modal Content -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-800 rounded-xl shadow-2xl p-6 w-11/12 max-w-sm border border-slate-700">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-900/30 mb-4">
                    <i data-lucide="log-out" class="text-red-500"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-100">ログアウトしますか？</h3>
                <p class="text-sm text-slate-400 mt-2">アカウントからログアウトしてもよろしいですか？</p>
            </div>
            <div class="mt-6 flex gap-3">
                <button onclick="closeLogoutModal()" class="w-full px-4 py-2 bg-slate-700 text-slate-300 rounded-lg hover:bg-slate-600 transition">キャンセル</button>
                <button onclick="alert('PHPのログアウト処理へリダイレクトします...'); closeLogoutModal();" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">ログアウト</button>
            </div>
        </div>
    </div>

    <script>
        // Initialize Icons
        lucide.createIcons();

        // Sidebar Toggle for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            // Tailwind classes to toggle visibility/position
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        // Tab Switching Logic
        function switchTab(tabId) {
            // 1. Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => {
                content.classList.remove('active');
            });

            // 2. Show the specific tab content
            const targetContent = document.getElementById(tabId);
            if (targetContent) {
                targetContent.classList.add('active');
            }

            // 3. Update Sidebar active state styling
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                // Reset styling for all items
                item.classList.remove('bg-slate-700', 'text-blue-400'); 
                item.classList.add('text-slate-300');
                
                // Add active styling if this is the clicked button
                if (item.dataset.target === tabId) {
                    item.classList.add('bg-slate-700', 'text-blue-400');
                    item.classList.remove('text-slate-300');
                }
            });

            // 4. On mobile, auto-close sidebar after selection
            if (window.innerWidth < 1024) {
                toggleSidebar();
            }
        }

        // Logout Modal Logic
        function openLogoutModal() {
            document.getElementById('logout-modal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logout-modal').classList.add('hidden');
        }
    </script>
</body>
</html>