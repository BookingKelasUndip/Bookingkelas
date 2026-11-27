# Booking-Kelas-Tambahan

Laravel 13 + Vite 8 + Tailwind 4 app for booking kelas tambahan.

## Commands
- Setup: `composer setup`
- Test: `php artisan test` (filter: `php artisan test --filter=<name>`)
- Lint: `vendor/bin/pint`
- Dev: `composer dev` (or `php artisan dev`, `npm run dev`)

## Environment & Infrastructure
- DB: MySQL (`booking-kelas-tambahan`, root/no password). Tests use SQLite `:memory:` (`phpunit.xml`).
- Timezone: `config/app.php` uses `Asia/Jakarta` (WIB). All booking time comparisons and the `window.bookingNowMinutes` value shown in `bookingkelas` use WIB — do not rely on UTC for "jam sudah lewat" checks.
- Logo asset: `asset('images/Logo_Universitas_Diponegoro.png')` (never use `logo-undip.png`).
- API routes: None in `routes/api.php`; `/api/calendar` registered in `routes/web.php`.

## Architecture & Domain Rules
- Auth & Roles: real auth is enabled. User routes use `['auth', 'role:user']`, admin routes use `['auth', 'role:admin']`; `EnsureUserHasRole` aborts 403 on role mismatch, and guests are redirected via `bootstrap/app.php` (`/admin/*` → `admin.login`, otherwise `login`). `AuthController` handles registration, OTP verification, user login, admin login, and logout (admin logout returns to `admin.login`, user logout to `login`). No demo user account (`2026_11_27_000100_remove_demo_user` deletes it on migrate); admin demo (`admin@undip.ac.id` / `password`) is seeded by `DatabaseSeeder`.
- Registration is three-step: `POST register.code` validates name, `@students.undip.ac.id` email and NIM, stores a hashed 6-digit OTP in session for 10 minutes, and sends `App\Mail\RegistrationCode` (no user row yet). `POST register.verify` checks the OTP and flags `registration_verified`; `GET register.password` shows the password form; `POST register.store` creates a **verified** user with hashed password and redirects to `/login` (no auto-login). User login requires email + NIM + password and rejects unverified accounts.
- Mail: Gmail SMTP is configured in `.env`/`.env.example` (`MAIL_HOST=smtp.gmail.com`, `MAIL_PORT=587`, `MAIL_SCHEME=tls`). `MAIL_USERNAME`, `MAIL_PASSWORD` (Gmail App Password) and `MAIL_FROM_ADDRESS` must be filled for real delivery; tests override `MAIL_MAILER=array`. Laravel 13 mail config uses `MAIL_SCHEME`, not `MAIL_ENCRYPTION`.
- Email existence check: `sendRegistrationCode` first probes the recipient's MX server via `App\Services\EmailExistenceChecker` (skipped when `mail.default` is not `smtp`), then sends the OTP through real SMTP and catches `TransportExceptionInterface` / `Throwable`. Explicit mailbox-unknown responses from the probe or SMTP (`5.1.x`, `user unknown`, `mailbox unavailable/not found`, `recipient address rejected`, `does not exist`, `no such user/mailbox`, `unverified address`) surface as "Email mahasiswa Undip tidak ditemukan atau tidak aktif"; inconclusive answers (for example IP blocklists) and other failures show a generic retry error. No `registration_data` session is stored when sending fails.
- Calendar & Time Constraints:
  - Sundays disabled (`getDay() === 0`), enforced server-side (`selected_date`, `tanggal_baru`).
  - `<x-calendar>` takes `allowPast` (default `false`); only admin `jadwalharian` passes `:allow-past="true"`. Past dates stay disabled in user `bookingkelas` and admin `buatkelas`. Sundays remain disabled everywhere. Today's date always gets a blue ring marker without being selected.
  - Weekday bookings: 09.00–19.00 (120-min max session starts by 17.00). Saturday bookings: 09.00–14.00. Past dates are rejected server-side; on today's date a start time earlier than the current time is rejected, and `bookingkelas` disables those past options via `window.bookingToday` / `window.bookingNowMinutes`.
  - Overlaps: `Booking::conflictsWith` rejects overlapping times for any status except `ditolak`. Proposed `reschedule_*` slots block time slots for `ajukan_ulang`.
- Workflow & Statuses (`menunggu`, `dikonfirmasi`, `ajukan_ulang`, `selesai`, `ditolak`):
  - Bookings numbered sequentially (`Bookingan ke-N`).
  - Admin kelola booking modals (`#konfirmasi-modal`, `#jadwal-ulang-modal`) handle confirm/reschedule inline; the card's `data-booking-*` attributes feed both modals, so preserve them when restyling cards.
  - User history (`riwayatbooking`) is scoped to `user_id === auth()->id()` so bookings never mix between users; `acceptReschedule`/`rejectReschedule` also abort 403 for bookings the user does not own. Both `riwayatbooking` and admin `kelolabooking` paginate 10 per page via `resources/views/components/pagination.blade.php`; links keep `?status=` via `withQueryString()`.
  - `2026_11_27_000200_remove_orphan_demo_bookings` deletes bookings with `user_id = null` left over from the deleted demo user, so no legacy demo history remains in admin views either.
  - Hidden routes: `admin.buatkelas` and `admin.kelolapermohonan` exist in routes but omitted from admin sidebar.
  - Admin `jadwalharian` (`dailySchedule`) loads all statuses, including `selesai`/`ditolak`, so clicking a past date still shows its booking cards.
- Auto-selesai: `FinalizePastBookings` is appended to the web middleware group in `bootstrap/app.php`; every web request calls `Booking::finalizePast()`, which flips `menunggu`/`dikonfirmasi`/`ajukan_ulang` to `selesai` once the effective end time is strictly before `now()` (uses `reschedule_*` for `ajukan_ulang`) and stamps `completed_at`. The same call purges `selesai` bookings older than 60 days from `completed_at` (fallback `updated_at`), so they disappear from both user and admin. Tests pin Carbon to `2026-11-27 08:00:00` in `tests/TestCase.php` so fixed fixtures stay active.
