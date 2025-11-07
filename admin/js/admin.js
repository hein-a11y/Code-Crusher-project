document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarClose = document.getElementById('sidebar-close');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    // サイドバーを開閉する関数
    function toggleSidebar() {
        if (!sidebar || !sidebarOverlay) return;

        if (sidebar.classList.contains('is-open')) {
            // 閉じる
            sidebar.classList.remove('is-open');
            sidebarOverlay.classList.add('hidden');
        } else {
            // 開く
            sidebar.classList.add('is-open');
            sidebarOverlay.classList.remove('hidden');
        }
    }

    // イベントリスナーを設定
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
    if (sidebarClose) {
        sidebarClose.addEventListener('click', toggleSidebar);
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleSidebar);
    }

    // ウィンドウリサイズ時にモバイルビューで閉じた状態にする
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            if (sidebar && sidebarOverlay) {
                sidebar.classList.remove('is-open');
                sidebarOverlay.classList.add('hidden');
            }
        }
    });
});