<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Laporan;

class PenggunaanValidatedNotification extends Notification
{
    use Queueable;

    protected $laporan;
    protected $status;
    protected $catatan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Laporan $laporan, $status, $catatan = null)
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

        $mailMessage = (new MailMessage)
            ->subject("Pengajuan Penggunaan {$statusText}")
            ->greeting("Halo {$notifiable->name}!")
            ->line("Pengajuan penggunaan Anda telah {$statusText}.")
            ->line("Detail pengajuan:")
            ->line("• Tujuan Penggunaan: {$this->laporan->tujuan_penggunaan}")
            ->line("• Waktu Mulai: " . ($this->laporan->waktu_mulai ? date('d/m/Y H:i', strtotime($this->laporan->waktu_mulai)) : '-'))
            ->line("• Waktu Selesai: " . ($this->laporan->waktu_selesai ? date('d/m/Y H:i', strtotime($this->laporan->waktu_selesai)) : '-'));

        // Determine what item is being used
        if ($this->laporan->alat) {
            $mailMessage->line("• Alat: {$this->laporan->alat->name} ({$this->laporan->alat->serial_number})");
        } elseif ($this->laporan->bahan) {
            $mailMessage->line("• Bahan: {$this->laporan->bahan->name} ({$this->laporan->bahan->serial_number})");
        } elseif ($this->laporan->ruangan) {
            $mailMessage->line("• Ruangan: {$this->laporan->ruangan->name} ({$this->laporan->ruangan->serial_number})");
        }

        if ($this->catatan) {
            $mailMessage->line("• Catatan: {$this->catatan}");
        }

        if ($this->status === 'Diterima') {
            $mailMessage->line("Silakan datang ke laboratorium sesuai jadwal yang telah ditentukan.")
                ->action('Lihat Detail', url('/client/riwayat/penggunaan'))
                ->line('Terima kasih telah menggunakan layanan kami.');
        } else {
            $mailMessage->line("Mohon maaf, pengajuan Anda tidak dapat diproses.")
                ->action('Ajukan Ulang', url('/client/penggunaan-alat'))
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
            'type' => 'penggunaan_validation'
        ];
    }
}
