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
</style>