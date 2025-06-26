<x-admin-layout>
    <x-slot name="title">
        Pengajuan Peminjaman
    </x-slot>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        .ts-wrapper.single .ts-control {
            @apply px-4 py-2 rounded border border-gray-300 text-sm;
            min-height: 2.5rem;
        }
    </style>

    @include('components.alert')

    <form class="bg-white p-8 rounded shadow mb-5" method="POST"
        action="{{ route('client.pengajuan-peminjaman.store') }}">
        @csrf

        <div x-data="pengajuanAlat()" x-init="() => initTomSelect(compactedAlat)">
            <input type="hidden" name="jenis" x-model="jenis" value="pribadi">

            <!-- Keperluan -->
            <div class="mb-6" x-data="{
                selectedKeperluan: '',
                customKeperluan: ''
            }">
                <label class="block font-semibold mb-2">Keperluan<span class="text-red-600">*</span></label>

                <select x-model="selectedKeperluan" class="w-full border border-gray-300 px-4 py-2 rounded mb-2"
                    required>
                    <option value="" disabled selected>Pilih keperluan</option>
                    <option value="Tugas Akhir">Tugas Akhir</option>
                    <option value="Tugas Mata Kuliah">Tugas Mata Kuliah</option>
                    <option value="lainnya">Lainnya</option>
                </select>

                <!-- Muncul hanya jika "Lainnya" dipilih -->
                <input x-show="selectedKeperluan === 'lainnya'" x-model="customKeperluan" type="text"
                    class="w-full border border-gray-300 px-4 py-2 rounded" placeholder="Masukkan keperluan lainnya"
                    :required="selectedKeperluan === 'lainnya'">

                <!-- Hidden input untuk dikirim ke server -->
                <input type="hidden" name="tujuan_peminjaman"
                    :value="selectedKeperluan === 'lainnya' ? customKeperluan : selectedKeperluan">
            </div>

            <!-- Durasi -->
            <div class="mb-6">
                <label class="block font-semibold mb-2">Durasi Kegiatan<span class="text-red-600">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Tanggal Mulai<span class="text-red-600">*</span></label>
                        <input type="date" name="tgl_peminjaman" x-model="tanggalPeminjaman" :min="hariIni"
                            class="w-full border border-gray-300 px-4 py-2 rounded" required
                            value="{{ old('tgl_peminjaman') }}">
                    </div>
                    <div>
                        <label class="block mb-1">Tanggal Selesai<span class="text-red-600">*</span></label>
                        <input type="date" name="tgl_pengembalian" x-model="tanggalPengembalian"
                            :min="tanggalPeminjaman" :disabled="!tanggalPeminjaman"
                            class="w-full border border-gray-300 px-4 py-2 rounded" required
                            value="{{ old('tgl_pengembalian') }}">
                    </div>
                </div>
            </div>

            <!-- Judul Penelitian / Kegiatan -->
            <div class="mb-6">
                <label class="block font-semibold mb-2">Judul Penelitian/Kegiatan<span
                        class="text-red-600">*</span></label>
                <input name="judul_penelitian" type="text" required
                    class="w-full border border-gray-300 px-4 py-2 rounded" placeholder="Judul Penelitian/Kegiatan"
                    value="{{ old('judul_penelitian') }}">
            </div>

            @if (!auth()->user()->hasRole('Dosen'))
                <!-- Dosen Pembimbing -->
                <div class="mb-6">
                    <label class="block font-semibold mb-2">Dosen Pembimbing<span class="text-red-600">*</span></label>
                    <select name="dosen_pembimbing" class="border border-gray-300 px-4 py-2 rounded w-full" required>
                        <option value="{{ old('dosen_pembimbing') }}" disabled selected>Pilih Dosen</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Tambah Alat -->
            <div class="mb-6">
                <label class="block font-semibold mb-2">Tambah Alat Yang Dipinjam<span
                        class="text-red-600">*</span></label>
                <div class="flex items-center gap-2 mb-2">
                    <select id="alatDropdown" x-model="selectedBaseName" class="w-1/2" required>
                        <option value="" disabled selected></option>
                    </select>
                    <input x-model.number="selectedQty" type="number" min="1"
                        :max="selectedBaseName ? compactedAlat[selectedBaseName]?.length : 1"
                        class="border border-gray-300 px-4 py-2 rounded w-20" :disabled="!selectedBaseName">
                    <button type="button" @click="addAlat()" class="bg-blue-600 text-white px-4 py-2 rounded"
                        :disabled="!selectedBaseName || !selectedQty || selectedQty < 1 || selectedQty > (compactedAlat[
                            selectedBaseName]?.length || 0)">+</button>
                </div>
                <ul class="list-decimal pl-5 space-y-1">
                    <template x-for="(item, index) in daftarAlat" :key="item.baseName">
                        <li>
                            <span x-text="item.baseName + ' (Qty: ' + item.ids.length + ')'"></span>
                            <button type="button" @click="removeAlat(item.baseName)"
                                class="text-red-600 ml-2">Hapus</button>
                        </li>
                    </template>
                </ul>
            </div>

            <!-- Hidden Inputs -->
            <input type="hidden" name="daftar_anggota" :value="JSON.stringify(daftarAnggota)">
            <input type="hidden" name="daftar_alat" :value="JSON.stringify(flatAlatIds)">

            <!-- Submit -->
            <button type="submit"
                @click.prevent="
                    if (flatAlatIds.length === 0) {
                        alert('Silakan tambahkan minimal satu alat sebelum submit.');
                        return;
                    }
                    $el.form.daftar_anggota.value = JSON.stringify(daftarAnggota);
                    $el.form.daftar_alat.value = JSON.stringify(flatAlatIds);
                    $el.form.submit();
                "
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">SUBMIT</button>
        </div>
    </form>
