class CourseListManager {
    constructor() {
        this.initializeElements();
        this.setupEventListeners();
        this.initializeFilters();
    }

    initializeElements() {
        this.gridViewBtn = document.querySelector('[data-view="grid"]');
        this.listViewBtn = document.querySelector('[data-view="list"]');
        this.gridView = document.getElementById('gridView');
        this.listView = document.getElementById('listView');
        this.priceRange = document.getElementById('priceRange');
        this.priceMin = document.getElementById('priceMin');
        this.priceMax = document.getElementById('priceMax');
        this.clearFiltersBtn = document.getElementById('clearFilters');
        this.sortOptions = document.getElementById('sortOptions');
        this.favoriteButtons = document.querySelectorAll('.btn-heart');
    }

    setupEventListeners() {
        // Vista Grid/Lista
        this.gridViewBtn?.addEventListener('click', () => this.switchView('grid'));
        this.listViewBtn?.addEventListener('click', () => this.switchView('list'));

        // Rango de Precios
        this.priceRange?.addEventListener('input', (e) => this.updatePriceRange(e));

        // Limpiar Filtros
        this.clearFiltersBtn?.addEventListener('click', () => this.clearFilters());

        // Ordenamiento
        this.sortOptions?.addEventListener('change', (e) => this.handleSort(e));

        // Favoritos
        this.favoriteButtons.forEach(button => {
            button.addEventListener('click', (e) => this.toggleFavorite(e));
        });
    }

    switchView(view) {
        if (view === 'grid') {
            this.gridView.classList.remove('d-none');
            this.listView.classList.add('d-none');
            this.gridViewBtn.classList.add('active');
            this.listViewBtn.classList.remove('active');
        } else {
            this.gridView.classList.add('d-none');
            this.listView.classList.remove('d-none');
            this.gridViewBtn.classList.remove('active');
            this.listViewBtn.classList.add('active');
        }
        localStorage.setItem('preferredView', view);
    }

    updatePriceRange(event) {
        const value = event.target.value;
        this.priceMax.textContent = `$${value}`;
        this.filterCourses();
    }

    clearFilters() {
        document.querySelectorAll('.filter-check').forEach(checkbox => {
            checkbox.checked = false;
        });
        this.priceRange.value = 1000;
        this.priceMax.textContent = '$1000';
        this.filterCourses();
    }

    handleSort(event) {
        const sortValue = event.target.value;
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('sort', sortValue);
        window.location.href = currentUrl.toString();
    }

    toggleFavorite(event) {
        const button = event.currentTarget;
        const icon = button.querySelector('i');
        const courseId = button.dataset.courseId;

        icon.classList.toggle('bi-heart');
        icon.classList.toggle('bi-heart-fill');
        icon.classList.toggle('text-danger');

        // Aquí puedes implementar la lógica para guardar en la base de datos
        this.updateFavoriteStatus(courseId, icon.classList.contains('bi-heart-fill'));
    }

    async updateFavoriteStatus(courseId, isFavorite) {
        try {
            const response = await fetch('/api/favorites', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ courseId, isFavorite })
            });

            if (!response.ok) throw new Error('Error al actualizar favoritos');

        } catch (error) {
            console.error('Error:', error);
            // Implementar manejo de errores UI
        }
    }

    filterCourses() {
        // Implementar lógica de filtrado
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    const courseList = new CourseListManager();
});