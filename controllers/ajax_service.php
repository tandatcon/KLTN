<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/ServiceProcess.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$db = new PDO("mysql:host=localhost;dbname=techcarepro;charset=utf8", "root", "");
$serviceProcessModel = new ServiceProcess($db);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $maDon = intval($_POST['maDon'] ?? 0);
    $maCTDon = intval($_POST['maCTDon'] ?? 0);

    try {
        switch ($action) {
            case 'save_diagnosis':
                $chanDoan = trim($_POST['diagnosis']);
                $chiPhiDuKien = floatval($_POST['estimated_cost']);
                $danhSachCongViecJSON = $_POST['danh_sach_cong_viec_json'] ?? '[]';
                $danhSachCongViec = json_decode($danhSachCongViecJSON, true);
                $quyetdinh = $_POST['decision'];
                $lydo = $_POST['reason'] ?? '';
                //$time = $_POST['time'];

                if (empty($chanDoan)) {
                    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập chẩn đoán!']);
                    exit();
                }

                if ($chiPhiDuKien <= 0) {
                    echo json_encode(['success' => false, 'message' => 'Chi phí dự kiến phải lớn hơn 0!']);
                    exit();
                }

                $ketQua = $serviceProcessModel->addDiagnosisWithJobs(
                    $maDon,
                    $maCTDon,
                    $chanDoan,
                    $chiPhiDuKien,
                    $_SESSION['user_id'],
                    $danhSachCongViec,
                    $quyetdinh,
                    $lydo
                    //$time
                );

                if ($ketQua) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Đã thêm chẩn đoán và báo giá thành công!',
                        'quyetDinhSC' => $quyetdinh
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không thể thêm chẩn đoán!']);
                }
                break;

            case 'start_service':
                $result = $serviceProcessModel->startServiceForDevice($maDon, $maCTDon, $_SESSION['user_id']);
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Đã bắt đầu sửa chữa thiết bị']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không thể bắt đầu sửa chữa']);
                }
                break;

            case 'complete_service':
                $result = $serviceProcessModel->completeServiceForDevice($maDon, $maCTDon, $_SESSION['user_id'], 0);
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Đã hoàn thành sửa chữa thiết bị']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không thể hoàn thành sửa chữa']);
                }
                break;

            case 'upload_evidence':
                $loaiMinhChung = $_POST['evidence_type'];
                $file = $_FILES['evidence_image'] ?? null;

                if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
                    echo json_encode(['success' => false, 'message' => 'Lỗi upload file']);
                    exit();
                }

                // Kiểm tra loại file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file['type'], $allowedTypes)) {
                    echo json_encode(['success' => false, 'message' => 'Chỉ chấp nhận file ảnh JPEG, PNG, GIF, WebP']);
                    exit();
                }

                // Kiểm tra kích thước
                if ($file['size'] > 5 * 1024 * 1024) {
                    echo json_encode(['success' => false, 'message' => 'File quá lớn, tối đa 5MB']);
                    exit();
                }

                // Upload file
                $uploadDir = __DIR__ . '/../assets/images/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $fileName = "minhchung_{$maDon}_{$maCTDon}_{$loaiMinhChung}_" . time() . "_" . uniqid() . ".{$fileExtension}";
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    $ketQua = $serviceProcessModel->saveEvidenceImage($maDon, $maCTDon, $loaiMinhChung, $fileName, $_SESSION['user_id']);
                    if ($ketQua) {
                        echo json_encode(['success' => true, 'message' => 'Đã upload ảnh minh chứng thành công!']);
                    } else {
                        unlink($filePath); // Xóa file nếu lưu database thất bại
                        echo json_encode(['success' => false, 'message' => 'Lỗi lưu thông tin database']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Lỗi di chuyển file']);
                }
                break;
            case 'save_additional_jobs':
                $danhSachCongViecJSON = $_POST['danh_sach_cong_viec_phat_sinh_json'] ?? '[]';
                $danhSachCongViec = json_decode($danhSachCongViecJSON, true);

                // DEBUG CHI TIẾT CẤU TRÚC DỮ LIỆU
                error_log("=== DEBUG CẤU TRÚC DỮ LIỆU ===");
                error_log("Toàn bộ mảng: " . print_r($danhSachCongViec, true));

                foreach ($danhSachCongViec as $index => $congViec) {
                    error_log("Công việc $index:");
                    error_log("  - Keys có trong mảng: " . implode(', ', array_keys($congViec)));
                    error_log("  - Toàn bộ công việc: " . print_r($congViec, true));
                }

                if (empty($danhSachCongViec)) {
                    echo json_encode(['success' => false, 'message' => 'Vui lòng thêm ít nhất một công việc phát sinh!']);
                    exit();
                }

                $ketQua = true;
                $soCongViec = 0;

                foreach ($danhSachCongViec as $congViec) {
                    // THỬ CÁC TÊN TRƯỜNG KHÁC NHAU
                    $thoiGianPhut = 0;
                    if (isset($congViec['time'])) {
                        $thoiGianPhut = floatval($congViec['time']);
                        error_log("Lấy thời gian từ 'time': " . $thoiGianPhut);
                    } elseif (isset($congViec['thoigian'])) {
                        $thoiGianPhut = floatval($congViec['thoigian']);
                        error_log("Lấy thời gian từ 'thoigian': " . $thoiGianPhut);
                    } else {
                        error_log("KHÔNG TÌM THẤY TRƯỜNG THỜI GIAN!");
                        error_log("Các trường có sẵn: " . implode(', ', array_keys($congViec)));
                    }

                    $ketQua = $serviceProcessModel->themCVSuaChua(
                        $maDon,
                        $maCTDon,
                        $congViec['name'],
                        $congViec['cost'],
                        $_SESSION['user_id'],
                        $thoiGianPhut
                    );

                    if ($ketQua) {
                        $soCongViec++;
                    } else {
                        break;
                    }
                }

                if ($ketQua && $soCongViec > 0) {
                    echo json_encode(['success' => true, 'message' => "Đã lưu $soCongViec công việc phát sinh thành công!"]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không thể lưu công việc phát sinh!']);
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Action không hợp lệ']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
}
?>