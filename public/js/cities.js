document.addEventListener('DOMContentLoaded', function() {
    const itemsPerPage = 8;
    let currentPage = 1;
    
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cityCards = document.querySelectorAll('.city-card');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    let currentFilter = 'all';

    // Filter functionality
    function filterCities(filter) {
        currentFilter = filter;
        currentPage = 1;
        let count = 0;

        cityCards.forEach(card => {
            if (filter === 'all' || card.dataset.country === filter) {
                count++;
                if (count <= itemsPerPage) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide load more button
        const visibleItems = document.querySelectorAll(`.city-card[style="display: block;"]`).length;
        const totalItems = filter === 'all' ? 
            cityCards.length : 
            document.querySelectorAll(`.city-card[data-country="${filter}"]`).length;
        
        loadMoreBtn.style.display = visibleItems < totalItems ? 'inline-block' : 'none';
    }

    // Load more functionality
    loadMoreBtn.addEventListener('click', function() {
        currentPage++;
        let count = 0;
        let shown = 0;

        cityCards.forEach(card => {
            if (currentFilter === 'all' || card.dataset.country === currentFilter) {
                count++;
                if (count > (currentPage - 1) * itemsPerPage && count <= currentPage * itemsPerPage) {
                    card.style.display = 'block';
                    shown++;
                }
            }
        });

        // Hide button if no more items to show
        if (shown < itemsPerPage) {
            loadMoreBtn.style.display = 'none';
        }
    });

    // Add click handlers to filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            filterCities(this.dataset.filter);
        });
    });

    // Initial filter
    filterCities('all');
});
