<x-admin-layout>

    <!-- Title -->
    <x-slot name="title">
        Jadwal
    </x-slot>

    <!-- Search & Navigation -->
    <x-slot name="search">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                @include('components.search')
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('jadwal', array_merge(request()->query(), ['month' => $prevMonth])) }}"
                    class="btn btn-outline-secondary btn-sm me-2">
                    <i class="fas fa-chevron-left"></i> Bulan Sebelumnya
                </a>
                <h5 class="mb-0 me-3">{{ $currentDate->translatedFormat('F Y') }}</h5>
                <a href="{{ route('jadwal', array_merge(request()->query(), ['month' => $nextMonth])) }}"
                    class="btn btn-outline-secondary btn-sm">
                    Bulan Berikutnya <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Calendar Container -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Jadwal Penggunaan Ruangan</h3>
            <div class="card-tools">
                <div class="d-flex align-items-center">
                    <span class="badge bg-success me-2">Diterima</span>
                    <span class="badge bg-warning me-2">Menunggu</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Detail Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Ruangan:</strong>
                            <p id="eventRoom"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Pemesan:</strong>
                            <p id="eventUser"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Waktu Mulai:</strong>
                            <p id="eventStartTime"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Waktu Selesai:</strong>
                            <p id="eventEndTime"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p id="eventStatus"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Tujuan:</strong>
                            <p id="eventPurpose"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
        <style>
            .fc-event {
                cursor: pointer;
                border-radius: 4px;
                padding: 2px 4px;
                font-size: 12px;
            }

            .fc-event:hover {
                opacity: 0.8;
            }

            .fc-daygrid-event {
                white-space: nowrap;
                border-radius: 3px;
            }

            .fc-toolbar-title {
                font-size: 1.2em;
            }

            .fc-button {
                background-color: #007bff;
                border-color: #007bff;
            }

            .fc-button:hover {
                background-color: #0056b3;
                border-color: #0056b3;
            }

            .fc-button:focus {
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }

            .fc-daygrid-day.fc-day-today {
                background-color: rgba(255, 193, 7, 0.1);
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'id',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    buttonText: {
                        today: 'Hari Ini',
                        month: 'Bulan',
                        week: 'Minggu',
                        day: 'Hari'
                    },
                    events: @json($calendarEvents),
                    eventClick: function(info) {
                        var event = info.event;
                        var extendedProps = event.extendedProps;

                        document.getElementById('eventRoom').textContent = event.title;
                        document.getElementById('eventUser').textContent = extendedProps.user;
                        document.getElementById('eventStartTime').textContent = extendedProps.start_time;
                        document.getElementById('eventEndTime').textContent = extendedProps.end_time;
                        document.getElementById('eventStatus').textContent = extendedProps.status;
                        document.getElementById('eventPurpose').textContent = extendedProps.purpose;

                        var modal = new bootstrap.Modal(document.getElementById('eventModal'));
                        modal.show();
                    },
                    eventDidMount: function(info) {
                        // Add tooltip
                        $(info.el).tooltip({
                            title: info.event.title + ' - ' + info.event.extendedProps.user,
                            placement: 'top',
                            trigger: 'hover',
                            container: 'body'
                        });
                    },
                    dayMaxEvents: true,
                    height: 'auto',
                    aspectRatio: 1.35,
                    firstDay: 1, // Start week on Monday
                    selectable: true,
                    selectMirror: true,
                    select: function(arg) {
                        // Handle date selection if needed
                    },
                    editable: false,
                    eventDrop: function(arg) {
                        // Handle event drop if needed
                    },
                    eventResize: function(arg) {
                        // Handle event resize if needed
                    }
                });

                calendar.render();
            });
        </script>
    @endpush

</x-admin-layout>
