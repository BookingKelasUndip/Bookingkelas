<?php

namespace Tests\Unit;

use App\Services\EmailExistenceChecker;
use Tests\TestCase;

class EmailExistenceCheckerTest extends TestCase
{
    public function test_domain_without_mail_server_is_unknown(): void
    {
        config()->set('mail.default', 'smtp');

        $checker = new class extends EmailExistenceChecker
        {
            protected function mxHosts(string $domain): array
            {
                return [];
            }
        };

        $this->assertTrue($checker->isUnknown('rizky@students.undip.ac.id'));
    }

    public function test_explicit_mailbox_rejection_is_unknown(): void
    {
        config()->set('mail.default', 'smtp');

        $checker = new class extends EmailExistenceChecker
        {
            protected function mxHosts(string $domain): array
            {
                return ['mx.example.test'];
            }

            protected function probe(string $host, string $email): ?string
            {
                return '550 5.1.1 The email account that you tried to reach does not exist.';
            }
        };

        $this->assertTrue($checker->isUnknown('rizky@students.undip.ac.id'));
    }

    public function test_policy_block_is_inconclusive_and_does_not_block_registration(): void
    {
        config()->set('mail.default', 'smtp');

        $checker = new class extends EmailExistenceChecker
        {
            protected function mxHosts(string $domain): array
            {
                return ['mx.example.test'];
            }

            protected function probe(string $host, string $email): ?string
            {
                return '550 5.7.1 Service unavailable, Client host blocked using Spamhaus.';
            }
        };

        $this->assertFalse($checker->isUnknown('rizky@students.undip.ac.id'));
    }

    public function test_accepted_recipient_is_not_unknown(): void
    {
        config()->set('mail.default', 'smtp');

        $checker = new class extends EmailExistenceChecker
        {
            protected function mxHosts(string $domain): array
            {
                return ['mx.example.test'];
            }

            protected function probe(string $host, string $email): ?string
            {
                return '250 2.1.5 Recipient OK';
            }
        };

        $this->assertFalse($checker->isUnknown('rizky@students.undip.ac.id'));
    }

    public function test_non_smtp_mailer_skips_mail_server_probe(): void
    {
        config()->set('mail.default', 'array');

        $checker = new class extends EmailExistenceChecker
        {
            protected function mxHosts(string $domain): array
            {
                return ['mx.example.test'];
            }

            protected function probe(string $host, string $email): ?string
            {
                return '550 5.1.1 The email account that you tried to reach does not exist.';
            }
        };

        $this->assertFalse($checker->isUnknown('rizky@students.undip.ac.id'));
    }
}
