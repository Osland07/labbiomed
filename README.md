# LabBiomed - Sistem Manajemen Laboratorium

## Daftar Fitur Utama

1. **Manajemen Kunjungan**
   - Check-in/Check-out QR Code (untuk tamu, mahasiswa, dosen, laboran, koordinator)
   - Riwayat kunjungan
   - Statistik kunjungan
   - Export laporan kunjungan
2. **Manajemen Pengguna**
   - Manajemen user (Super Admin, Dosen, Mahasiswa, Koordinator Laboratorium, Laboran)
   - Manajemen role & permission
3. **Manajemen Alat & Bahan**
   - Data alat & bahan
   - Penggunaan alat & bahan
   - Peminjaman alat
   - Pengembalian alat
   - Laporan kerusakan alat
   - Stok bahan masuk & keluar
4. **Manajemen Ruangan**
   - Data ruangan
   - Booking/penggunaan ruangan
   - Monitoring penggunaan ruangan
5. **Transaksi & Validasi**
   - Pengajuan peminjaman alat
   - Validasi laboran & koordinator
   - Upload & validasi surat
   - Pengembalian alat
   - Validasi pengembalian
6. **Laporan & Monitoring**
   - Laporan peminjaman, penggunaan, kerusakan (export Excel)
   - Monitoring aktivitas mahasiswa (kunjungan, peminjaman, penggunaan alat)
   - Statistik aktivitas
7. **Autentikasi & Profil**
   - Login, register, reset password
   - Edit profil, cetak ID card
   - Manajemen password
8. **Fitur Client**
   - Cek alat/bahan/ruangan tersedia
   - Riwayat pengajuan, penggunaan, kunjungan
   - Penggunaan bahan
   - Jadwal booking ruangan

## Role Pengguna
- Super Admin
- Koordinator Laboratorium
- Laboran
- Dosen
- Mahasiswa
- Tamu/Umum

## Template Pengujian Blackbox

| No | Role | Skenario Pengujian | Test Case | Hasil yang Diharapkan |
|----|------|--------------------|-----------|----------------------|
| 1  | Koordinator | Login | Koordinator memasukkan username dan password yang valid | Sistem menampilkan halaman dashboard Koordinator |
| 2  | Koordinator | Login gagal | Login dengan password salah | Pesan error ditampilkan |
| 3  | Mahasiswa | Check-in Kunjungan | Mahasiswa scan QR dan isi form check-in | Data kunjungan tercatat, diarahkan ke halaman sukses |
| 4  | Mahasiswa | Check-out Kunjungan | Mahasiswa scan QR check-out | Data kunjungan diupdate, durasi tampil |
| 5  | Mahasiswa | Pengajuan Peminjaman Alat | Mahasiswa mengisi form pengajuan alat | Pengajuan tercatat, status menunggu validasi |
| 6  | Laboran | Validasi Peminjaman | Laboran memvalidasi pengajuan alat | Status pengajuan berubah sesuai aksi |
| 7  | Koordinator | Validasi Peminjaman | Koordinator memvalidasi pengajuan alat | Status pengajuan berubah sesuai aksi |
| 8  | Mahasiswa | Upload Surat | Mahasiswa upload surat validasi | Surat tersimpan, status pengajuan update |
| 9  | Mahasiswa | Penggunaan Alat | Mahasiswa mengajukan penggunaan alat | Penggunaan tercatat, status menunggu validasi |
| 10 | Laboran | Validasi Penggunaan | Laboran memvalidasi penggunaan alat | Status penggunaan berubah sesuai aksi |
| 11 | Mahasiswa | Pengembalian Alat | Mahasiswa mengembalikan alat | Status pengembalian update, bisa upload foto |
| 12 | Laboran | Validasi Pengembalian | Laboran memvalidasi pengembalian alat | Status pengembalian update |
| 13 | Mahasiswa | Penggunaan Bahan | Mahasiswa mengajukan penggunaan bahan | Penggunaan bahan tercatat |
| 14 | Admin | Manajemen User | Admin menambah/mengedit/menghapus user | Data user terupdate sesuai aksi |
| 15 | Admin | Manajemen Role | Admin menambah/mengedit/menghapus role | Data role terupdate sesuai aksi |
| 16 | Admin | Manajemen Alat/Bahan/Ruangan | Admin CRUD alat, bahan, ruangan | Data alat/bahan/ruangan terupdate |
| 17 | Mahasiswa | Riwayat | Mahasiswa melihat riwayat kunjungan/pengajuan/penggunaan | Data riwayat tampil sesuai user |
| 18 | Semua | Reset Password | User melakukan reset password | Email reset dikirim, password bisa diubah |
| 19 | Semua | Edit Profil | User mengubah data profil | Data profil terupdate |
| 20 | Semua | Logout | User logout dari sistem | Kembali ke halaman login |

> **Catatan:**
> - Silakan tambahkan skenario lain sesuai kebutuhan pengujian.
> - Pastikan setiap fitur diuji untuk role yang relevan.
