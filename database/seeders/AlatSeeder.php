<?php

namespace Database\Seeders;

use App\Models\Alat;
use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    public function run(): void
    {
        $alats = [
            [
                'name' => 'Treadmill',
                'desc' => "MEREK / Tipe : Treadmill Jezfit Multifungsi\n-Dinamo Power: 2.5 HP DC\n-Speed: 0 - 14 km/h\n-Running Belt Size: 126 x 46 cm\n-Monitor: LCD\n-Max Weight Capacity: 120 kg\n-Incline: Auto Incline 0 - 15%\n-Dimensi: 165 x 72 x 134 cm\n-Berat Produk: 75 kg\n-Daya: 450 W",
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Komputer Desktop Core i9',
                'desc' => 'Dell Inspiron G5 5090 Desktops Core i9 32GB 512SSD 2TB Win10\nInspiron G5 5090 Desktops / 9th Gen Intel(R) Core(TM) i9 9900 (8-Core, 16MB Cache, up to 5GHz with Intel(R) Turbo Boost Technology) / 32GB DDR4 at 2666MHz Dual Channel / 512GB M.2 PCIe NVMe Solid State Drive + 2TB 7200 rpm 3.5" SATA Hard Drive / NVIDIA® GeForce RTX 2070 8GB GDDR6 / Killer Wi-Fi 6 AX1650 (2x2) and Bluetooth 5.0 / USB Optical Mouse & USB Keyboard / Windows 10 Home Plus Single Language English / McAfee(R) Multi Device Security 15 month subscription / DELL MONITOR SE2417HGX / 1Yr Premium Support:Onsite Service-Retail',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Komputer Desktop Core i7',
                'desc' => 'DELL INSPIRON 3670 (CORE I7-9700, 8GB, 1TB + 16GB OPTANE)',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Osiloskop Hantek',
                'desc' => "Manufacturer : HANTEK\nOscilloscope type : digital\nBandwidth : 100MHz\nNumber of channels : 2\nMemory record length : 40kpts\nSampling : 1Gsps (in real time), 25Gsps (in equivalent time)\nRise time : 3,5ns\nType of display used : LCD 7\" (800x480), color\nTime base : 4n...40s/div\nVertical resolution : 8bit\nWeight : 3.5KG(with Packing); 2.08KG(without Packing\nPower supply : 100...120VAC, 45...440Hz, 120...240VAC, 45...66Hz\nOverall dimensions (W x D x H) : 313 x 108 x 142mm\nInterface : USB",
                'quantity' => 2,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Osiloskop Rigol',
                'desc' => 'PHYSIK-LAB OSILOSKOP DIGITAL DOUBLE 100 MHZ\nMaksimal bandwidth 100 MHz, tegangan input maksimal 400 V (peak to peak). Mempunyai 2 channel input, pengaturan volt/div, trigger, dan adjusment. Input dapat berupa arus AC atau DC.',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Multimeter',
                'desc' => "Display:22000 counts\n- Auto range\n- True RMS\n- Bandwidth(Hz):45Hz~10KHz\n- Duty Cycle:0.1%~99.9%\n- Auto Power off: Around 15 mins\n- RS232 cable: Connecting to computer cable",
                'quantity' => 6,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Elektrospinning',
                'desc' => "Product Name : ELECTROSPINNING\nBrand / Model : ILMI / N101\nTegangan tinggi 0-21.5 kV\nSyringe pump: min 0.73uL/hr, maks 1500 mL/hr\n1 kolektor drum geser berputar, adjustable speed\n2 kolektor plat, dimensi 8x8 cm dan 16x16 cm\nKabel Ground\nKamera CCD, monitor TFT\nAdaptor BNC to USB",
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'USG',
                'desc' => "▶ Dimensi 310 × 295 × 70 mm (PxLxT)\n▶ Dimensi LCD 12 inci\n▶ Berat 4 Kg\n▶ Suplai Tegangan 100V-240 V, 50/60 Hz , 14V DC\n▶ Baterai 5200mAh rechargeable Li-ion Battery\n▶ Daya 110 VA\n▶ Fungsi I/O dengan format DICOM\n▶ Standard probe : T35R60BN convex array\n▶ Optional probe: T65R13BN type cavity\n▶ Probe : 80 elements, R60, frequency of 3.5 MHz\n▶ Probe : 80 elements, R13, frequency of 6.5 MHz",
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Ventilator',
                'desc' => 'Hibah dari RS Pertamina Bintang Amin',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Hibah',
            ],
            [
                'name' => 'Sepeda statis',
                'desc' => "MEREK / Tipe : Sepeda Fitness Multifungsi Orbitrack Elliptical Bike\n-Box Size : 99 x 22.5 x 78cm\n-Flywheel : Chain\n-Nett Weight : 40kg\n-Extras : 4pcs Dumbbell",
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Timbangan analitik',
                'desc' => "Nama merek BIOBASE\nNomor model BA2004C\nProduct name Weight Scale\nCapacity 0~200g\nReadability 0.1mg\nScale Size 90mm\nCalibration Internal Cal\nWork Space Height 240mm",
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'EEG',
                'desc' => "I. Umum\n1. Spesifikasi Fungsional :\nPraktikum Instrumentasi Biomedik\n\n2. Spesifikasi Kinerja : Untuk mengukur gelombang elektromagnetik pada otak",
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'ECG',
                'desc' => "I. Umum\n1. Spesifikasi Fungsional :\nAlat praktikum Instrumentasi Biomedik 2. Spesifikasi Kinerja :\nmengevaluasi fungsi jantung. Merekam aktivitas listrik dari jantung dan mengidentifikasi jika ada peredaran atau aliran darah yang tidak normal.\nII. Spesifikasi Teknis\n-Sampling rate 16,000Hz\n-CMRR 140dB\n-Lebar Bandwidth 0,01 - 300Hz\n-15/16-lead sampling and interpretation",
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => '3D Printer Resin',
                'desc' => "SKU : PHR-MEGA8KS\nSpek :\nPrint Size 33 x 18.5 x 30 cm\nSudah terakit, siap pakai\nUpgraded Mainboard untuk hasil print lebih bagus\nUpdated Touch Screen Interface\nEasy Leveling\nPreview Model di Layar sebelum print\nLayar Touch Screen\nDisplay Proses printing real time di layar\nPrint Technology : MONO LCD 8K\nX and Y Axis Resolution : 43 microns dengan layar double dan 76 microns dengan layar besar\nZ Axis Resolution : 10 microns\nSuggested Layer Thickness : 0.01 - 0.30 microns\nSuggested Print Speed : 600 layers per hour\nLight Source : UV LED (405 nm Wave Length)",
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Nikon TS100-F Inverted Fluorescence Phase Contrast Microscope',
                'desc' => 'Manufacturer: Nikon, Model Eclipse TS100-F Trinocular Inverted Fluorescence Microscope',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Inkubator CelCulture',
                'desc' => 'CelCulture® Incubator 170L IR Sensor, CO2 Control ULPA, Moist Heat Decon - Warna Campuran - Campuran',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Oven',
                'desc' => "Model BOV-H50\nCapacity 50L\nDisplay LED\nTemp. Range RT+20〜400°C\nTemp. Precision 0.1°C\nTemp. Fluctuation ±0.5°C\nAmbient Temp. 5~40°C\nTiming Range 0~5999min\nShelves No. 2 pcs\nConsumption 3250W\nPower Supply 380V,50HZ\nInternal Size(W*D*H)mm 350*350*400\nExternal Size(W*D*H)mm 890*700*920\nPacking Size (W*D*H)mm 1010*820*1150\nGross Weight.(kg) 198",
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Tensimeter OMRON',
                'desc' => "I. Umum\n1. Spesifikasi Fungsional :\nAlat praktikum ergonomi modul fisiologi kerja 2. Spesifikasi Kinerja :\nMengukur tekanan darah manusia sebelum bekerja dan sesudah bekerja\nII. Spesifikasi Teknis Teknologi Intelli-sense\nIndikator pemakaian manset\nMendeteksi gerakan tubuh\nIndikator Hipertensi\nMemori hasil pengukuran terakhir\nPengukur tekanan dari 0 sampai 299mmHg\nPulse 40 sampai 180 beats/min",
                'quantity' => 2,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Stetoskop',
                'desc' => "I. Umum\n1. Spesifikasi Fungsional :\nPraktikum Instrumentasi Biomedik\n\n2. Spesifikasi Kinerja : Untuk mendengar suara yang ada dalam tubuh\n\nII. Spesifikasi Teknis\nDual membran sehingga bisa digunakan untuk dewasa dan anak-anak",
                'quantity' => 10,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Alat cek darah',
                'desc' => 'Easy Touch GCU, Dimension 88x64x22, massa 5 gram ,Electrode-based Biosensoch',
                'quantity' => 3,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Electronics modul kit amplifiers',
                'desc' => 'Power 220 V, 50 Hz',
                'quantity' => 2,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Electronics Hand Dynamometer',
                'desc' => 'CAMRY model EH101, Max 90 Kg/ 198 lb, power 2 x 1,5 V AAA , 0~35 C',
                'quantity' => 3,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Penetrometer Digital',
                'desc' => 'Penetrometer Digital, Control Group,110 VAC/230 VAC, 50/60 Hz, 100 W, Dimention 130x130x130 mm, Netto 6 kg.',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Stopwatch',
                'desc' => 'Stopwatch Q&Q Digital',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Tensimeter ABN Analog',
                'desc' => 'ABN Premium Aneroid Sphygmomanometer, 0-300 mnHg, Manual metode',
                'quantity' => 11,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Timbangan digital',
                'desc' => 'Electronic kitchen scale. Power 1.5 V x 2 AAA Battery , LCD Display',
                'quantity' => 2,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Tensimeter One Med Analog',
                'desc' => 'TENSIMETER ANEROID 200 ONEMED, Akurasi medical grade +/-3 mmHg',
                'quantity' => 3,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Magnetic Stirrer',
                'desc' => 'Work plate material: Glass ceramic',
                'quantity' => 4,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Centrifugal Machine',
                'desc' => '800-1 Centrifugal Machine Made in China',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Microscope Biology',
                'desc' => 'Total Magnifiying : 600 X Eyepiece: H5X, H10X, H12,SX Objective: Achromatic 4X, 10X, 40X',
                'quantity' => 2,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Laminating Machine',
                'desc' => 'Cold and Hot Pouch Thickness : 80 - 100 mic hot pouch Laminating Speed : 330 mm /min',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Ampere Meter',
                'desc' => 'Digital Clamp Meter DC/AC 600A',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Fingertrip Pulse Oximeter',
                'desc' => 'ChoiceMMed',
                'quantity' => 2,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Beurer medical Thermometer',
                'desc' => 'FT 65 Multifunctiom thermometer',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Muscle Simulator',
                'desc' => 'yuwell SDP-330 Nerve and muscle simulator',
                'quantity' => 1,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Hairdryer',
                'desc' => 'Philips',
                'quantity' => 2,
                'location' => 'Lab Rekayasa Sel dan Jaringan (Lab Biomedis)',
                'location_id' => 1,
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Inkubator Baby',
                'desc' => ">37°C temperature setting\nAdded with guardrail\nAir temp.and skin temp.servo-controlled by computer",
                'quantity' => 1,
                'location' => 'Lab Instrumen Biomedic (Lab Biomedis)',
                'location_id' => 2, // ID Lokasi Berbeda
                'source' => 'Pengadaan',
            ],
            [
                'name' => 'Patient Monitor & Trolley',
                'desc' => 'Power 220 V, 50 Hz. Battery, ,Measurement EKG, SPo2, Tensi.',
                'quantity' => 2,
                'location' => 'Lab Instrumen Biomedic (Lab Biomedis)',
                'location_id' => 2, // ID Lokasi Berbeda
                'source' => 'Pengadaan',
            ],
        ];

        foreach ($alats as $index => $alatData) {
            // Loop sebanyak jumlah ('quantity') untuk membuat record individual
            for ($i = 1; $i <= $alatData['quantity']; $i++) {
                Alat::create([
                    // Menggunakan nama dari data array
                    'name' => $alatData['name'],
                    
                    // Membuat serial number unik berdasarkan index, tahun, dan nomor unit
                    'serial_number' => 'ALAT-' . date('Y') . '-' . ($index + 1) . '-' . $i,
                    
                    // Default value, bisa disesuaikan
                    'auto_validate' => false,
                    
                    // Menggunakan deskripsi dari data array
                    'desc' => $alatData['desc'],
                    
                    // ID Lokasi. Penting: Sesuaikan ID ini dengan tabel lokasi Anda.
                    // 1 = Lab Rekayasa Sel dan Jaringan, 2 = Lab Instrumen Biomedic
                    'location' => $alatData['location_id'],
                    
                    // Nama detail lokasi dari data array
                    'detail_location' => $alatData['location'],
                    
                    // Menggunakan tanggal hari ini
                    'date_received' => now()->format('Y-m-d'),
                    
                    // Menggunakan sumber dari data array (contoh: 'Pengadaan' atau 'Hibah')
                    'source' => $alatData['source'],
                    
                    // ID Kategori. Penting: Ganti placeholder ini dengan ID kategori yang sesuai.
                    'category_id' => 1, 
                ]);
            }
        }
    }
}
