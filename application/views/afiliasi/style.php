<style>
/* Styling untuk calendar day */
.calendar-day {
    position: relative;
    min-height: 80px;
    padding: 8px;
    border-radius: 8px;
    background-color: white;
    border: 1px solid #e0e0e0;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.calendar-day:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    background-color: #f8f9fa;
}

.calendar-day.other-month {
    background-color: #f8f9fa;
    opacity: 0.6;
}

.calendar-day.today {
    border: 2px solid #ffc107;
    background-color: #fff9e6;
}

.calendar-day.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.calendar-day.active .day-number {
    color: white;
}

.day-number {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

/* Grid layout untuk calendar */
.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}

/* Responsive untuk mobile */
@media (max-width: 768px) {
    .calendar-day {
        min-height: 60px;
        padding: 4px;
    }
    
    .day-number {
        font-size: 14px;
    }
    
    .calendar-days {
        gap: 4px;
    }
}

/* Custom scrollbar styling */
#horizontalScrollContainer::-webkit-scrollbar {
    height: 8px;
}

#horizontalScrollContainer::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#horizontalScrollContainer::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

#horizontalScrollContainer::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Styling untuk activity item card di modal */
.activity-item-card {
    transition: all 0.2s ease;
    background-color: #fff;
}

.activity-item-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateX(5px);
}

/* Animasi untuk modal */
.modal-content {
    border-radius: 12px;
    overflow: hidden;
}

.modal-header {
    border-bottom: none;
}

.modal-footer {
    border-top: none;
}

/* Styling untuk scrollbar modal */
#activitiesModalBody::-webkit-scrollbar {
    width: 6px;
}

#activitiesModalBody::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#activitiesModalBody::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

#activitiesModalBody::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* ==================== STYLING CALENDAR ==================== */
.calendar-weekdays {
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
}

/* Styling untuk setiap hari */
.calendar-day {
    position: relative;
    min-height: 100px;
    padding: 12px 8px;
    border-radius: 12px;
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    cursor: pointer;
    transition: all 0.25s ease-in-out;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.calendar-day:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    background: var(--bs-tertiary-bg);
    border-color: var(--bs-warning);
}

/* Hari dari bulan lain */
.calendar-day.other-month {
    background: var(--bs-secondary-bg);
    opacity: 0.6;
    border-color: var(--bs-border-color);
}

.calendar-day.other-month:hover {
    opacity: 0.8;
    background: var(--bs-secondary-bg);
    transform: translateY(-2px);
}

/* Hari ini (today) */
.calendar-day.today {
    background: var(--bs-warning-bg-subtle);
    border: 2px solid var(--bs-warning);
    box-shadow: 0 2px 8px rgba(var(--bs-warning-rgb), 0.2);
}

.calendar-day.today .day-number {
    color: var(--bs-warning-text-emphasis);
    font-weight: bold;
}

/* Hari yang aktif dipilih */
.calendar-day.active {
    background: var(--bs-primary);
    border-color: var(--bs-primary-dark);
    color: var(--bs-white);
}

.calendar-day.active .day-number {
    color: var(--bs-white);
    font-weight: bold;
}

.calendar-day.active .activity-count {
    background-color: rgba(255,255,255,0.3);
    color: var(--bs-white);
}

.calendar-day.active .activity-item {
    background-color: rgba(255,255,255,0.2);
    color: var(--bs-white);
}

.calendar-day.active .activity-item .text-success {
    color: var(--bs-warning) !important;
}

/* Nomor tanggal */
.day-number {
    font-size: 18px;
    font-weight: 600;
    color: var(--bs-body-color);
    margin-bottom: 10px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s;
}

.calendar-day:hover .day-number {
    background-color: rgba(var(--bs-warning-rgb), 0.2);
}

/* Badge jumlah aktivitas */
.activity-count {
    display: inline-block;
    background-color: var(--bs-info);
    color: var(--bs-white);
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 11px;
    font-weight: bold;
    margin-top: 5px;
    transition: all 0.2s;
}

.activity-count i {
    font-size: 10px;
    margin-right: 3px;
}

.calendar-day:hover .activity-count {
    background-color: var(--bs-info-dark);
    transform: scale(1.05);
}

/* Styling untuk activity list di calendar */
.activity-list {
    width: 100%;
    margin-top: 5px;
}

.activity-item {
    background-color: var(--bs-tertiary-bg);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
    transition: all 0.2s;
    color: var(--bs-body-color);
}

.activity-item:hover {
    background-color: var(--bs-secondary-bg);
    transform: translateX(2px);
}

/* Legenda */
.calendar-legend-today {
    width: 20px;
    height: 20px;
    background: var(--bs-warning-bg-subtle);
    border: 1px solid var(--bs-warning);
    border-radius: 4px;
}

.calendar-legend-active {
    width: 20px;
    height: 20px;
    background: var(--bs-primary);
    border-radius: 4px;
}

.calendar-legend-other {
    width: 20px;
    height: 20px;
    background: var(--bs-secondary-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 4px;
    opacity: 0.6;
}

/* Styling untuk aktivitas di modal */
.activity-item-card {
    transition: all 0.2s ease;
    background: var(--bs-body-bg);
    border-left: 4px solid var(--bs-primary);
}

.activity-item-card:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-left-color: var(--bs-warning);
}

