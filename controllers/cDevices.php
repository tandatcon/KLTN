<?php
require_once __DIR__ . '/../models/mDevices.php';

class cDevices
{
    private $mDevice;

    public function __construct()
    {
        $this->mDevice = new mDevices();
    }

    // Lấy dữ liệu cho trang giá
    public function cGetPriceData()
    {
        try {
            $devices = $this->mDevice->mGetDevices();
            $deviceId = isset($_GET['device_id']) ? intval($_GET['device_id']) : null;

            if ($deviceId) {
                $priceList = $this->mDevice->mGetPriceByDevice($deviceId);
                $device = $this->mDevice->mGetDevice($deviceId);
            } else {
                $priceList = $this->mDevice->mGetAllPrice();
                $device = null;
            }

            return [
                'devices' => $devices,
                'priceList' => $priceList,
                'deviceId' => $deviceId,
                'device' => $device
            ];
            
        } catch (Exception $e) {
            error_log("Get Price Data Error: " . $e->getMessage());
            return [
                'devices' => [],
                'priceList' => [],
                'deviceId' => null,
                'device' => null
            ];
        }
    }

    // API: Lấy hãng theo thiết bị - FIXED
    public function cGetBrands($deviceId)
    {
        try {
            $brands = $this->mDevice->mGetBrands($deviceId);
            return [
                'success' => true,
                'brands' => $brands
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ];
        }
    }

    // API: Lấy mẫu theo hãng - FIXED
    public function cGetModels($brandId)
    {
        try {
            $models = $this->mDevice->mGetModels($brandId);
            return [
                'success' => true,
                'models' => $models
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ];
        }
    }

    // API: Lấy giá theo mẫu - FIXED
    public function cGetPriceByModel($modelId)
{
    try {
        // Validate modelId
        if (!$modelId || !is_numeric($modelId)) {
            return [
                'success' => false,
                'message' => 'ID mẫu sản phẩm không hợp lệ'
            ];
        }

        $prices = $this->mDevice->mGetPriceByModel($modelId);
        
        // KIỂM TRA KẾT QUẢ TRẢ VỀ
        if ($prices === false) {
            return [
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu từ database'
            ];
        }
        
        if (empty($prices)) {
            return [
                'success' => true,
                'message' => 'Không tìm thấy bảng giá cho mẫu sản phẩm này',
                'prices' => []
            ];
        }

        return [
            'success' => true,
            'prices' => $prices
        ];
    } catch (Exception $e) {
        error_log("Controller Get Price By Model Error: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Lỗi server: ' . $e->getMessage()
        ];
    }
}
    // API endpoints cho AJAX - giữ nguyên để xử lý request từ client
    public function cAjaxGetBrands()
    {
        try {
            if (!isset($_POST['maThietBi'])) {
                echo json_encode(['success' => false, 'message' => 'Thiếu tham số']);
                return;
            }

            $deviceId = intval($_POST['maThietBi']);
            $result = $this->cGetBrands($deviceId);
            echo json_encode($result);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ]);
        }
    }

    public function cAjaxGetModels()
    {
        try {
            if (!isset($_POST['maHang'])) {
                echo json_encode(['success' => false, 'message' => 'Thiếu tham số']);
                return;
            }

            $brandId = intval($_POST['maHang']);
            $result = $this->cGetModels($brandId);
            echo json_encode($result);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ]);
        }
    }

    public function cAjaxGetPriceByModel()
    {
        try {
            if (!isset($_POST['maMau'])) {
                echo json_encode(['success' => false, 'message' => 'Thiếu tham số']);
                return;
            }

            $modelId = intval($_POST['maMau']);
            $result = $this->cGetPriceByModel($modelId);
            echo json_encode($result);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ]);
        }
    }

    // Các hàm admin giữ nguyên
    public function cAddDevice()
    {
        try {
            if (!isset($_POST['tenThietBi']) || empty(trim($_POST['tenThietBi']))) {
                $_SESSION['error'] = 'Tên thiết bị không được để trống';
                $this->cRedirectBack();
                return;
            }

            $name = trim($_POST['tenThietBi']);
            $result = $this->mDevice->mAddDevice($name);

            if ($result) {
                $_SESSION['success'] = 'Thêm thiết bị thành công';
            } else {
                $_SESSION['error'] = 'Thêm thiết bị thất bại';
            }

            $this->cRedirectBack();

        } catch (Exception $e) {
            $this->cHandleError("Lỗi thêm thiết bị: " . $e->getMessage());
        }
    }

    public function cUpdateDevice()
    {
        try {
            if (!isset($_POST['maThietBi']) || !isset($_POST['tenThietBi'])) {
                $_SESSION['error'] = 'Thiếu thông tin';
                $this->cRedirectBack();
                return;
            }

            $id = intval($_POST['maThietBi']);
            $name = trim($_POST['tenThietBi']);
            $success = $this->mDevice->mUpdateDevice($id, $name);

            if ($success) {
                $_SESSION['success'] = 'Cập nhật thành công';
            } else {
                $_SESSION['error'] = 'Cập nhật thất bại';
            }

            $this->cRedirectBack();

        } catch (Exception $e) {
            $this->cHandleError("Lỗi cập nhật: " . $e->getMessage());
        }
    }

    public function cDeleteDevice()
    {
        try {
            if (!isset($_POST['maThietBi'])) {
                $_SESSION['error'] = 'Thiếu thông tin';
                $this->cRedirectBack();
                return;
            }

            $id = intval($_POST['maThietBi']);
            $success = $this->mDevice->mDeleteDevice($id);

            if ($success) {
                $_SESSION['success'] = 'Xóa thành công';
            } else {
                $_SESSION['error'] = 'Xóa thất bại';
            }

            $this->cRedirectBack();

        } catch (Exception $e) {
            $this->cHandleError("Lỗi xóa: " . $e->getMessage());
        }
    }

    public function cGetStats()
    {
        try {
            return $this->mDevice->mGetStats();
        } catch (Exception $e) {
            error_log("Get Stats Error: " . $e->getMessage());
            return [];
        }
    }

    public function cTestConnection()
    {
        try {
            $result = $this->mDevice->mTestConnection();
            return ['success' => true, 'message' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function cRedirectBack()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    private function cHandleError($msg)
    {
        error_log($msg);
        $_SESSION['error'] = 'Đã có lỗi xảy ra. Vui lòng thử lại.';
        $this->cRedirectBack();
    }
}
?>