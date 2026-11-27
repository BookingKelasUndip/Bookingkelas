<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'booking_number',
        'date',
        'start_time',
        'duration',
        'end_time',
        'problem_description',
        'status',
        'tutor_pendamping',
        'catatan',
        'confirmed_at',
        'completed_at',
        'reschedule_date',
        'reschedule_start_time',
        'reschedule_end_time',
        'reschedule_duration',
        'reschedule_alasan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', '!=', 'ditolak');
    }

    public function effectiveDate(): ?string
    {
        if ($this->status === 'ajukan_ulang' && $this->reschedule_date) {
            return $this->reschedule_date->format('Y-m-d');
        }

        return $this->date?->format('Y-m-d');
    }

    public function effectiveStartMinutes(): int
    {
        $time = $this->status === 'ajukan_ulang' && $this->reschedule_start_time
            ? $this->reschedule_start_time
            : $this->start_time;

        return self::minutesFromTime($time);
    }

    public function effectiveEndMinutes(): int
    {
        $time = $this->status === 'ajukan_ulang' && $this->reschedule_end_time
            ? $this->reschedule_end_time
            : $this->end_time;

        return self::minutesFromTime($time);
    }

    public static function minutesFromTime(?string $value): int
    {
        if (! $value || ! preg_match('/^(\d{1,2})[.:](\d{2})$/', $value, $matches)) {
            return 0;
        }

        return ((int) $matches[1] * 60) + (int) $matches[2];
    }

    public function effectiveEndAt(): ?Carbon
    {
        $date = $this->effectiveDate();

        if (! $date) {
            return null;
        }

        $minutes = $this->effectiveEndMinutes();

        return Carbon::parse(sprintf(
            '%s %02d:%02d',
            $date,
            intdiv($minutes, 60) % 24,
            $minutes % 60,
        ));
    }

    public static function finalizePast(): int
    {
        if (! Schema::hasTable('bookings')) {
            return 0;
        }

        $hasCompletedAt = Schema::hasColumn('bookings', 'completed_at');

        $finalized = static::query()
            ->whereIn('status', ['menunggu', 'dikonfirmasi', 'ajukan_ulang'])
            ->get()
            ->filter(fn (self $booking) => $booking->effectiveEndAt()?->lt(now()) ?? false)
            ->each(function (self $booking) use ($hasCompletedAt) {
                $booking->update($hasCompletedAt
                    ? ['status' => 'selesai', 'completed_at' => now()]
                    : ['status' => 'selesai']);
            })
            ->count();

        static::purgeOldCompleted();

        return $finalized;
    }

    public static function purgeOldCompleted(): int
    {
        if (! Schema::hasTable('bookings') || ! Schema::hasColumn('bookings', 'completed_at')) {
            return 0;
        }

        $cutoff = now()->subDays(60);

        return static::query()
            ->where('status', 'selesai')
            ->where(function (Builder $query) use ($cutoff) {
                $query->where('completed_at', '<=', $cutoff)
                    ->orWhere(function (Builder $query) use ($cutoff) {
                        $query->whereNull('completed_at')
                            ->where('updated_at', '<=', $cutoff);
                    });
            })
            ->delete();
    }

    public static function conflictsWith(string $date, int $start, int $end, ?int $ignoreId = null): ?self
    {
        return static::active()
            ->when($ignoreId, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
            ->get()
            ->first(fn (self $booking) => $booking->effectiveDate() === $date
                && $start < $booking->effectiveEndMinutes()
                && $end > $booking->effectiveStartMinutes());
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'booking_number' => 'integer',
            'duration' => 'integer',
            'confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
            'reschedule_date' => 'date',
            'reschedule_duration' => 'integer',
        ];
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => match ($this->status) {
            'menunggu' => 'Menunggu',
            'dikonfirmasi' => 'Terkonfirmasi',
            'ajukan_ulang' => 'Ajukan Ulang',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => ucfirst((string) $this->status),
        });
    }

    protected function sessionLabel(): Attribute
    {
        return Attribute::get(fn () => match ((int) $this->duration) {
            30 => 'Sesi 1 (30 Menit)',
            60 => 'Sesi 2 (60 Menit)',
            90 => 'Sesi 3 (90 Menit)',
            120 => 'Sesi 4 (120 Menit)',
            default => ((int) $this->duration).' Menit',
        });
    }

    protected function rescheduleSessionLabel(): Attribute
    {
        return Attribute::get(fn () => match ((int) $this->reschedule_duration) {
            30 => 'Sesi 1 (30 Menit)',
            60 => 'Sesi 2 (60 Menit)',
            90 => 'Sesi 3 (90 Menit)',
            120 => 'Sesi 4 (120 Menit)',
            default => ((int) $this->reschedule_duration).' Menit',
        });
    }
}
