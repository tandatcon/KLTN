<?php
require_once 'models/User.php';
require_once 'vendor/autoload.php';

class AuthController {
    private $client;

    public function __construct() {
        $this->client = new Google_Client();
        $this->client->setClientId("YOUR_CLIENT_ID");
        $this->client->setClientSecret("YOUR_CLIENT_SECRET");
        $this->client->setRedirectUri("http://localhost/DatDichVu/?controller=auth&action=googleCallback");
        $this->client->addScope("email");
        $this->client->addScope("profile");
    }

    // B1: Gọi khi nhấn nút "Đăng ký với Google"
    public function loginGoogle() {
        $authUrl = $this->client->createAuthUrl();
        header("Location: " . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit;
    }

    // B2: Callback từ Google
    public function googleCallback() {
        if (isset($_GET['code'])) {
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
            $this->client->setAccessToken($token);

            $oauth2 = new Google_Service_Oauth2($this->client);
            $userInfo = $oauth2->userinfo->get();

            $userModel = new User();
            $user = $userModel->findByEmail($userInfo->email);

            if (!$user) {
                // Tạo tài khoản mới
                $userModel->create([
                    'hoTen' => $userInfo->name,
                    'email' => $userInfo->email,
                    'password' => null, // đăng ký bằng Google thì không cần mật khẩu
                    'google_id' => $userInfo->id
                ]);
            }

            // Lưu session
            $_SESSION['user'] = [
                'maND' => $userInfo->id,
                'hoTen' => $userInfo->name,
                'email' => $userInfo->email
            ];

            header("Location: index.php");
            exit;
        } else {
            echo "Lỗi: Không có mã code từ Google!";
        }
    }
}
