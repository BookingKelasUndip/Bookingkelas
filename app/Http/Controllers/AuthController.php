<?php

namespace App\Http\Controllers;

use App\Mail\RegistrationCode;
use App\Models\User;
use App\Services\EmailExistenceChecker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Throwable;

class AuthController extends Controller
{
    public function __construct(private EmailExistenceChecker $emailExistenceChecker) {}

    public function showRegister()
    {
        return view('auth.register');
    }

    public function sendRegistrationCode(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
            'nim' => trim((string) $request->input('nim')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'regex:/@students\.undip\.ac\.id$/i', Rule::unique('users', 'email')],
            'nim' => ['required', 'string', 'regex:/^\d{8,20}$/', Rule::unique('users', 'nim')],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email mahasiswa wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Gunakan email resmi mahasiswa Undip (@students.undip.ac.id).',
            'email.unique' => 'Email ini sudah terdaftar.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.regex' => 'NIM harus berupa 8–20 digit angka.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
        ]);

        try {
            if ($this->emailExistenceChecker->isUnknown($validated['email'])) {
                return back()->withErrors([
                    'email' => 'Email mahasiswa Undip tidak ditemukan atau tidak aktif. Periksa kembali alamat email Anda.',
                ])->withInput($request->except(['otp', 'password', 'password_confirmation']));
            }

            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            Mail::to($validated['email'])->send(new RegistrationCode($validated['name'], $code));
        } catch (TransportExceptionInterface $exception) {
            return back()->withErrors([
                'email' => $this->isUnknownRecipient($exception->getMessage())
                    ? 'Email mahasiswa Undip tidak ditemukan atau tidak aktif. Periksa kembali alamat email Anda.'
                    : 'Kode verifikasi gagal dikirim. Silakan coba lagi beberapa saat.',
            ])->withInput($request->except(['otp']));
        } catch (Throwable) {
            return back()->withErrors([
                'email' => 'Kode verifikasi gagal dikirim. Silakan coba lagi beberapa saat.',
            ])->withInput($request->except(['otp']));
        }

        $request->session()->put('registration_data', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nim' => $validated['nim'],
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(10)->getTimestamp(),
        ]);
        $request->session()->forget('registration_verified');

        return back()->with('code-sent', 'Kode verifikasi dikirim ke '.$validated['email'].'. Berlaku selama 10 menit.')
            ->withInput($request->except(['otp', 'password', 'password_confirmation']));
    }

    private function isUnknownRecipient(string $message): bool
    {
        foreach ([
            '5.1.1',
            'user unknown',
            'unknown user',
            'unknown recipient',
            'recipient address rejected',
            'recipient rejected',
            'mailbox unavailable',
            'mailbox not found',
            'does not exist',
            'no such user',
            'no such mailbox',
            'unverified address',
        ] as $needle) {
            if (str_contains(strtolower($message), $needle)) {
                return true;
            }
        }

        return false;
    }

    public function verifyRegistrationCode(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.required' => 'Kode verifikasi wajib diisi.',
            'otp.digits' => 'Kode verifikasi harus 6 digit angka.',
        ]);

        $pending = $request->session()->get('registration_data');

        if (! $pending || $pending['expires_at'] < now()->getTimestamp()) {
            $request->session()->forget(['registration_data', 'registration_verified']);

            return back()->withErrors([
                'otp' => 'Kode tidak valid atau sudah kedaluwarsa. Silakan kirim ulang.',
            ])->withInput();
        }

        if (! Hash::check((string) $request->input('otp'), $pending['code'])) {
            return back()->withErrors([
                'otp' => 'Kode verifikasi tidak sesuai.',
            ])->withInput();
        }

        $request->session()->put('registration_verified', true);

        return redirect()->route('register.password');
    }

    public function showPasswordSetup()
    {
        if (! session('registration_verified')) {
            return redirect()->route('register')->withErrors([
                'otp' => 'Verifikasi kode belum dilakukan atau sesi sudah habis.',
            ]);
        }

        if (! session()->has('registration_data')) {
            return redirect()->route('register')->withErrors([
                'otp' => 'Sesi pendaftaran habis. Silakan isi data kembali.',
            ]);
        }

        return view('auth.password-setup', [
            'email' => session('registration_data.email'),
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->session()->get('registration_data');

        if (! $data || ! session('registration_verified')) {
            return redirect()->route('register')->withErrors([
                'otp' => 'Sesi pendaftaran tidak valid. Silakan daftar ulang.',
            ]);
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nim' => $data['nim'],
            'password' => $validated['password'],
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $request->session()->forget(['registration_data', 'registration_verified']);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan login menggunakan email, NIM, dan kata sandi Anda.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'nim' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $user = User::where('email', strtolower(trim($credentials['email'])))
            ->where('nim', trim($credentials['nim']))
            ->where('role', 'user')
            ->first();

        if (! $user || ! $user->email_verified_at || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Email, NIM, atau kata sandi tidak sesuai.',
            ])->withInput($request->except('password'));
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('beranda');
    }

    public function showAdminLogin()
    {
        return view('auth.adminlogin');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (! Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
            return back()->withErrors([
                'email' => 'Email undip atau kata sandi admin tidak sesuai.',
            ])->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route('admin.kelolabooking');
    }

    public function logout(Request $request)
    {
        $redirectRoute = $request->user()?->role === 'admin' ? 'admin.login' : 'login';

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectRoute);
    }
}
