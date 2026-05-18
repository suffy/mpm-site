<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Interaktif dengan Klik per Tanggal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 1000px;
            width: 100%;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(to right, #4b6cb7, #182848);
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        
        .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .main-content {
            display: flex;
            flex-wrap: wrap;
            padding: 0;
        }
        
        .calendar-section {
            flex: 1;
            min-width: 300px;
            padding: 30px;
            border-right: 1px solid #eee;
        }
        
        .info-section {
            flex: 1;
            min-width: 300px;
            padding: 30px;
            background-color: #f9fafc;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .month-year {
            font-size: 1.8rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .nav-buttons button {
            background-color: #f0f4ff;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            color: #4b6cb7;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .nav-buttons button:hover {
            background-color: #4b6cb7;
            color: white;
        }
        
        .weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-weight: 600;
            color: #4b6cb7;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .days-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
        }
        
        .day {
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .day:hover {
            background-color: #f0f4ff;
            transform: translateY(-3px);
        }
        
        .day.active {
            background-color: #4b6cb7;
            color: white;
            box-shadow: 0 5px 10px rgba(75, 108, 183, 0.3);
        }
        
        .day.today {
            background-color: #ffefd6;
            color: #e67e22;
            font-weight: 700;
        }
        
        .day.other-month {
            color: #bbb;
        }
        
        .day.has-event::after {
            content: '';
            position: absolute;
            bottom: 5px;
            width: 5px;
            height: 5px;
            background-color: #e74c3c;
            border-radius: 50%;
        }
        
        .day.active.has-event::after {
            background-color: white;
        }
        
        .selected-date-info {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }
        
        .selected-date-title {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f4ff;
        }
        
        .selected-date {
            font-size: 2.2rem;
            font-weight: 700;
            color: #4b6cb7;
            margin-bottom: 10px;
        }
        
        .selected-day {
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        
        .event-form {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .event-form h3 {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f4ff;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }
        
        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        
        .form-group input:focus, 
        .form-group textarea:focus {
            border-color: #4b6cb7;
            outline: none;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .events-list {
            margin-top: 25px;
        }
        
        .events-list h3 {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f4ff;
        }
        
        .event-item {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #4b6cb7;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
        }
        
        .event-date {
            font-weight: 600;
            color: #4b6cb7;
            margin-bottom: 5px;
        }
        
        .event-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .event-desc {
            color: #666;
            font-size: 0.9rem;
        }
        
        .no-events {
            color: #888;
            font-style: italic;
            text-align: center;
            padding: 20px;
        }
        
        .btn {
            background-color: #4b6cb7;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .btn:hover {
            background-color: #3a5795;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
        
        .btn-delete {
            background-color: #e74c3c;
            padding: 5px 10px;
            font-size: 0.8rem;
            margin-top: 8px;
        }
        
        .btn-delete:hover {
            background-color: #c0392b;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 0.9rem;
            border-top: 1px solid #eee;
        }
        
        .instructions {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        .instructions h4 {
            color: #4b6cb7;
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
            
            .calendar-section, .info-section {
                border-right: none;
                border-bottom: 1px solid #eee;
            }
            
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="far fa-calendar-alt"></i> Kalender Interaktif</h1>
            <p class="subtitle">Klik pada tanggal untuk melihat detail dan menambahkan acara</p>
        </header>
        
        <div class="main-content">
            <section class="calendar-section">
                <div class="calendar-header">
                    <h2 class="month-year">Februari 2024</h2>
                    <div class="nav-buttons">
                        <button id="prev-month"><i class="fas fa-chevron-left"></i></button>
                        <button id="today-btn">Hari Ini</button>
                        <button id="next-month"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                
                <div class="weekdays">
                    <div>Minggu</div>
                    <div>Senin</div>
                    <div>Selasa</div>
                    <div>Rabu</div>
                    <div>Kamis</div>
                    <div>Jumat</div>
                    <div>Sabtu</div>
                </div>
                
                <div class="days-grid" id="calendar-days">
                    <!-- Hari-hari akan diisi dengan JavaScript -->
                </div>
                
                <div class="instructions">
                    <h4><i class="fas fa-info-circle"></i> Cara menggunakan:</h4>
                    <p>1. Klik pada tanggal di kalender untuk memilihnya</p>
                    <p>2. Tambahkan acara untuk tanggal yang dipilih menggunakan form di sebelah kanan</p>
                    <p>3. Gunakan tombol panah untuk berpindah bulan</p>
                </div>
            </section>
            
            <section class="info-section">
                <div class="selected-date-info">
                    <h3 class="selected-date-title">Tanggal Terpilih</h3>
                    <div class="selected-date" id="selected-date">15 Februari 2024</div>
                    <div class="selected-day" id="selected-day">Kamis</div>
                    <p>Klik tanggal lain di kalender untuk mengubah pilihan</p>
                </div>
                
                <div class="event-form">
                    <h3><i class="far fa-calendar-plus"></i> Tambah Acara untuk Tanggal Terpilih</h3>
                    <form id="add-event-form">
                        <div class="form-group">
                            <label for="event-title">Judul Acara</label>
                            <input type="text" id="event-title" placeholder="Masukkan judul acara" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="event-description">Deskripsi Acara</label>
                            <textarea id="event-description" placeholder="Masukkan deskripsi acara"></textarea>
                        </div>
                        
                        <button type="submit" class="btn"><i class="far fa-save"></i> Simpan Acara</button>
                    </form>
                </div>
                
                <div class="events-list">
                    <h3><i class="far fa-list-alt"></i> Acara untuk Tanggal Terpilih</h3>
                    <div id="events-container">
                        <p class="no-events">Belum ada acara untuk tanggal ini. Tambahkan acara menggunakan form di atas.</p>
                    </div>
                </div>
            </section>
        </div>
        
        <footer>
            <p>Kalender Interaktif &copy; 2024 | Dibuat dengan HTML, CSS, dan JavaScript</p>
        </footer>
    </div>

    <script>
        // Variabel global
        let currentDate = new Date();
        let selectedDate = new Date();
        let events = {};
        
        // Inisialisasi kalender
        document.addEventListener('DOMContentLoaded', function() {
            renderCalendar(currentDate);
            updateSelectedDateInfo(selectedDate);
            
            // Event listeners untuk navigasi
            document.getElementById('prev-month').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });
            
            document.getElementById('next-month').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });
            
            document.getElementById('today-btn').addEventListener('click', () => {
                currentDate = new Date();
                selectedDate = new Date();
                renderCalendar(currentDate);
                updateSelectedDateInfo(selectedDate);
                updateEventsList(selectedDate);
            });
            
            // Event listener untuk form tambah acara
            document.getElementById('add-event-form').addEventListener('submit', function(e) {
                e.preventDefault();
                addEvent();
            });
            
            // Tambahkan beberapa contoh acara
            addSampleEvents();
        });
        
        // Render kalender berdasarkan bulan dan tahun
        function renderCalendar(date) {
            const monthYear = document.querySelector('.month-year');
            const daysGrid = document.getElementById('calendar-days');
            
            // Set judul bulan dan tahun
            const monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            
            monthYear.textContent = `${monthNames[date.getMonth()]} ${date.getFullYear()}`;
            
            // Kosongkan grid hari
            daysGrid.innerHTML = '';
            
            // Dapatkan hari pertama dan terakhir bulan ini
            const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
            const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            
            // Dapatkan hari terakhir bulan sebelumnya
            const prevMonthLastDay = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
            
            // Hitung hari dari bulan sebelumnya yang perlu ditampilkan
            const firstDayIndex = firstDay.getDay(); // 0 = Minggu, 1 = Senin, dst
            
            // Tambahkan hari dari bulan sebelumnya
            for (let i = firstDayIndex; i > 0; i--) {
                const day = document.createElement('div');
                day.classList.add('day', 'other-month');
                day.textContent = prevMonthLastDay - i + 1;
                daysGrid.appendChild(day);
            }
            
            // Tambahkan hari dari bulan ini
            const today = new Date();
            const isToday = (day) => {
                return day === today.getDate() && 
                       date.getMonth() === today.getMonth() && 
                       date.getFullYear() === today.getFullYear();
            };
            
            const isSelected = (day) => {
                return day === selectedDate.getDate() && 
                       date.getMonth() === selectedDate.getMonth() && 
                       date.getFullYear() === selectedDate.getFullYear();
            };
            
            for (let i = 1; i <= lastDay.getDate(); i++) {
                const day = document.createElement('div');
                day.classList.add('day');
                day.textContent = i;
                
                // Tandai hari ini
                if (isToday(i)) {
                    day.classList.add('today');
                }
                
                // Tandai tanggal yang dipilih
                if (isSelected(i)) {
                    day.classList.add('active');
                }
                
                // Tandai tanggal yang memiliki acara
                const dateKey = getDateKey(new Date(date.getFullYear(), date.getMonth(), i));
                if (events[dateKey] && events[dateKey].length > 0) {
                    day.classList.add('has-event');
                }
                
                // Tambahkan event listener untuk klik
                day.addEventListener('click', () => {
                    selectDate(new Date(date.getFullYear(), date.getMonth(), i));
                });
                
                daysGrid.appendChild(day);
            }
            
            // Tambahkan hari dari bulan berikutnya jika diperlukan
            const totalCells = 42; // 6 minggu * 7 hari
            const nextMonthDays = totalCells - (firstDayIndex + lastDay.getDate());
            
            for (let i = 1; i <= nextMonthDays; i++) {
                const day = document.createElement('div');
                day.classList.add('day', 'other-month');
                day.textContent = i;
                daysGrid.appendChild(day);
            }
        }
        
        // Pilih tanggal
        function selectDate(date) {
            selectedDate = date;
            renderCalendar(currentDate);
            updateSelectedDateInfo(date);
            updateEventsList(date);
        }
        
        // Perbarui informasi tanggal terpilih
        function updateSelectedDateInfo(date) {
            const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            
            document.getElementById('selected-date').textContent = 
                `${date.getDate()} ${monthNames[date.getMonth()]} ${date.getFullYear()}`;
            
            document.getElementById('selected-day').textContent = 
                `${dayNames[date.getDay()]}`;
        }
        
        // Tambah acara
        function addEvent() {
            const titleInput = document.getElementById('event-title');
            const descInput = document.getElementById('event-description');
            
            const title = titleInput.value.trim();
            const description = descInput.value.trim();
            
            if (!title) {
                alert('Judul acara harus diisi!');
                return;
            }
            
            const dateKey = getDateKey(selectedDate);
            
            if (!events[dateKey]) {
                events[dateKey] = [];
            }
            
            const event = {
                id: Date.now(),
                title: title,
                description: description,
                date: new Date(selectedDate)
            };
            
            events[dateKey].push(event);
            
            // Reset form
            titleInput.value = '';
            descInput.value = '';
            
            // Perbarui tampilan
            renderCalendar(currentDate);
            updateEventsList(selectedDate);
            
            // Tampilkan konfirmasi
            alert(`Acara "${title}" berhasil ditambahkan!`);
        }
        
        // Hapus acara
        function deleteEvent(eventId, date) {
            const dateKey = getDateKey(date);
            
            if (events[dateKey]) {
                events[dateKey] = events[dateKey].filter(event => event.id !== eventId);
                
                // Perbarui tampilan
                renderCalendar(currentDate);
                updateEventsList(date);
            }
        }
        
        // Perbarui daftar acara untuk tanggal terpilih
        function updateEventsList(date) {
            const eventsContainer = document.getElementById('events-container');
            const dateKey = getDateKey(date);
            
            if (!events[dateKey] || events[dateKey].length === 0) {
                eventsContainer.innerHTML = '<p class="no-events">Belum ada acara untuk tanggal ini. Tambahkan acara menggunakan form di atas.</p>';
                return;
            }
            
            let eventsHTML = '';
            
            events[dateKey].forEach(event => {
                eventsHTML += `
                    <div class="event-item">
                        <div class="event-title">${event.title}</div>
                        <div class="event-desc">${event.description || 'Tidak ada deskripsi'}</div>
                        <button class="btn btn-delete" onclick="deleteEvent(${event.id}, new Date(${date.getTime()}))">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    </div>
                `;
            });
            
            eventsContainer.innerHTML = eventsHTML;
        }
        
        // Tambahkan beberapa contoh acara
        function addSampleEvents() {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);
            
            const nextWeek = new Date(today);
            nextWeek.setDate(today.getDate() + 7);
            
            // Acara untuk hari ini
            const todayKey = getDateKey(today);
            events[todayKey] = [
                {
                    id: 1,
                    title: "Rapat Tim Proyek",
                    description: "Rapat membahas kemajuan proyek aplikasi baru",
                    date: today
                },
                {
                    id: 2,
                    title: "Kunjungan Klien",
                    description: "Presentasi produk baru kepada klien",
                    date: today
                }
            ];
            
            // Acara untuk besok
            const tomorrowKey = getDateKey(tomorrow);
            events[tomorrowKey] = [
                {
                    id: 3,
                    title: "Presentasi Investor",
                    description: "Presentasi laporan kuartal kepada investor",
                    date: tomorrow
                }
            ];
            
            // Acara untuk minggu depan
            const nextWeekKey = getDateKey(nextWeek);
            events[nextWeekKey] = [
                {
                    id: 4,
                    title: "Workshop Teknologi",
                    description: "Workshop tentang pengembangan web modern",
                    date: nextWeek
                }
            ];
        }
        
        // Helper function untuk mendapatkan key tanggal dalam format YYYY-MM-DD
        function getDateKey(date) {
            return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
        }
    </script>
</body>
</html>