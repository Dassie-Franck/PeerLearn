<?php
// ============================================================
//  core/mailer.php
//  Envoi d'emails (remplacer mail() par PHPMailer en prod)
// ============================================================

function sendMail(string $to, string $subject, string $htmlBody): bool
{
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: PeerLearn <noreply@peerlearn.local>\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    return mail($to, $subject, $htmlBody, $headers);
}

function buildResetEmail(string $prenom, string $resetLink): string
{
    return <<<HTML
    <!DOCTYPE html>
    <html lang="fr">
    <head><meta charset="UTF-8"></head>
    <body style="font-family:'Segoe UI',sans-serif;background:#f4f4f4;margin:0;padding:40px;">
      <div style="max-width:520px;margin:auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);">
        <div style="background:#1a1a2e;padding:32px 40px;">
          <h1 style="color:#e8c547;margin:0;font-size:22px;letter-spacing:1px;">PeerLearn</h1>
          <p style="color:#aaa;margin:4px 0 0;font-size:13px;">Plateforme de tutorat entre pairs</p>
        </div>
        <div style="padding:40px;">
          <h2 style="color:#1a1a2e;margin:0 0 16px;">Bonjour {$prenom},</h2>
          <p style="color:#555;line-height:1.7;">
            Vous avez demandé la réinitialisation de votre mot de passe.<br>
            Ce lien est valable <strong>1 heure</strong>.
          </p>
          <div style="text-align:center;margin:32px 0;">
            <a href="{$resetLink}"
               style="background:#e8c547;color:#1a1a2e;padding:14px 36px;border-radius:8px;
                      text-decoration:none;font-weight:700;font-size:15px;display:inline-block;">
              Réinitialiser mon mot de passe
            </a>
          </div>
          <p style="color:#999;font-size:12px;line-height:1.6;">
            Si vous n'avez pas fait cette demande, ignorez cet email.<br>
            Le lien expirera automatiquement dans 1 heure.
          </p>
          <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">
          <p style="color:#bbb;font-size:11px;margin:0;">© 2026 PeerLearn</p>
        </div>
      </div>
    </body>
    </html>
    HTML;
}
