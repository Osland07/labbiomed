<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat roles jika belum ada
        $roles = [
            'Super Admin',
            'Dosen',
            'Mahasiswa',
            'Koordinator Laboratorium',
            'Laboran',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Buat user default
        $defaultUsers = [
            [
                'name' => 'Super Administrator',
                'email' => 'super@admin.com',
                'status' => 'aktif',
                'no_hp' => '08123456789',
                'password' => Hash::make('password'),
                'role' => 'Super Admin',
            ],
            [
                'name' => 'Dosen',
                'email' => 'dosen@dosen.com',
                'status' => 'aktif',
                'no_hp' => '08123456789',
                'password' => Hash::make('password'),
                'role' => 'Dosen',
            ],
            [
                'name' => 'Mahasiswa',
                'email' => 'mahasiswa@mahasiswa.com',
                'status' => 'aktif',
                'no_hp' => '08123456789',
                'password' => Hash::make('password'),
                'role' => 'Mahasiswa',
            ],
        ];




        foreach ($defaultUsers as $data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'status'   => $data['status'],
                'no_hp'    => $data['no_hp'],
                'password' => $data['password'],
            ]);
            $user->assignRole($data['role']);
        }

        // Daftar dosen BM ITERA (tanpa Doni Bowo)
        $lecturers = [
            'Amir Faisal, S.T., M.Eng., Ph.D.',
            'Nova Resfita, S.T., M.Sc.',
            'Marsudi Siburian, S.Si., M.Biotech.',
            'Muhammad Wildan Gifari, S.T., M.Sc., Ph.D.',
            'Rudi Setiawan, S.T., M.T.',
            'Africo Ramadhani, M.Pd',
            'Endah, S.Pd., M.Biotech.',
            'Muhammad Artha Jabatsudewa Maras, M.T.',
            'Burhaan Shodiq, M.Or.',
            'Dr. Aldi Herbanu, S.Si.',
            'I Gde Eka Dirgayussa, M.Si.',
            'Meita Mahardianti, S.Si., M.Biomed.',
            'Muhamad Ihsan Hufadz, M.Pd.',
            'Rafli Filano, S.Si., M.T.',
            'Rosita Wati, S.Pd., M.Sc.',
            'Sekar Asri Tresnaningtyas, S.Si., M.Biomed.',
            'Retno Maharsi, M.Si.',
            'Dwi Susanti, S.Pd., M.Sc.',
            'Yusuf Maulana, S.T., M.Sc.',
            'Nurul Maulidiyah, S.Si, M.S',
            'Asy Syifa Labibah, M.Sc.',
        ];

        $used_emails = [];
        foreach ($lecturers as $index => $name) {
            $name = ucwords(strtolower($name));
            $parts = preg_split('/\s+/', $name);
            $first = isset($parts[0]) ? preg_replace('/[^a-z]/i', '', $parts[0]) : '';
            $second = isset($parts[1]) ? preg_replace('/[^a-z]/i', '', $parts[1]) : '';
            $email_base = strtolower($first . $second);
            $email = $email_base . '@itera.ac.id';
            $counter = 1;
            while (in_array($email, $used_emails)) {
                $email = $email_base . $counter . '@itera.ac.id';
                $counter++;
            }
            $used_emails[] = $email;
            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                'status'   => 'aktif',
                'no_hp'    => '08123456789',
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('Dosen');
        }

        // Doni Bowo Nugroho sebagai Koordinator Laboratorium
        $doni = User::create([
            'name'     => ucwords(strtolower('Koordinator Laboratorium, S.Pd., M.Sc.')),
            'email'    => 'koorlab@koorlab.com',
            'status'   => 'aktif',
            'no_hp'    => '08123456789',
            'nim'      => '1992092420211411',
            'password' => Hash::make('password'),
        ]);
        $doni->assignRole('Koordinator Laboratorium');

        // Laboran: Ading Atma Gamilang
        $laboran = User::create([
            'name'     => ucwords(strtolower('Laboran Uhuy')),
            'email'    => 'laboran@laboran.com',
            'status'   => 'aktif',
            'no_hp'    => '08123456789',
            'nim'      => '1990010119900101',
            'password' => Hash::make('password'),
        ]);
        $laboran->assignRole('Laboran');

        // Data mahasiswa dari gambar
        $mahasiswas = [
            ['nim' => '121430053', 'name' => 'MAHARANI JATI KESUMANINGRUM', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430054', 'name' => 'Annisa Sri Rahmayani', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430055', 'name' => 'FADHILAH BAFAHDAL', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430056', 'name' => 'ROPI NURAHMAN', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430057', 'name' => 'Kevin Elfancyus Herman', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430058', 'name' => 'Maisara', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430059', 'name' => 'Wahdaniatul Munawaroh', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430060', 'name' => 'Syahira Kurnia', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430061', 'name' => 'Dyah Aprilisyafirah', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430062', 'name' => 'MAHARANI PUTRI QOHAR', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430063', 'name' => 'NABILA MARDATILLAH AGDA', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430064', 'name' => 'Desy Fitri Ani', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430065', 'name' => 'PETRA ANUGRAH Y.M. SITOMPUL', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430066', 'name' => 'Zahra Maulidya', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430067', 'name' => 'LAILIKA ANINGRUM', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430068', 'name' => 'Wirda Azavira', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430069', 'name' => 'Santi Aji Nurhasanah Rambe', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430070', 'name' => 'Aliyah Zahra Soraya', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430071', 'name' => 'OSLAND FIRST PURBA', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430072', 'name' => 'RAYMOND NISSI DAUD SIHOMBING', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430073', 'name' => 'HIPOLITUS HARKA BAGUS BINANTORO', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430074', 'name' => 'Nismara Anindya', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430075', 'name' => 'AZZAHRA PUTRI FATWALI', 'prodi' => 'Teknik Biomedis'],
            ['nim' => '121430076', 'name' => 'FEBRIAN PUTRA FAHRUDIN', 'prodi' => 'Teknik Biomedis'],
        ];
        foreach ($mahasiswas as $mhs) {
            $nama_title = ucwords(strtolower($mhs['name']));
            $nama_bersih = strtolower(preg_replace('/[^a-z]/i', '', str_replace(' ', '', $mhs['name'])));
            $email = $nama_bersih . '.' . $mhs['nim'] . '@itera.ac.id';
            $user = User::updateOrCreate(
                ['nim' => $mhs['nim']],
                [
                    'name' => $nama_title,
                    'prodi' => $mhs['prodi'],
                    'email' => $email,
                    'status' => 'aktif',
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole('Mahasiswa');
        }
    }
}
