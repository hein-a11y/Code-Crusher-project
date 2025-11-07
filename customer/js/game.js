document.addEventListener('DOMContentLoaded', function() {
    
    // --- ▼▼▼ ヒーロースライドショー (ロジック修正) ▼▼▼ ---
    const heroSliderContainer = document.querySelector('.hero-slider-container');
    const heroSlides = document.querySelectorAll('.hero-slide');
    
    // (ドットコンテナを削除)
    // ▼▼▼ 追記 (新しいナビゲーションボタン) ▼▼▼
    const heroThumbButtons = document.querySelectorAll('.hero-thumb-button'); 

    // ▼▼▼ 修正 (if条件を変更) ▼▼▼
    if (heroSlides.length > 1 && heroSliderContainer && heroThumbButtons.length === heroSlides.length) {
        let currentSlide = 0;
        let slideInterval;
        const SLIDE_DURATION = 5000; // 5秒ごとに切り替え

        // 1. (ドット生成ロジックはすべて削除)
        
        // ▼▼▼ 追記 (サムネイルボタンのクリックイベント) ▼▼▼
        heroThumbButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                goToSlide(index);
                resetInterval(); // 自動再生タイマーをリセット
            });
        });

        // 2. スライド切り替え関数 (修正)
        function goToSlide(slideIndex) {
            // 全てのスライドから active を削除
            heroSlides.forEach(slide => slide.classList.remove('active'));
            // (ドットのアクティブ削除ロジックを削除)
            
            // ▼▼▼ 追記 (サムネイルボタンのアクティブ状態を管理) ▼▼▼
            heroThumbButtons.forEach(button => button.classList.remove('active'));

            // 現在のスライドインデックスを更新 (ループ対応)
            currentSlide = (slideIndex + heroSlides.length) % heroSlides.length;

            // 対象のスライドに active を追加
            heroSlides[currentSlide].classList.add('active');
            // (ドットのアクティブ追加ロジックを削除)

            // ▼▼▼ 追記 (サムネイルボタンのアクティブ状態を管理) ▼▼▼
            heroThumbButtons[currentSlide].classList.add('active');
        }

        // 3. 次のスライドへ
        function nextSlide() {
            goToSlide(currentSlide + 1);
        }

        // 4. 自動再生の制御
        function startInterval() {
            slideInterval = setInterval(nextSlide, SLIDE_DURATION);
        }

        function resetInterval() {
            clearInterval(slideInterval);
            startInterval();
        }

        // 5. 初期化
        // (HTML側で .active を設定したので goToSlide(0) は不要)
        startInterval(); // 自動再生開始
    }
    // --- ▲▲▲ ヒーロースライドショー (ここまで) ▲▲▲ ---


    // --- 既存の「Our Games」スライダーロジック (変更なし) ---
    const slider = document.getElementById('our-games-slider');
    const prevButton = document.getElementById('slider-prev');
    const nextButton = document.getElementById('slider-next');

    function updateArrowState() {
        if (!slider) return;
        const scrollLeft = slider.scrollLeft;
        const scrollWidth = slider.scrollWidth;
        const clientWidth = slider.clientWidth;

        prevButton.disabled = scrollLeft <= 0;
        nextButton.disabled = scrollLeft + clientWidth >= scrollWidth - 1; // -1 for precision issues
    }

    if (slider) {
        // Scroll by one "page" (the visible width of the container)
        const scrollAmount = () => slider.clientWidth;

        prevButton.addEventListener('click', () => {
            slider.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
        });

        nextButton.addEventListener('click', () => {
            slider.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
        });

        // Update arrows on scroll
        slider.addEventListener('scroll', updateArrowState);
        
        // Update arrows on load and resize
        updateArrowState();
        window.addEventListener('resize', updateArrowState);
    }
});