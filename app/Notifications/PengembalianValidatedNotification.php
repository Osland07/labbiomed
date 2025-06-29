<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Laporan;

class PengembalianValidatedNotification extends Notification
{
    use Queueable;

    protected $laporan;
    protected $kondisiSetelah;
    protected $catatan;
    protected $deskripsiKerusakan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Laporan $laporan, $kondisiSetelah, $catatan = null, $deskripsiKerusakan = null)
    {
        $this->laporan = $laporan;
        $this->kondisiSetelah = $kondisiSetelah;
        $this->catatan = $catatan;
        $this->deskripsiKerusakan = $deskripsiKerusakan;
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
        $mailMessage = (new MailMessage)
            ->subject("Pengembalian Berhasil Diproses")
            ->greeting("Halo {$notifiable->name}!")
            ->line("Pengembalian Anda telah berhasil diproses.")
            ->line("Detail pengembalian:")
            ->line("• Tujuan Penggunaan: {$this->laporan->tujuan_penggunaan}")
            ->line("• Tanggal Pengembalian: " . date('d/m/Y H:i', strtotime($this->laporan->tgl_pengembalian)))
            ->line("• Kondisi Setelah: {$this->kondisiSetelah}");

        // Determine what item was returned
        if ($this->laporan->alat) {
            $mailMessage->line("• Alat: {$this->laporan->alat->name} ({$this->laporan->alat->serial_number})");
        } elseif ($this->laporan->bahan) {
            $mailMessage->line("• Bahan: {$this->laporan->bahan->name} ({$this->laporan->bahan->serial_number})");
        } elseif ($this->laporan->ruangan) {
            $mailMessage->line("• Ruangan: {$this->laporan->ruangan->name} ({$this->laporan->ruangan->serial_number})");
        }

        if ($this->kondisiSetelah === 'Rusak' && $this->deskripsiKerusakan) {
            $mailMessage->line("• Deskripsi Kerusakan: {$this->deskripsiKerusakan}");
        }

        if ($this->catatan) {
            $mailMessage->line("• Catatan: {$this->catatan}");
        }

        if ($this->kondisiSetelah === 'Baik') {
            $mailMessage->line("Terima kasih telah mengembalikan dalam kondisi yang baik.")
                ->action('Lihat Riwayat', url('/client/riwayat/penggunaan'))
                ->line('Kami berharap dapat melayani Anda kembali.');
        } else {
            $mailMessage->line("Mohon maaf, item yang dikembalikan dalam kondisi rusak.")
                ->action('Lihat Riwayat', url('/client/riwayat/penggunaan'))
                ->line('Tim kami akan melakukan pengecekan lebih lanjut.');
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
            'kondisi_setelah' => $this->kondisiSetelah,
            'catatan' => $this->catatan,
            'deskripsi_kerusakan' => $this->deskripsiKerusakan,
            'type' => 'pengembalian_validation'
        ];
    }
}
