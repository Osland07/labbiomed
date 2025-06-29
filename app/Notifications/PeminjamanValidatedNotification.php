<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LaporanPeminjaman;

class PeminjamanValidatedNotification extends Notification
{
    use Queueable;

    protected $laporan;
    protected $status;
    protected $catatan;

    /**
     * Create a new notification instance.
     */
    public function __construct(LaporanPeminjaman $laporan, $status, $catatan = null)
    {
        $this->laporan = $laporan;
        $this->status = $status;
        $this->catatan = $catatan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->status === 'Diterima' ? 'DITERIMA' : 'DITOLAK';
        $statusColor = $this->status === 'Diterima' ? 'success' : 'error';

        $mailMessage = (new MailMessage)
            ->subject("Pengajuan Peminjaman {$statusText}")
            ->greeting("Halo {$notifiable->name}!")
            ->line("Pengajuan peminjaman Anda telah {$statusText}.")
            ->line("Detail pengajuan:")
            ->line("• Tujuan Peminjaman: {$this->laporan->tujuan_peminjaman}")
            ->line("• Tanggal Peminjaman: " . ($this->laporan->tgl_peminjaman ? date('d/m/Y', strtotime($this->laporan->tgl_peminjaman)) : '-'))
            ->line("• Tanggal Pengembalian: " . ($this->laporan->tgl_pengembalian ? date('d/m/Y', strtotime($this->laporan->tgl_pengembalian)) : '-'));

        if ($this->laporan->alat_id && is_array($this->laporan->alat_id)) {
            $alatList = \App\Models\Alat::whereIn('id', $this->laporan->alat_id)->pluck('name')->toArray();
            $mailMessage->line("• Alat yang dipinjam: " . implode(', ', $alatList));
        }

        if ($this->catatan) {
            $mailMessage->line("• Catatan: {$this->catatan}");
        }

        if ($this->status === 'Diterima') {
            $mailMessage->line("Silakan datang ke laboratorium sesuai jadwal yang telah ditentukan.")
                ->action('Lihat Detail', url('/client/riwayat/pengajuan'))
                ->line('Terima kasih telah menggunakan layanan kami.');
        } else {
            $mailMessage->line("Mohon maaf, pengajuan Anda tidak dapat diproses.")
                ->action('Ajukan Ulang', url('/client/pengajuan-peminjaman'))
                ->line('Silakan ajukan ulang dengan melengkapi persyaratan yang diperlukan.');
        }

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'laporan_id' => $this->laporan->id,
            'status' => $this->status,
            'catatan' => $this->catatan,
            'type' => 'peminjaman_validation'
        ];
    }
}