</x-admin-layout>

<script>
    function pengajuanAlat() {
        const allAlats = @json($alats);
        let compacted = {};
        allAlats.forEach(alat => {
            const baseName = alat.name.replace(/\s+#\d+$/, '');
            if (!compacted[baseName]) compacted[baseName] = [];
            compacted[baseName].push(alat);
        });

        return {
            jenis: 'pribadi',
            anggota: '',
            daftarAnggota: [],
            selectedBaseName: '',
            selectedQty: 1,
            compactedAlat: compacted,
            daftarAlat: [],
            tanggalPeminjaman: '',
            tanggalPengembalian: '',
            hariIni: new Date().toISOString().split('T')[0],

            get flatAlatIds() {
                return this.daftarAlat.flatMap(item => item.ids);
            },

            addAlat() {
                if (!this.selectedBaseName || !this.selectedQty) return;
                const available = this.compactedAlat[this.selectedBaseName] || [];
                const alreadySelected = this.flatAlatIds;
                const filtered = available.filter(a => !alreadySelected.includes(a.id));
                if (filtered.length < this.selectedQty) return;
                const ids = filtered.slice(0, this.selectedQty).map(a => a.id);
                this.daftarAlat.push({
                    baseName: this.selectedBaseName,
                    ids
                });
                this.selectedBaseName = '';
                this.selectedQty = 1;
                if (window.alatSelect) alatSelect.clear(); // clear dropdown selection
            },

            removeAlat(baseName) {
                this.daftarAlat = this.daftarAlat.filter(item => item.baseName !== baseName);
            },

            initTomSelect(alatGrouped) {
                const alatDropdown = document.getElementById('alatDropdown');
                // Kosongkan dulu
                alatDropdown.innerHTML = '<option value=""></option>';
                for (const baseName in alatGrouped) {
                    const option = document.createElement('option');
                    option.value = baseName;
                    option.textContent = `${baseName} (${alatGrouped[baseName].length} tersedia)`;
                    alatDropdown.appendChild(option);
                }

                window.alatSelect = new TomSelect('#alatDropdown', {
                    placeholder: 'Cari alat...',
                    allowEmptyOption: true,
                    maxOptions: 100,
                });
            }
        }
    }
</script>
