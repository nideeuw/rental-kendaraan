<?php
function loadEnv($file) {
    $env = [];
 
    if (!file_exists($file)) {
        die("File .env tidak ditemukan di path: " . $file);
    }
    
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        if (strpos($line, '#') === 0 || empty($line)) continue;
        
        list($key, $value) = explode('=', $line, 2);
        
        $env[trim($key)] = trim($value);
        $_ENV[trim($key)] = trim($value);
    }
    
    return $env;
}

$envFilePath = __DIR__ . '/../.env';

if (!file_exists($envFilePath)) {
    die("File .env tidak ditemukan di path: " . $envFilePath);
}

$env = loadEnv($envFilePath);

$host = $env['DB_HOST'];
$port = $env['DB_PORT'];
$dbname = $env['DB_NAME'];
$user = $env['DB_USER'];
$password = $env['DB_PASSWORD'];

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Koneksi gagal: " . pg_last_error());
} else {
    echo "Koneksi berhasil!";
}
?>