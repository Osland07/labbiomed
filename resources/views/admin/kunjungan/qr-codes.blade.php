<x-admin-table>
    <!-- Title -->
    <x-slot name="title">
        QR Code Kunjungan Laboratorium
    </x-slot>

    @include('components.alert')
    
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">QR Code Kunjungan Laboratorium</h1>
                <p class="text-muted">Generate dan cetak QR code untuk check-in/check-out per ruangan</p>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.kunjungan.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali ke Daftar
                </a>
                <button class="btn btn-primary" onclick="printAllQRCodes()">
                    <i class="fas fa-print mr-1"></i>Cetak Semua
                </button>
            </div>
        </div>

        <!-- Instructions Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle mr-2"></i>Petunjuk Penggunaan QR Code
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-success">
                                    <i class="fas fa-sign-in-alt mr-1"></i>Check-in (Hijau)
                                </h6>
                                <ul class="mb-0">
                                    <li>Tempel di pintu masuk ruangan</li>
                                    <li>Pengunjung scan untuk mendaftar masuk</li>
                                    <li>Isi form data kunjungan</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-danger">
                                    <i class="fas fa-sign-out-alt mr-1"></i>Check-out (Merah)
                                </h6>
                                <ul class="mb-0">
                                    <li>Tempel di pintu keluar ruangan</li>
                                    <li>Pengunjung scan untuk mendaftar keluar</li>
                                    <li>Konfirmasi keluar dari ruangan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Codes Grid -->
        <div class="row">
            @foreach($ruangans as $ruangan)
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-building mr-2"></i>{{ $ruangan->name }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Check-in QR Code -->
                                <div class="col-md-6 text-center">
                                    <div class="qr-section mb-3">
                                        <h6 class="text-success mb-3">
                                            <i class="fas fa-sign-in-alt mr-1"></i>Check-in
                                        </h6>
                                        <div class="qr-code-container mb-3">
                                            <img src="http://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('kunjungan.checkin', $ruangan->id)) }}&size=300x300" 
                                                 alt="QR Code Check-in {{ $ruangan->name }}"
                                                 class="img-fluid border rounded shadow-sm"
                                                 style="max-width: 180px;">
                                        </div>
                                        <div class="btn-group-vertical btn-group-sm w-100" role="group">
                                            <button type="button" class="btn btn-outline-success mb-1" 
                                                    onclick="downloadQRCode('http://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('kunjungan.checkin', $ruangan->id)) }}&size=300x300', 'checkin-{{ $ruangan->name }}.png')">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </button>
                                            <button type="button" class="btn btn-outline-primary" 
                                                    onclick="printQRCode('http://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('kunjungan.checkin', $ruangan->id)) }}&size=300x300', 'Check-in {{ $ruangan->name }}')">
                                                <i class="fas fa-print mr-1"></i>Cetak
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Check-out QR Code -->
                                <div class="col-md-6 text-center">
                                    <div class="qr-section">
                                        <h6 class="text-danger mb-3">
                                            <i class="fas fa-sign-out-alt mr-1"></i>Check-out
                                        </h6>
                                        <div class="qr-code-container mb-3">
                                            <img src="http://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('kunjungan.checkout', $ruangan->id)) }}&size=300x300" 
                                                 alt="QR Code Check-out {{ $ruangan->name }}"
                                                 class="img-fluid border rounded shadow-sm"
                                                 style="max-width: 180px;">
                                        </div>
                                        <div class="btn-group-vertical btn-group-sm w-100" role="group">
                                            <button type="button" class="btn btn-outline-danger mb-1" 
                                                    onclick="downloadQRCode('http://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('kunjungan.checkout', $ruangan->id)) }}&size=300x300', 'checkout-{{ $ruangan->name }}.png')">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </button>
                                            <button type="button" class="btn btn-outline-primary" 
                                                    onclick="printQRCode('http://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('kunjungan.checkout', $ruangan->id)) }}&size=300x300', 'Check-out {{ $ruangan->name }}')">
                                                <i class="fas fa-print mr-1"></i>Cetak
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Scan QR code untuk akses langsung ke halaman check-in/check-out
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-rocket mr-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <button class="btn btn-outline-success btn-lg mb-2" onclick="printAllQRCodes()">
                                    <i class="fas fa-print mr-2"></i>Cetak Semua QR Code
                                </button>
                                <p class="text-muted small">Cetak semua QR code untuk semua ruangan sekaligus</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <button class="btn btn-outline-primary btn-lg mb-2" onclick="downloadAllQRCodes()">
                                    <i class="fas fa-download mr-2"></i>Download Semua
                                </button>
                                <p class="text-muted small">Download semua QR code dalam format ZIP</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <a href="{{ route('admin.kunjungan.index') }}" class="btn btn-outline-secondary btn-lg mb-2">
                                    <i class="fas fa-list mr-2"></i>Lihat Data Kunjungan
                                </a>
                                <p class="text-muted small">Kembali ke halaman daftar kunjungan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .btn, .card-footer, .card-header, .quick-actions {
                display: none !important;
            }
            .card {
                border: 1px solid #000 !important;
                break-inside: avoid;
                margin-bottom: 20px;
            }
            .qr-code-container img {
                max-width: 150px !important;
            }
            .qr-section {
                page-break-inside: avoid;
            }
        }
        
        .qr-code-container {
            display: inline-block;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px solid #e9ecef;
        }
        
        .qr-section {
            padding: 10px;
            border-radius: 8px;
            background: #f8f9fa;
        }
    </style>

    <script>
        function downloadQRCode(url, filename) {
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            alert('QR code berhasil diunduh!');
        }

        function printQRCode(url, title) {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${title}</title>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            text-align: center; 
                            padding: 20px;
                            margin: 0;
                        }
                        .qr-container {
                            display: inline-block;
                            padding: 20px;
                            border: 2px solid #333;
                            border-radius: 10px;
                            margin: 20px;
                        }
                        .qr-title {
                            font-size: 18px;
                            font-weight: bold;
                            margin-bottom: 15px;
                        }
                        .qr-image {
                            max-width: 200px;
                            height: auto;
                        }
                    </style>
                </head>
                <body>
                    <div class="qr-container">
                        <div class="qr-title">${title}</div>
                        <img src="${url}" alt="${title}" class="qr-image">
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }

        function printAllQRCodes() {
            window.print();
        }

        function downloadAllQRCodes() {
            // Get all QR code images
            const qrImages = document.querySelectorAll('.qr-code-container img');
            const urls = [];
            const filenames = [];
            
            qrImages.forEach((img, index) => {
                urls.push(img.src);
                const alt = img.alt || `qr-code-${index + 1}`;
                filenames.push(alt.replace(/\s+/g, '-').toLowerCase() + '.png');
            });
            
            // Download each QR code
            urls.forEach((url, index) => {
                setTimeout(() => {
                    downloadQRCode(url, filenames[index]);
                }, index * 500); // Delay each download by 500ms
            });
            
            alert(`Memulai download ${urls.length} QR code...`);
        }
    </script>
</x-admin-table> 