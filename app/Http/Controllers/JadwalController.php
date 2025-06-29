<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
            'month' => 'nullable|date_format:Y-m',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);
        $month = $request->input('month', now()->format('Y-m'));

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        // Parse the month parameter
        $currentDate = Carbon::parse($month . '-01');
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        // Get events for the calendar
        $query = Laporan::whereNotNull('ruangan_id')
            ->whereHas('ruangan')
            ->whereBetween('waktu_mulai', [$startOfMonth, $endOfMonth])
            ->whereNotIn('status_peminjaman', ['Ditolak']); // Exclude rejected bookings

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $events = $query->with(['user', 'ruangan'])->get();

        // Format events for calendar
        $calendarEvents = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->ruangan->name ?? 'Ruangan',
                'start' => $event->waktu_mulai,
                'end' => $event->waktu_selesai,
                'user' => $event->user->name ?? 'Unknown',
                'purpose' => $event->tujuan_penggunaan ?? '-',
                'status' => $event->status_peminjaman ?? 'Menunggu',
                'backgroundColor' => $this->getEventColor($event->status_peminjaman),
                'borderColor' => $this->getEventColor($event->status_peminjaman),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'user' => $event->user->name ?? 'Unknown',
                    'purpose' => $event->tujuan_penggunaan ?? '-',
                    'status' => $event->status_peminjaman ?? 'Menunggu',
                    'start_time' => Carbon::parse($event->waktu_mulai)->format('H:i'),
                    'end_time' => Carbon::parse($event->waktu_selesai)->format('H:i'),
                ]
            ];
        });

        // For backward compatibility, also provide paginated data
        if ($search) {
            $laporans = Laporan::whereNotNull('ruangan_id')
                ->whereHas('ruangan')
                ->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate($validPerPage);
        } else {
            $laporans = Laporan::whereNotNull('ruangan_id')
                ->whereHas('ruangan')
                ->paginate($validPerPage);
        }

        // Navigation months
        $prevMonth = $currentDate->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentDate->copy()->addMonth()->format('Y-m');

        return view('jadwal.index', compact(
            'laporans',
            'search',
            'perPage',
            'calendarEvents',
            'currentDate',
            'prevMonth',
            'nextMonth'
        ));
    }

    private function getEventColor($status)
    {
        switch ($status) {
            case 'Diterima':
                return '#28a745'; // Green
            case 'Ditolak':
                return '#dc3545'; // Red
            case 'Menunggu':
                return '#ffc107'; // Yellow
            default:
                return '#6c757d'; // Gray
        }
    }
}
