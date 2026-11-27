<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_is_saved_and_visible_in_history_and_admin(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('bookingkelas.store'), [
            'selected_date' => '2026-11-27',
            'start_time' => '09.00',
            'duration' => '60',
            'problem_description' => 'Membahas integrasi matriks.',
        ]);

        $response->assertRedirect(route('riwayatbooking'));

        $this->assertDatabaseHas('bookings', [
            'booking_number' => 1,
            'date' => '2026-11-27 00:00:00',
            'start_time' => '09.00',
            'end_time' => '10.00',
            'duration' => 60,
            'status' => 'menunggu',
            'user_id' => $user->id,
            'problem_description' => 'Membahas integrasi matriks.',
        ]);

        $this->get(route('riwayatbooking'))
            ->assertOk()
            ->assertSee($user->name)
            ->assertSee('Bookingan ke-1')
            ->assertSee('Menunggu')
            ->assertSee('09.00 – 10.00 WIB')
            ->assertSee('Membahas integrasi matriks.');

        $this->actingAs($admin)->get(route('admin.kelolabooking'))
            ->assertOk()
            ->assertSee($user->name)
            ->assertSee('Bookingan ke-1')
            ->assertSee('Menunggu Konfirmasi')
            ->assertSee('Membahas integrasi matriks.');
    }

    public function test_booking_requires_every_field(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post(route('bookingkelas.store'), [])
            ->assertSessionHasErrors(['selected_date', 'start_time', 'duration', 'problem_description']);
    }

    public function test_sunday_is_disabled_in_calendar(): void
    {
        $component = file_get_contents(resource_path('views/components/calendar.blade.php'));

        $this->assertIsString($component);
        $this->assertStringContainsString('getDay() === 0', $component);
        $this->assertStringContainsString('(!allowPast && thisDate < today) || isSunday', $component);
    }

    public function test_calendar_marks_today_with_blue_ring(): void
    {
        $component = file_get_contents(resource_path('views/components/calendar.blade.php'));

        $this->assertIsString($component);
        $this->assertStringContainsString('const isToday = thisDate.getTime() === today.getTime();', $component);
        $this->assertStringContainsString('ring-2 ring-[#163683] ring-offset-1', $component);
    }

    public function test_user_calendar_cannot_select_past_dates(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get(route('bookingkelas'))
            ->assertOk()
            ->assertSee('data-allow-past="0"', false);
    }

    public function test_admin_daily_calendar_can_select_past_dates(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.jadwalharian'))
            ->assertOk()
            ->assertSee('data-allow-past="1"', false);
    }

    public function test_booking_cannot_be_made_on_sunday(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post(route('bookingkelas.store'), [
            'selected_date' => '2026-11-29',
            'start_time' => '09.00',
            'duration' => '30',
            'problem_description' => 'Membahas integrasi matriks.',
        ])->assertSessionHasErrors('selected_date');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_admin_reschedule_cannot_be_on_sunday(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->makeBooking('dikonfirmasi', 1);

        $this->actingAs($admin)->post(route('admin.booking.reschedule', $booking), [
            'tanggal_baru' => '2026-11-29',
            'jam_baru' => '09.00',
            'durasi' => '30',
            'tutor_pendamping' => 'Bu Rina, S.Pd., M.Kom',
            'alasan' => 'Perubahan jadwal dosen.',
        ])->assertSessionHasErrors('tanggal_baru');
    }

    public function test_saturday_booking_must_end_by_1400(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $payload = [
            'selected_date' => '2026-11-28',
            'problem_description' => 'Membahas integrasi matriks.',
        ];

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '13.00',
            'duration' => '120',
        ])->assertSessionHasErrors('start_time');

        $this->assertStringContainsString('14.00', (string) session('errors')->first('start_time'));

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '13.30',
            'duration' => '30',
        ])->assertRedirect(route('riwayatbooking'));

        $this->assertDatabaseHas('bookings', [
            'date' => '2026-11-28 00:00:00',
            'start_time' => '13.30',
            'end_time' => '14.00',
            'duration' => 30,
        ]);
    }

    public function test_admin_reschedule_cannot_end_after_1400_on_saturday(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->makeBooking('dikonfirmasi', 1);

        $this->actingAs($admin)->post(route('admin.booking.reschedule', $booking), [
            'tanggal_baru' => '2026-11-28',
            'jam_baru' => '13.30',
            'durasi' => '120',
            'tutor_pendamping' => 'Bu Rina, S.Pd., M.Kom',
            'alasan' => 'Perubahan jadwal dosen.',
        ])->assertSessionHasErrors('jam_baru');

        $this->assertStringContainsString('14.00', (string) session('errors')->first('jam_baru'));

        $this->actingAs($admin)->post(route('admin.booking.reschedule', $booking), [
            'tanggal_baru' => '2026-11-28',
            'jam_baru' => '12.00',
            'durasi' => '120',
            'tutor_pendamping' => 'Bu Rina, S.Pd., M.Kom',
            'alasan' => 'Perubahan jadwal dosen.',
        ])->assertRedirect(route('admin.kelolabooking'));

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'reschedule_end_time' => '14.00',
            'reschedule_duration' => 120,
        ]);
    }

    public function test_booking_form_applies_saturday_end_limit(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $content = $this->actingAs($user)->get(route('bookingkelas'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('const saturdayEnd = 14 * 60;', $content);
        $this->assertStringContainsString('getDay() === 6 ? saturdayEnd : maxEnd', $content);
        $this->assertStringContainsString('Booking hari Sabtu harus selesai maksimal 14.00 WIB', $content);
    }

    public function test_booking_calendar_receives_active_booked_slots(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $otherUser = User::factory()->create(['role' => 'user']);

        Booking::create([
            'user_id' => $user->id,
            'booking_number' => 1,
            'date' => '2026-11-28 00:00:00',
            'start_time' => '09.00',
            'duration' => 60,
            'end_time' => '10.00',
            'problem_description' => 'Membahas integrasi matriks.',
            'status' => 'menunggu',
        ]);

        Booking::create([
            'user_id' => $otherUser->id,
            'booking_number' => 2,
            'date' => '2026-11-28 00:00:00',
            'start_time' => '18.30',
            'duration' => 30,
            'end_time' => '19.00',
            'problem_description' => 'Membahas turunan fungsi.',
            'status' => 'dikonfirmasi',
        ]);

        Booking::create([
            'user_id' => $otherUser->id,
            'booking_number' => 3,
            'date' => '2026-11-29 00:00:00',
            'start_time' => '13.00',
            'duration' => 30,
            'end_time' => '13.30',
            'problem_description' => 'Booking ditolak.',
            'status' => 'ditolak',
        ]);

        $content = $this->actingAs($user)->get(route('bookingkelas'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('Bookingan pada Tanggal Terpilih', $content);
        $this->assertStringContainsString('id="booked-slots-list"', $content);
        $this->assertStringContainsString('sudah dibooking', $content);
        $this->assertStringContainsString('renderBookedSlots(event.detail.date, event.detail.label)', $content);
        $this->assertStringContainsString('"date":"2026-11-28","start":540,"end":600', $content);
        $this->assertStringContainsString('"date":"2026-11-28","start":1110,"end":1140', $content);
        $this->assertStringNotContainsString('"date":"2026-11-29"', $content);
    }

    public function test_booking_cannot_end_after_1900(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $payload = [
            'selected_date' => '2026-11-27',
            'problem_description' => 'Membahas integrasi matriks.',
        ];

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '19.00',
            'duration' => '30',
        ])->assertSessionHasErrors('start_time');

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '18.30',
            'duration' => '90',
        ])->assertSessionHasErrors('start_time');

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '18.30',
            'duration' => '30',
        ])->assertRedirect(route('riwayatbooking'));

        $this->assertDatabaseHas('bookings', [
            'start_time' => '18.30',
            'end_time' => '19.00',
            'duration' => 30,
        ]);
    }

    public function test_app_uses_wib_for_booking_time_validation(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
        $this->assertSame('Asia/Jakarta', now()->timezoneName);
    }

    public function test_user_cannot_book_a_past_time_today(): void
    {
        Carbon::setTestNow('2026-11-27 11:35:00');

        $user = User::factory()->create(['role' => 'user']);
        $payload = [
            'selected_date' => '2026-11-27',
            'duration' => '30',
            'problem_description' => 'Membahas integrasi matriks.',
        ];

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '09.00',
        ])->assertSessionHasErrors('start_time');

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '11.00',
        ])->assertSessionHasErrors('start_time');

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '11.30',
        ])->assertSessionHasErrors('start_time');

        $this->assertDatabaseCount('bookings', 0);

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '12.00',
        ])->assertRedirect(route('riwayatbooking'));

        $this->assertDatabaseHas('bookings', ['start_time' => '12.00']);
    }

    public function test_user_cannot_book_a_past_date(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post(route('bookingkelas.store'), [
            'selected_date' => '2026-11-26',
            'start_time' => '09.00',
            'duration' => '30',
            'problem_description' => 'Membahas integrasi matriks.',
        ])->assertSessionHasErrors('selected_date');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_page_knows_current_time_for_today_slots(): void
    {
        Carbon::setTestNow('2026-11-27 11:35:00');

        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get(route('bookingkelas'))
            ->assertOk()
            ->assertSee('window.bookingNowMinutes = 695;', false)
            ->assertSee('window.bookingToday = "2026-11-27";', false)
            ->assertSee('refreshStartTimeOptions();', false);
    }

    public function test_booking_supports_120_minute_session(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post(route('bookingkelas.store'), [
            'selected_date' => '2026-12-02',
            'start_time' => '09.00',
            'duration' => '120',
            'problem_description' => 'Membahas integrasi matriks.',
        ])->assertRedirect(route('riwayatbooking'));

        $this->assertDatabaseHas('bookings', [
            'start_time' => '09.00',
            'end_time' => '11.00',
            'duration' => 120,
        ]);

        $this->get(route('riwayatbooking'))
            ->assertOk()
            ->assertSee('Sesi 4 (120 Menit)');
    }

    public function test_120_minute_session_must_start_by_1700(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $payload = [
            'selected_date' => '2026-12-02',
            'problem_description' => 'Membahas integrasi matriks.',
            'duration' => '120',
        ];

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '17.30',
        ])->assertSessionHasErrors('start_time');

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '17.00',
        ])->assertRedirect(route('riwayatbooking'));

        $this->assertDatabaseHas('bookings', [
            'start_time' => '17.00',
            'end_time' => '19.00',
            'duration' => 120,
        ]);
    }

    public function test_admin_reschedule_supports_120_minute_session(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->makeBooking('dikonfirmasi', 1);

        $this->actingAs($admin)->post(route('admin.booking.reschedule', $booking), [
            'tanggal_baru' => '2026-12-03',
            'jam_baru' => '13.00',
            'durasi' => '120',
            'tutor_pendamping' => 'Bu Rina, S.Pd., M.Kom',
            'alasan' => 'Perubahan jadwal dosen.',
        ])->assertRedirect(route('admin.kelolabooking'));

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'reschedule_start_time' => '13.00',
            'reschedule_end_time' => '15.00',
            'reschedule_duration' => 120,
        ]);

        $this->actingAs($admin)->get(route('admin.kelolabooking'))
            ->assertOk()
            ->assertSee('Sesi 4 (120 Menit)');
    }

    public function test_booking_form_has_half_hour_start_options_and_fourth_session(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get(route('bookingkelas'))
            ->assertOk()
            ->assertSee('Pilih Jam Mulai Bimbingan')
            ->assertSee('value="09.00"', false)
            ->assertSee('value="09.30"', false)
            ->assertSee('value="18.30"', false)
            ->assertSee('Sesi 4 (120 Menit)')
            ->assertDontSee('placeholder="09.00"', false);
    }

    public function test_same_slot_cannot_be_booked_by_another_user(): void
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        $payload = [
            'selected_date' => '2026-12-01',
            'start_time' => '09.00',
            'duration' => '30',
            'problem_description' => 'Membahas integrasi matriks.',
        ];

        $this->actingAs($user1)->post(route('bookingkelas.store'), $payload)
            ->assertRedirect(route('riwayatbooking'));

        $this->actingAs($user2)->post(route('bookingkelas.store'), $payload)
            ->assertSessionHasErrors('start_time');

        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_overlapping_slots_are_rejected_but_adjacent_slots_are_allowed(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $user = User::factory()->create(['role' => 'user']);
        $this->makeBooking('menunggu', 1, $owner);

        $payload = [
            'selected_date' => '2026-11-27',
            'problem_description' => 'Membahas integrasi matriks.',
        ];

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '09.30',
            'duration' => '30',
        ])->assertSessionHasErrors('start_time');

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'start_time' => '10.00',
            'duration' => '30',
        ])->assertRedirect(route('riwayatbooking'));

        $this->assertDatabaseCount('bookings', 2);
    }

    public function test_rejected_booking_releases_its_slot(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $user = User::factory()->create(['role' => 'user']);
        $this->makeBooking('ditolak', 1, $owner);

        $this->actingAs($user)->post(route('bookingkelas.store'), [
            'selected_date' => '2026-11-27',
            'start_time' => '09.00',
            'duration' => '30',
            'problem_description' => 'Membahas integrasi matriks.',
        ])->assertRedirect(route('riwayatbooking'));
    }

    public function test_reschedule_proposal_locks_the_proposed_slot(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $user = User::factory()->create(['role' => 'user']);
        $booking = $this->makeBooking('ajukan_ulang', 1, $owner);
        $booking->update([
            'reschedule_date' => '2026-12-01',
            'reschedule_start_time' => '11.00',
            'reschedule_end_time' => '11.30',
            'reschedule_duration' => 30,
        ]);

        $payload = [
            'problem_description' => 'Membahas integrasi matriks.',
            'duration' => '30',
        ];

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'selected_date' => '2026-11-27',
            'start_time' => '09.00',
        ])->assertRedirect(route('riwayatbooking'));

        $this->actingAs($user)->post(route('bookingkelas.store'), $payload + [
            'selected_date' => '2026-12-01',
            'start_time' => '11.00',
        ])->assertSessionHasErrors('start_time');
    }

    public function test_admin_reschedule_conflicting_slot_is_rejected_but_own_slot_is_allowed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->makeBooking('menunggu', 1);
        $other = $this->makeBooking('dikonfirmasi', 2);

        $payload = [
            'tanggal_baru' => '2026-11-27',
            'jam_baru' => '09.00',
            'durasi' => '30',
            'tutor_pendamping' => 'Bu Rina, S.Pd., M.Kom',
            'alasan' => 'Perubahan jadwal dosen.',
        ];

        $this->actingAs($admin)->post(route('admin.booking.reschedule', $booking), $payload)
            ->assertSessionHasErrors('jam_baru');

        $other->delete();

        $this->actingAs($admin)->post(route('admin.booking.reschedule', $booking), $payload)
            ->assertRedirect(route('admin.kelolabooking'));
    }

    public function test_booking_form_receives_occupied_slots(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->makeBooking('menunggu', 1);

        $this->actingAs($user)->get(route('bookingkelas'))
            ->assertOk()
            ->assertSee('window.bookingOccupiedSlots', false)
            ->assertSee('"date":"2026-11-27"', false)
            ->assertSee('"start":540', false)
            ->assertSee('"end":600', false);
    }

    public function test_admin_reschedule_cannot_end_after_1900(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->makeBooking('dikonfirmasi', 1);

        $this->actingAs($admin)->post(route('admin.booking.reschedule', $booking), [
            'tanggal_baru' => '2026-12-01',
            'jam_baru' => '19.00',
            'durasi' => '30',
            'tutor_pendamping' => 'Bu Rina, S.Pd., M.Kom',
            'alasan' => 'Perubahan jadwal dosen.',
        ])->assertSessionHasErrors('jam_baru');
    }

    public function test_logo_asset_points_to_existing_file(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $logoUrl = asset('images/Logo_Universitas_Diponegoro.png');

        foreach (['beranda', 'bookingkelas', 'riwayatbooking'] as $routeName) {
            $content = $this->actingAs($user)->get(route($routeName))
                ->assertOk()
                ->getContent();

            $this->assertStringContainsString($logoUrl, $content);
            $this->assertStringNotContainsString('images/logo-undip.png', $content);
        }

        $this->assertFileExists(public_path('images/Logo_Universitas_Diponegoro.png'));
    }

    public function test_user_pages_use_blue_accent_without_dark_navy(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->makeBooking('menunggu', 1, $user);

        foreach (['beranda', 'bookingkelas', 'riwayatbooking'] as $routeName) {
            $content = $this->actingAs($user)->get(route($routeName))
                ->assertOk()
                ->getContent();

            $this->assertStringContainsString('#1d3c8a', $content);
            $this->assertStringNotContainsString('#10182b', $content);
        }
    }

    public function test_calendar_uses_blue_accent_instead_of_dark_navy(): void
    {
        $component = file_get_contents(resource_path('views/components/calendar.blade.php'));

        $this->assertIsString($component);
        $this->assertStringContainsString('bg-[#163683]', $component);
        $this->assertStringNotContainsString('#10182b', $component);
    }

    public function test_booking_form_only_offers_custom_time_session(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get(route('bookingkelas'))
            ->assertOk()
            ->assertDontSee('Sesi Standar Kampus')
            ->assertDontSee('Custom Jam Mandiri')
            ->assertSee('Atur Jam Mandiri Sendiri (Fleksibel)')
            ->assertSee('Atur jam mulai dan durasi bimbingan sesuai waktu luang Anda.')
            ->assertSee('Pilih Jam Mulai Bimbingan')
            ->assertSee('Pilihan Durasi Bimbingan');
    }

    public function test_admin_empty_state_matches_user_history_style(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.kelolabooking'))
            ->assertOk()
            ->assertSee('Belum ada booking')
            ->assertSee('Permohonan bimbingan mahasiswa akan muncul di sini')
            ->assertDontSee('Belum ada Bookingan')
            ->assertDontSee('Buat Kelas Baru')
            ->assertDontSee(route('admin.buatkelas'), false)
            ->assertDontSee(route('admin.kelolapermohonan'), false);
    }

    public function test_admin_kelola_booking_has_confirmation_modal(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Booking::create([
            'booking_number' => 1,
            'date' => '2026-11-27 00:00:00',
            'start_time' => '09.00',
            'duration' => 60,
            'end_time' => '10.00',
            'problem_description' => 'Membahas integrasi matriks.',
            'status' => 'menunggu',
        ]);

        $this->actingAs($admin)->get(route('admin.kelolabooking'))
            ->assertOk()
            ->assertSee('Bookingan ke-1')
            ->assertSee('Konfirmasi', false)
            ->assertSee('Jadwal Ulang')
            ->assertSee('Tutor Pendamping')
            ->assertSee('Catatan')
            ->assertSee('Kembali')
            ->assertSee('Setujui')
            ->assertSee('konfirmasi-modal')
            ->assertSee('Pilih Tanggal Baru')
            ->assertSee('Atur Jam Pelaksanaan Baru')
            ->assertSee('Durasi / Sesi Bimbingan')
            ->assertSee('Sesi 1 (30 Menit)')
            ->assertSee('Sesi 3 (90 Menit)')
            ->assertSee('Alasan Pengajuan Jadwal Ulang')
            ->assertSee('Ajukan Jadwal Ulang')
            ->assertSee('jadwal-ulang-modal');
    }

    public function test_admin_can_confirm_booking_and_user_sees_the_answer(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $booking = $this->makeBooking('menunggu', 1, $user);

        $this->actingAs($admin)->post(route('admin.booking.konfirmasi', $booking), [
            'tutor_pendamping' => 'Dr. Sutanto, M.Kom',
            'catatan' => 'Datang tepat waktu.',
        ])->assertRedirect(route('admin.kelolabooking'));

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'dikonfirmasi',
            'tutor_pendamping' => 'Dr. Sutanto, M.Kom',
            'catatan' => 'Datang tepat waktu.',
        ]);
        $this->assertNotNull($booking->fresh()->confirmed_at);

        $this->actingAs($user)->get(route('riwayatbooking'))
            ->assertOk()
            ->assertSee('Terkonfirmasi')
            ->assertSee('Lihat Jawaban')
            ->assertSee('Bookingan anda telah dikonfirmasi')
            ->assertSee('Dr. Sutanto, M.Kom')
            ->assertSee('Datang tepat waktu.');
    }

    public function test_confirmed_booking_without_note_shows_tidak_ada_catatan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $booking = $this->makeBooking('menunggu', 1, $user);

        $this->actingAs($admin)->post(route('admin.booking.konfirmasi', $booking), [
            'tutor_pendamping' => 'Bu Rina, S.Pd., M.Kom',
            'catatan' => '',
        ])->assertRedirect(route('admin.kelolabooking'));

        $this->assertNull($booking->fresh()->catatan);

        $this->actingAs($user)->get(route('riwayatbooking'))
            ->assertOk()
            ->assertSee('data-catatan="Tidak ada catatan"', false);
    }

    public function test_confirmation_requires_tutor_and_only_admins_can_confirm(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $booking = $this->makeBooking();

        $this->actingAs($admin)->post(route('admin.booking.konfirmasi', $booking), [
            'catatan' => 'Tanpa tutor.',
        ])->assertSessionHasErrors('tutor_pendamping');

        $this->post(route('logout'));
        $this->post(route('admin.booking.konfirmasi', $booking))
            ->assertRedirect(route('admin.login'));

        $this->actingAs($user)->post(route('admin.booking.konfirmasi', $booking))
            ->assertForbidden();
    }

    public function test_status_filters_work_on_user_history_and_admin_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $this->makeBooking('menunggu', 1, $user);
        $this->makeBooking('dikonfirmasi', 2, $user);
        $this->makeBooking('ditolak', 3, $user);

        $this->actingAs($user)->get(route('riwayatbooking', ['status' => 'menunggu']))
            ->assertOk()
            ->assertSee('Bookingan ke-1')
            ->assertDontSee('Bookingan ke-2')
            ->assertDontSee('Bookingan ke-3');

        $this->actingAs($user)->get(route('riwayatbooking', ['status' => 'dikonfirmasi']))
            ->assertOk()
            ->assertSee('Bookingan ke-2')
            ->assertDontSee('Bookingan ke-1')
            ->assertDontSee('Bookingan ke-3');

        $this->actingAs($admin)->get(route('admin.kelolabooking', ['status' => 'menunggu']))
            ->assertOk()
            ->assertSee('Bookingan ke-1')
            ->assertDontSee('Bookingan ke-2')
            ->assertDontSee('Bookingan ke-3');

        $this->actingAs($admin)->get(route('admin.kelolabooking', ['status' => 'dikonfirmasi']))
            ->assertOk()
            ->assertSee('Bookingan ke-2')
            ->assertDontSee('Bookingan ke-1')
            ->assertDontSee('Bookingan ke-3');

        $this->actingAs($user)->get(route('riwayatbooking'))
            ->assertOk()
            ->assertSee('Bookingan ke-1')
            ->assertSee('Bookingan ke-2')
            ->assertSee('Bookingan ke-3');
    }

    public function test_admin_action_buttons_hide_after_booking_is_no_longer_menunggu(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->makeBooking('menunggu', 1);
        $this->makeBooking('dikonfirmasi', 2);
        $this->makeBooking('ditolak', 3);
        $this->makeBooking('ajukan_ulang', 4);

        $this->actingAs($admin)->get(route('admin.kelolabooking'))
            ->assertOk()
            ->assertSee('js-konfirmasi', false)
            ->assertSee('js-jadwal-ulang', false);

        foreach (['dikonfirmasi', 'ditolak', 'ajukan_ulang'] as $status) {
            $response = $this->actingAs($admin)->get(route('admin.kelolabooking', ['status' => $status]))
                ->assertOk()
                ->assertDontSee('Bookingan ke-1');

            $content = $response->getContent();
            $this->assertStringNotContainsString('js-konfirmasi', substr($content, 0, strpos($content, '<script>')));
            $this->assertStringNotContainsString('js-jadwal-ulang', substr($content, 0, strpos($content, '<script>')));
        }
    }

    public function test_admin_reschedule_is_proposed_to_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $booking = $this->makeBooking('dikonfirmasi', 1, $user);
        $booking->update(['tutor_pendamping' => 'Dr. Sutanto, M.Kom']);

        $this->actingAs($admin)->post(route('admin.booking.reschedule', $booking), [
            'tanggal_baru' => '2026-12-01',
            'jam_baru' => '10.00',
            'durasi' => '90',
            'tutor_pendamping' => 'Dr. Sutanto, M.Kom',
            'alasan' => 'Dosen ada keperluan mendadak.',
        ])->assertRedirect(route('admin.kelolabooking'));

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'ajukan_ulang',
            'reschedule_date' => '2026-12-01 00:00:00',
            'reschedule_start_time' => '10.00',
            'reschedule_end_time' => '11.30',
            'reschedule_duration' => 90,
            'reschedule_alasan' => 'Dosen ada keperluan mendadak.',
        ]);

        $this->actingAs($admin)->get(route('admin.kelolabooking'))
            ->assertOk()
            ->assertSee('Usulan Jadwal Ulang Terkirim')
            ->assertSee('Tanggal Baru')
            ->assertSee('Selasa, 01 Desember 2026')
            ->assertSee('10.00 WIB')
            ->assertSee('Sesi 3 (90 Menit)')
            ->assertSee('Dosen ada keperluan mendadak.')
            ->assertSee('Jadwal sebelumnya')
            ->assertSee('Jumat, 27 November 2026');

        $this->actingAs($user)->get(route('riwayatbooking'))
            ->assertOk()
            ->assertSee('Ajukan Ulang')
            ->assertSee('Lihat Jawaban')
            ->assertSee('Usulan Jadwal Ulang dari Admin')
            ->assertSee('01 Desember 2026')
            ->assertSee('10.00 – 11.30 WIB')
            ->assertSee('Sesi 3 (90 Menit)')
            ->assertSee('Dosen ada keperluan mendadak.');
    }

    public function test_user_accepting_reschedule_applies_new_schedule(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $booking = $this->makeBooking('dikonfirmasi', 1, $user);

        $this->actingAs($admin)->post(route('admin.booking.reschedule', $booking), [
            'tanggal_baru' => '2026-12-01',
            'jam_baru' => '10.00',
            'durasi' => '90',
            'tutor_pendamping' => 'Bu Rina, S.Pd., M.Kom',
            'alasan' => 'Perubahan jadwal dosen.',
        ]);

        $this->actingAs($user)->post(route('riwayatbooking.setuju', $booking))
            ->assertRedirect(route('riwayatbooking'));

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'dikonfirmasi',
            'date' => '2026-12-01 00:00:00',
            'start_time' => '10.00',
            'end_time' => '11.30',
            'duration' => 90,
            'tutor_pendamping' => 'Bu Rina, S.Pd., M.Kom',
            'reschedule_date' => null,
            'reschedule_alasan' => null,
        ]);
    }

    public function test_user_rejecting_reschedule_sets_status_ditolak(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $booking = $this->makeBooking('dikonfirmasi', 1, $user);

        $this->actingAs($admin)->post(route('admin.booking.reschedule', $booking), [
            'tanggal_baru' => '2026-12-01',
            'jam_baru' => '10.00',
            'durasi' => '30',
            'tutor_pendamping' => 'Bu Rina, S.Pd., M.Kom',
            'alasan' => 'Perubahan jadwal dosen.',
        ]);

        $this->actingAs($user)->post(route('riwayatbooking.tolak', $booking))
            ->assertRedirect(route('riwayatbooking'));

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'ditolak',
            'date' => '2026-11-27 00:00:00',
            'reschedule_date' => null,
            'reschedule_alasan' => null,
        ]);
    }

    public function test_reschedule_requires_all_fields_and_only_admins_can_reschedule(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $booking = $this->makeBooking('dikonfirmasi', 1);

        $this->actingAs($admin)->post(route('admin.booking.reschedule', $booking), [
            'tanggal_baru' => '2026-12-01',
            'jam_baru' => '10.00',
            'durasi' => '60',
            'tutor_pendamping' => 'Bu Rina, S.Pd., M.Kom',
        ])->assertSessionHasErrors('alasan');

        $this->actingAs($user)->post(route('admin.booking.reschedule', $booking))
            ->assertForbidden();
    }

    public function test_admin_daily_schedule_summary_is_above_calendar_and_compact(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $content = $this->actingAs($admin)->get(route('admin.jadwalharian'))
            ->assertOk()
            ->getContent();

        $summaryPosition = strpos($content, 'Ringkasan Hari Terpilih');
        $calendarPosition = strpos($content, 'admin-jadwal-calendar');

        $this->assertNotFalse($summaryPosition);
        $this->assertNotFalse($calendarPosition);
        $this->assertLessThan($calendarPosition, $summaryPosition);
        $this->assertStringNotContainsString('grid grid-cols-1 gap-4 sm:grid-cols-2', $content);
    }

    public function test_admin_daily_schedule_shows_the_user_booking_card(): void
    {
        Carbon::setTestNow('2026-11-27 10:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user', 'name' => 'Budi Santoso']);
        $this->makeBooking('menunggu', 1, $user);

        $this->actingAs($admin)->get(route('admin.jadwalharian'))
            ->assertOk()
            ->assertSee('Jumat, 27 November 2026')
            ->assertSee('1 Booking Mandiri')
            ->assertSee('Budi Santoso')
            ->assertSee('Bookingan ke-1')
            ->assertSee('09.00 – 10.00 WIB')
            ->assertSee('Sesi 2 (60 Menit)')
            ->assertSee('data-booking-date="2026-11-27"', false)
            ->assertSee('id="jadwalharian-empty" class="hidden ', false);
    }

    public function test_admin_daily_schedule_includes_completed_and_rejected_bookings(): void
    {
        Carbon::setTestNow('2026-11-27 10:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user', 'name' => 'Budi Santoso']);
        $completedUser = User::factory()->create(['role' => 'user', 'name' => 'Siti Aminah']);
        $rejectedUser = User::factory()->create(['role' => 'user', 'name' => 'Andi Pratama']);

        $this->makeBooking('menunggu', 1, $user);
        $this->makeBooking('selesai', 2, $completedUser);
        $this->makeBooking('ditolak', 3, $rejectedUser);

        $this->actingAs($admin)->get(route('admin.jadwalharian'))
            ->assertOk()
            ->assertSee('Budi Santoso')
            ->assertSee('Siti Aminah')
            ->assertSee('Andi Pratama')
            ->assertSee('3 Booking Mandiri');
    }

    public function test_admin_daily_schedule_uses_rescheduled_slot_for_ajukan_ulang(): void
    {
        Carbon::setTestNow('2026-12-01 10:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user', 'name' => 'Budi Santoso']);
        $booking = $this->makeBooking('ajukan_ulang', 1, $user);
        $booking->update([
            'reschedule_date' => '2026-12-01',
            'reschedule_start_time' => '11.00',
            'reschedule_end_time' => '11.30',
            'reschedule_duration' => 30,
            'reschedule_alasan' => 'Perubahan jadwal dosen.',
        ]);

        $this->actingAs($admin)->get(route('admin.jadwalharian'))
            ->assertOk()
            ->assertSee('Selasa, 1 Desember 2026')
            ->assertSee('11.00 – 11.30 WIB')
            ->assertSee('Sesi 1 (30 Menit)')
            ->assertSee('data-booking-date="2026-12-01"', false)
            ->assertDontSee('09.00 – 10.00 WIB')
            ->assertSee('1 Booking Mandiri');
    }

    public function test_booking_heading_shows_the_name_of_the_user_who_booked_it(): void
    {
        $user = User::factory()->create(['role' => 'user', 'name' => 'Budi Santoso']);
        $other = User::factory()->create(['role' => 'user', 'name' => 'Siti Aminah']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->makeBooking('menunggu', 1, $user);
        $this->makeBooking('dikonfirmasi', 2, $other);

        $this->actingAs($user)->get(route('riwayatbooking'))
            ->assertOk()
            ->assertSee('Budi Santoso')
            ->assertDontSee('Siti Aminah');

        $this->actingAs($other)->get(route('riwayatbooking'))
            ->assertOk()
            ->assertSee('Siti Aminah')
            ->assertDontSee('Budi Santoso');

        $this->actingAs($admin)->get(route('admin.kelolabooking'))
            ->assertOk()
            ->assertSee('Budi Santoso')
            ->assertSee('Siti Aminah');
    }

    public function test_user_history_and_reschedule_actions_are_isolated_per_user(): void
    {
        $user = User::factory()->create(['role' => 'user', 'name' => 'Budi Santoso']);
        $other = User::factory()->create(['role' => 'user', 'name' => 'Siti Aminah']);

        $mine = $this->makeBooking('menunggu', 1, $user);
        $theirs = $this->makeBooking('ajukan_ulang', 2, $other);
        $theirs->update([
            'reschedule_date' => '2026-12-01',
            'reschedule_start_time' => '11.00',
            'reschedule_end_time' => '11.30',
            'reschedule_duration' => 30,
        ]);

        $this->actingAs($user)->get(route('riwayatbooking'))
            ->assertOk()
            ->assertSee('Bookingan ke-1')
            ->assertDontSee('Bookingan ke-2')
            ->assertDontSee('Siti Aminah');

        $this->actingAs($user)->post(route('riwayatbooking.setuju', $theirs))->assertForbidden();
        $this->actingAs($user)->post(route('riwayatbooking.tolak', $theirs))->assertForbidden();

        $this->assertDatabaseHas('bookings', ['id' => $mine->id, 'user_id' => $user->id]);
        $this->assertDatabaseHas('bookings', ['id' => $theirs->id, 'status' => 'ajukan_ulang']);
    }

    public function test_admin_kelolabooking_paginates_ten_per_page_and_keeps_status_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        for ($number = 1; $number <= 25; $number++) {
            $this->makeBooking('menunggu', $number);
        }

        $first = $this->actingAs($admin)->get(route('admin.kelolabooking'))->assertOk();
        $this->assertSame(10, substr_count($first->getContent(), '<article'));
        $first->assertSee('Menampilkan 1–10 dari 25 booking.');

        $second = $this->get(route('admin.kelolabooking', ['page' => 2]))->assertOk();
        $this->assertSame(10, substr_count($second->getContent(), '<article'));
        $second->assertSee('Menampilkan 11–20 dari 25 booking.');

        $third = $this->get(route('admin.kelolabooking', ['page' => 3]))->assertOk();
        $this->assertSame(5, substr_count($third->getContent(), '<article'));
        $third->assertSee('Menampilkan 21–25 dari 25 booking.');

        $filtered = $this->get(route('admin.kelolabooking', ['status' => 'menunggu']))->assertOk();
        $this->assertMatchesRegularExpression('/status=menunggu[^"\s]*page=2/', $filtered->getContent());
    }

    public function test_user_history_paginates_ten_per_page_and_keeps_status_filter(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        for ($number = 1; $number <= 25; $number++) {
            $this->makeBooking('menunggu', $number, $user);
        }

        $first = $this->actingAs($user)->get(route('riwayatbooking'))->assertOk();
        $this->assertSame(10, substr_count($first->getContent(), '<article'));
        $first->assertSee('Menampilkan 1–10 dari 25 booking.');

        $second = $this->get(route('riwayatbooking', ['page' => 2]))->assertOk();
        $this->assertSame(10, substr_count($second->getContent(), '<article'));
        $second->assertSee('Menampilkan 11–20 dari 25 booking.');

        $third = $this->get(route('riwayatbooking', ['page' => 3]))->assertOk();
        $this->assertSame(5, substr_count($third->getContent(), '<article'));
        $third->assertSee('Menampilkan 21–25 dari 25 booking.');

        $filtered = $this->get(route('riwayatbooking', ['status' => 'menunggu']))->assertOk();
        $this->assertMatchesRegularExpression('/status=menunggu[^"\s]*page=2/', $filtered->getContent());
    }

    public function test_completed_bookings_older_than_sixty_days_are_purged_everywhere(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $old = $this->makeBooking('selesai', 1, $user);
        $old->update(['completed_at' => now()->subDays(61)]);

        $recent = $this->makeBooking('selesai', 2, $user);
        $recent->update(['completed_at' => now()->subDays(59)]);

        $this->actingAs($user)->get(route('riwayatbooking'))->assertOk();

        $this->assertDatabaseMissing('bookings', ['id' => $old->id]);
        $this->assertDatabaseHas('bookings', ['id' => $recent->id]);

        $this->actingAs($admin)->get(route('admin.kelolabooking'))->assertOk();
        $this->assertDatabaseMissing('bookings', ['id' => $old->id]);
    }

    public function test_past_bookings_are_automatically_marked_selesai_on_request(): void
    {
        Carbon::setTestNow('2026-11-27 11:00:00');

        $user = User::factory()->create(['role' => 'user']);
        $pastToday = $this->makeBooking('dikonfirmasi', 1, $user);
        $pastYesterday = $this->makeBooking('dikonfirmasi', 2, $user);
        $pastYesterday->update([
            'date' => '2026-11-26',
            'start_time' => '18.00',
            'end_time' => '19.00',
        ]);

        $future = $this->makeBooking('menunggu', 3, $user);
        $future->update(['date' => '2026-11-28']);

        $rescheduledFuture = $this->makeBooking('ajukan_ulang', 4, $user);
        $rescheduledFuture->update([
            'reschedule_date' => '2026-11-28',
            'reschedule_start_time' => '11.00',
            'reschedule_end_time' => '11.30',
            'reschedule_duration' => 30,
        ]);

        $this->actingAs($user)->get(route('riwayatbooking'))->assertOk();

        $this->assertDatabaseHas('bookings', [
            'id' => $pastToday->id,
            'status' => 'selesai',
            'completed_at' => '2026-11-27 11:00:00',
        ]);
        $this->assertDatabaseHas('bookings', [
            'id' => $pastYesterday->id,
            'status' => 'selesai',
            'completed_at' => '2026-11-27 11:00:00',
        ]);
        $this->assertDatabaseHas('bookings', ['id' => $future->id, 'status' => 'menunggu']);
        $this->assertDatabaseHas('bookings', ['id' => $rescheduledFuture->id, 'status' => 'ajukan_ulang']);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function makeBooking(string $status = 'menunggu', int $number = 1, ?User $user = null): Booking
    {
        return Booking::create([
            'user_id' => $user?->id,
            'booking_number' => $number,
            'date' => '2026-11-27 00:00:00',
            'start_time' => '09.00',
            'duration' => 60,
            'end_time' => '10.00',
            'problem_description' => 'Membahas integrasi matriks.',
            'status' => $status,
        ]);
    }
}
