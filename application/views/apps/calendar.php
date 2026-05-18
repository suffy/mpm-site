<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $title ?> - Course Calendar</title>
  <meta name="description" content="Course Calendar Management System">
  
  <!-- Improved font loading -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    :root {
      --primary: #4a6bdf;
      --primary-light: #e0e6ff;
      --primary-dark: #2c4ac1;
      --background: #f8f9fa;
      --success: #d1fae5;
      --success-text: #065f46;
      --error: #fee2e2;
      --error-text: #b91c1c;
      --warning: #fef3c7;
      --warning-text: #92400e;
      --gray-100: #f3f4f6;
      --gray-200: #e5e7eb;
      --gray-300: #d1d5db;
      --gray-700: #374151;
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --radius-sm: 0.25rem;
      --radius-md: 0.375rem;
      --radius-lg: 0.5rem;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Inter", sans-serif;
      background-color: var(--background);
      color: var(--gray-700);
      line-height: 1.6;
      min-height: 100vh;
    }

    header {
      background-color: var(--primary);
      color: white;
      padding: 1.5rem 1rem;
      text-align: center;
      box-shadow: var(--shadow-md);
    }

    header h1 {
      font-size: 1.5rem;
      font-weight: 600;
    }

    /* Clock */
    .clock-container {
      background-color: var(--primary-dark);
      color: white;
      font-size: 1.5rem;
      font-weight: 600;
      padding: 0.75rem;
      text-align: center;
      letter-spacing: 1px;
      box-shadow: var(--shadow-sm);
    }

    @media (max-width: 768px) {
      .clock-container {
        font-size: 1.2rem;
        padding: 0.5rem;
      }
    }

    /* Calendar Container */
    .calendar-container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1rem;
    }

    .calendar {
      background-color: white;
      padding: 1.5rem;
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-md);
    }

    .nav-btn-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.25rem;
    }

    .nav-btn {
      background: var(--primary);
      color: white;
      border: none;
      border-radius: var(--radius-sm);
      padding: 0.5rem 1rem;
      cursor: pointer;
      font-size: 0.9rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: background 0.2s ease;
    }

    .nav-btn:hover {
      background: var(--primary-dark);
    }

    .month-year {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--primary-dark);
    }

    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 0.75rem;
    }

    /* Mobile (Grid) */
    @media (max-width: 768px) {
      .calendar-grid {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        gap: 0.5rem;
        padding-bottom: 0.5rem;
      }

      .day, .day-name {
        min-width: 85%;
        flex-shrink: 0;
        scroll-snap-align: start;
      }
    }

    .day, .day-name {
      text-align: center;
    }

    .day-name {
      font-weight: 600;
      color: var(--primary-dark);
      padding: 0.5rem;
      font-size: 0.9rem;
    }

    .day {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-md);
      min-height: 120px;
      padding: 0.5rem;
      display: flex;
      flex-direction: column;
      position: relative;
      transition: all 0.2s ease;
    }

    .day:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-sm);
    }

    .day.today {
      background: var(--primary-light);
      border-color: var(--primary);
    }

    .day.today .date-number {
      color: var(--primary-dark);
      font-weight: 700;
    }

    .date-number {
      font-weight: 600;
      margin-bottom: 0.5rem;
      align-self: flex-end;
      width: 1.75rem;
      height: 1.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }

    .day.today .date-number {
      background: var(--primary);
      color: white;
    }

    .events {
      flex: 1;
      overflow-y: auto;
      margin-top: 0.25rem;
    }

    .event {
      background-color: var(--primary);
      color: white;
      padding: 0.5rem;
      border-radius: var(--radius-sm);
      margin-top: 0.5rem;
      font-size: 0.8rem;
      cursor: pointer;
      line-height: 1.4;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      box-shadow: var(--shadow-sm);
      transition: all 0.2s ease;
    }

    .event:hover {
      transform: translateY(-1px);
      box-shadow: var(--shadow-md);
    }

    .event .course {
      font-weight: 600;
      font-size: 0.8rem;
    }

    .event .instructor {
      font-size: 0.75rem;
      opacity: 0.9;
      margin: 0.1rem 0;
    }

    .event .time {
      font-size: 0.7rem;
      color: rgba(255, 255, 255, 0.9);
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    /* Alert Boxes */
    .alert-container {
      max-width: 800px;
      margin: 1rem auto;
      padding: 0 1rem;
    }

    .alert {
      padding: 1rem;
      border-radius: var(--radius-md);
      text-align: center;
      font-weight: 500;
      margin-bottom: 1rem;
      box-shadow: var(--shadow-sm);
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .success {
      background: var(--success);
      color: var(--success-text);
      border-left: 4px solid var(--success-text);
    }

    .error {
      background: var(--error);
      color: var(--error-text);
      border-left: 4px solid var(--error-text);
    }

    .warning {
      background: var(--warning);
      color: var(--warning-text);
      border-left: 4px solid var(--warning-text);
    }

    /* Modal Popup */
    .modal {
      position: fixed;
      inset: 0;
      display: none;
      align-items: center;
      justify-content: center;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      padding: 1rem;
      animation: fadeIn 0.3s ease;
    }

    .modal-content {
      background-color: white;
      padding: 1.5rem;
      border-radius: var(--radius-lg);
      max-width: 500px;
      width: 100%;
      box-shadow: var(--shadow-lg);
      max-height: 90vh;
      overflow-y: auto;
    }

    .modal-header {
      margin-bottom: 1.5rem;
    }

    .modal-header h3 {
      font-size: 1.25rem;
      color: var(--primary-dark);
      font-weight: 600;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      font-weight: 500;
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }

    .form-group input {
      width: 100%;
      padding: 0.75rem;
      font-size: 0.9rem;
      border: 1px solid var(--gray-300);
      border-radius: var(--radius-sm);
      transition: border-color 0.2s ease;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(74, 107, 223, 0.1);
    }

    .form-actions {
      display: flex;
      gap: 0.75rem;
      margin-top: 1.5rem;
    }

    .btn {
      padding: 0.75rem 1rem;
      border: none;
      border-radius: var(--radius-sm);
      font-size: 0.9rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn-primary {
      background-color: var(--primary);
      color: white;
      flex: 1;
    }

    .btn-primary:hover {
      background-color: var(--primary-dark);
    }

    .btn-danger {
      background-color: #dc3545;
      color: white;
    }

    .btn-danger:hover {
      background-color: #bb2d3b;
    }

    .btn-secondary {
      background-color: var(--gray-200);
      color: var(--gray-700);
    }

    .btn-secondary:hover {
      background-color: var(--gray-300);
    }

    /* DropDown for multiple events */
    #eventSelectorWrapper {
      margin-bottom: 1.5rem;
    }

    #eventSelector {
      width: 100%;
      padding: 0.75rem;
      font-size: 0.9rem;
      border-radius: var(--radius-sm);
      border: 1px solid var(--gray-300);
      background-color: white;
    }

    /* Day overlay buttons */
    .day-overlay {
      position: absolute;
      top: 0.5rem;
      right: 0.5rem;
      display: none;
      flex-direction: column;
      gap: 0.25rem;
      z-index: 2;
    }

    .day:hover .day-overlay {
      display: flex;
    }

    .overlay-btn {
      background: var(--primary-dark);
      color: white;
      padding: 0.25rem 0.5rem;
      font-size: 0.7rem;
      border: none;
      border-radius: var(--radius-sm);
      cursor: pointer;
      transition: background 0.2s ease;
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .overlay-btn:hover {
      background: var(--primary);
    }

    /* Empty state */
    .empty-state {
      color: var(--gray-300);
      font-size: 0.8rem;
      text-align: center;
      padding: 1rem 0;
      font-style: italic;
    }

    /* Loading spinner */
    .spinner {
      display: inline-block;
      width: 1rem;
      height: 1rem;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>

<body>
  <header>
    <h1><i class="fas fa-calendar-alt"></i> Course Calendar</h1>
  </header>

  <!-- Clock -->
  <div class="clock-container">
    <div id="clock"></div>
  </div>

  <!-- Alerts -->
  <?php if (!empty($successMsg)): ?>
    <div class="alert-container">
      <div class="alert success">
        <i class="fas fa-check-circle"></i> <?= $successMsg ?>
      </div>
    </div>
  <?php endif; ?>
  
  <?php if (!empty($errorMsg)): ?>
    <div class="alert-container">
      <div class="alert error">
        <i class="fas fa-exclamation-circle"></i> <?= $errorMsg ?>
      </div>
    </div>
  <?php endif; ?>

  <!-- Calendar -->
  <div class="calendar-container">
    <div class="calendar">
      <div class="nav-btn-container">
        <button onclick="changeMonth(-1)" class="nav-btn">
          <i class="fas fa-chevron-left"></i> Previous
        </button>
        <h2 class="month-year" id="monthYear"></h2>
        <button onclick="changeMonth(1)" class="nav-btn">
          Next <i class="fas fa-chevron-right"></i>
        </button>
      </div>

      <div class="calendar-grid" id="calendar"></div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal" id="eventModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="modalTitle">Add New Appointment</h3>
      </div>

      <!-- Event Selector (for multiple events) -->
      <div id="eventSelectorWrapper" style="display: none;">
        <div class="form-group">
          <label for="eventSelector"><i class="fas fa-calendar-day"></i> Select Event:</label>
          <select id="eventSelector" onchange="handleEventSelection(this.value)">
            <option disabled selected>Choose Event...</option>
          </select>
        </div>
      </div>

      <!-- Form -->
      <form method="POST" id="eventForm">
        <input type="hidden" name="action" id="formAction" value="add">
        <input type="hidden" name="event_id" id="eventId">

        <div class="form-group">
          <label for="courseName"><i class="fas fa-book"></i> Course Title:</label>
          <input type="text" name="course_name" id="courseName" required>
        </div>

        <div class="form-group">
          <label for="instructorName"><i class="fas fa-chalkboard-teacher"></i> Instructor Name:</label>
          <input type="text" name="instructor_name" id="instructorName" required>
        </div>

        <div class="form-group">
          <label for="startDate"><i class="fas fa-calendar-day"></i> Start Date:</label>
          <input type="date" name="start_date" id="startDate" required>
        </div>

        <div class="form-group">
          <label for="endDate"><i class="fas fa-calendar-day"></i> End Date:</label>
          <input type="date" name="end_date" id="endDate" required>
        </div>

        <div class="form-group">
          <label for="startTime"><i class="fas fa-clock"></i> Start Time:</label>
          <input type="time" name="start_time" id="startTime" required>
        </div>

        <div class="form-group">
          <label for="endTime"><i class="fas fa-clock"></i> End Time:</label>
          <input type="time" name="end_time" id="endTime" required>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary" id="submitBtn">
            <i class="fas fa-save"></i> Save
          </button>
          
          <button type="button" class="btn btn-danger" id="deleteBtn" style="display: none;" onclick="confirmDelete()">
            <i class="fas fa-trash-alt"></i> Delete
          </button>
          
          <button type="button" class="btn btn-secondary" onclick="closeModal()">
            <i class="fas fa-times"></i> Cancel
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal" id="confirmModal" style="display: none;">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h3>
      </div>
      <p>Are you sure you want to delete this appointment? This action cannot be undone.</p>
      <div class="form-actions">
        <form method="POST" id="deleteForm">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="event_id" id="confirmDeleteEventId">
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> Delete
          </button>
          <button type="button" class="btn btn-secondary" onclick="closeConfirmModal()">
            <i class="fas fa-times"></i> Cancel
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Events Data -->
  <script>
    const events = <?= json_encode($eventsFromDB, JSON_UNESCAPED_UNICODE); ?>;
  </script>

  <script>
    // DOM Elements
    const calendarEl = document.getElementById("calendar");
    const monthYearEl = document.getElementById("monthYear");
    const modalEl = document.getElementById("eventModal");
    const confirmModalEl = document.getElementById("confirmModal");
    const modalTitleEl = document.getElementById("modalTitle");
    const deleteBtn = document.getElementById("deleteBtn");
    let currentDate = new Date();

    // Initialize calendar
    document.addEventListener('DOMContentLoaded', function() {
      renderCalendar(currentDate);
      updateClock();
      setInterval(updateClock, 1000);
    });

    // Render calendar
    function renderCalendar(date = new Date()) {
      calendarEl.innerHTML = "";

      const year = date.getFullYear();
      const month = date.getMonth();
      const today = new Date();

      const totalDays = new Date(year, month + 1, 0).getDate();
      const firstDayOfMonth = new Date(year, month, 1).getDay();

      monthYearEl.textContent = date.toLocaleDateString("en-US", {
        month: "long",
        year: "numeric",
      });

      // Weekday headers
      const weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
      weekDays.forEach((day) => {
        const dayEl = document.createElement("div");
        dayEl.className = "day-name";
        dayEl.textContent = day;
        calendarEl.appendChild(dayEl);
      });

      // Empty cells for days before the 1st
      for (let i = 0; i < firstDayOfMonth; i++) {
        calendarEl.appendChild(document.createElement("div"));
      }

      // Calendar days
      for (let day = 1; day <= totalDays; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
        const cell = createDayCell(day, dateStr, today, year, month);
        calendarEl.appendChild(cell);
      }
    }

    // Create a day cell
    function createDayCell(day, dateStr, today, year, month) {
      const cell = document.createElement("div");
      cell.className = "day";

      // Highlight today
      if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
        cell.classList.add("today");
      }

      // Date number
      const dateEl = document.createElement("div");
      dateEl.className = "date-number";
      dateEl.textContent = day;
      cell.appendChild(dateEl);

      // Events for this day
      const eventsToday = events.filter((e) => e.date === dateStr);
      const eventBox = document.createElement("div");
      eventBox.className = "events";

      if (eventsToday.length > 0) {
        eventsToday.forEach((event) => {
          eventBox.appendChild(createEventElement(event));
        });
      } else {
        const emptyState = document.createElement("div");
        emptyState.className = "empty-state";
        emptyState.textContent = "No events";
        eventBox.appendChild(emptyState);
      }

      // Overlay buttons
      const overlay = createOverlayButtons(dateStr, eventsToday);
      cell.appendChild(overlay);
      cell.appendChild(eventBox);

      return cell;
    }

    // Create an event element
    function createEventElement(event) {
      const ev = document.createElement("div");
      ev.className = "event";
      ev.onclick = (e) => {
        e.stopPropagation();
        openModalForEdit([event]);
      };

      const [course, instructor] = event.title.split(" - ");

      const courseEl = document.createElement("div");
      courseEl.className = "course";
      courseEl.textContent = course;

      const instructorEl = document.createElement("div");
      instructorEl.className = "instructor";
      instructorEl.innerHTML = `<i class="fas fa-user-tie"></i> ${instructor}`;

      const timeEl = document.createElement("div");
      timeEl.className = "time";
      timeEl.innerHTML = `<i class="fas fa-clock"></i> ${event.start_time} - ${event.end_time}`;

      ev.appendChild(courseEl);
      ev.appendChild(instructorEl);
      ev.appendChild(timeEl);

      return ev;
    }

    // Create overlay buttons
    function createOverlayButtons(dateStr, eventsToday) {
      const overlay = document.createElement("div");
      overlay.className = "day-overlay";

      // Add button
      const addBtn = document.createElement("button");
      addBtn.className = "overlay-btn";
      addBtn.innerHTML = '<i class="fas fa-plus"></i> Add';
      addBtn.onclick = (e) => {
        e.stopPropagation();
        openModalForAdd(dateStr);
      };
      overlay.appendChild(addBtn);

      // Edit button (if events exist)
      if (eventsToday.length > 0) {
        const editBtn = document.createElement("button");
        editBtn.className = "overlay-btn";
        editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit';
        editBtn.onclick = (e) => {
          e.stopPropagation();
          openModalForEdit(eventsToday);
        };
        overlay.appendChild(editBtn);
      }

      return overlay;
    }

    // Open modal for adding event
    function openModalForAdd(dateStr) {
      document.getElementById("formAction").value = "add";
      document.getElementById("eventId").value = "";
      document.getElementById("courseName").value = "";
      document.getElementById("instructorName").value = "";
      document.getElementById("startDate").value = dateStr;
      document.getElementById("endDate").value = dateStr;
      document.getElementById("startTime").value = "09:00";
      document.getElementById("endTime").value = "10:00";
      document.getElementById("confirmDeleteEventId").value = "";

      modalTitleEl.textContent = "Add New Appointment";
      deleteBtn.style.display = "none";

      const selector = document.getElementById("eventSelector");
      const wrapper = document.getElementById("eventSelectorWrapper");
      if (selector && wrapper) {
        selector.innerHTML = "";
        wrapper.style.display = "none";
      }

      modalEl.style.display = "flex";
    }

    // Open modal for editing event
    function openModalForEdit(eventsOnDate) {
      document.getElementById("formAction").value = "edit";
      modalTitleEl.textContent = "Edit Appointment";
      deleteBtn.style.display = "inline-flex";

      const selector = document.getElementById("eventSelector");
      const wrapper = document.getElementById("eventSelectorWrapper");

      selector.innerHTML = '<option disabled selected>Choose event...</option>';

      eventsOnDate.forEach((e) => {
        const option = document.createElement("option");
        option.value = JSON.stringify(e);
        option.textContent = `${e.title.split(" - ")[0]} (${formatDate(e.start)} - ${formatDate(e.end)})`;
        selector.appendChild(option);
      });

      if (eventsOnDate.length > 1) {
        wrapper.style.display = "block";
      } else {
        wrapper.style.display = "none";
      }

      handleEventSelection(JSON.stringify(eventsOnDate[0]));
      modalEl.style.display = "flex";
    }

    // Format date for display
    function formatDate(dateStr) {
      const date = new Date(dateStr);
      return date.toLocaleDateString("en-US", { month: "short", day: "numeric" });
    }

    // Handle event selection in dropdown
    function handleEventSelection(eventJSON) {
      const event = JSON.parse(eventJSON);

      document.getElementById("eventId").value = event.id;
      document.getElementById("confirmDeleteEventId").value = event.id;

      const [course, instructor] = event.title.split(" - ").map((e) => e.trim());

      document.getElementById("courseName").value = course || "";
      document.getElementById("instructorName").value = instructor || "";
      document.getElementById("startDate").value = event.start || "";
      document.getElementById("endDate").value = event.end || "";
      document.getElementById("startTime").value = event.start_time || "";
      document.getElementById("endTime").value = event.end_time || "";
    }

    // Confirm delete
    function confirmDelete() {
      closeModal();
      confirmModalEl.style.display = "flex";
    }

    // Close confirmation modal
    function closeConfirmModal() {
      confirmModalEl.style.display = "none";
      modalEl.style.display = "flex";
    }

    // Close modal
    function closeModal() {
      modalEl.style.display = "none";
      confirmModalEl.style.display = "none";
    }

    // Change month
    function changeMonth(offset) {
      currentDate.setMonth(currentDate.getMonth() + offset);
      renderCalendar(currentDate);
    }

    // Update clock
    function updateClock() {
      const now = new Date();
      const clock = document.getElementById("clock");
      clock.innerHTML = `
        <i class="fas fa-clock"></i> 
        ${now.toLocaleTimeString("en-US", { hour: '2-digit', minute: '2-digit', second: '2-digit' })}
        <span style="font-size:0.8em;opacity:0.8">${now.toLocaleDateString("en-US", { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</span>
      `;
    }
  </script>
</body>
</html>