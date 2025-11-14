<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>

    <div class="main-content">
        
        <header class="header">
            <button id="sidebar-toggle" class="sidebar-toggle-btn">
                <i class="fas fa-bars"></i>
            </button>
            <div class="header-search">
                <div class="search-wrapper">
                    <input type="text" placeholder="注文、商品、またはユーザーを検索..." class="search-input">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>
            
        </header>

        <main class="page-content">
            <div id="sidebar-overlay" class="sidebar-overlay hidden"></div>

            <section id="orders-page" class="admin-page">
                <h2 class="page-title">注文一覧 (18, 19)</h2>
                
                <div class="card">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>注文ID</th>
                                    <th>日付</th>
                                    <th>顧客</th>
                                    <th>ステータス</th>
                                    <th>合計</th>
                                    <th>追跡 (22, 23)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#98345</td>
                                    <td>2024-10-25</td>
                                    <td>J. Doe</td>
                                    <td><span class="status-badge delivered">配送済み</span></td>
                                    <td>¥8,980</td>
                                    <td class="table-actions">
                                        <button onclick="alert('注文 #98345 の追跡情報をシミュレート')"><i class="fas fa-truck"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#98346</td>
                                    <td>2024-10-25</td>
                                    <td>A. Smith</td>
                                    <td><span class="status-badge processing">処理中</span></td>
                                    <td>¥15,000</td>
                                    <td class="table-actions">
                                        <button onclick="alert('注文 #98346 の追跡情報をシミュレート')"><i class="fas fa-truck"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#98347</td>
                                    <td>2024-10-24</td>
                                    <td>C. Brown</td>
                                    <td><span class="status-badge shipped">発送済み</span></td>
                                    <td>¥5,500</td>
                                    <td class="table-actions">
                                        <button onclick="alert('注文 #98347 の追跡情報をシミュレート')"><i class="fas fa-truck"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>