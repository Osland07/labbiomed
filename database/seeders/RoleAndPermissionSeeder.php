<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        $entities = [
            'dashboard' => ['jadwal'],
            'user' => ['view', 'create', 'edit', 'delete'],
            'role' => ['view', 'create', 'edit', 'delete'],
            'category' => ['view', 'create', 'edit', 'delete'],
            'alat' => ['view', 'create', 'edit', 'delete'],
            'bahan' => ['view', 'create', 'edit', 'delete'],
            'ruangan' => ['view', 'create', 'edit', 'delete'],
            'transaksi' => ['view', 'peminjaman', 'penggunaan', 'pengembalian'],
            'laporan' => ['view', 'peminjaman', 'penggunaan', 'kerusakan'],
            'kunjungan' => ['view', 'create', 'edit', 'delete'],
            'client' => ['check', 'pengajuan-peminjaman', 'penggunaan-alat', 'penggunaan-ruangan', 'penggunaan-bahan', 'history'],
            'mahasiswa' => ['monitoring'],
        ];

        foreach ($entities as $entity => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action}-{$entity}"]);
            }
        }

        $roles = [
            'Super Admin' => Permission::pluck('name')->toArray(),

            'Admin' => Permission::where('name', 'not like', '%-role')->where('name', 'not like', '%-client')->pluck('name')->toArray(),
            'Laboran' => [
                'jadwal-dashboard',
                'view-alat', 'create-alat', 'edit-alat', 'delete-alat',
                'view-bahan', 'create-bahan', 'edit-bahan', 'delete-bahan',
                'view-category', 'create-category', 'edit-category', 'delete-category',
                'view-ruangan', 'create-ruangan', 'edit-ruangan', 'delete-ruangan',
                'view-transaksi', 'peminjaman-transaksi', 'penggunaan-transaksi', 'pengembalian-transaksi',
                'view-laporan', 'peminjaman-laporan', 'penggunaan-laporan', 'kerusakan-laporan',
                'view-kunjungan',
            ],
            'Koordinator Laboratorium' => [
                'jadwal-dashboard',
                'view-alat', 'create-alat', 'edit-alat', 'delete-alat',
                'view-bahan', 'create-bahan', 'edit-bahan', 'delete-bahan',
                'view-category', 'create-category', 'edit-category', 'delete-category',
                'view-ruangan', 'create-ruangan', 'edit-ruangan', 'delete-ruangan',
                'view-transaksi', 'peminjaman-transaksi', 'penggunaan-transaksi', 'pengembalian-transaksi',
                'view-laporan', 'peminjaman-laporan', 'penggunaan-laporan', 'kerusakan-laporan',
                'view-kunjungan',
            ],
            'Dosen' => [
                'check-client',
                'pengajuan-peminjaman-client',
                'penggunaan-alat-client',
                'penggunaan-ruangan-client',
                'jadwal-dashboard',
                'history-client',
                'view-kunjungan',
                'monitoring-mahasiswa',
                'penggunaan-bahan-client',
            ],
            'Mahasiswa' => [
                'check-client',
                'pengajuan-peminjaman-client',
                'penggunaan-alat-client',
                'penggunaan-ruangan-client',
                'jadwal-dashboard',
                'history-client',
                'create-kunjungan',
                'penggunaan-bahan-client',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($permissions);
        }

        // Migrasi user dari role 'Koordinator' ke 'Koordinator Laboratorium'
        if (Role::where('name', 'Koordinator')->exists() && Role::where('name', 'Koordinator Laboratorium')->exists()) {
            foreach (\App\Models\User::role('Koordinator')->get() as $user) {
                $user->removeRole('Koordinator');
                $user->assignRole('Koordinator Laboratorium');
            }
        }
    }
}
