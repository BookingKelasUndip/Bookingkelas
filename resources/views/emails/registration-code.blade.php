<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kode Verifikasi Registrasi</title>
</head>
<body style="margin:0; padding:24px; background-color:#f4f7fb; font-family:Arial, sans-serif; color:#1e293b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; margin:0 auto; background:#ffffff; border:1px solid #e2e8f0; border-radius:12px;">
        <tr>
            <td style="padding:28px 32px;">
                <p style="margin:0 0 8px; font-size:11px; letter-spacing:0.12em; font-weight:bold; color:#1d3c8a; text-transform:uppercase;">
                    Portal Akademik Mahasiswa UNDIP
                </p>
                <h1 style="margin:0 0 12px; font-size:22px; color:#0f172a;">Verifikasi Akun Anda</h1>
                <p style="margin:0 0 20px; font-size:14px; line-height:1.6; color:#475569;">
                    Halo {{ $name }}, gunakan kode berikut untuk menyelesaikan registrasi akun mahasiswa Anda.
                </p>

                <div style="padding:18px; background:#f8fafd; border:1px solid #dce6f8; text-align:center;">
                    <p style="margin:0 0 8px; font-size:11px; letter-spacing:0.12em; font-weight:bold; color:#64748b; text-transform:uppercase;">
                        Kode Verifikasi
                    </p>
                    <p style="margin:0; font-size:32px; letter-spacing:0.2em; font-weight:bold; color:#1d3c8a;">
                        {{ $code }}
                    </p>
                </div>

                <p style="margin:20px 0 0; font-size:12px; line-height:1.6; color:#64748b;">
                    Kode berlaku selama 10 menit. Jangan bagikan kode ini kepada siapa pun.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