/* Card styling */
.card {
    background: var(--bs-body-bg);
    border-color: var(--bs-border-color);
}

.card-header {
    background-color: var(--bs-tertiary-bg);
    border-bottom-color: var(--bs-border-color);
}

/* Modal styling */
.modal-content {
    border-radius: 12px;
    overflow: hidden;
    background: var(--bs-body-bg);
    border-color: var(--bs-border-color);
}

.modal-header {
    border-bottom: 1px solid var(--bs-border-color);
    background: var(--bs-tertiary-bg);
}

.modal-footer {
    border-top: 1px solid var(--bs-border-color);
    background: var(--bs-tertiary-bg);
}

/* List group item */
.list-group-item {
    background-color: var(--bs-body-bg);
    border-color: var(--bs-border-color);
    transition: all 0.2s;
}

.list-group-item:hover {
    background-color: var(--bs-tertiary-bg);
    transform: translateX(3px);
}

/* Alert styling */
.alert {
    background-color: var(--bs-body-bg);
    border-color: var(--bs-border-color);
}

/* Form control */
.form-control, .form-select {
    background-color: var(--bs-body-bg);
    border-color: var(--bs-border-color);
    color: var(--bs-body-color);
}

.form-control:focus, .form-select:focus {
    background-color: var(--bs-body-bg);
    color: var(--bs-body-color);
}

/* Button close */
.btn-close {
    filter: var(--bs-btn-close-filter);
}

/* Text colors */
.text-muted {
    color: var(--bs-secondary-color) !important;
}

.text-primary {
    color: var(--bs-primary) !important;
}

.text-success {
    color: var(--bs-success) !important;
}

.text-danger {
    color: var(--bs-danger) !important;
}

.text-warning {
    color: var(--bs-warning) !important;
}

.text-info {
    color: var(--bs-info) !important;
}

/* Border colors */
.border-primary {
    border-color: var(--bs-primary) !important;
}

.border-success {
    border-color: var(--bs-success) !important;
}

.border-warning {
    border-color: var(--bs-warning) !important;
}

.border-danger {
    border-color: var(--bs-danger) !important;
}

/* Background colors */
.bg-primary {
    background-color: var(--bs-primary) !important;
}

.bg-success {
    background-color: var(--bs-success) !important;
}

.bg-warning {
    background-color: var(--bs-warning) !important;
}

.bg-danger {
    background-color: var(--bs-danger) !important;
}

.bg-light {
    background-color: var(--bs-tertiary-bg) !important;
}

/* Loading overlay */
#loadingOverlay {
    background-color: rgba(var(--bs-body-bg-rgb), 0.9) !important;
    backdrop-filter: blur(3px);
}

#loadingOverlay p {
    color: var(--bs-body-color) !important;
}

/* Scrollbar styling */
#horizontalScrollContainer::-webkit-scrollbar {
    height: 8px;
}

#horizontalScrollContainer::-webkit-scrollbar-track {
    background: var(--bs-secondary-bg);
    border-radius: 10px;
}

#horizontalScrollContainer::-webkit-scrollbar-thumb {
    background: var(--bs-secondary-color);
    border-radius: 10px;
}

#horizontalScrollContainer::-webkit-scrollbar-thumb:hover {
    background: var(--bs-body-color);
}

#activitiesModalBody::-webkit-scrollbar {
    width: 6px;
}

#activitiesModalBody::-webkit-scrollbar-track {
    background: var(--bs-secondary-bg);
    border-radius: 10px;
}

#activitiesModalBody::-webkit-scrollbar-thumb {
    background: var(--bs-secondary-color);
    border-radius: 10px;
}

#activitiesModalBody::-webkit-scrollbar-thumb:hover {
    background: var(--bs-body-color);
}

/* Responsive untuk mobile */
@media (max-width: 768px) {
    .calendar-days {
        gap: 6px;
    }
    
    .calendar-day {
        min-height: 70px;
        padding: 8px 4px;
    }
    
    .day-number {
        font-size: 14px;
        width: 28px;
        height: 28px;
        margin-bottom: 5px;
    }
    
    .activity-count {
        font-size: 9px;
        padding: 2px 6px;
    }
    
    .calendar-weekdays div {
        font-size: 12px;
        padding: 6px !important;
    }
    
    .activity-item {
        font-size: 8px;
        padding: 1px 4px;
    }
    
    .activity-item span {
        display: none;
    }
    
    .activity-item i {
        margin-right: 0 !important;
    }
}

@media (max-width: 576px) {
    .calendar-day {
        min-height: 60px;
    }
    
    .calendar-weekdays div {
        font-size: 10px;
    }
    
    .activity-count span {
        display: none;
    }
    
    .activity-count i {
        margin-right: 0;
    }
}

/* Print styling */
@media print {
    .calendar-day {
        break-inside: avoid;
        page-break-inside: avoid;
    }
    
    .btn, .modal, .calendar-nav-buttons {
        display: none !important;
    }
}

/* Animation untuk modal */
.modal-content {
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Hover effect untuk tombol */
.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Shadow utility */
.shadow-sm {
    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
}

.shadow-sm:hover {
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}

</style>