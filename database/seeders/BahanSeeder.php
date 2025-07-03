<?php

namespace Database\Seeders;

use App\Models\Bahan;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BahanSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories for different types of materials
        $categories = [
            'Reagen Kimia' => 'bahan',
            'Media Kultur' => 'bahan',
            'Peralatan Laboratorium' => 'bahan',
            'Bahan Elektronik' => 'bahan',
            'Bahan Biomedis' => 'bahan',
            'Konsumabel' => 'bahan'
        ];

        $categoryIds = [];
        foreach ($categories as $name => $type) {
            $category = Category::firstOrCreate(
                ['name' => $name],
                ['type' => $type]
            );
            $categoryIds[$name] = $category->id;
        }

        // Materials commonly used in biomedical engineering laboratories
        $materials = [
            // Reagen Kimia
            [
                'name' => 'Natrium Hidroksida (NaOH)',
                'desc' => 'Reagen kimia untuk pH adjustment dan cleaning',
                'unit' => 'Kg',
                'stock' => 25,
                'min_stock' => 5,
                'date_received' => now()->subDays(30)->format('Y-m-d'),
                'date_expired' => now()->addMonths(24)->format('Y-m-d'),
                'location' => 'Lemari Kimia A',
                'category_id' => $categoryIds['Reagen Kimia']
            ],
            [
                'name' => 'Asam Klorida (HCl)',
                'desc' => 'Reagen kimia untuk pH adjustment dan etching',
                'unit' => 'Liter',
                'stock' => 15,
                'min_stock' => 3,
                'date_received' => now()->subDays(45)->format('Y-m-d'),
                'date_expired' => now()->addMonths(18)->format('Y-m-d'),
                'location' => 'Lemari Kimia A',
                'category_id' => $categoryIds['Reagen Kimia']
            ],
            [
                'name' => 'Etanol 96%',
                'desc' => 'Pelarut organik untuk sterilisasi dan ekstraksi',
                'unit' => 'Liter',
                'stock' => 40,
                'min_stock' => 10,
                'date_received' => now()->subDays(20)->format('Y-m-d'),
                'date_expired' => now()->addMonths(12)->format('Y-m-d'),
                'location' => 'Lemari Kimia B',
                'category_id' => $categoryIds['Reagen Kimia']
            ],
            [
                'name' => 'Metanol HPLC Grade',
                'desc' => 'Pelarut untuk analisis HPLC',
                'unit' => 'Liter',
                'stock' => 20,
                'min_stock' => 5,
                'date_received' => now()->subDays(15)->format('Y-m-d'),
                'date_expired' => now()->addMonths(36)->format('Y-m-d'),
                'location' => 'Lemari Kimia B',
                'category_id' => $categoryIds['Reagen Kimia']
            ],
            [
                'name' => 'Buffer PBS 10x',
                'desc' => 'Buffer fosfat untuk kultur sel',
                'unit' => 'Liter',
                'stock' => 30,
                'min_stock' => 5,
                'date_received' => now()->subDays(10)->format('Y-m-d'),
                'date_expired' => now()->addMonths(6)->format('Y-m-d'),
                'location' => 'Lemari Kimia C',
                'category_id' => $categoryIds['Reagen Kimia']
            ],
            [
                'name' => 'Trypsin-EDTA 0.25%',
                'desc' => 'Enzim untuk detaching sel dari kultur',
                'unit' => 'Liter',
                'stock' => 12,
                'min_stock' => 2,
                'date_received' => now()->subDays(5)->format('Y-m-d'),
                'date_expired' => now()->addMonths(3)->format('Y-m-d'),
                'location' => 'Freezer -20°C',
                'category_id' => $categoryIds['Reagen Kimia']
            ],

            // Media Kultur
            [
                'name' => 'DMEM High Glucose',
                'desc' => 'Media kultur sel mamalia',
                'unit' => 'Liter',
                'stock' => 25,
                'min_stock' => 5,
                'date_received' => now()->subDays(8)->format('Y-m-d'),
                'date_expired' => now()->addMonths(4)->format('Y-m-d'),
                'location' => 'Lemari Es 4°C',
                'category_id' => $categoryIds['Media Kultur']
            ],
            [
                'name' => 'RPMI 1640',
                'desc' => 'Media kultur untuk sel imun',
                'unit' => 'Liter',
                'stock' => 18,
                'min_stock' => 3,
                'date_received' => now()->subDays(12)->format('Y-m-d'),
                'date_expired' => now()->addMonths(4)->format('Y-m-d'),
                'location' => 'Lemari Es 4°C',
                'category_id' => $categoryIds['Media Kultur']
            ],
            [
                'name' => 'Fetal Bovine Serum (FBS)',
                'desc' => 'Serum untuk supplement media kultur',
                'unit' => 'Liter',
                'stock' => 8,
                'min_stock' => 1,
                'date_received' => now()->subDays(3)->format('Y-m-d'),
                'date_expired' => now()->addMonths(2)->format('Y-m-d'),
                'location' => 'Freezer -20°C',
                'category_id' => $categoryIds['Media Kultur']
            ],
            [
                'name' => 'Penicillin-Streptomycin',
                'desc' => 'Antibiotik untuk media kultur',
                'unit' => 'Liter',
                'stock' => 15,
                'min_stock' => 2,
                'date_received' => now()->subDays(7)->format('Y-m-d'),
                'date_expired' => now()->addMonths(6)->format('Y-m-d'),
                'location' => 'Freezer -20°C',
                'category_id' => $categoryIds['Media Kultur']
            ],
            [
                'name' => 'L-Glutamine 200mM',
                'desc' => 'Amino acid supplement untuk kultur sel',
                'unit' => 'Liter',
                'stock' => 10,
                'min_stock' => 2,
                'date_received' => now()->subDays(5)->format('Y-m-d'),
                'date_expired' => now()->addMonths(3)->format('Y-m-d'),
                'location' => 'Freezer -20°C',
                'category_id' => $categoryIds['Media Kultur']
            ],

            // Peralatan Laboratorium
            [
                'name' => 'Pipet Tip 200μL',
                'desc' => 'Tip pipet untuk volume 200 mikroliter',
                'unit' => 'Box',
                'stock' => 50,
                'min_stock' => 10,
                'date_received' => now()->subDays(20)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Pipet',
                'category_id' => $categoryIds['Peralatan Laboratorium']
            ],
            [
                'name' => 'Pipet Tip 1000μL',
                'desc' => 'Tip pipet untuk volume 1000 mikroliter',
                'unit' => 'Box',
                'stock' => 40,
                'min_stock' => 8,
                'date_received' => now()->subDays(25)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Pipet',
                'category_id' => $categoryIds['Peralatan Laboratorium']
            ],
            [
                'name' => 'Eppendorf Tube 1.5mL',
                'desc' => 'Tabung mikro untuk sampel',
                'unit' => 'Box',
                'stock' => 60,
                'min_stock' => 15,
                'date_received' => now()->subDays(15)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Tabung',
                'category_id' => $categoryIds['Peralatan Laboratorium']
            ],
            [
                'name' => 'Falcon Tube 15mL',
                'desc' => 'Tabung konikal untuk kultur sel',
                'unit' => 'Box',
                'stock' => 35,
                'min_stock' => 8,
                'date_received' => now()->subDays(18)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Tabung',
                'category_id' => $categoryIds['Peralatan Laboratorium']
            ],
            [
                'name' => 'Falcon Tube 50mL',
                'desc' => 'Tabung konikal besar untuk kultur sel',
                'unit' => 'Box',
                'stock' => 25,
                'min_stock' => 5,
                'date_received' => now()->subDays(22)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Tabung',
                'category_id' => $categoryIds['Peralatan Laboratorium']
            ],
            [
                'name' => 'Petri Dish 100mm',
                'desc' => 'Cawan petri untuk kultur sel',
                'unit' => 'Box',
                'stock' => 45,
                'min_stock' => 10,
                'date_received' => now()->subDays(12)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Cawan',
                'category_id' => $categoryIds['Peralatan Laboratorium']
            ],
            [
                'name' => 'Cover Slip 24x24mm',
                'desc' => 'Kaca penutup untuk mikroskop',
                'unit' => 'Box',
                'stock' => 30,
                'min_stock' => 5,
                'date_received' => now()->subDays(30)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Mikroskop',
                'category_id' => $categoryIds['Peralatan Laboratorium']
            ],
            [
                'name' => 'Microscope Slide',
                'desc' => 'Kaca objek untuk mikroskop',
                'unit' => 'Box',
                'stock' => 40,
                'min_stock' => 8,
                'date_received' => now()->subDays(28)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Mikroskop',
                'category_id' => $categoryIds['Peralatan Laboratorium']
            ],

            // Bahan Elektronik
            [
                'name' => 'Kabel USB Type-C',
                'desc' => 'Kabel USB untuk perangkat elektronik',
                'unit' => 'Pcs',
                'stock' => 25,
                'min_stock' => 5,
                'date_received' => now()->subDays(40)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Lemari Elektronik',
                'category_id' => $categoryIds['Bahan Elektronik']
            ],
            [
                'name' => 'Battery 9V',
                'desc' => 'Baterai untuk perangkat elektronik',
                'unit' => 'Pcs',
                'stock' => 50,
                'min_stock' => 10,
                'date_received' => now()->subDays(35)->format('Y-m-d'),
                'date_expired' => now()->addMonths(24)->format('Y-m-d'),
                'location' => 'Lemari Elektronik',
                'category_id' => $categoryIds['Bahan Elektronik']
            ],
            [
                'name' => 'Arduino Uno R3',
                'desc' => 'Board mikrokontroler untuk prototyping',
                'unit' => 'Pcs',
                'stock' => 15,
                'min_stock' => 3,
                'date_received' => now()->subDays(50)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Lemari Elektronik',
                'category_id' => $categoryIds['Bahan Elektronik']
            ],
            [
                'name' => 'Sensor Suhu LM35',
                'desc' => 'Sensor suhu analog untuk monitoring',
                'unit' => 'Pcs',
                'stock' => 30,
                'min_stock' => 5,
                'date_received' => now()->subDays(45)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Lemari Elektronik',
                'category_id' => $categoryIds['Bahan Elektronik']
            ],
            [
                'name' => 'LED 5mm Red',
                'desc' => 'LED merah untuk indikator',
                'unit' => 'Pcs',
                'stock' => 100,
                'min_stock' => 20,
                'date_received' => now()->subDays(60)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Lemari Elektronik',
                'category_id' => $categoryIds['Bahan Elektronik']
            ],
            [
                'name' => 'Resistor 220Ω',
                'desc' => 'Resistor untuk rangkaian elektronik',
                'unit' => 'Pcs',
                'stock' => 200,
                'min_stock' => 50,
                'date_received' => now()->subDays(55)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Lemari Elektronik',
                'category_id' => $categoryIds['Bahan Elektronik']
            ],
            [
                'name' => 'Breadboard 830 Point',
                'desc' => 'Papan prototipe untuk rangkaian',
                'unit' => 'Pcs',
                'stock' => 20,
                'min_stock' => 5,
                'date_received' => now()->subDays(42)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Lemari Elektronik',
                'category_id' => $categoryIds['Bahan Elektronik']
            ],

            // Bahan Biomedis
            [
                'name' => 'Collagen Type I',
                'desc' => 'Protein untuk scaffold tissue engineering',
                'unit' => 'mg',
                'stock' => 500,
                'min_stock' => 100,
                'date_received' => now()->subDays(10)->format('Y-m-d'),
                'date_expired' => now()->addMonths(6)->format('Y-m-d'),
                'location' => 'Freezer -80°C',
                'category_id' => $categoryIds['Bahan Biomedis']
            ],
            [
                'name' => 'Alginate Sodium',
                'desc' => 'Polisakarida untuk hydrogel',
                'unit' => 'g',
                'stock' => 200,
                'min_stock' => 50,
                'date_received' => now()->subDays(15)->format('Y-m-d'),
                'date_expired' => now()->addMonths(12)->format('Y-m-d'),
                'location' => 'Lemari Kimia D',
                'category_id' => $categoryIds['Bahan Biomedis']
            ],
            [
                'name' => 'Chitosan',
                'desc' => 'Polisakarida untuk biomaterial',
                'unit' => 'g',
                'stock' => 150,
                'min_stock' => 30,
                'date_received' => now()->subDays(20)->format('Y-m-d'),
                'date_expired' => now()->addMonths(12)->format('Y-m-d'),
                'location' => 'Lemari Kimia D',
                'category_id' => $categoryIds['Bahan Biomedis']
            ],
            [
                'name' => 'Gelatin Type A',
                'desc' => 'Protein untuk scaffold dan coating',
                'unit' => 'g',
                'stock' => 300,
                'min_stock' => 75,
                'date_received' => now()->subDays(8)->format('Y-m-d'),
                'date_expired' => now()->addMonths(8)->format('Y-m-d'),
                'location' => 'Lemari Kimia D',
                'category_id' => $categoryIds['Bahan Biomedis']
            ],
            [
                'name' => 'Hyaluronic Acid',
                'desc' => 'Polisakarida untuk tissue engineering',
                'unit' => 'mg',
                'stock' => 250,
                'min_stock' => 50,
                'date_received' => now()->subDays(12)->format('Y-m-d'),
                'date_expired' => now()->addMonths(6)->format('Y-m-d'),
                'location' => 'Freezer -20°C',
                'category_id' => $categoryIds['Bahan Biomedis']
            ],
            [
                'name' => 'Growth Factor FGF-2',
                'desc' => 'Faktor pertumbuhan untuk kultur sel',
                'unit' => 'μg',
                'stock' => 100,
                'min_stock' => 20,
                'date_received' => now()->subDays(5)->format('Y-m-d'),
                'date_expired' => now()->addMonths(2)->format('Y-m-d'),
                'location' => 'Freezer -80°C',
                'category_id' => $categoryIds['Bahan Biomedis']
            ],
            [
                'name' => 'VEGF Recombinant',
                'desc' => 'Faktor pertumbuhan pembuluh darah',
                'unit' => 'μg',
                'stock' => 80,
                'min_stock' => 15,
                'date_received' => now()->subDays(7)->format('Y-m-d'),
                'date_expired' => now()->addMonths(2)->format('Y-m-d'),
                'location' => 'Freezer -80°C',
                'category_id' => $categoryIds['Bahan Biomedis']
            ],

            // Konsumabel
            [
                'name' => 'Sarung Tangan Latex M',
                'desc' => 'Sarung tangan untuk keamanan laboratorium',
                'unit' => 'Box',
                'stock' => 25,
                'min_stock' => 5,
                'date_received' => now()->subDays(30)->format('Y-m-d'),
                'date_expired' => now()->addMonths(24)->format('Y-m-d'),
                'location' => 'Lemari PPE',
                'category_id' => $categoryIds['Konsumabel']
            ],
            [
                'name' => 'Sarung Tangan Nitrile L',
                'desc' => 'Sarung tangan nitrile untuk kimia',
                'unit' => 'Box',
                'stock' => 20,
                'min_stock' => 4,
                'date_received' => now()->subDays(25)->format('Y-m-d'),
                'date_expired' => now()->addMonths(24)->format('Y-m-d'),
                'location' => 'Lemari PPE',
                'category_id' => $categoryIds['Konsumabel']
            ],
            [
                'name' => 'Masker N95',
                'desc' => 'Masker untuk proteksi partikel',
                'unit' => 'Box',
                'stock' => 15,
                'min_stock' => 3,
                'date_received' => now()->subDays(20)->format('Y-m-d'),
                'date_expired' => now()->addMonths(36)->format('Y-m-d'),
                'location' => 'Lemari PPE',
                'category_id' => $categoryIds['Konsumabel']
            ],
            [
                'name' => 'Lab Coat Cotton',
                'desc' => 'Jas laboratorium katun',
                'unit' => 'Pcs',
                'stock' => 30,
                'min_stock' => 8,
                'date_received' => now()->subDays(35)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Lemari PPE',
                'category_id' => $categoryIds['Konsumabel']
            ],
            [
                'name' => 'Kertas Tisu Kimia',
                'desc' => 'Tisu untuk pembersihan peralatan',
                'unit' => 'Box',
                'stock' => 40,
                'min_stock' => 10,
                'date_received' => now()->subDays(15)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Tisu',
                'category_id' => $categoryIds['Konsumabel']
            ],
            [
                'name' => 'Aluminum Foil',
                'desc' => 'Foil aluminium untuk wrapping',
                'unit' => 'Roll',
                'stock' => 12,
                'min_stock' => 3,
                'date_received' => now()->subDays(40)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Foil',
                'category_id' => $categoryIds['Konsumabel']
            ],
            [
                'name' => 'Parafilm M',
                'desc' => 'Film parafin untuk sealing',
                'unit' => 'Roll',
                'stock' => 8,
                'min_stock' => 2,
                'date_received' => now()->subDays(28)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Foil',
                'category_id' => $categoryIds['Konsumabel']
            ],
            [
                'name' => 'Label Sticker 1x2 inch',
                'desc' => 'Label untuk identifikasi sampel',
                'unit' => 'Sheet',
                'stock' => 100,
                'min_stock' => 20,
                'date_received' => now()->subDays(50)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Label',
                'category_id' => $categoryIds['Konsumabel']
            ],
            [
                'name' => 'Marker Permanent Black',
                'desc' => 'Spidol permanen untuk labeling',
                'unit' => 'Pcs',
                'stock' => 35,
                'min_stock' => 8,
                'date_received' => now()->subDays(45)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Alat Tulis',
                'category_id' => $categoryIds['Konsumabel']
            ],
            [
                'name' => 'Notebook Lab A4',
                'desc' => 'Buku catatan laboratorium',
                'unit' => 'Pcs',
                'stock' => 20,
                'min_stock' => 5,
                'date_received' => now()->subDays(60)->format('Y-m-d'),
                'date_expired' => now()->addMonths(60)->format('Y-m-d'),
                'location' => 'Rak Alat Tulis',
                'category_id' => $categoryIds['Konsumabel']
            ]
        ];

        foreach ($materials as $material) {
            Bahan::create($material);
        }
    }
}
