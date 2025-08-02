// Namespace para evitar conflictos
const DashboardTabs = {
    init() {
        this.initializeTooltips();
        this.initializeSearch();
        this.initializeFilters();
        this.initializeActions();
    },

    initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover'
            });
        });
    },

    initializeSearch() {
        const searchInputs = document.querySelectorAll('.search-input');
        searchInputs.forEach(input => {
            input.addEventListener('input', this.debounce((e) => {
                const searchText = e.target.value.toLowerCase();
                const tableBody = e.target.closest('.tab-pane').querySelector('tbody');
                const rows = tableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchText) ? '' : 'none';
                });
            }, 300));
        });
    },

    initializeFilters() {
        const filterSelects = document.querySelectorAll('.filter-select');
        filterSelects.forEach(select => {
            select.addEventListener('change', (e) => {
                const filter = e.target.value;
                const tableBody = e.target.closest('.tab-pane').querySelector('tbody');
                const rows = tableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    if (filter === 'all') {
                        row.style.display = '';
                        return;
                    }

                    const badge = row.querySelector('.badge');
                    if (badge) {
                        const status = badge.textContent.trim();
                        row.style.display = status === filter ? '' : 'none';
                    }
                });
            });
        });
    },

    initializeActions() {
        document.addEventListener('click', (e) => {
            const target = e.target.closest('[data-action]');
            if (!target) return;

            const action = target.dataset.action;
            const id = target.dataset.id;
            const row = target.closest('tr');

            switch (action) {
                case 'mark-read':
                    this.handleMarkAsRead(id, row);
                    break;
                case 'delete':
                    this.handleDelete(id, row);
                    break;
                case 'undo':
                    this.handleUndo(id, row);
                    break;
                case 'view-course':
                    this.handleViewCourse(id);
                    break;
                case 'edit-course':
                    this.handleEditCourse(id);
                    break;
                case 'delete-course':
                    this.handleDeleteCourse(id, row);
                    break;
                case 'export-courses':
                    this.handleExportCourses();
                    break;
                case 'mark-all-read':
                    this.handleMarkAllRead();
                    break;
            }
        });
    },

    // Funciones de manejo de acciones
    handleMarkAsRead(id, row) {
        fetch(`/notifications/${id}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateNotificationUI(row, 'read');
                this.showToast('Notificación marcada como leída', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showToast('Error al marcar como leída', 'error');
        });
    },

    handleDelete(id, row) {
        if (!confirm('¿Estás seguro de que deseas eliminar este elemento?')) return;

        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                row.classList.add('fade-out');
                setTimeout(() => row.remove(), 300);
                this.showToast('Elemento eliminado correctamente', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showToast('Error al eliminar', 'error');
        });
    },

    // Utilidades
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    showToast(message, type = 'info') {
        // Implementar tu sistema de notificaciones preferido
        console.log(`${type}: ${message}`);
    },

    updateNotificationUI(row, action) {
        const badge = row.querySelector('.badge');
        const actionButtons = row.querySelector('.btn-group');

        switch (action) {
            case 'read':
                badge.className = 'badge bg-secondary';
                badge.textContent = 'Leído';
                row.classList.remove('table-light');
                // Actualizar botones
                break;
            case 'unread':
                badge.className = 'badge bg-primary';
                badge.textContent = 'No leído';
                row.classList.add('table-light');
                // Actualizar botones
                break;
        }
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    DashboardTabs.init();
});
