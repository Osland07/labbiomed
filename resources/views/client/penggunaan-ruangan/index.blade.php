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
    </style>

    <form id="penggunaanRuanganForm" class="bg-white p-4 rounded shadow mb-4 pb-4" method="POST"
        action="{{ route('client.penggunaan-ruangan.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Ruangan -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Ruangan<span class="text-danger">*</span></label>
            <select name="ruangan_id" class="form-select" required>
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
                        placeholder="{{ now()->format('Y-m-d H:i') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Waktu Selesai<span class="text-danger">*</span></label>
                    <input type="text" id="end_time" name="waktu_selesai" class="form-control" required
                        placeholder="{{ now()->addHours(2)->format('Y-m-d H:i') }}" disabled>
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
            const startPicker = flatpickr("#start_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minDate: "today",
                onChange: function(selectedDates, dateStr) {
                    startTime = dateStr;
                    if (endPicker) {
                        endPicker.set('minDate', dateStr);
                    }
                    // Enable end time field only when start time is selected
                    const endTimeField = document.getElementById('end_time');
                    endTimeField.disabled = !dateStr;
                    if (dateStr) {
                        endTimeField.classList.remove('disabled');
                    } else {
                        endTimeField.classList.add('disabled');
                    }
                    validateTimes();
                }
            });

            endPicker = flatpickr("#end_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minDate: "today",
                onChange: function(selectedDates, dateStr) {
                    endTime = dateStr;
                    validateTimes();
                }
            });
            
            // Add event listener for manual input on start time
            document.getElementById('start_time').addEventListener('input', function() {
                const dateStr = this.value;
                startTime = dateStr;
                const endTimeField = document.getElementById('end_time');
                endTimeField.disabled = !dateStr;
                if (dateStr) {
                    endTimeField.classList.remove('disabled');
                    console.log('End time field enabled');
                } else {
                    endTimeField.classList.add('disabled');
                    console.log('End time field disabled');
                }
                validateTimes();
            });
            

            // AJAX: Update jadwal booking saat ruangan dipilih
            const ruanganSelect = document.querySelector('select[name=ruangan_id]');
            ruanganSelect.addEventListener('change', function() {
                const ruanganId = this.value;
                const ruanganName = this.options[this.selectedIndex].text;
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
                                let mulai = new Date(item.waktu_mulai.split('-').reverse().join('-'));
                                let selesai = new Date(item.waktu_selesai.split('-').reverse().join('-'));
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
            });
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
    </script>

</x-admin-layout>
