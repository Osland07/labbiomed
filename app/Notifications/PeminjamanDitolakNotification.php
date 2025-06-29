<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LaporanPeminjaman;

class PeminjamanDitolakNotification extends Notification
{
    use Queueable;

    protected $laporan;
    protected $validatedBy;
    protected $catatan;

    /**
     * Create a new notification instance.
     */
    public function __construct(LaporanPeminjaman $laporan, string $validatedBy, string $catatan)
    {
        $this->laporan = $laporan;
        $this->validatedBy = $validatedBy;
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
        $validatedByText = $this->validatedBy === 'laboran' ? 'Laboran' : 'Koordinator Laboratorium';
        
        return (new MailMessage)
            ->subject('Pengajuan Peminjaman Ditolak')
            ->greeting('Halo ' . $notifiable->name)
            ->line("Pengajuan peminjaman Anda ditolak oleh {$validatedByText}.")
            ->line("Judul Penelitian: {$this->laporan->judul_penelitian}")
            ->line("Tujuan Peminjaman: {$this->laporan->tujuan_peminjaman}")
            ->line("Alasan Penolakan: {$this->catatan}")
            ->action('Lihat Detail Pengajuan', url('/client/upload-surat'))
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
            'validated_by' => $this->validatedBy,
            'catatan' => $this->catatan,
            'message' => "Pengajuan peminjaman ditolak oleh {$this->validatedBy}"
        ];
    }
} 