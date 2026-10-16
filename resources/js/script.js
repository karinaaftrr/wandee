
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        window.lucide.createIcons();
    }

    const navLinks = document.querySelectorAll('.nav-links a');

    navLinks.forEach((link) => {
          link.addEventListener('click', () => {
              navLinks.forEach((item) => item.classList.remove('active'));
              link.classList.add('active');
          });
    });

  if (window.lucide) {
            lucide.createIcons();
        }

        const popup = document.getElementById('authErrorPopup');
        const closePopup = document.getElementById('closeAuthErrorPopup');

        if (popup && closePopup) {
            closePopup.addEventListener('click', () => {
                popup.classList.remove('is-open');
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && popup) {
                popup.classList.remove('is-open');
            }
        });

        const loginPanel = document.getElementById('loginPanel');
        const registerPanel = document.getElementById('registerPanel');
        const forgotPanel = document.getElementById('forgotPanel');

        function showPanel(panel) {
            [loginPanel, registerPanel, forgotPanel].forEach((item) => {
                item.classList.add('auth-card-hidden');
            });

            panel.classList.remove('auth-card-hidden');

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        document.getElementById('showRegister')?.addEventListener('click', (event) => {
            event.preventDefault();
            showPanel(registerPanel);
        });

        document.getElementById('showLogin')?.addEventListener('click', (event) => {
            event.preventDefault();
            showPanel(loginPanel);
        });

        document.getElementById('showLoginText')?.addEventListener('click', (event) => {
            event.preventDefault();
            showPanel(loginPanel);
        });

        document.getElementById('showForgot')?.addEventListener('click', (event) => {
            event.preventDefault();
            showPanel(forgotPanel);
        });

        document.getElementById('showLoginFromForgot')?.addEventListener('click', (event) => {
            event.preventDefault();
            showPanel(loginPanel);
        });

    
    const dashboardData = window.wandeeDashboardData;

    if (dashboardData && window.Chart) {
        const bookingsCanvas = document.getElementById('bookingsChart');
        const categoryCanvas = document.getElementById('categoryChart');

        Chart.defaults.color = '#aab5ad';
        Chart.defaults.font.family = 'Inter';

        if (bookingsCanvas) {
            new Chart(bookingsCanvas, {
                type: 'line',
                data: {
                    labels: dashboardData.labels,
                    datasets: [{
                        label: 'Pemesanan',
                        data: dashboardData.bookings,
                        borderColor: '#39ff78',
                        backgroundColor: 'rgba(57,255,120,0.12)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointBackgroundColor: '#39ff78',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            grid: {
                                color: 'rgba(255,255,255,0.07)'
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        if (categoryCanvas) {
            new Chart(categoryCanvas, {
                type: 'doughnut',
                data: {
                    labels: dashboardData.categories,
                    datasets: [{
                        data: dashboardData.categoryTotals,
                        backgroundColor: [
                            '#39ff78',
                            '#20b85a',
                            '#a2f5b8',
                            '#418d61',
                            '#7b8d80',
                            '#d0e7d7'
                        ],
                        borderColor: '#0c120e',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    }

    const searchInput = document.querySelector('.search-destination input');
    const searchButton = document.querySelector('.btn-search');
    const categoryCards = document.querySelectorAll('.cat-card');
    const destinationCards = document.querySelectorAll('.dest-card');
    const destinationGrid = document.querySelector('.dest-grid');
    const destinationSection = document.querySelector('#destinations');

    let activeCategory = null;

    function filterDestinations() {
        const query = searchInput?.value.trim().toLowerCase() || '';
        const words = query.split(/\s+/).filter(Boolean);
        let visibleCount = 0;

        destinationCards.forEach((card) => {
            const name = card.querySelector('.dest-name')?.textContent.toLowerCase() || '';
            const location = card.querySelector('.dest-loc')?.textContent.toLowerCase() || '';
            const category = card.dataset.category?.toLowerCase() || '';

            const searchableText = `${name} ${location} ${category}`;

            const matchesSearch = words.every((word) => searchableText.includes(word));
            const matchesCategory = !activeCategory || category === activeCategory;

            if (matchesSearch && matchesCategory) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        let emptyMessage = destinationGrid?.querySelector('.filter-empty');

        if (visibleCount === 0 && destinationCards.length > 0) {
            if (!emptyMessage) {
                emptyMessage = document.createElement('div');
                emptyMessage.className = 'dest-empty filter-empty';
                emptyMessage.textContent = 'Tidak ada destinasi yang sesuai.';
                destinationGrid?.appendChild(emptyMessage);
            }
        } else {
            emptyMessage?.remove();
        }
    }

    categoryCards.forEach((card) => {
        card.addEventListener('click', () => {
            const category = card.dataset.cat.toLowerCase();

            activeCategory = activeCategory === category ? null : category;

            categoryCards.forEach((item) => {
                item.classList.toggle(
                    'active',
                    item.dataset.cat.toLowerCase() === activeCategory
                );
            });

            filterDestinations();

            destinationSection?.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });

    searchInput?.addEventListener('input', filterDestinations);

    searchButton?.addEventListener('click', () => {
        filterDestinations();

        destinationSection?.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    });
});
