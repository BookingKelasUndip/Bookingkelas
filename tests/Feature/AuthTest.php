<?php

namespace Tests\Feature;

use App\Mail\RegistrationCode;
use App\Models\User;
use App\Services\EmailExistenceChecker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
    }

    public function test_registration_sends_otp_verifies_and_creates_account_after_password(): void
    {
        $payload = $this->registrationPayload();

        $this->post(route('register.code'), $payload)
            ->assertSessionHas('code-sent');

        $this->assertDatabaseMissing('users', ['email' => $payload['email']]);

        $code = $this->sentCode($payload['email']);

        $this->post(route('register.verify'), ['otp' => $code])
            ->assertRedirect(route('register.password'));

        $this->get(route('register.password'))
            ->assertOk()
            ->assertSee($payload['email'])
            ->assertSee('Buat Kata Sandi');

        $response = $this->post(route('register.store'), [
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);
        $response->assertRedirect(route('login'));
        $response->assertSessionMissing('registration_data');
        $response->assertSessionMissing('registration_verified');

        $user = User::where('email', $payload['email'])->firstOrFail();
        $this->assertNotNull($user->email_verified_at);
        $this->assertSame('user', $user->role);
        $this->assertSame($payload['name'], $user->name);
        $this->assertSame($payload['nim'], $user->nim);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
        $this->assertGuest();
    }

    public function test_wrong_otp_does_not_allow_password_setup(): void
    {
        $payload = $this->registrationPayload();
        $this->post(route('register.code'), $payload);

        $code = $this->sentCode($payload['email']);
        $wrongCode = $code === '000000' ? '111111' : '000000';

        $this->post(route('register.verify'), ['otp' => $wrongCode])
            ->assertSessionHasErrors('otp');

        $this->get(route('register.password'))->assertRedirect(route('register'));

        $this->assertDatabaseMissing('users', ['email' => $payload['email']]);
        $this->assertGuest();
    }

    public function test_registration_rejects_non_undip_email(): void
    {
        $this->post(route('register.code'), $this->registrationPayload([
            'email' => 'rizky@gmail.com',
        ]))->assertSessionHasErrors('email');

        Mail::assertNothingSent();
        $this->assertDatabaseMissing('users', ['email' => 'rizky@gmail.com']);
    }

    public function test_registration_rejects_email_that_does_not_exist_on_mail_server(): void
    {
        Mail::shouldReceive('to')->once()->andReturnSelf();
        Mail::shouldReceive('send')->once()->andThrow(
            new TransportException('550 5.1.1 The email account that you tried to reach does not exist.'),
        );

        $payload = $this->registrationPayload();
        $response = $this->post(route('register.code'), $payload);

        $response->assertSessionHasErrors([
            'email' => 'Email mahasiswa Undip tidak ditemukan atau tidak aktif. Periksa kembali alamat email Anda.',
        ]);
        $response->assertSessionMissing('registration_data');
        $this->assertDatabaseMissing('users', ['email' => $payload['email']]);
    }

    public function test_registration_rejects_email_reported_unknown_by_mail_server_probe(): void
    {
        $this->mock(EmailExistenceChecker::class)
            ->shouldReceive('isUnknown')->once()->andReturn(true);

        $payload = $this->registrationPayload();
        $response = $this->post(route('register.code'), $payload);

        $response->assertSessionHasErrors([
            'email' => 'Email mahasiswa Undip tidak ditemukan atau tidak aktif. Periksa kembali alamat email Anda.',
        ]);
        $response->assertSessionMissing('registration_data');
        Mail::assertNothingSent();
        $this->assertDatabaseMissing('users', ['email' => $payload['email']]);
    }

    public function test_registration_reports_generic_mail_failure(): void
    {
        Mail::shouldReceive('to')->once()->andReturnSelf();
        Mail::shouldReceive('send')->once()->andThrow(
            new TransportException('535 5.7.8 Username and Password not accepted'),
        );

        $this->post(route('register.code'), $this->registrationPayload())
            ->assertSessionHasErrors([
                'email' => 'Kode verifikasi gagal dikirim. Silakan coba lagi beberapa saat.',
            ]);
    }

    public function test_registration_requires_matching_password(): void
    {
        $payload = $this->registrationPayload();
        $this->post(route('register.code'), $payload);

        $this->post(route('register.verify'), ['otp' => $this->sentCode($payload['email'])])
            ->assertRedirect(route('register.password'));

        $this->post(route('register.store'), [
            'password' => 'rahasia123',
            'password_confirmation' => 'berbeda123',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => $payload['email']]);
    }

    public function test_password_setup_and_account_creation_require_verified_otp(): void
    {
        $this->get(route('register.password'))->assertRedirect(route('register'));

        $this->post(route('register.store'), [
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('register'));

        $this->assertDatabaseCount('users', 0);
    }

    public function test_user_logs_in_with_registered_email_nim_and_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Rizky Pratama',
            'email' => 'rizky@students.undip.ac.id',
            'nim' => '21120120140088',
            'password' => 'rahasia123',
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $this->post(route('login.store'), [
            'email' => 'rizky@students.undip.ac.id',
            'nim' => '21120120140088',
            'password' => 'rahasia123',
        ])->assertRedirect(route('beranda'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_unverified_user_cannot_login(): void
    {
        User::factory()->create([
            'email' => 'rizky@students.undip.ac.id',
            'nim' => '21120120140088',
            'password' => 'rahasia123',
            'role' => 'user',
            'email_verified_at' => null,
        ]);

        $this->post(route('login.store'), [
            'email' => 'rizky@students.undip.ac.id',
            'nim' => '21120120140088',
            'password' => 'rahasia123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_login_and_logout_still_work(): void
    {
        $this->seed();

        $this->post(route('admin.login.store'), [
            'email' => 'admin@undip.ac.id',
            'password' => 'password',
        ])->assertRedirect(route('admin.kelolabooking'));

        $this->assertAuthenticatedAs(User::where('email', 'admin@undip.ac.id')->firstOrFail());

        $this->post(route('logout'))->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_demo_user_account_is_gone(): void
    {
        $this->seed();

        $this->assertDatabaseMissing('users', ['email' => 'mahasiswa@students.undip.ac.id']);
        $this->get(route('login'))->assertOk()->assertDontSee('mahasiswa@students.undip.ac.id');
    }

    public function test_auth_pages_render(): void
    {
        $this->get(route('register'))->assertOk()->assertSee('Kirim Kode');
        $this->get(route('login'))->assertOk()->assertSee('Kata Sandi');
        $this->get(route('admin.login'))->assertOk()->assertSee('Masuk Portal Admin');
    }

    public function test_admin_pages_show_logout_button(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        foreach (['admin.kelolabooking', 'admin.jadwalharian', 'admin.buatkelas', 'admin.kelolapermohonan'] as $routeName) {
            $this->actingAs($admin)->get(route($routeName))
                ->assertOk()
                ->assertSee(route('logout'), false)
                ->assertSee('Logout');
        }
    }

    public function test_user_logout_still_goes_to_user_login(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_guests_are_redirected_and_roles_are_enforced(): void
    {
        $this->get(route('beranda'))->assertRedirect(route('login'));
        $this->get(route('admin.kelolabooking'))->assertRedirect(route('admin.login'));

        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get(route('admin.kelolabooking'))->assertForbidden();
        $this->actingAs($admin)->get(route('beranda'))->assertForbidden();
    }

    private function sentCode(string $email): string
    {
        $code = null;

        Mail::assertSent(RegistrationCode::class, function (RegistrationCode $mail) use (&$code, $email) {
            $code = $mail->code;

            return $mail->to[0]['address'] === $email;
        });

        $this->assertNotNull($code);

        return $code;
    }

    private function registrationPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Muhammad Rizky Pratama',
            'email' => 'rizky@students.undip.ac.id',
            'nim' => '21120120140088',
        ], $overrides);
    }
}
