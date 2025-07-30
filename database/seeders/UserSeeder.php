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
        

       
    }
}
