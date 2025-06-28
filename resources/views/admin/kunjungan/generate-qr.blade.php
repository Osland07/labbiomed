<x-admin-table>
    <!-- Title -->
    <x-slot name="title">
        QR Code Kunjungan Laboratorium
    </x-slot>

    @include('components.alert')
    
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-end align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
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
        <div class="row g-3">
            @foreach($ruangans as $ruangan)
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-building mr-2"></i>{{ $ruangan->name }}
                            </h5>
                        </div>
                        <div class="card-body py-3 px-2">
                            <div class="row g-2">
                                <!-- Check-in QR Code -->
                                <div class="col-6 text-center">
                                    <div class="qr-section mb-2 p-1">
                                        <div class="print-judul text-success mb-1" style="font-size: 15px; font-weight: bold;">Check-in</div>
                                        <div class="print-nama-lab mb-1" style="font-size: 13px;">{{ $ruangan->name }}</div>
                                        <div class="qr-code-container mb-2" style="display:inline-block; padding:8px; background:#f6fdf9; border-radius:12px; border:2px solid #198754; box-shadow:0 2px 8px #19875422;">
                                            <img src="http://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('kunjungan.checkin', $ruangan->id)) }}&size=180x180" 
                                                 alt="QR Code Check-in {{ $ruangan->name }}"
                                                 class="img-fluid"
                                                 style="max-width: 80px; border-radius:8px;">
                                        </div>
                                        <button type="button" class="btn btn-outline-success btn-sm mb-1" 
                                                onclick="downloadQRCode('http://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('kunjungan.checkin', $ruangan->id)) }}&size=300x300', 'checkin-{{ $ruangan->name }}.png')">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                onclick="printQRCode('http://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('kunjungan.checkin', $ruangan->id)) }}&size=300x300', 'Check-in', '{{ $ruangan->name }}')">
                                            <i class="fas fa-print mr-1"></i>Cetak
                                        </button>
                                    </div>
                                </div>
                                <!-- Check-out QR Code -->
                                <div class="col-6 text-center">
                                    <div class="qr-section mb-2 p-1">
                                        <div class="print-judul text-danger mb-1" style="font-size: 15px; font-weight: bold;">Check-out</div>
                                        <div class="print-nama-lab mb-1" style="font-size: 13px;">{{ $ruangan->name }}</div>
                                        <div class="qr-code-container mb-2" style="display:inline-block; padding:8px; background:#fdf6f6; border-radius:12px; border:2px solid #dc3545; box-shadow:0 2px 8px #dc354522;">
                                            <img src="http://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('kunjungan.checkout', $ruangan->id)) }}&size=180x180" 
                                                 alt="QR Code Check-out {{ $ruangan->name }}"
                                                 class="img-fluid"
                                                 style="max-width: 80px; border-radius:8px;">
                                        </div>
                                        <button type="button" class="btn btn-outline-danger btn-sm mb-1" 
                                                onclick="downloadQRCode('http://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('kunjungan.checkout', $ruangan->id)) }}&size=300x300', 'checkout-{{ $ruangan->name }}.png')">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                onclick="printQRCode('http://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(route('kunjungan.checkout', $ruangan->id)) }}&size=300x300', 'Check-out', '{{ $ruangan->name }}')">
                                            <i class="fas fa-print mr-1"></i>Cetak
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light py-2 px-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Scan QR code untuk akses langsung ke halaman check-in/check-out
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tambahkan SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @media print {
            body {
                background: #fff !important;
            }
            .sidebar, .navbar, .btn, .card-footer, .card-header, .quick-actions, .alert, .swal2-container {
                display: none !important;
            }
            .container-fluid {
                margin: 0;
                padding: 0;
            }
            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 20px;
            }
            .print-header img {
                max-height: 60px;
                margin-bottom: 10px;
            }
            .card {
                border: 1px solid #000 !important;
                break-inside: avoid;
                margin-bottom: 30px;
                box-shadow: none !important;
            }
            .qr-code-container img {
                max-width: 150px !important;
            }
            .qr-section {
                page-break-inside: avoid;
            }
            .print-title {
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 10px;
            }
            .print-ruangan {
                font-size: 16px;
                margin-bottom: 5px;
            }
        }
        .print-header {
            display: none;
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
        .qr-section {
            background: #f8f9fa;
            border-radius: 6px;
            min-height: 170px;
        }
        .qr-code-container img {
            max-width: 80px;
            height: auto;
        }
        @media (max-width: 991.98px) {
            .col-lg-3 { flex: 0 0 50%; max-width: 50%; }
        }
        @media (max-width: 767.98px) {
            .col-md-4, .col-lg-3 { flex: 0 0 100%; max-width: 100%; }
        }
    </style>

    <!-- Header untuk print (hanya muncul saat print) -->
    <div class="print-header">
        <img src="/assets/logo.jpg" alt="Logo Lab" />
        <div class="print-title">QR Code Kunjungan Laboratorium</div>
        <div class="print-ruangan">Fakultas/Laboratorium Biomedik</div>
        <hr style="margin: 10px 0;">
    </div>

    <script>
        function downloadQRCode(url, filename) {
            // Show loading indicator
            Swal.fire({
                icon: 'info',
                title: 'Memulai Download',
                text: 'Mengunduh QR code...',
                allowOutsideClick: false,
                showConfirmButton: false
            });

            // Method 1: Try fetch with blob
            fetch(url, {
                method: 'GET',
                mode: 'cors',
                headers: {
                    'Accept': 'image/png,image/*,*/*;q=0.8'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.blob();
            })
            .then(blob => {
                // Create download link
                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;
                link.style.display = 'none';
                
                // Append to body and trigger download
                document.body.appendChild(link);
                link.click();
                
                // Cleanup
                setTimeout(() => {
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(link.href);
                }, 100);
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'QR code berhasil diunduh!',
                    timer: 1500,
                    showConfirmButton: false
                });
            })
            .catch(error => {
                console.error('Download failed:', error);
                
                // Method 2: Fallback to direct link
                Swal.fire({
                    icon: 'warning',
                    title: 'Mencoba Metode Alternatif',
                    text: 'Menggunakan metode download alternatif...',
                    timer: 1000,
                    showConfirmButton: false
                }).then(() => {
                    // Create a temporary link and trigger download
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = filename;
                    link.target = '_blank';
                    link.style.display = 'none';
                    
                    document.body.appendChild(link);
                    link.click();
                    
                    setTimeout(() => {
                        document.body.removeChild(link);
                    }, 100);
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Download Selesai',
                        text: 'QR code telah dibuka di tab baru. Silakan simpan manual.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            });
        }

        // Shared template function for consistent design
        function generateQRPageTemplate(ruanganName, qrType, qrUrl, instructions) {
            const isCheckin = qrType === 'checkin';
            const mainColor = isCheckin ? '#198754' : '#dc3545';
            const shadowColor = isCheckin ? '#19875422' : '#dc354522';
            const scanTitle = 'Scan Disini';
            const subTitle = isCheckin
                ? 'Sebelum memasuki ruangan ini, scan kode QR untuk check-in.'
                : 'Sebelum meninggalkan ruangan ini, scan kode QR untuk check-out.';
            const afterScan = 'Setelah scan, isi form kunjungan dengan data yang benar.';
            const caraScanTitle = 'CARA SCAN QR CODE';
            const caraScanColor = mainColor;
            const langkah1 = 'Buka kamera atau aplikasi scan QR di HP Anda';
            const langkah2 = 'Scan QR code di atas';
            const langkah3 = 'Ikuti instruksi dan isi form kunjungan';
            
            return `
                <div class="page" style="box-sizing: border-box; width: 100%; max-width: 180mm; min-height: 245mm; margin: 0 auto; padding: 2mm 2mm 2mm 2mm; display: flex; flex-direction: column; justify-content: flex-start; align-items: center;">
                    <div class="print-header" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 32px;">
                        <img src="/assets/logo.jpg" alt="Logo Lab" style="max-height: 70px;">
                        <div style="text-align: right;">
                            <div style="font-size: 26px; font-weight: bold; color: #1a4c7c;">Laboratorium Teknik Biomedis</div>
                        </div>
                    </div>
                    <div style="text-align: center; margin-bottom: 18px; width: 100%;">
                        <div style="font-size: 38px; font-weight: bold; color: ${mainColor}; margin-bottom: 10px;">${scanTitle}</div>
                        <div style="font-size: 18px; color: #444; margin-bottom: 14px;">${subTitle}</div>
                    </div>
                    <div style="display: flex; justify-content: center; margin-bottom: 28px; width: 100%;">
                        <div style="display: inline-block; padding: 24px; background: #fff; border-radius: 18px; border: 5px solid ${mainColor}; box-shadow: 0 2px 16px ${shadowColor};">
                            <img src="${qrUrl}" alt="QR Code ${qrType} ${ruanganName}" style="width: 80mm; max-width: 100%; border-radius: 14px;">
                        </div>
                    </div>
                    <div style="text-align: center; font-size: 26px; font-weight: bold; color: #1a4c7c; margin-bottom: 10px;">${ruanganName}</div>
                    <div style="text-align: center; color: #444; font-size: 17px; margin-bottom: 18px;">${afterScan}</div>
                    <div style="background: #f8f9fa; border-radius: 12px; padding: 18px 24px; margin-bottom: 16px; border: 2px solid ${mainColor}; max-width: 120mm; margin-left: auto; margin-right: auto;">
                        <div style="font-weight: bold; color: ${caraScanColor}; font-size: 18px; margin-bottom: 10px;">${caraScanTitle}</div>
                        <ol style="font-size: 16px; color: #222; text-align: left; margin: 0 0 0 22px; padding: 0;">
                            <li>${langkah1}</li>
                            <li>${langkah2}</li>
                            <li>${langkah3}</li>
                        </ol>
                    </div>
                </div>
            `;
        }

        function printAllQRCodes() {
            const ruangans = @json($ruangans);
            const printWindow = window.open('', '_blank');
            
            let printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>QR Code Kunjungan Laboratorium</title>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            margin: 0; 
                            padding: 0; 
                            background: #fff;
                        }
                        .page {
                            width: 210mm;
                            height: 297mm;
                            margin: 0 auto;
                            padding: 20mm;
                            box-sizing: border-box;
                            page-break-after: always;
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                        }
                        .page:last-child {
                            page-break-after: avoid;
                        }
                    </style>
                </head>
                <body>
            `;
            
            // Generate pages in order: check-in and check-out for each room consecutively
            ruangans.forEach((ruangan, index) => {
                const checkinUrl = `http://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent('{{ url("kunjungan/checkin") }}/' + ruangan.id)}&size=300x300`;
                const checkoutUrl = `http://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent('{{ url("kunjungan/checkout") }}/' + ruangan.id)}&size=300x300`;
                
                // Check-in page for this room
                printContent += generateQRPageTemplate(
                    ruangan.name,
                    'checkin',
                    checkinUrl,
                    'Buka kamera HP → Scan QR Code → Isi form kunjungan'
                );
                
                // Check-out page for this room
                printContent += generateQRPageTemplate(
                    ruangan.name,
                    'checkout',
                    checkoutUrl,
                    'Buka kamera HP → Scan QR Code → Konfirmasi keluar'
                );
            });
            
            printContent += `
                </body>
                </html>
            `;
            
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }

        function printQRCode(url, jenis, namaLab) {
            const isCheckin = jenis === 'Check-in';
            const qrType = isCheckin ? 'checkin' : 'checkout';
            const instructions = isCheckin ? 'Buka kamera HP → Scan QR Code → Isi form kunjungan' : 'Buka kamera HP → Scan QR Code → Konfirmasi keluar';
            
            const printWindow = window.open('', '_blank');
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${jenis} - ${namaLab}</title>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            margin: 0; 
                            padding: 0; 
                            background: #fff;
                        }
                        .page {
                            width: 210mm;
                            height: 297mm;
                            margin: 0 auto;
                            padding: 20mm;
                            box-sizing: border-box;
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                        }
                    </style>
                </head>
                <body>
                    ${generateQRPageTemplate(namaLab, qrType, url, instructions)}
                </body>
                </html>
            `;
            
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }
    </script>
</x-admin-table> 