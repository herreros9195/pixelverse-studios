<?php
/**
 * Helper d'envoi d'emails
 * Fallback vers un fichier log si mail() ne fonctionne pas (environnement local)
 */
class MailHelper {
    private static $logDir = __DIR__ . '/../logs/mails/';

    public static function send($to, $subject, $message, $from = 'noreply@pixelverse.com') {
        $headers = "From: {$from}\r\nContent-Type: text/plain; charset=utf-8";
        
        // Essai avec mail() natif
        $sent = @mail($to, $subject, $message, $headers);
        
        if (!$sent) {
            // Fallback : écriture dans un fichier log
            self::logToFile($to, $subject, $message, $from);
        }
        
        return true; // On considère que c'est OK même en local
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
                'content' => file_get_contents($file)
            ];
        }
        return $logs;
    }
}
