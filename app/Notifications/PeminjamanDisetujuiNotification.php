<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LaporanPeminjaman;

class PeminjamanDisetujuiNotification extends Notification
{
    use Queueable;

    protected $laporan;

    /**
     * Create a new notification instance.
     */
    public function __construct(LaporanPeminjaman $laporan)
    {
        $this->laporan = $laporan;
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
        return (new MailMessage)
            ->subject('Pengajuan Peminjaman Disetujui')
            ->greeting('Halo ' . $notifiable->name)
            ->line('Pengajuan peminjaman Anda telah disetujui oleh Koordinator Laboratorium.')
            ->line("Judul Penelitian: {$this->laporan->judul_penelitian}")
            ->line("Tujuan Peminjaman: {$this->laporan->tujuan_peminjaman}")
            ->line('Surat peminjaman telah digenerate dan siap diunduh.')
            ->action('Download Surat Peminjaman', route('client.pengajuan-peminjaman.generate-formulir', $this->laporan->id))
            ->line('Silakan download surat, tandatangani, dan upload kembali.')
            ->line('Terima kasih telah menggunakan layanan kami.');
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
            'message' => 'Pengajuan peminjaman disetujui oleh koordinator'
        ];
    }
} 