<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $status = $this->resolveStatus($request);

        return view('user.riwayatbooking', [
            'bookings' => Booking::with('user')
                ->where('user_id', auth()->id())
                ->when($status !== 'all', fn ($query) => $query->where('status', $status))
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'status' => $status,
        ]);
    }

    public function adminIndex(Request $request)
    {
        $status = $this->resolveStatus($request);

        return view('admin.kelolabooking', [
            'bookings' => Booking::with('user')
                ->when($status !== 'all', fn ($query) => $query->where('status', $status))
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'status' => $status,
        ]);
    }

    public function dailySchedule()
    {
        $bookings = Booking::with('user')
            ->latest()
            ->get();

        $today = Carbon::today();

        $slots = $bookings->map(function (Booking $booking) {
            $date = $booking->effectiveDate();

            return [
                'date' => $date,
                'label' => $date ? Carbon::parse($date)->locale('id')->translatedFormat('l, j F Y') : '',
                'start' => $this->formatMinutes($booking->effectiveStartMinutes()),
                'end' => $this->formatMinutes($booking->effectiveEndMinutes()),
                'session' => $booking->status === 'ajukan_ulang' && $booking->reschedule_duration
                    ? $booking->reschedule_session_label
                    : $booking->session_label,
                'user' => $booking->user?->name ?? 'Mahasiswa',
                'bookingNumber' => $booking->booking_number,
                'status' => $booking->status_label,
            ];
        })->filter(fn (array $slot) => $slot['date'] !== null)->values();

        return view('admin.jadwalharian', [
            'bookings' => $bookings,
            'slots' => $slots,
            'selectedDate' => $today->toDateString(),
            'selectedDateLabel' => $today->locale('id')->translatedFormat('l, j F Y'),
        ]);
    }

    private function ensureOwnBooking(Booking $booking): void
    {
        abort_unless($booking->user_id === auth()->id(), 403, 'Booking ini bukan milik Anda.');
    }

    private function resolveStatus(Request $request): string
    {
        $status = $request->query('status', 'all');

        return in_array($status, ['all', 'menunggu', 'dikonfirmasi', 'ajukan_ulang', 'selesai', 'ditolak'], true)
            ? $status
            : 'all';
    }

    public function create()
    {
        return view('user.bookingkelas', [
            'occupiedSlots' => Booking::active()
                ->get()
                ->map(fn (Booking $booking) => [
                    'date' => $booking->effectiveDate(),
                    'start' => $booking->effectiveStartMinutes(),
                    'end' => $booking->effectiveEndMinutes(),
                ])
                ->filter(fn (array $slot) => $slot['date'] !== null)
                ->values()
                ->all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'selected_date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'string', 'regex:/^\d{1,2}[.:]\d{2}$/'],
            'duration' => ['required', 'in:30,60,90,120'],
            'problem_description' => ['required', 'string', 'max:600'],
        ], [
            'selected_date.required' => 'Tanggal bimbingan wajib dipilih.',
            'start_time.required' => 'Jam mulai bimbingan wajib diisi.',
            'duration.required' => 'Durasi bimbingan wajib dipilih.',
            'problem_description.required' => 'Keluhan / kendala belajar wajib diisi.',
        ]);

        if ($this->isSunday($validated['selected_date'])) {
            return back()->withErrors([
                'selected_date' => 'Booking tidak tersedia di hari Minggu. Silakan pilih hari lain.',
            ])->withInput();
        }

        $start = $this->parseTime($validated['start_time']);

        if (! $start) {
            return back()->withErrors([
                'start_time' => 'Format jam mulai tidak valid.',
            ])->withInput();
        }

        $startMinutes = $start['hours'] * 60 + $start['minutes'];

        if ($validated['selected_date'] < now()->toDateString()) {
            return back()->withErrors([
                'selected_date' => 'Tanggal bimbingan tidak boleh di masa lalu.',
            ])->withInput();
        }

        if ($validated['selected_date'] === now()->toDateString()) {
            $nowMinutes = ((int) now()->format('H')) * 60 + (int) now()->format('i');

            if ($startMinutes < $nowMinutes) {
                return back()->withErrors([
                    'start_time' => 'Jam mulai tidak boleh lebih awal dari waktu sekarang.',
                ])->withInput();
            }
        }

        $duration = (int) $validated['duration'];
        $maxEnd = $this->maxEndMinutesForDate($validated['selected_date']);

        if ($startMinutes < 540 || $startMinutes > 1140) {
            return back()->withErrors([
                'start_time' => 'Jam mulai hanya boleh antara 09.00 dan 19.00 WIB.',
            ])->withInput();
        }

        if ($startMinutes + $duration > $maxEnd) {
            return back()->withErrors([
                'start_time' => $this->endLimitMessage($validated['selected_date']),
            ])->withInput();
        }

        $endMinutes = $startMinutes + $duration;

        if (Booking::conflictsWith($validated['selected_date'], $startMinutes, $endMinutes)) {
            return back()->withErrors([
                'start_time' => 'Jadwal ini sudah dibooking, silakan pilih jam lain.',
            ])->withInput();
        }

        Booking::create([
            'user_id' => auth()->id(),
            'booking_number' => (Booking::max('booking_number') ?? 0) + 1,
            'date' => $validated['selected_date'],
            'start_time' => $this->formatTime($start),
            'duration' => $duration,
            'end_time' => $this->formatTime([
                'hours' => intdiv($endMinutes, 60) % 24,
                'minutes' => $endMinutes % 60,
            ]),
            'problem_description' => $validated['problem_description'],
            'status' => 'menunggu',
        ]);

        return redirect()->route('riwayatbooking')
            ->with('success', 'Booking berhasil dikirim dan menunggu konfirmasi.');
    }

    public function confirm(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'tutor_pendamping' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'tutor_pendamping.required' => 'Tutor pendamping wajib diisi.',
            'catatan.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        $booking->update([
            'status' => 'dikonfirmasi',
            'tutor_pendamping' => $validated['tutor_pendamping'],
            'catatan' => $validated['catatan'] ?: null,
            'confirmed_at' => now(),
        ]);

        return redirect()->route('admin.kelolabooking')
            ->with('success', 'Bookingan ke-'.$booking->booking_number.' berhasil dikonfirmasi.');
    }

    public function reschedule(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'tanggal_baru' => ['required', 'date_format:Y-m-d'],
            'jam_baru' => ['required', 'string', 'regex:/^\d{1,2}[.:]\d{2}$/'],
            'durasi' => ['required', 'in:30,60,90,120'],
            'tutor_pendamping' => ['required', 'string', 'max:255'],
            'alasan' => ['required', 'string', 'max:1000'],
        ], [
            'tanggal_baru.required' => 'Tanggal baru wajib dipilih.',
            'jam_baru.required' => 'Jam pelaksanaan baru wajib diisi.',
            'durasi.required' => 'Durasi sesi wajib dipilih.',
            'tutor_pendamping.required' => 'Tutor pendamping wajib diisi.',
            'alasan.required' => 'Alasan pengajuan jadwal ulang wajib diisi.',
        ]);

        if ($this->isSunday($validated['tanggal_baru'])) {
            return back()->withErrors([
                'tanggal_baru' => 'Jadwal ulang tidak tersedia di hari Minggu. Silakan pilih tanggal lain.',
            ])->withInput();
        }

        $start = $this->parseTime($validated['jam_baru']);

        if (! $start) {
            return back()->withErrors([
                'jam_baru' => 'Format jam pelaksanaan tidak valid.',
            ])->withInput();
        }

        $startMinutes = $start['hours'] * 60 + $start['minutes'];
        $duration = (int) $validated['durasi'];
        $maxEnd = $this->maxEndMinutesForDate($validated['tanggal_baru']);

        if ($startMinutes < 540 || $startMinutes > 1140) {
            return back()->withErrors([
                'jam_baru' => 'Jam mulai hanya boleh antara 09.00 dan 19.00 WIB.',
            ])->withInput();
        }

        if ($startMinutes + $duration > $maxEnd) {
            return back()->withErrors([
                'jam_baru' => $this->endLimitMessage($validated['tanggal_baru']),
            ])->withInput();
        }

        $endMinutes = $startMinutes + $duration;

        if (Booking::conflictsWith($validated['tanggal_baru'], $startMinutes, $endMinutes, $booking->id)) {
            return back()->withErrors([
                'jam_baru' => 'Jadwal ini sudah dibooking, silakan pilih jam lain.',
            ])->withInput();
        }

        $booking->update([
            'status' => 'ajukan_ulang',
            'tutor_pendamping' => $validated['tutor_pendamping'],
            'reschedule_date' => $validated['tanggal_baru'],
            'reschedule_start_time' => $this->formatTime($start),
            'reschedule_end_time' => $this->formatTime([
                'hours' => intdiv($endMinutes, 60) % 24,
                'minutes' => $endMinutes % 60,
            ]),
            'reschedule_duration' => $duration,
            'reschedule_alasan' => $validated['alasan'],
        ]);

        return redirect()->route('admin.kelolabooking')
            ->with('success', 'Usulan jadwal ulang Bookingan ke-'.$booking->booking_number.' dikirim ke mahasiswa.');
    }

    public function acceptReschedule(Booking $booking)
    {
        $this->ensureOwnBooking($booking);

        if (! $booking->reschedule_date) {
            return back()->withErrors(['reschedule' => 'Tidak ada usulan jadwal ulang untuk booking ini.']);
        }

        $booking->update([
            'date' => $booking->reschedule_date,
            'start_time' => $booking->reschedule_start_time,
            'end_time' => $booking->reschedule_end_time,
            'duration' => $booking->reschedule_duration,
            'status' => 'dikonfirmasi',
            'confirmed_at' => now(),
            'reschedule_date' => null,
            'reschedule_start_time' => null,
            'reschedule_end_time' => null,
            'reschedule_duration' => null,
            'reschedule_alasan' => null,
        ]);

        return redirect()->route('riwayatbooking')
            ->with('success', 'Jadwal ulang disetujui. Detail booking terbaru sudah diperbarui.');
    }

    public function rejectReschedule(Booking $booking)
    {
        $this->ensureOwnBooking($booking);

        if (! $booking->reschedule_date) {
            return back()->withErrors(['reschedule' => 'Tidak ada usulan jadwal ulang untuk booking ini.']);
        }

        $booking->update([
            'status' => 'ditolak',
            'reschedule_date' => null,
            'reschedule_start_time' => null,
            'reschedule_end_time' => null,
            'reschedule_duration' => null,
            'reschedule_alasan' => null,
        ]);

        return redirect()->route('riwayatbooking')
            ->with('success', 'Usulan jadwal ulang ditolak.');
    }

    /**
     * @return array{hours: int, minutes: int}|null
     */
    private function parseTime(string $value): ?array
    {
        if (! preg_match('/^(\d{1,2})[.:](\d{2})$/', trim($value), $matches)) {
            return null;
        }

        $hours = (int) $matches[1];
        $minutes = (int) $matches[2];

        if ($hours > 23 || $minutes > 59) {
            return null;
        }

        return ['hours' => $hours, 'minutes' => $minutes];
    }

    /**
     * @param  array{hours: int, minutes: int}  $time
     */
    private function formatTime(array $time): string
    {
        return sprintf('%02d.%02d', $time['hours'], $time['minutes']);
    }

    private function formatMinutes(int $minutes): string
    {
        return sprintf('%02d.%02d', intdiv($minutes, 60) % 24, $minutes % 60);
    }

    private function isSunday(string $date): bool
    {
        return Carbon::parse($date)->dayOfWeek === 0;
    }

    private function isSaturday(string $date): bool
    {
        return Carbon::parse($date)->dayOfWeek === 6;
    }

    private function maxEndMinutesForDate(string $date): int
    {
        return $this->isSaturday($date) ? 14 * 60 : 19 * 60;
    }

    private function endLimitMessage(string $date): string
    {
        return $this->isSaturday($date)
            ? 'Booking hari Sabtu harus selesai maksimal 14.00 WIB. Sesuaikan jam mulai atau durasi.'
            : 'Bimbingan harus selesai maksimal 19.00 WIB. Sesuaikan jam mulai atau durasi.';
    }
}
