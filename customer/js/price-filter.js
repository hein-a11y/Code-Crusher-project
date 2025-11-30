document.addEventListener('DOMContentLoaded', function() {
    const priceMinSlider = document.getElementById('price-min-slider');
    const priceMaxSlider = document.getElementById('price-max-slider');
    const priceMinInput = document.getElementById('price-min-input');
    const priceMaxInput = document.getElementById('price-max-input');

    if (!priceMinSlider || !priceMaxSlider || !priceMinInput || !priceMaxInput) {
        return; // 価格フィルターが存在しない場合は処理を終了
    }

    // スライダーの値が変わった時の処理
    priceMinSlider.addEventListener('input', function() {
        let minVal = parseInt(priceMinSlider.value);
        let maxVal = parseInt(priceMaxSlider.value);

        // 最小値が最大値より大きくなるのを防止
        if (minVal > maxVal) {
            minVal = maxVal;
            priceMinSlider.value = minVal;
        }

        priceMinInput.value = minVal;
        updateSliderBackground();
    });

    priceMaxSlider.addEventListener('input', function() {
        let minVal = parseInt(priceMinSlider.value);
        let maxVal = parseInt(priceMaxSlider.value);

        // 最大値が最小値より小さくなるのを防止
        if (maxVal < minVal) {
            maxVal = minVal;
            priceMaxSlider.value = maxVal;
        }

        priceMaxInput.value = maxVal;
        updateSliderBackground();
    });

    // テキスト入力フィールドの値が変わった時の処理
    priceMinInput.addEventListener('input', function() {
        let minVal = parseInt(priceMinInput.value) || 0;
        const maxVal = parseInt(priceMaxSlider.value);

        if (minVal > maxVal) {
            minVal = maxVal;
        }

        priceMinSlider.value = minVal;
        priceMinInput.value = minVal;
        updateSliderBackground();
    });

    priceMaxInput.addEventListener('input', function() {
        let maxVal = parseInt(priceMaxInput.value) || 0;
        const minVal = parseInt(priceMinSlider.value);

        if (maxVal < minVal) {
            maxVal = minVal;
        }

        priceMaxSlider.value = maxVal;
        priceMaxInput.value = maxVal;
        updateSliderBackground();
    });

    // スライダーのバックグラウンド色を更新（視覚的フィードバック）
    // ここでは range input の背景ではなく独立した .price-range-highlight 要素を動かす方式に変更
    const priceSliders = document.querySelector('.price-sliders');
    const highlight = priceSliders ? priceSliders.querySelector('.price-range-highlight') : null;

    function updateSliderBackground() {
        const minVal = parseInt(priceMinSlider.value);
        const maxVal = parseInt(priceMaxSlider.value);
        const minBound = parseInt(priceMinSlider.min);
        const maxBound = parseInt(priceMaxSlider.max);
        const range = maxBound - minBound;

        const minPercent = ((minVal - minBound) / range) * 100;
        const maxPercent = ((maxVal - minBound) / range) * 100;

        if (highlight && priceSliders) {
            // trackWidth は .price-sliders の内幅から左右パディング分を引く
            const pad = 16; // CSS と同じパディング
            const totalWidth = priceSliders.clientWidth;
            const trackWidth = Math.max(totalWidth - pad * 2, 0);
            const leftPx = (minPercent / 100) * trackWidth;
            const widthPx = ((maxPercent - minPercent) / 100) * trackWidth;

            // ハイライトはトラック内にのみ表示（つまみの外側にははみ出さない）
            let highlightLeft = pad + leftPx;
            let highlightWidth = Math.max(widthPx, 0);

            // トラック境界内にクランプ（はみ出しを完全に防止）
            const minLeft = pad;
            const maxRight = pad + trackWidth;
            if (highlightLeft < minLeft) highlightLeft = minLeft;
            if (highlightLeft + highlightWidth > maxRight) highlightWidth = Math.max(maxRight - highlightLeft, 0);

            highlight.style.left = highlightLeft + 'px';
            highlight.style.width = highlightWidth + 'px';
        }

        // inputs の background は空にしておく（ブラウザ依存で見た目がズレるため）
        priceMinSlider.style.background = 'transparent';
        priceMaxSlider.style.background = 'transparent';
    }

    // 初期化
    updateSliderBackground();
});
