<?php

echo "🔍 Laravel Log Monitor\n";
echo "=====================\n\n";

$logFile = 'storage/logs/laravel.log';

if (!file_exists($logFile)) {
    echo "❌ Log file not found: {$logFile}\n";
    echo "💡 Try creating an ebook first to generate logs.\n";
    exit(1);
}

echo "📄 Monitoring: {$logFile}\n";
echo "🔄 Press Ctrl+C to stop monitoring\n\n";

// Get current file size to start monitoring from the end
$lastSize = filesize($logFile);

while (true) {
    clearstatcache();
    $currentSize = filesize($logFile);
    
    if ($currentSize > $lastSize) {
        $handle = fopen($logFile, 'r');
        fseek($handle, $lastSize);
        
        while (($line = fgets($handle)) !== false) {
            // Highlight important log entries
            if (strpos($line, 'EBOOK CREATION') !== false) {
                echo "\033[1;32m" . $line . "\033[0m"; // Green
            } elseif (strpos($line, 'ERROR') !== false) {
                echo "\033[1;31m" . $line . "\033[0m"; // Red
            } elseif (strpos($line, 'WARNING') !== false) {
                echo "\033[1;33m" . $line . "\033[0m"; // Yellow
            } elseif (strpos($line, 'PASS:') !== false) {
                echo "\033[1;34m" . $line . "\033[0m"; // Blue
            } else {
                echo $line;
            }
        }
        
        fclose($handle);
        $lastSize = $currentSize;
    }
    
    usleep(500000); // Sleep for 0.5 seconds
} 