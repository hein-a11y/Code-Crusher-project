document.addEventListener('DOMContentLoaded', function() {
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