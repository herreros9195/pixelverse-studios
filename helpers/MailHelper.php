<?php
/**
 * Helper d'envoi d'emails.
 * En local, un fichier log est genere si mail() echoue.
 */
class MailHelper {
    private static string $logDir = __DIR__ . '/../logs/mails/';

    public static function send($to, $subject, $message, $from = 'noreply@pixelverse.com') {
        $headers = [
            "From: {$from}",
            "Reply-To: {$from}",
            "MIME-Version: 1.0",
            "Content-Type: text/plain; charset=utf-8",
        ];
        $headerString = implode("\r\n", $headers);

        if (filter_var($from, FILTER_VALIDATE_EMAIL)) {
            @ini_set('sendmail_from', $from);
            $sent = @mail($to, $subject, $message, $headerString, '-f ' . $from);
        } else {
            $sent = @mail($to, $subject, $message, $headerString);
        }

        if (!$sent) {
            self::logToFile($to, $subject, $message, $from);
        }

        return $sent;
    }

    private static function logToFile($to, $subject, $message, $from) {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }

        $filename = self::$logDir . date('Y-m-d_H-i-s') . '_' . uniqid() . '.txt';
        $content = "Date: " . date('d/m/Y H:i:s') . "\n";
        $content .= "From: {$from}\n";
        $content .= "To: {$to}\n";
        $content .= "Subject: {$subject}\n";
        $content .= "--------------------\n";
        $content .= $message . "\n";

        file_put_contents($filename, $content);
    }

    public static function getLogs() {
        $files = glob(self::$logDir . '*.txt');
        rsort($files);
        $logs = [];

        foreach (array_slice($files, 0, 50) as $file) {
            $logs[] = [
                'date' => date('d/m/Y H:i:s', filemtime($file)),
                'content' => file_get_contents($file),
            ];
        }

        return $logs;
    }
}
