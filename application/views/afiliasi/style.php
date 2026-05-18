<style>
/* Style untuk kalender */
.calendar-container {
    background-color: var(--bs-body-bg);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 25px;
    border: 1px solid var(--bs-border-color);
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--bs-border-color);
}

.calendar-month-year {
    font-size: 1.6rem;
    font-weight: 600;
    color: var(--bs-dark-text-emphasis);
}

.calendar-nav-buttons {
    display: flex;
    gap: 10px;
}

.calendar-nav-btn {
    background-color: var(--bs-dark-bg-subtle);
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    cursor: pointer;
    color: var(--bs-dark-text-emphasis);
    font-size: 1.2rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.calendar-nav-btn:hover {
    background-color: var(--bs-dark-border-subtle);
    transform: translateY(-2px);
}

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    font-weight: 600;
    color: var(--bs-dark-text-emphasis);
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--bs-border-color);
    font-size: 0.95rem;
}

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}

.calendar-day {
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s ease;
    position: relative;
    background-color: var(--bs-body-bg);
    color: var(--bs-body-color);
    border: 1px solid transparent;
}

.calendar-day:hover {
    background-color: var(--bs-dark-bg-subtle);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-color: var(--bs-dark-border-subtle);
}

.calendar-day.active {
    background-color: var(--bs-primary);
    color: white;
    box-shadow: 0 5px 15px rgba(var(--bs-primary-rgb), 0.3);
    border-color: var(--bs-primary);
}

.calendar-day.today {
    background-color: var(--bs-warning-bg-subtle);
    color: var(--bs-warning-text-emphasis);
    font-weight: 700;
    border: 1px solid var(--bs-warning-border-subtle);
}

.calendar-day.other-month {
    color: var(--bs-secondary-color);
    opacity: 0.6;
}

.calendar-day.has-event::after {
    content: '';
    position: absolute;
    bottom: 6px;
    width: 5px;
    height: 5px;
    background-color: var(--bs-danger);
    border-radius: 50%;
}

.calendar-day.active.has-event::after {
    background-color: white;
}

/* Panel informasi tanggal terpilih */
.selected-date-panel {
    background-color: var(--bs-dark-bg-subtle);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    border: 1px solid var(--bs-border-color);
}

.selected-date-title {
    font-size: 1.1rem;
    color: var(--bs-dark-text-emphasis);
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--bs-border-color);
}

.selected-date-display {
    font-size: 2rem;
    font-weight: 700;
    color: var(--bs-primary);
    margin-bottom: 8px;
}

.selected-day-display {
    font-size: 1.1rem;
    color: var(--bs-secondary-color);
    margin-bottom: 15px;
}

/* Form acara */
.event-form-panel {
    background-color: var(--bs-body-bg);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--bs-border-color);
    margin-bottom: 25px;
}

.event-form-panel h4 {
    font-size: 1.2rem;
    color: var(--bs-dark-text-emphasis);
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--bs-border-color);
}

.event-input {
    background-color: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    color: var(--bs-body-color);
    padding: 10px 15px;
    border-radius: 8px;
    width: 100%;
    margin-bottom: 15px;
    transition: border-color 0.3s;
}

.event-input:focus {
    border-color: var(--bs-primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.1);
}

.event-textarea {
    min-height: 100px;
    resize: vertical;
}

/* Daftar acara */
.events-list-panel {
    background-color: var(--bs-body-bg);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--bs-border-color);
}

.events-list-panel h4 {
    font-size: 1.2rem;
    color: var(--bs-dark-text-emphasis);
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--bs-border-color);
}

.event-item {
    background-color: var(--bs-dark-bg-subtle);
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    border-left: 4px solid var(--bs-primary);
}

.event-title {
    font-weight: 600;
    color: var(--bs-dark-text-emphasis);
    margin-bottom: 5px;
}

.event-description {
    color: var(--bs-secondary-color);
    font-size: 0.9rem;
    margin-bottom: 10px;
}

.no-events {
    color: var(--bs-secondary-color);
    text-align: center;
    padding: 20px;
    font-style: italic;
}

/* Tombol khusus kalender */
.calendar-btn {
    background-color: var(--bs-primary);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.calendar-btn:hover {
    background-color: var(--bs-primary-border-subtle);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.calendar-btn-secondary {
    background-color: var(--bs-secondary-bg-subtle);
    color: var(--bs-secondary-color);
}

.calendar-btn-secondary:hover {
    background-color: var(--bs-secondary-border-subtle);
}

/* Responsif */
@media (max-width: 768px) {
    .calendar-container {
        padding: 15px;
    }
    
    .calendar-day {
        height: 40px;
        font-size: 0.9rem;
    }
    
    .calendar-month-year {
        font-size: 1.3rem;
    }
    
    .selected-date-display {
        font-size: 1.5rem;
    }
}

<style>
/* Style tambahan untuk loading overlay */
#loadingOverlay {
    transition: all 0.3s ease;
    z-index: 9999;
}

.spinner-border {
    animation: spinner-border 0.75s linear infinite;
}

@keyframes spinner-border {
    to { transform: rotate(360deg); }
}
</style>

</style>