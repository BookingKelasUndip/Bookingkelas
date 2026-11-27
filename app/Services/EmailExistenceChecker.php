<?php

namespace App\Services;

use Illuminate\Support\Str;

class EmailExistenceChecker
{
    /**
     * Returns true only when the recipient's own mail server explicitly says
     * the mailbox does not exist. Inconclusive answers never block registration.
     */
    public function isUnknown(string $email): bool
    {
        // Mail::fake() and log mailers never talk to an SMTP server, so probing
        // here would only add network calls without producing useful answers.
        if (config('mail.default') !== 'smtp') {
            return false;
        }

        $email = strtolower(trim($email));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        $hosts = $this->mxHosts(Str::afterLast($email, '@'));

        if ($hosts === []) {
            return true;
        }

        $sawUnknown = false;

        foreach ($hosts as $host) {
            $response = $this->probe($host, $email);

            if ($response === null) {
                continue;
            }

            if ($this->looksUnknown($response)) {
                $sawUnknown = true;

                continue;
            }

            // Any mail server willing to accept the recipient means the mailbox
            // cannot be proven missing.
            if (preg_match('/^[23]\d\d/', trim($response))) {
                return false;
            }
        }

        return $sawUnknown;
    }

    /**
     * @return array<int, string>
     */
    protected function mxHosts(string $domain): array
    {
        $records = dns_get_record($domain, DNS_MX) ?: [];
        usort($records, fn (array $a, array $b) => ($a['pri'] ?? 0) <=> ($b['pri'] ?? 0));

        $hosts = array_column($records, 'target');

        if ($hosts === []) {
            $hosts = array_column(dns_get_record($domain, DNS_A) ?: [], 'ip');
        }

        return array_values(array_unique(array_filter($hosts)));
    }

    protected function probe(string $host, string $email): ?string
    {
        $socket = @fsockopen($host, 25, $errno, $errstr, 2);

        if (! $socket) {
            return null;
        }

        stream_set_timeout($socket, 2);

        $localDomain = parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $sender = (string) config('mail.from.address') ?: 'noreply@localhost';

        try {
            if (! $this->isSuccess($this->read($socket))) {
                return null;
            }

            $ehlo = $this->send($socket, 'EHLO '.$localDomain);

            if (! $this->isSuccess($ehlo)) {
                $helo = $this->send($socket, 'HELO '.$localDomain);

                if (! $this->isSuccess($helo)) {
                    return null;
                }
            }

            if (! $this->isSuccess($this->send($socket, 'MAIL FROM:<'.$sender.'>'))) {
                return null;
            }

            return $this->send($socket, 'RCPT TO:<'.$email.'>');
        } finally {
            @fwrite($socket, "QUIT\r\n");
            fclose($socket);
        }
    }

    private function send($socket, string $command): string
    {
        fwrite($socket, $command."\r\n");

        return $this->read($socket);
    }

    private function read($socket): string
    {
        $response = '';

        while (($line = fgets($socket)) !== false) {
            $response .= $line;

            if (strlen($line) < 4 || $line[3] !== '-') {
                break;
            }
        }

        return $response;
    }

    private function isSuccess(string $response): bool
    {
        return (bool) preg_match('/^[23]\d\d/', trim($response));
    }

    private function looksUnknown(string $response): bool
    {
        return (bool) preg_match(
            '/5\.1\.[123]|user unknown|unknown user|unknown recipient|recipient (?:address )?rejected|mailbox unavailable|mailbox not found|does not exist|no such user|no such mailbox|unverified address/i',
            $response,
        );
    }
}
