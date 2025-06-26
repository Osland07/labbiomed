<x-admin-layout>

    <!-- Title -->
    <x-slot name="title">
        Penggunaan Ruangan
    </x-slot>

    @include('components.alert')

    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Flatpickr Time Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.css">

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
                        placeholder="2020-01-01 00:00">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Waktu Selesai<span class="text-danger">*</span></label>
                    <input type="text" id="end_time" name="waktu_selesai" class="form-control" required
                        placeholder="2020-01-01 02:00" disabled>
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
                    document.getElementById('end_time').disabled = !dateStr;
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
