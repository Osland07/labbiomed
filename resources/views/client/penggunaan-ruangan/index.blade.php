<x-admin-layout>

    <!-- Title -->
    <x-slot name="title">
        Penggunaan Ruangan
    </x-slot>

    @include('components.alert')

    <!-- Petunjuk Penggunaan Ruangan vs Kunjungan -->
    <div class="alert alert-info mb-4">
        <strong>Penggunaan Ruangan:</strong> Ajukan penggunaan ruangan untuk kegiatan seperti rapat, praktikum, atau seminar. Pilih ruangan dan waktu yang diinginkan. Ruangan tidak dapat digunakan oleh orang lain pada waktu yang sama.<br>
        <strong>Kunjungan:</strong> Lakukan kunjungan ke ruangan/lab tanpa harus membooking ruangan. Cocok untuk keperluan singkat, monitoring, atau tamu.
    </div>

    <!-- TomSelect CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Flatpickr Time Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.css">
    
    <style>
        .form-control:disabled {
            background-color: #e9ecef;
            opacity: 0.65;
            cursor: not-allowed;
        }
        .form-control:not(:disabled) {
            background-color: #fff;
            opacity: 1;
            cursor: text;
        }
        
        .ts-wrapper.single .ts-control {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            font-size: 0.875rem;
            min-height: 2.5rem;
        }
        .ts-wrapper.single .ts-control.focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }
        
        /* Mobile-specific styles */
        @media (max-width: 768px) {
            .form-control:disabled {
                background-color: #f8f9fa;
                opacity: 0.5;
                border-color: #dee2e6;
            }
            .form-control:not(:disabled) {
                background-color: #fff;
                opacity: 1;
                border-color: #80bdff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }
        }
    </style>

    <form id="penggunaanRuanganForm" class="bg-white p-4 rounded shadow mb-4 pb-4" method="POST"
        action="{{ route('client.penggunaan-ruangan.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Ruangan -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Ruangan<span class="text-danger">*</span></label>
            <select name="ruangan_id" class="form-select" id="ruangan_id_pengajuan" required>
                <option value="" disabled selected>Pilih Ruangan</option>
                @foreach ($ruangans as $ruangan)
                    <option value="{{ $ruangan->id }}">{{ $ruangan->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Durasi Kegiatan -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Durasi Kegiatan<span class="text-danger">*</span></label>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Waktu Mulai<span class="text-danger">*</span></label>
                    <input type="text" id="start_time" name="waktu_mulai" class="form-control" required
                        placeholder="Pilih waktu mulai">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Waktu Selesai<span class="text-danger">*</span></label>
                    <input type="text" id="end_time" name="waktu_selesai" class="form-control" required
                        placeholder="Pilih waktu selesai">
                    <div id="errorMessage" class="invalid-feedback d-block" style="display:none;"></div>
                </div>
            </div>
        </div>

        <!-- Tujuan Penggunaan -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Tujuan Penggunaan<span class="text-danger">*</span></label>
            <input name="tujuan_penggunaan" type="text" class="form-control" required
                value="{{ old('tujuan_penggunaan') }}" placeholder="Tujuan Penggunaan">
        </div>

        <!-- Surat -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Surat (Opsional)</label>
            <input name="penanggung_jawab" type="file" accept="application/pdf" class="form-control">
        </div>

        <!-- Submit -->
        <div class="text-center">
            <button class="btn btn-primary px-5 py-2" type="submit">SUBMIT</button>
        </div>
    </form>

    <!-- Jadwal Booking Ruangan -->
    <div class="card mt-4">
        <div class="card-header">
            <strong id="judul-jadwal-booking">Jadwal Booking Semua Ruangan</strong>
        </div>
        <div class="card-body">
            <div id="jadwal-booking-container">
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Ruangan</th>
                            <th>Tujuan</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="jadwal-booking-body">
                        @if(isset($jadwalBooking) && count($jadwalBooking) > 0)
                            @foreach($jadwalBooking as $booking)
                                <tr>
                                    <td>{{ $booking->user->name ?? '-' }}</td>
                                    <td>{{ $booking->ruangan->name ?? '-' }}</td>
                                    <td>{{ $booking->tujuan_penggunaan ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d-m-Y H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('d-m-Y H:i') }}</td>
                                    <td>
                                        @php
                                            $now = now();
                                            $keterangan = '';
                                            $badge = '';
                                            if ($now->between($booking->waktu_mulai, $booking->waktu_selesai)) {
                                                $keterangan = 'Sedang Berlangsung';
                                                $badge = '<span class="badge bg-success">Sedang Berlangsung</span>';
                                            } elseif ($booking->waktu_mulai > $now) {
                                                $keterangan = 'Akan Datang';
                                                $badge = '<span class="badge bg-primary">Akan Datang</span>';
                                            }
                                        @endphp
                                        {!! $badge !!}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="6" class="text-center">Belum ada booking aktif untuk ruangan ini.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let startTime = '';
        let endTime = '';
        let endPicker = null;
        let errorMessage = '';

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize TomSelect for ruangan dropdown
            new TomSelect('#ruangan_id_pengajuan', {
                placeholder: 'Cari ruangan...',
                allowEmptyOption: true,
                maxOptions: 100,
                onChange: function(value) {
                    // Update jadwal booking when ruangan is selected
                    if (value) {
                        const ruanganId = value;
                        const ruanganName = this.options[value]?.text || '';
                        document.getElementById('judul-jadwal-booking').innerHTML = `Jadwal Booking Ruangan "${ruanganName}"`;
                        fetch(`/ajax/jadwal-booking-ruangan/${ruanganId}`)
                            .then(response => response.json())
                            .then(data => {
                                const tbody = document.getElementById('jadwal-booking-body');
                                tbody.innerHTML = '';
                                if (data.length === 0) {
                                    tbody.innerHTML = '<tr><td colspan="6" class="text-center">Belum ada booking aktif untuk ruangan ini.</td></tr>';
                                } else {
                                    data.forEach(item => {
                                        let now = new Date();
                                        // Parse tanggal dengan format yang benar (d-m-Y H:i)
                                        let mulaiParts = item.waktu_mulai.split(' ');
                                        let selesaiParts = item.waktu_selesai.split(' ');
                                        
                                        let mulaiDate = mulaiParts[0].split('-');
                                        let selesaiDate = selesaiParts[0].split('-');
                                        
                                        // Format: d-m-Y H:i -> Y-m-d H:i
                                        let mulai = new Date(mulaiDate[2] + '-' + mulaiDate[1] + '-' + mulaiDate[0] + ' ' + mulaiParts[1]);
                                        let selesai = new Date(selesaiDate[2] + '-' + selesaiDate[1] + '-' + selesaiDate[0] + ' ' + selesaiParts[1]);
                                        
                                        let keterangan = '';
                                        let badge = '';
                                        
                                        
                                        if (now >= mulai && now <= selesai) {
                                            keterangan = 'Sedang Berlangsung';
                                            badge = '<span class="badge bg-success">Sedang Berlangsung</span>';
                                        } else if (mulai > now) {
                                            keterangan = 'Akan Datang';
                                            badge = '<span class="badge bg-primary">Akan Datang</span>';
                                        }
                                        
                                        const row = `<tr>
                                            <td>${item.nama}</td>
                                            <td>${ruanganName}</td>
                                            <td>${item.tujuan}</td>
                                            <td>${item.waktu_mulai}</td>
                                            <td>${item.waktu_selesai}</td>
                                            <td>${badge}</td>
                                        </tr>`;
                                        tbody.innerHTML += row;
                                    });
                                }
                            });
                    }
                }
            });

            // Check if device is mobile
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            
            const startPicker = flatpickr("#start_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minDate: "today",
                // Mobile-specific settings
                disableMobile: false, // Enable native mobile picker
                allowInput: true, // Allow manual input
                onChange: function(selectedDates, dateStr) {
                    startTime = dateStr;
                    if (endPicker) {
                        endPicker.set('minDate', dateStr);
                        // Don't auto-fill end time, just set the minimum date
                    }
                    validateTimes();
                },
                onClose: function(selectedDates, dateStr) {
                    // Update min date for end picker when start time is selected
                    if (dateStr && dateStr.trim() !== '') {
                        startTime = dateStr;
                        if (endPicker) {
                            endPicker.set('minDate', dateStr);
                        }
                        validateTimes();
                    }
                }
            });

            endPicker = flatpickr("#end_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minDate: "today",
                allowInput: true, // Allow manual input
                disableMobile: false, // Enable native mobile picker
                onChange: function(selectedDates, dateStr) {
                    endTime = dateStr;
                    validateTimes();
                },
                onClose: function(selectedDates, dateStr) {
                    // Only update if user actually selected a date
                    if (dateStr && dateStr.trim() !== '') {
                        endTime = dateStr;
                        validateTimes();
                    }
                }
            });
            
            // Simple event listener for manual input
            document.getElementById('start_time').addEventListener('input', function() {
                const dateStr = this.value;
                startTime = dateStr;
                if (endPicker && dateStr && dateStr.trim() !== '') {
                    endPicker.set('minDate', dateStr);
                }
                validateTimes();
            });

            // Ensure end time field is empty on page load
            document.getElementById('end_time').value = '';
        });

        function validateTimes() {
            const errorDiv = document.getElementById('errorMessage');
            const endInput = document.getElementById('end_time');
            if (startTime && endTime && endTime < startTime) {
                errorDiv.textContent = "Waktu selesai tidak boleh lebih awal dari waktu mulai.";
                errorDiv.style.display = 'block';
                endInput.value = '';
                endTime = '';
            } else {
                errorDiv.textContent = '';
                errorDiv.style.display = 'none';
            }
        }
        
        // Add form validation before submit
        document.getElementById('penggunaanRuanganForm').addEventListener('submit', function(e) {
            const startTimeValue = document.getElementById('start_time').value;
            const endTimeValue = document.getElementById('end_time').value;
            
            if (!startTimeValue || !endTimeValue) {
                e.preventDefault();
                alert('Mohon isi waktu mulai dan waktu selesai');
                return false;
            }
            
            if (endTimeValue < startTimeValue) {
                e.preventDefault();
                alert('Waktu selesai tidak boleh lebih awal dari waktu mulai');
                return false;
            }
        });
    </script>

</x-admin-layout>
