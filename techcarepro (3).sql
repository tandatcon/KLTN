-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 13, 2025 lúc 06:28 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `techcarepro`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banggiasc`
--

CREATE TABLE `banggiasc` (
  `maGia` int(11) NOT NULL,
  `maThietBi` int(11) NOT NULL,
  `chitietLoi` varchar(255) NOT NULL,
  `khoangGia` varchar(100) NOT NULL,
  `ghiChu` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `DVT` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `banggiasc`
--

INSERT INTO `banggiasc` (`maGia`, `maThietBi`, `chitietLoi`, `khoangGia`, `ghiChu`, `created_at`, `DVT`) VALUES
(1, 1, 'Bảo dưỡng định kỳ tủ lạnh', '200.000 - 400.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:39', ''),
(2, 1, 'Tủ lạnh không lạnh - thay block', '1.200.000 - 2.500.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:39', ''),
(3, 1, 'Tủ lạnh không lạnh - nạp gas', '500.000 - 800.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:39', ''),
(4, 1, 'Đóng tuyết ngăn đá - thay board xả tuyết', '600.000 - 1.200.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:39', ''),
(5, 1, 'Chảy nước trong tủ - thông ống thoát nước', '150.000 - 300.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:39', ''),
(6, 1, 'Kêu to - thay quạt dàn nóng', '400.000 - 700.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:39', ''),
(7, 1, 'Cánh tủ bị hở - thay ron cao su', '300.000 - 600.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:39', ''),
(8, 1, 'Đèn trong tủ không sáng', '100.000 - 250.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:39', ''),
(9, 1, 'Bảng điều khiển hỏng', '500.000 - 1.000.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:39', ''),
(10, 1, 'Tủ chạy liên tục không ngắt', '400.000 - 800.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:39', ''),
(11, 2, 'Bảo dưỡng máy lạnh định kỳ', '300.000 - 500.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:52', ''),
(12, 2, 'Máy chạy không lạnh - thay block', '1.500.000 - 3.000.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:52', ''),
(13, 2, 'Máy chạy không lạnh - nạp gas', '600.000 - 1.200.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:52', ''),
(14, 2, 'Chảy nước trong nhà - thông ống nước', '200.000 - 400.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:52', ''),
(15, 2, 'Máy kêu to - thay quạt dàn nóng', '500.000 - 900.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:52', ''),
(16, 2, 'Máy kêu to - thay quạt dàn lạnh', '400.000 - 700.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:52', ''),
(17, 2, 'Không khởi động - thay board mạch', '800.000 - 1.500.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:52', ''),
(18, 2, 'Remote không hoạt động', '150.000 - 300.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:52', ''),
(19, 2, 'Máy không tự động ngắt', '500.000 - 900.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:52', ''),
(20, 2, 'Mùi hôi khi hoạt động', '250.000 - 450.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:48:52', ''),
(21, 3, 'Màn hình bị sọc ngang/dọc', '800.000 - 3.000.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:08', ''),
(22, 3, 'Màn hình bị vỡ - thay màn hình', '1.500.000 - 5.000.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:08', ''),
(23, 3, 'Mất tiếng - thay loa', '300.000 - 600.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:08', ''),
(24, 3, 'Mất tiếng - sửa board âm thanh', '500.000 - 900.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:08', ''),
(25, 3, 'Không lên nguồn - thay board nguồn', '600.000 - 1.200.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:08', ''),
(26, 3, 'Tivi tự tắt nguồn', '500.000 - 1.000.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:08', ''),
(27, 3, 'Remote không hoạt động', '100.000 - 250.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:08', ''),
(28, 3, 'Không lên hình chỉ có tiếng', '700.000 - 1.500.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:08', ''),
(29, 3, 'Hình ảnh bị nhòe, mờ', '600.000 - 1.200.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:08', ''),
(30, 3, 'Không kết nối được Wifi/Internet', '300.000 - 600.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:08', ''),
(31, 4, 'Bảo dưỡng máy giặt định kỳ', '200.000 - 400.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:26', ''),
(32, 4, 'Không vắt được - thay board điều khiển', '700.000 - 1.300.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:26', ''),
(33, 4, 'Không vắt được - thay motor', '900.000 - 1.800.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:26', ''),
(34, 4, 'Chảy nước dưới đáy máy - thay phớt', '250.000 - 500.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:26', ''),
(35, 4, 'Không xả nước - thay bơm xả', '400.000 - 700.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:26', ''),
(36, 4, 'Không cấp nước vào', '300.000 - 600.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:26', ''),
(37, 4, 'Mất nguồn hoàn toàn', '300.000 - 600.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:26', ''),
(38, 4, 'Kêu to khi vắt - thay bạc đạn', '600.000 - 1.100.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:26', ''),
(39, 4, 'Cửa không mở được', '200.000 - 400.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:26', ''),
(40, 4, 'Máy giặt cửa ngang kêu to', '500.000 - 900.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:26', ''),
(41, 5, 'Không nóng nước - thay thanh gia nhiệt', '500.000 - 1.000.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:36', ''),
(42, 5, 'Không nóng nước - thay rơ le nhiệt', '300.000 - 600.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:36', ''),
(43, 5, 'Rò rỉ điện - cách điện lại', '250.000 - 500.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:36', ''),
(44, 5, 'Chảy nước từ thân máy - thay gioăng', '200.000 - 400.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:36', ''),
(45, 5, 'Bật không lên - thay board điều khiển', '400.000 - 800.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:36', ''),
(46, 5, 'Nước không đủ nóng', '350.000 - 650.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:36', ''),
(47, 5, 'Đèn báo không sáng', '150.000 - 300.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:36', ''),
(48, 5, 'Máy ngắt điện liên tục', '300.000 - 600.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:36', ''),
(49, 5, 'Van an toàn bị rò nước', '180.000 - 350.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:36', ''),
(50, 6, 'Một vùng không nóng - thay mâm từ', '400.000 - 800.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:46', ''),
(51, 6, 'Báo lỗi trên màn hình', '300.000 - 600.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:46', ''),
(52, 6, 'Nút bấm không nhạy - thay bàn phím', '200.000 - 400.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:46', ''),
(53, 6, 'Quạt không chạy - thay quạt tản nhiệt', '250.000 - 500.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:46', ''),
(54, 6, 'Bếp không nhận nồi', '350.000 - 650.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:46', ''),
(55, 6, 'Mất nguồn hoàn toàn', '280.000 - 550.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:46', ''),
(56, 6, 'Màn hình hiển thị lỗi', '320.000 - 620.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:46', ''),
(57, 6, 'Bếp nóng không đều', '380.000 - 720.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:46', ''),
(58, 7, 'Không nóng - thay magnetron', '500.000 - 900.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:58', ''),
(59, 7, 'Quay không được - thay motor quay', '300.000 - 600.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:58', ''),
(60, 7, 'Mất nguồn - thay board điều khiển', '400.000 - 750.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:58', ''),
(61, 7, 'Cửa không đóng kín - thay khóa cửa', '200.000 - 400.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:58', ''),
(62, 7, 'Đèn trong lò không sáng', '150.000 - 300.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:58', ''),
(63, 7, 'Nút bấm không hoạt động', '180.000 - 350.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:58', ''),
(64, 7, 'Lò hoạt động nhưng không nóng', '450.000 - 850.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:58', ''),
(65, 7, 'Có tia lửa điện trong lò', '350.000 - 700.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:49:58', ''),
(66, 9, 'Không nóng - thay mâm nhiệt', '250.000 - 500.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:14', ''),
(67, 9, 'Nồi nhảy nút liên tục', '180.000 - 350.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:14', ''),
(68, 9, 'Cơm sống/cháy không đều', '200.000 - 400.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:14', ''),
(69, 9, 'Mất nguồn - thay dây điện', '120.000 - 250.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:14', ''),
(70, 9, 'Nút bấm không nhạy', '100.000 - 220.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:14', ''),
(71, 9, 'Nồi cơm điện tử báo lỗi', '300.000 - 600.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:14', ''),
(72, 9, 'Rò rỉ điện', '150.000 - 300.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:14', ''),
(73, 9, 'Đèn báo không sáng', '80.000 - 180.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:14', ''),
(74, 10, 'Quạt không quay - thay motor', '200.000 - 400.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:35', ''),
(75, 10, 'Không có gió - thay cánh quạt', '150.000 - 300.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:35', ''),
(76, 10, 'Không điều chỉnh được tốc độ', '120.000 - 250.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:35', ''),
(77, 10, 'Quạt kêu to - bảo dưỡng motor', '100.000 - 220.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:35', ''),
(78, 10, 'Quạt không xoay được', '130.000 - 260.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:35', ''),
(79, 10, 'Mất nguồn - sửa dây điện', '80.000 - 180.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:35', ''),
(80, 10, 'Nút bấm hỏng', '70.000 - 150.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:35', ''),
(81, 10, 'Quạt chạy yếu', '90.000 - 200.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:35', ''),
(82, 11, 'Không thổi gió - thay quạt gió', '300.000 - 600.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:46', ''),
(83, 11, 'Đèn báo lỗi - sửa board mạch', '350.000 - 700.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:46', ''),
(84, 11, 'Mùi khét khi hoạt động', '250.000 - 500.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:46', ''),
(85, 11, 'Không bắt mùi - thay lõi lọc', '400.000 - 800.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:46', ''),
(86, 11, 'Máy chạy yếu - vệ sinh lõi lọc', '180.000 - 350.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:46', ''),
(87, 11, 'Không khởi động được', '280.000 - 550.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:46', ''),
(88, 11, 'Màn hình không hiển thị', '320.000 - 620.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:46', ''),
(89, 11, 'Máy kêu to bất thường', '220.000 - 450.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:46', ''),
(90, 11, 'Không cảm biến được chất lượng không khí', '380.000 - 720.000 VND', 'Giá đã bao gồm công sửa chữa', '2025-10-26 13:50:46', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bangkhunggio`
--

CREATE TABLE `bangkhunggio` (
  `maKhungGio` int(11) NOT NULL,
  `soThuTu` int(11) NOT NULL,
  `khoangGio` varchar(20) NOT NULL,
  `gioBatDau` time NOT NULL,
  `gioKetThuc` time NOT NULL,
  `gioChan` int(11) NOT NULL,
  `moTa` varchar(100) DEFAULT NULL,
  `dangHoatDong` tinyint(1) DEFAULT 1,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bangkhunggio`
--

INSERT INTO `bangkhunggio` (`maKhungGio`, `soThuTu`, `khoangGio`, `gioBatDau`, `gioKetThuc`, `gioChan`, `moTa`, `dangHoatDong`, `ngayTao`) VALUES
(1, 1, '8-10', '08:00:00', '10:00:00', 10, 'Khung giờ sáng 8-10', 1, '2025-11-12 14:11:45'),
(2, 2, '10-12', '10:00:00', '12:00:00', 12, 'Khung giờ sáng 10-12', 1, '2025-11-12 14:11:45'),
(3, 3, '12-14', '12:00:00', '14:00:00', 14, 'Khung giờ trưa 12-14', 1, '2025-11-12 14:11:45'),
(4, 4, '14-16', '14:00:00', '16:00:00', 16, 'Khung giờ chiều 14-16', 1, '2025-11-12 14:11:45'),
(5, 5, '16-18', '16:00:00', '18:00:00', 18, 'Khung giờ chiều 16-18', 1, '2025-11-12 14:11:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bangphanboslot`
--

CREATE TABLE `bangphanboslot` (
  `maPhanBo` int(11) NOT NULL,
  `ngay` date NOT NULL,
  `maKhungGio` int(11) NOT NULL,
  `tongKTV` int(11) NOT NULL,
  `soDonToiDa` int(11) NOT NULL,
  `soDonHienTai` int(11) DEFAULT 0,
  `soDonConLai` int(11) NOT NULL,
  `daHetGio` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bangphanboslot`
--

INSERT INTO `bangphanboslot` (`maPhanBo`, `ngay`, `maKhungGio`, `tongKTV`, `soDonToiDa`, `soDonHienTai`, `soDonConLai`, `daHetGio`) VALUES
(1, '2025-11-12', 1, 1, 0, 0, 0, 1),
(2, '2025-11-12', 2, 1, 0, 0, 0, 1),
(3, '2025-11-12', 3, 1, 0, 0, 0, 1),
(4, '2025-11-12', 4, 1, 3, 0, 3, 0),
(5, '2025-11-12', 5, 1, 3, 0, 3, 0),
(6, '2025-11-12', 1, 1, 0, 0, 0, 1),
(7, '2025-11-12', 2, 1, 0, 0, 0, 1),
(8, '2025-11-12', 3, 1, 0, 0, 0, 1),
(9, '2025-11-12', 4, 1, 0, 0, 0, 1),
(10, '2025-11-12', 5, 1, 0, 0, 0, 1),
(11, '2025-11-12', 1, 1, 0, 0, 0, 1),
(12, '2025-11-12', 2, 1, 0, 0, 0, 1),
(13, '2025-11-12', 3, 1, 0, 0, 0, 1),
(14, '2025-11-12', 4, 1, 0, 0, 0, 1),
(15, '2025-11-12', 5, 1, 0, 0, 0, 1),
(16, '2025-11-12', 1, 1, 0, 0, 0, 1),
(17, '2025-11-12', 2, 1, 0, 0, 0, 1),
(18, '2025-11-12', 3, 1, 0, 0, 0, 1),
(19, '2025-11-12', 4, 1, 0, 0, 0, 1),
(20, '2025-11-12', 5, 1, 0, 0, 0, 1),
(21, '2025-11-12', 1, 1, 0, 0, 0, 1),
(22, '2025-11-12', 2, 1, 0, 0, 0, 1),
(23, '2025-11-12', 3, 1, 0, 0, 0, 1),
(24, '2025-11-12', 4, 1, 0, 0, 0, 1),
(25, '2025-11-12', 5, 1, 0, 0, 0, 1),
(26, '2025-11-12', 1, 1, 0, 0, 0, 1),
(27, '2025-11-12', 2, 1, 0, 0, 0, 1),
(28, '2025-11-12', 3, 1, 0, 0, 0, 1),
(29, '2025-11-12', 4, 1, 0, 0, 0, 1),
(30, '2025-11-12', 5, 1, 0, 0, 0, 1),
(31, '2025-11-12', 1, 1, 0, 0, 0, 1),
(32, '2025-11-12', 2, 1, 0, 0, 0, 1),
(33, '2025-11-12', 3, 1, 0, 0, 0, 1),
(34, '2025-11-12', 4, 1, 0, 0, 0, 1),
(35, '2025-11-12', 5, 1, 0, 0, 0, 1),
(36, '2025-11-12', 1, 1, 1, 0, 1, 0),
(37, '2025-11-12', 2, 1, 1, 0, 1, 0),
(38, '2025-11-12', 3, 1, 1, 0, 1, 0),
(39, '2025-11-12', 4, 1, 1, 0, 1, 0),
(40, '2025-11-12', 5, 1, 1, 0, 1, 0),
(41, '2025-11-12', 1, 1, 0, 0, 0, 1),
(42, '2025-11-12', 2, 1, 0, 0, 0, 1),
(43, '2025-11-12', 3, 1, 0, 0, 0, 1),
(44, '2025-11-12', 4, 1, 0, 0, 0, 1),
(45, '2025-11-12', 5, 1, 0, 0, 0, 1),
(46, '2025-11-12', 1, 1, 0, 0, 0, 1),
(47, '2025-11-12', 2, 1, 0, 0, 0, 1),
(48, '2025-11-12', 3, 1, 0, 0, 0, 1),
(49, '2025-11-12', 4, 1, 0, 0, 0, 1),
(50, '2025-11-12', 5, 1, 0, 0, 0, 1),
(51, '2025-11-12', 1, 1, 0, 0, 0, 1),
(52, '2025-11-12', 2, 1, 0, 0, 0, 1),
(53, '2025-11-12', 3, 1, 0, 0, 0, 1),
(54, '2025-11-12', 4, 1, 0, 0, 0, 1),
(55, '2025-11-12', 5, 1, 0, 0, 0, 1),
(56, '2025-11-12', 1, 1, 0, 0, 0, 1),
(57, '2025-11-12', 2, 1, 0, 0, 0, 1),
(58, '2025-11-12', 3, 1, 0, 0, 0, 1),
(59, '2025-11-12', 4, 1, 0, 0, 0, 1),
(60, '2025-11-12', 5, 1, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdondichvu`
--

CREATE TABLE `chitietdondichvu` (
  `maCTDon` int(11) NOT NULL,
  `maDon` int(11) NOT NULL,
  `id_nhanvien` int(11) DEFAULT NULL,
  `loai_thietbi` varchar(100) NOT NULL,
  `phienban` text NOT NULL,
  `mota_tinhtrang` varchar(255) NOT NULL,
  `trangThai` int(11) NOT NULL,
  `minhchung_den` text NOT NULL,
  `minhchung_thietbi` text NOT NULL,
  `minhchunghoanthanh` text NOT NULL,
  `chuandoanKTV` varchar(255) NOT NULL,
  `baoGiaSC` int(11) NOT NULL,
  `gioBatDau` text NOT NULL,
  `gioKetThuc` text NOT NULL,
  `quyetDinhSC` int(11) NOT NULL,
  `lyDoTC` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdondichvu`
--

INSERT INTO `chitietdondichvu` (`maCTDon`, `maDon`, `id_nhanvien`, `loai_thietbi`, `phienban`, `mota_tinhtrang`, `trangThai`, `minhchung_den`, `minhchung_thietbi`, `minhchunghoanthanh`, `chuandoanKTV`, `baoGiaSC`, `gioBatDau`, `gioKetThuc`, `quyetDinhSC`, `lyDoTC`) VALUES
(39, 30, 4, '7', 'Pa 1', 'Không hoạt động ', 0, '', '', '', '', 0, '', '', 0, ''),
(40, 30, 8, '2', 'La 1', 'Không lạnh', 0, '', '', '', '', 0, '', '', 0, ''),
(41, 31, 4, '5', 'A05', 'Nước không nóng', 0, '', '', '', '', 0, '', '', 0, ''),
(42, 32, 4, '1', 'A03 2018', 'Rò rỉ nước, lạnh yếu', 3, 'minhchung_32_42_arrival_1761731897_6901e539cbcb8.jpeg', 'minhchung_32_42_device_1761731918_6901e54ea50af.jpeg', 'minhchung_32_42_completion_1762512623_690dceef747a7.png', 'abc', 460000, '2025-10-29 17:02:49', '2025-11-07 18:48:17', 1, ''),
(43, 32, 4, '2', 'A0', 'Không hoạt độbg, ', 3, 'minhchung_32_43_arrival_1761737766_6901fc261c7e7.jpeg', 'minhchung_32_43_device_1762438630_690cade63f2e9.jpg', '', 'abc', 700000, '2025-11-06 22:55:52', '2025-11-06 23:04:22', 1, ''),
(44, 33, NULL, '2', 'AAAA', '123', 0, '', '', '', '', 0, '', '', 0, ''),
(45, 34, NULL, '2', 'AAAA', 'ssasa', 0, '', '', '', '', 0, '', '', 0, ''),
(46, 35, NULL, '11', 'AAAA', 'sasa', 0, '', '', '', '', 0, '', '', 0, ''),
(47, 36, NULL, '10', 'sasa', 'sasa', 0, '', '', '', '', 0, '', '', 0, ''),
(48, 37, NULL, '10', 'AAA1', 'saas', 0, '', '', '', '', 0, '', '', 0, ''),
(49, 38, NULL, '11', 'hhh', 'ddd', 0, '', '', '', '', 0, '', '', 0, ''),
(50, 39, NULL, '10', 'AAAA', 'aaas', 0, '', '', '', '', 0, '', '', 0, ''),
(51, 40, NULL, '11', 'sss', 'sss', 0, '', '', '', '', 0, '', '', 0, ''),
(52, 41, NULL, '10', 'aaaa', 'ssds', 0, '', '', '', '', 0, '', '', 0, ''),
(53, 42, NULL, '9', 'Sony 911', '123', 0, '', '', '', '', 0, '', '', 0, ''),
(54, 43, NULL, '12', 'â', 'â', 0, '', '', '', '', 0, '', '', 0, ''),
(55, 43, NULL, '11', 'â', 'â', 0, '', '', '', '', 0, '', '', 0, ''),
(56, 44, NULL, '12', 'Sony 911', '121', 0, '', '', '', '', 0, '', '', 0, ''),
(57, 44, NULL, '8', '121', '121', 0, '', '', '', '', 0, '', '', 0, ''),
(58, 45, NULL, '2', 'A', 'Â', 0, '', '', '', '', 0, '', '', 0, ''),
(59, 46, NULL, '11', 'Sony 911', 'aa', 0, '', '', '', '', 0, '', '', 0, ''),
(60, 46, NULL, '10', 'â', 'â', 0, '', '', '', '', 0, '', '', 0, ''),
(61, 47, 4, '11', 'A00', 'hư òi', 0, '', '', '', '', 0, '', '', 0, ''),
(62, 47, NULL, '1', 'hiha', '0909', 0, '', '', '', '', 0, '', '', 0, ''),
(63, 48, NULL, '11', '123', '123', 0, '', '', '', '', 0, '', '', 0, ''),
(64, 49, NULL, '11', 'aA', 'aA', 0, '', '', '', '', 0, '', '', 0, ''),
(65, 50, NULL, '1', 'AA', 'AAA', 0, '', '', '', '', 0, '', '', 0, ''),
(66, 51, NULL, '11', 'aa', 'aa', 0, '', '', '', '', 0, '', '', 0, ''),
(67, 52, 4, '12', 'aa', 'aaa', 0, '', '', '', '', 0, '', '', 0, ''),
(68, 53, 3, '11', 'aa', 'aa', 0, '', '', '', '', 0, '', '', 0, ''),
(69, 54, 3, '2', 'aa', 'aa', 0, '', '', '', '', 0, '', '', 0, ''),
(70, 55, 4, '10', '123', '123', 0, '', '', '', '', 0, '', '', 0, ''),
(71, 56, 4, '10', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(72, 57, 4, '8', '123', '123', 0, '', '', '', '', 0, '', '', 0, ''),
(73, 58, 4, '11', '123', '123', 0, '', '', '', '', 0, '', '', 0, ''),
(74, 59, 3, '2', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(75, 59, 3, '10', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(76, 59, 3, '11', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(77, 59, 8, '1', '2', '2', 0, '', '', '', '', 0, '', '', 0, ''),
(78, 59, 8, '10', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(79, 59, 8, '11', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(80, 60, NULL, '8', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(81, 60, NULL, '10', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(82, 60, NULL, '12', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(83, 60, NULL, '9', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(84, 60, NULL, '8', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(85, 60, NULL, '11', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(86, 61, 4, '11', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(87, 61, 4, '11', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(88, 61, NULL, '11', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(89, 61, NULL, '11', '2', '2', 0, '', '', '', '', 0, '', '', 0, ''),
(99, 65, NULL, '10', '1', '11', 0, '', '', '', '', 0, '', '', 0, ''),
(100, 65, NULL, '1', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(101, 65, NULL, '10', '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(102, 66, NULL, '1', '1', '1', 0, '', '', '', '', 0, '', '', 0, '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietsuachua`
--

CREATE TABLE `chitietsuachua` (
  `maCTSuaChua` int(11) NOT NULL,
  `maDon` int(11) NOT NULL,
  `maThietBi` varchar(50) NOT NULL,
  `loiSuaChua` text DEFAULT NULL,
  `chiPhi` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `maCTDon` int(11) NOT NULL,
  `loai` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietsuachua`
--

INSERT INTO `chitietsuachua` (`maCTSuaChua`, `maDon`, `maThietBi`, `loiSuaChua`, `chiPhi`, `created_at`, `updated_at`, `maCTDon`, `loai`) VALUES
(51, 32, '', 'Bảo dưỡng định kỳ tủ lạnh', 300000.00, '2025-10-29 10:02:24', '2025-10-29 10:02:24', 42, 'Báo giá'),
(52, 32, '', 'gan them ốc', 10000.00, '2025-10-29 10:02:24', '2025-10-29 10:02:24', 42, 'Báo giá'),
(53, 32, '', 'Chảy nước trong tủ - thông ống thoát nước', 150000.00, '2025-10-29 10:03:36', '2025-10-29 10:03:36', 42, 'Phát sinh'),
(54, 32, '', 'Bảo dưỡng máy lạnh định kỳ', 3000000.00, '2025-10-29 11:37:53', '2025-10-29 11:37:53', 43, 'Báo giá'),
(55, 32, '', 'Máy chạy không lạnh - thay block', 1.00, '2025-10-29 11:39:32', '2025-10-29 11:39:32', 43, 'Phát sinh'),
(56, 32, '', 'Bảo dưỡng máy lạnh định kỳ', 300000.00, '2025-11-06 14:40:47', '2025-11-06 14:40:47', 43, 'Báo giá'),
(57, 32, '', 'Máy chạy không lạnh - nạp gas', 700000.00, '2025-11-06 15:44:21', '2025-11-06 15:44:21', 43, 'Báo giá');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dondichvu`
--

CREATE TABLE `dondichvu` (
  `maDon` int(11) NOT NULL,
  `maKH` int(11) NOT NULL,
  `diemhen` varchar(100) NOT NULL,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngayDat` varchar(100) NOT NULL,
  `gioDat` varchar(50) NOT NULL,
  `ghiChu` varchar(255) NOT NULL,
  `trangThai` int(11) NOT NULL,
  `noiSuaChua` int(11) NOT NULL,
  `maKTV` int(11) NOT NULL,
  `maKhungGio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dondichvu`
--

INSERT INTO `dondichvu` (`maDon`, `maKH`, `diemhen`, `ngayTao`, `ngayDat`, `gioDat`, `ghiChu`, `trangThai`, `noiSuaChua`, `maKTV`, `maKhungGio`) VALUES
(31, 7, 'Số 1 Nguyễn Văn Bảo, Gò Vấp\r\n', '2025-10-20 17:56:37', '2025-10-21\r\n', 'chieu', 'Tới đúng hẹn', 0, 0, 0, 0),
(32, 7, '41 Lê Lợi, Phường 4, Gò Vấp', '2025-11-03 14:59:54', '2025-11-7', 'sang', 'Gọi điện cho tôi trước khi đến', 4, 0, 0, 0),
(55, 7, 'aaa, Phường Thạnh Xuân, Quận 12, TP Hồ Chí Minh', '2025-11-11 15:34:43', '2025-11-14', '1', '123', 3, 0, 0, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hskythuatvien`
--

CREATE TABLE `hskythuatvien` (
  `maHS` int(11) NOT NULL,
  `maKTV` int(11) NOT NULL,
  `soDon` int(11) NOT NULL,
  `danhGia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hskythuatvien`
--

INSERT INTO `hskythuatvien` (`maHS`, `maKTV`, `soDon`, `danhGia`) VALUES
(1, 3, 10, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
--

CREATE TABLE `khachhang` (
  `maKH` int(11) NOT NULL,
  `maND` int(11) NOT NULL,
  `diemTichLuy` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `kythuatvien`
--

CREATE TABLE `kythuatvien` (
  `maKTV` int(11) NOT NULL,
  `maND` int(11) NOT NULL,
  `maLichCung` int(11) NOT NULL,
  `diemTBDanhGia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `kythuatvien`
--

INSERT INTO `kythuatvien` (`maKTV`, `maND`, `maLichCung`, `diemTBDanhGia`) VALUES
(1, 4, 1, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichcung`
--

CREATE TABLE `lichcung` (
  `maLichCung` int(11) NOT NULL,
  `tenLich` varchar(255) NOT NULL,
  `loaiLich` varchar(50) DEFAULT NULL,
  `ngayLamViec` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ngayLamViec`)),
  `ngayNghi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ngayNghi`)),
  `moTa` text DEFAULT NULL,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngayCapNhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lichcung`
--

INSERT INTO `lichcung` (`maLichCung`, `tenLich`, `loaiLich`, `ngayLamViec`, `ngayNghi`, `moTa`, `ngayTao`, `ngayCapNhat`) VALUES
(1, 'Lich 1 (Lam T3-T7)', '1: Lich 1', '[2,3,4,5,6]', '[0,1]', 'Lam viec tu Thu 3 den Thu 7, nghi Chu nhat va Thu 2', '2025-10-20 09:26:12', '2025-10-20 09:26:12'),
(2, 'Lich 2 (Lam T2-T5,CN)', '2: Lich 2', '[1,2,3,4,0]', '[5,6]', 'Lam viec Thu 2 den Thu 5 va Chu nhat, nghi Thu 6 va Thu 7', '2025-10-20 09:26:12', '2025-10-20 09:26:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichphancong`
--

CREATE TABLE `lichphancong` (
  `id` int(11) NOT NULL,
  `maKTV` int(11) NOT NULL,
  `maDon` varchar(20) NOT NULL,
  `ngayLamViec` date NOT NULL,
  `khungGio` varchar(10) NOT NULL,
  `trangThai` varchar(20) DEFAULT 'cho_xac_nhan',
  `ghiChu` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `soThietBi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lichphancong`
--

INSERT INTO `lichphancong` (`id`, `maKTV`, `maDon`, `ngayLamViec`, `khungGio`, `trangThai`, `ghiChu`, `created_at`, `soThietBi`) VALUES
(6, 4, '30', '2025-10-25', 'trongngay', 'assigned', NULL, '2025-10-19 17:11:13', 0),
(7, 4, '30', '2025-10-25', 'trongngay', 'assigned', NULL, '2025-10-19 17:31:27', 0),
(8, 4, '31', '2025-10-22', 'chieu', 'assigned', NULL, '2025-10-20 17:57:55', 0),
(9, 3, '32', '2025-10-28', 'sang', 'assigned', NULL, '2025-10-25 17:23:29', 0),
(16, 4, '65', '2025-11-12', '1', '1', NULL, '2025-11-11 16:29:07', 3),
(17, 3, '66', '2025-11-13', '2', '1', NULL, '2025-11-12 20:37:09', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichxinnghi`
--

CREATE TABLE `lichxinnghi` (
  `maLichXN` int(11) NOT NULL,
  `maNV` int(11) NOT NULL,
  `ngayNghi` text NOT NULL,
  `ngayBatDau` date NOT NULL,
  `ngayKetThuc` date NOT NULL,
  `soNgayXN` decimal(4,1) NOT NULL,
  `lyDo` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trangThai` int(11) NOT NULL DEFAULT 0,
  `nguoiDuyet` int(11) DEFAULT NULL,
  `ngayDuyet` datetime DEFAULT NULL,
  `ghiChu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ngayTao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lichxinnghi`
--

INSERT INTO `lichxinnghi` (`maLichXN`, `maNV`, `ngayNghi`, `ngayBatDau`, `ngayKetThuc`, `soNgayXN`, `lyDo`, `trangThai`, `nguoiDuyet`, `ngayDuyet`, `ghiChu`, `ngayTao`) VALUES
(17, 4, '', '2025-10-28', '2025-10-28', 1.0, 'abc', 3, NULL, NULL, NULL, '2025-10-26 01:40:27'),
(18, 4, '', '2025-10-29', '2025-10-29', 1.0, 'abc', 3, NULL, NULL, NULL, '2025-10-26 01:41:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_thaotac`
--

CREATE TABLE `lich_su_thaotac` (
  `maLog` int(11) NOT NULL,
  `maDon` int(11) DEFAULT NULL,
  `maKTV` int(11) DEFAULT NULL,
  `loai_hanh_dong` varchar(100) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `thoi_gian_tao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_su_thaotac`
--

INSERT INTO `lich_su_thaotac` (`maLog`, `maDon`, `maKTV`, `loai_hanh_dong`, `mo_ta`, `thoi_gian_tao`) VALUES
(1, 32, 4, 'Khách đồng ý', 'Khách hàng đồng ý sửa chữa', '2025-10-27 19:50:46'),
(2, 32, 4, 'Khách đồng ý', 'Khách hàng đồng ý sửa chữa', '2025-10-27 20:10:30'),
(3, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 21:47:50'),
(4, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 21:47:51'),
(5, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 21:47:52'),
(6, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 21:47:53'),
(7, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 21:47:54'),
(8, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 21:47:55'),
(9, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 21:47:56'),
(10, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 21:47:57'),
(11, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-27 21:47:58'),
(12, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-27 21:47:59'),
(13, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-27 21:48:00'),
(14, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 21:49:45'),
(15, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 21:49:48'),
(16, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 1', '2025-10-27 22:20:21'),
(17, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 43', '2025-10-27 22:31:17'),
(18, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 43', '2025-10-27 22:31:19'),
(19, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 43', '2025-10-27 22:31:19'),
(20, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 43', '2025-10-27 22:31:19'),
(21, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 43', '2025-10-27 22:31:19'),
(22, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 43', '2025-10-27 22:31:20'),
(23, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 22:31:29'),
(24, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 1', '2025-10-27 22:40:28'),
(25, 32, 4, 'Chẩn đoán', 'Chẩn đoán: Bảng điều khiển không hiện đèn, hộp nước bể, thay block máy lạnh - Báo giá: 1,785,000', '2025-10-27 23:31:30'),
(26, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 23:34:31'),
(27, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 23:34:33'),
(28, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 23:34:38'),
(29, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-27 23:35:01'),
(30, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 1', '2025-10-27 23:36:26'),
(31, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-28 00:01:04'),
(32, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 00:05:09'),
(33, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-28 00:10:31'),
(34, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 00:38:23'),
(35, 32, 4, 'Chẩn đoán', 'Chẩn đoán: Hư hỏng ít, thay thế 1 vài phụ kiện ốc vít - Báo giá: 1,750,000', '2025-10-28 00:57:39'),
(36, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 1', '2025-10-28 01:07:24'),
(37, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 1', '2025-10-28 01:08:32'),
(38, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 1', '2025-10-28 01:09:24'),
(39, 32, 4, 'Chẩn đoán', 'Chẩn đoán: anv - Báo giá: 1', '2025-10-28 01:10:30'),
(40, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 1', '2025-10-28 01:12:12'),
(41, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-28 01:16:01'),
(42, 32, 4, 'Thêm công việc phát sinh', 'Thêm công việc: Bảng điều khiển hỏng - Chi phí: 123 VND', '2025-10-28 01:45:15'),
(43, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 01:45:39'),
(44, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 1', '2025-10-28 01:49:18'),
(45, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 1,000,000', '2025-10-28 20:29:18'),
(46, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-28 20:49:53'),
(47, 32, 4, 'Thêm công việc phát sinh', 'Thêm công việc: Tủ lạnh không lạnh - nạp gas - Chi phí: 550,000 VND', '2025-10-28 21:11:03'),
(48, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 1,005,000', '2025-10-28 22:04:06'),
(49, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-28 22:10:26'),
(50, 32, 4, 'Thêm công việc phát sinh', 'Thêm công việc: Thay ốc vít - Chi phí: 15,000 VND', '2025-10-28 22:29:53'),
(51, 32, 4, 'Upload ảnh minh chứng', 'Upload ảnh đến nhà', '2025-10-28 22:35:01'),
(52, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 12', '2025-10-28 22:35:29'),
(53, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:12'),
(54, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:12'),
(55, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:12'),
(56, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:12'),
(57, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:12'),
(58, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:12'),
(59, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:13'),
(60, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:13'),
(61, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:13'),
(62, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:13'),
(63, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:13'),
(64, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:13'),
(65, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:13'),
(66, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:14'),
(67, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:14'),
(68, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:14'),
(69, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:14'),
(70, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:14'),
(71, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:14'),
(72, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:15'),
(73, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:15'),
(74, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:15'),
(75, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:15'),
(76, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:15'),
(77, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:15'),
(78, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:16'),
(79, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:16'),
(80, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:16'),
(81, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:16'),
(82, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:16'),
(83, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:16'),
(84, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:16'),
(85, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:17'),
(86, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:17'),
(87, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:17'),
(88, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:17'),
(89, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:17'),
(90, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:17'),
(91, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:18'),
(92, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:18'),
(93, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:18'),
(94, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:18'),
(95, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:18'),
(96, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:18'),
(97, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:19'),
(98, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:19'),
(99, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:19'),
(100, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:19'),
(101, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:19'),
(102, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:20'),
(103, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:21'),
(104, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:22'),
(105, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:23'),
(106, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:24'),
(107, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:25'),
(108, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:26'),
(109, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:27'),
(110, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:28'),
(111, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:29'),
(112, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:30'),
(113, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:30'),
(114, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:31'),
(115, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:31'),
(116, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:31'),
(117, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:31'),
(118, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:31'),
(119, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:31'),
(120, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:31'),
(121, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-28 22:41:32'),
(122, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 43', '2025-10-28 23:05:22'),
(123, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 43', '2025-10-28 23:06:26'),
(124, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 400,000', '2025-10-28 23:08:47'),
(125, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 43', '2025-10-28 23:09:21'),
(126, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 43', '2025-10-28 23:11:04'),
(127, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 200,000', '2025-10-28 23:13:28'),
(128, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 43', '2025-10-28 23:14:42'),
(129, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 43', '2025-10-28 23:15:37'),
(130, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 43', '2025-10-28 23:16:24'),
(131, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 43', '2025-10-28 23:16:51'),
(132, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 200,000', '2025-10-28 23:31:02'),
(133, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 43', '2025-10-28 23:38:04'),
(134, 32, 4, 'Upload ảnh minh chứng', 'Upload ảnh đến nhà', '2025-10-29 00:06:52'),
(135, 32, 4, 'Upload ảnh minh chứng', 'Upload ảnh thiết bị', '2025-10-29 00:07:27'),
(136, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 420,000', '2025-10-29 00:11:31'),
(137, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-29 00:42:38'),
(138, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-29 00:44:14'),
(139, 32, 4, 'Upload ảnh minh chứng', 'Upload ảnh đến nhà', '2025-10-29 00:44:35'),
(140, 32, 4, 'Upload ảnh minh chứng', 'Upload ảnh thiết bị', '2025-10-29 00:44:43'),
(141, 32, 4, 'Chẩn đoán', 'Chẩn đoán: aaa - Báo giá: 500,000', '2025-10-29 00:46:36'),
(142, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 43', '2025-10-29 00:49:45'),
(143, 32, 4, 'Thêm công việc phát sinh', 'Thêm công việc: Tủ chạy liên tục không ngắt - Chi phí: 400,000 VND', '2025-10-29 00:59:56'),
(144, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 43', '2025-10-29 01:00:11'),
(145, 32, 4, 'Upload ảnh minh chứng', 'Upload ảnh đến nhà', '2025-10-29 01:53:31'),
(146, 32, 4, 'Upload ảnh minh chứng', 'Upload ảnh thiết bị', '2025-10-29 01:53:37'),
(147, 32, 4, 'Chẩn đoán', 'Chẩn đoán: hư block máy, thiếu ốc nắp máy - Báo giá: 2,020,000', '2025-10-29 01:54:57'),
(148, 32, 4, 'Kết thúc sửa thiết bị', 'Kết thúc sửa thiết bị mã: 42', '2025-10-29 02:02:54'),
(149, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-29 02:03:33'),
(150, 32, 4, 'Bắt đầu sửa thiết bị', 'Bắt đầu sửa thiết bị mã: 42', '2025-10-29 02:04:15'),
(170, 32, 4, 'Chẩn đoán', 'Chẩn đoán: bbb - Báo giá: 400,000', '2025-10-29 16:06:21'),
(171, 32, 4, 'Thêm công việc phát sinh', 'Thêm công việc: Bảng điều khiển hỏng - Chi phí: 500,000 VND', '2025-10-29 16:07:57'),
(172, 32, 4, 'Upload ảnh minh chứng', 'Upload ảnh đến nhà', '2025-10-29 16:58:17'),
(173, 32, 4, 'Upload ảnh minh chứng', 'Upload ảnh thiết bị', '2025-10-29 16:58:38'),
(174, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 310,000', '2025-10-29 17:02:24'),
(175, 32, 4, 'Thêm công việc phát sinh', 'Thêm công việc: Chảy nước trong tủ - thông ống thoát nước - Chi phí: 150,000 VND', '2025-10-29 17:03:36'),
(176, 32, 4, 'Upload ảnh minh chứng', 'Upload ảnh đến nhà', '2025-10-29 18:36:06'),
(177, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 3,000,000', '2025-10-29 18:37:53'),
(178, 32, 4, 'Thêm công việc phát sinh', 'Thêm công việc: Máy chạy không lạnh - thay block - Chi phí: 1 VND', '2025-10-29 18:39:32'),
(179, 32, 4, 'Upload ảnh minh chứng', 'Upload ảnh thiết bị', '2025-11-06 21:17:10'),
(180, 32, 4, 'Chẩn đoán', 'Chẩn đoán: a - Báo giá: 300,000', '2025-11-06 21:40:47'),
(181, 32, 4, 'Chẩn đoán', 'Chẩn đoán: abc - Báo giá: 700,000', '2025-11-06 22:44:21'),
(182, 32, 4, 'Upload ảnh minh chứng', 'Upload ảnh thiết bị', '2025-11-07 17:50:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `maND` int(11) NOT NULL,
  `hoTen` varchar(100) NOT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `google_id` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `login_method` varchar(100) DEFAULT NULL,
  `maVaiTro` int(11) DEFAULT NULL,
  `trangThaiHD` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`maND`, `hoTen`, `sdt`, `email`, `password`, `google_id`, `avatar`, `created_at`, `login_method`, `maVaiTro`, `trangThaiHD`) VALUES
(3, 'Kỹ thuật viên A', '0999999999', NULL, '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '2025-09-27 16:15:56', '', 3, 1),
(4, 'Kỹ thuật viên B', '0000000000', NULL, '$2y$10$KFWXVALnKdOB51JQrrZySuq.17gihvGFh3SBn5nRfkS3CxzVVdqYO', NULL, NULL, '2025-09-27 16:24:53', 'normal', 3, 1),
(7, 'Dat Nguyen', '0999999998', 'datnguyen.iclod@gmail.com', NULL, '108069514814137194980', NULL, '2025-09-28 11:50:40', 'google', 1, 1),
(8, 'Tấn Đạt Nguyễn', '0999999995', 'nguyentandat250703@gmail.com', NULL, '112112315522809127983', NULL, '2025-09-28 11:52:47', 'google', 3, 1),
(13, 'Nguyen A', '0999999991', NULL, '$2y$10$KFWXVALnKdOB51JQrrZySuq.17gihvGFh3SBn5nRfkS3CxzVVdqYO', NULL, NULL, '2025-10-07 17:34:01', 'normal', 2, 1),
(14, 'Quản lý A', '0111111111', 'quanlya@gmail.com', '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '2025-10-12 12:34:57', 'normal', 4, 1),
(15, 'Nguyễn Hi', '0909090909', NULL, '$2y$10$xk1EadxdLZsrJA1yTuQise8vmFmuMChdNcQVwxU8Asodyt0WxGsSC', NULL, NULL, '2025-11-05 18:38:49', 'normal', 1, 0),
(16, 'Nguyễn Hi', '0909090908', NULL, '$2y$10$gQIxd9psBE/Zt8K03Yk96e3UrmE6Xuvdx344mavt3Rorc7Q7aVCsu', NULL, NULL, '2025-11-05 18:40:07', 'normal', 1, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Họ và tên',
  `birthdate` date NOT NULL COMMENT 'Ngày sinh',
  `gender` enum('male','female','other') NOT NULL COMMENT 'Giới tính',
  `email` varchar(100) NOT NULL COMMENT 'Email',
  `phone` varchar(15) NOT NULL COMMENT 'Số điện thoại',
  `password` varchar(255) NOT NULL COMMENT 'Mật khẩu (hashed)',
  `address` text DEFAULT NULL COMMENT 'Địa chỉ',
  `chuyenMon` varchar(100) DEFAULT NULL COMMENT 'Chuyên môn/Kỹ năng',
  `role` tinyint(4) NOT NULL DEFAULT 3 COMMENT '1: CSKH, 2: Kinh doanh, 3: KTV, 4: Quản lý',
  `work_schedule` tinyint(4) DEFAULT NULL COMMENT '1: Lịch 1, 2: Lịch 2',
  `status` enum('active','inactive','suspended') DEFAULT 'active' COMMENT 'Trạng thái',
  `assigned_by` int(11) DEFAULT NULL COMMENT 'ID người phân công lịch',
  `schedule_assigned_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian phân công lịch',
  `danhGia` decimal(3,2) DEFAULT 5.00 COMMENT 'Đánh giá trung bình (1-5)',
  `total_assignments` int(11) DEFAULT 0 COMMENT 'Tổng số công việc đã nhận',
  `completed_assignments` int(11) DEFAULT 0 COMMENT 'Số công việc hoàn thành',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `work_location` enum('store','customer_home','both') DEFAULT 'both',
  `max_assignments_per_day` int(11) DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhanvien`
--

INSERT INTO `nhanvien` (`id`, `name`, `birthdate`, `gender`, `email`, `phone`, `password`, `address`, `chuyenMon`, `role`, `work_schedule`, `status`, `assigned_by`, `schedule_assigned_at`, `danhGia`, `total_assignments`, `completed_assignments`, `created_at`, `updated_at`, `work_location`, `max_assignments_per_day`) VALUES
(1, 'Nguyễn Văn Quản Lý', '1985-05-15', 'male', 'quanly@techcare.com', '0901111111', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Đường Lê Lợi, Quận 1, TP.HCM', NULL, 4, 1, 'active', NULL, NULL, 5.00, 0, 0, '2025-10-19 14:41:18', '2025-10-19 14:41:18', 'both', 3),
(2, 'Trần Văn Kỹ Thuật', '1990-08-20', 'male', 'ktv1@techcare.com', '0902222222', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Nguyễn Huệ, Quận 1, TP.HCM', 'Điện lạnh, Điện tử', 3, 1, 'active', NULL, NULL, 5.00, 0, 0, '2025-10-19 14:41:18', '2025-10-19 15:42:20', 'store', 3),
(3, 'Lê Thị Bảo Trì', '1992-03-10', 'female', 'ktv2@techcare.com', '0903333333', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '789 Lý Tự Trọng, Quận 3, TP.HCM', 'Điện tử, Viễn thông', 3, 2, 'active', NULL, NULL, 5.00, 0, 0, '2025-10-19 14:41:18', '2025-10-19 15:42:20', 'customer_home', 3),
(4, 'Phạm Văn Sửa Chữa', '1988-11-25', 'male', 'ktv3@techcare.com', '0904444444', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '321 Cách Mạng Tháng 8, Quận 10, TP.HCM', 'Cơ khí, Điện lạnh', 3, 1, 'active', NULL, NULL, 5.00, 0, 0, '2025-10-19 14:41:18', '2025-10-19 14:41:18', 'both', 3),
(5, 'Hoàng Thị Kinh Doanh', '1993-07-18', 'female', 'kinhdoanh@techcare.com', '0905555555', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '159 Pasteur, Quận 3, TP.HCM', NULL, 2, 2, 'active', NULL, NULL, 5.00, 0, 0, '2025-10-19 14:41:18', '2025-10-19 14:41:18', 'both', 3),
(6, 'Võ Văn Chăm Sóc', '1991-12-05', 'male', 'cskh@techcare.com', '0906666666', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '753 Hai Bà Trưng, Quận 1, TP.HCM', NULL, 1, 1, 'active', NULL, NULL, 5.00, 0, 0, '2025-10-19 14:41:18', '2025-10-19 14:41:18', 'both', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phancongnhanvien`
--

CREATE TABLE `phancongnhanvien` (
  `maPhanCong` int(11) NOT NULL,
  `maDon` int(11) NOT NULL,
  `maCTDon` int(11) NOT NULL,
  `maNhanVien` int(11) NOT NULL,
  `ngayPhanCong` datetime DEFAULT current_timestamp(),
  `trangThai` enum('cho_nhan','dang_sua','hoan_thanh','huy') DEFAULT 'cho_nhan',
  `thoiGianBatDau` datetime DEFAULT NULL,
  `thoiGianKetThuc` datetime DEFAULT NULL,
  `danhGia` int(11) DEFAULT NULL CHECK (`danhGia` >= 1 and `danhGia` <= 5),
  `ghiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thietbi`
--

CREATE TABLE `thietbi` (
  `maThietBi` int(11) NOT NULL,
  `tenThietBi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thietbi`
--

INSERT INTO `thietbi` (`maThietBi`, `tenThietBi`) VALUES
(1, 'Tủ lạnh'),
(2, 'Máy lạnh'),
(3, 'Tivi'),
(4, 'Máy giặt'),
(5, 'Máy nước nóng'),
(6, 'Bếp điện'),
(7, 'Lò vi sóng'),
(8, 'Máy sấy'),
(9, 'Nồi cơm điện'),
(10, 'Quạt điện'),
(11, 'Máy lọc không khí'),
(12, 'Thiết bị khác');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vaitro`
--

CREATE TABLE `vaitro` (
  `maVaiTro` int(11) NOT NULL,
  `tenVaiTro` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vaitro`
--

INSERT INTO `vaitro` (`maVaiTro`, `tenVaiTro`) VALUES
(1, 'Khách hàng'),
(3, 'Kỹ thuật viên'),
(5, 'Kỹ thuật viên trưởng'),
(2, 'Nhân viên'),
(4, 'Quản lý');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `work_calendar`
--

CREATE TABLE `work_calendar` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL COMMENT 'ID nhân viên',
  `work_date` date NOT NULL COMMENT 'Ngày làm việc',
  `day_of_week` tinyint(4) NOT NULL COMMENT '0: CN, 1: T2, ..., 6: T7',
  `shift_type` enum('morning','afternoon','evening','full_day') DEFAULT 'full_day',
  `start_time` time DEFAULT NULL COMMENT 'Giờ bắt đầu',
  `end_time` time DEFAULT NULL COMMENT 'Giờ kết thúc',
  `total_assignments` int(11) DEFAULT 0 COMMENT 'Tổng số công việc trong ngày',
  `completed_assignments` int(11) DEFAULT 0 COMMENT 'Số công việc đã hoàn thành',
  `status` enum('scheduled','working','completed','day_off','sick_leave','vacation') DEFAULT 'scheduled',
  `notes` text DEFAULT NULL COMMENT 'Ghi chú ngày làm việc',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `work_schedules`
--

CREATE TABLE `work_schedules` (
  `id` int(11) NOT NULL,
  `schedule_name` varchar(100) NOT NULL COMMENT 'Tên lịch làm việc',
  `schedule_type` tinyint(4) NOT NULL COMMENT '1: Lịch 1, 2: Lịch 2',
  `working_days` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Danh sách ngày làm việc [2,3,4,5,6]' CHECK (json_valid(`working_days`)),
  `off_days` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Danh sách ngày nghỉ [0,1]' CHECK (json_valid(`off_days`)),
  `description` text DEFAULT NULL COMMENT 'Mô tả chi tiết lịch làm',
  `color` varchar(7) DEFAULT '#3498db' COMMENT 'Màu sắc hiển thị',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Lịch có đang hoạt động',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `work_schedules`
--

INSERT INTO `work_schedules` (`id`, `schedule_name`, `schedule_type`, `working_days`, `off_days`, `description`, `color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Lịch 1 (Làm T3-T7)', 1, '[2,3,4,5,6]', '[0,1]', 'Làm việc từ Thứ 3 đến Thứ 7, nghỉ Chủ nhật và Thứ 2', '#27ae60', 1, '2025-10-19 14:41:46', '2025-10-19 14:41:46'),
(2, 'Lịch 2 (Làm T2-T5,CN)', 2, '[1,2,3,4,0]', '[5,6]', 'Làm việc Thứ 2 đến Thứ 5 và Chủ nhật, nghỉ Thứ 6 và Thứ 7', '#3498db', 1, '2025-10-19 14:41:46', '2025-10-19 14:41:46');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `banggiasc`
--
ALTER TABLE `banggiasc`
  ADD PRIMARY KEY (`maGia`);

--
-- Chỉ mục cho bảng `bangkhunggio`
--
ALTER TABLE `bangkhunggio`
  ADD PRIMARY KEY (`maKhungGio`),
  ADD UNIQUE KEY `soThuTu` (`soThuTu`);

--
-- Chỉ mục cho bảng `bangphanboslot`
--
ALTER TABLE `bangphanboslot`
  ADD PRIMARY KEY (`maPhanBo`);

--
-- Chỉ mục cho bảng `chitietdondichvu`
--
ALTER TABLE `chitietdondichvu`
  ADD PRIMARY KEY (`maCTDon`);

--
-- Chỉ mục cho bảng `chitietsuachua`
--
ALTER TABLE `chitietsuachua`
  ADD PRIMARY KEY (`maCTSuaChua`),
  ADD KEY `idx_ma_don` (`maDon`),
  ADD KEY `idx_ma_thiet_bi` (`maThietBi`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Chỉ mục cho bảng `dondichvu`
--
ALTER TABLE `dondichvu`
  ADD PRIMARY KEY (`maDon`);

--
-- Chỉ mục cho bảng `hskythuatvien`
--
ALTER TABLE `hskythuatvien`
  ADD PRIMARY KEY (`maHS`);

--
-- Chỉ mục cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`maKH`);

--
-- Chỉ mục cho bảng `kythuatvien`
--
ALTER TABLE `kythuatvien`
  ADD PRIMARY KEY (`maKTV`);

--
-- Chỉ mục cho bảng `lichcung`
--
ALTER TABLE `lichcung`
  ADD PRIMARY KEY (`maLichCung`);

--
-- Chỉ mục cho bảng `lichphancong`
--
ALTER TABLE `lichphancong`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `lichxinnghi`
--
ALTER TABLE `lichxinnghi`
  ADD PRIMARY KEY (`maLichXN`);

--
-- Chỉ mục cho bảng `lich_su_thaotac`
--
ALTER TABLE `lich_su_thaotac`
  ADD PRIMARY KEY (`maLog`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`maND`),
  ADD UNIQUE KEY `phone` (`sdt`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `assigned_by` (`assigned_by`);

--
-- Chỉ mục cho bảng `phancongnhanvien`
--
ALTER TABLE `phancongnhanvien`
  ADD PRIMARY KEY (`maPhanCong`),
  ADD KEY `maDon` (`maDon`),
  ADD KEY `maCTDon` (`maCTDon`),
  ADD KEY `maNhanVien` (`maNhanVien`);

--
-- Chỉ mục cho bảng `thietbi`
--
ALTER TABLE `thietbi`
  ADD PRIMARY KEY (`maThietBi`);

--
-- Chỉ mục cho bảng `vaitro`
--
ALTER TABLE `vaitro`
  ADD PRIMARY KEY (`maVaiTro`),
  ADD UNIQUE KEY `role_name` (`tenVaiTro`);

--
-- Chỉ mục cho bảng `work_calendar`
--
ALTER TABLE `work_calendar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_employee_work_date` (`employee_id`,`work_date`),
  ADD KEY `idx_work_calendar_employee` (`employee_id`),
  ADD KEY `idx_work_calendar_date` (`work_date`),
  ADD KEY `idx_work_calendar_status` (`status`);

--
-- Chỉ mục cho bảng `work_schedules`
--
ALTER TABLE `work_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `schedule_type` (`schedule_type`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `banggiasc`
--
ALTER TABLE `banggiasc`
  MODIFY `maGia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT cho bảng `bangkhunggio`
--
ALTER TABLE `bangkhunggio`
  MODIFY `maKhungGio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `bangphanboslot`
--
ALTER TABLE `bangphanboslot`
  MODIFY `maPhanBo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT cho bảng `chitietdondichvu`
--
ALTER TABLE `chitietdondichvu`
  MODIFY `maCTDon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT cho bảng `chitietsuachua`
--
ALTER TABLE `chitietsuachua`
  MODIFY `maCTSuaChua` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT cho bảng `dondichvu`
--
ALTER TABLE `dondichvu`
  MODIFY `maDon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT cho bảng `hskythuatvien`
--
ALTER TABLE `hskythuatvien`
  MODIFY `maHS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `maKH` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `kythuatvien`
--
ALTER TABLE `kythuatvien`
  MODIFY `maKTV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `lichcung`
--
ALTER TABLE `lichcung`
  MODIFY `maLichCung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `lichphancong`
--
ALTER TABLE `lichphancong`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `lichxinnghi`
--
ALTER TABLE `lichxinnghi`
  MODIFY `maLichXN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `lich_su_thaotac`
--
ALTER TABLE `lich_su_thaotac`
  MODIFY `maLog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `maND` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `phancongnhanvien`
--
ALTER TABLE `phancongnhanvien`
  MODIFY `maPhanCong` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `thietbi`
--
ALTER TABLE `thietbi`
  MODIFY `maThietBi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `vaitro`
--
ALTER TABLE `vaitro`
  MODIFY `maVaiTro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `work_calendar`
--
ALTER TABLE `work_calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `work_schedules`
--
ALTER TABLE `work_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietsuachua`
--
ALTER TABLE `chitietsuachua`
  ADD CONSTRAINT `chitietsuachua_ibfk_1` FOREIGN KEY (`maDon`) REFERENCES `dondichvu` (`maDon`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lichphancong`
--
ALTER TABLE `lichphancong`
  ADD CONSTRAINT `lichphancong_ibfk_1` FOREIGN KEY (`maKTV`) REFERENCES `nguoidung` (`maND`);

--
-- Các ràng buộc cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`maVaiTro`) REFERENCES `vaitro` (`maVaiTro`);

--
-- Các ràng buộc cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`assigned_by`) REFERENCES `nhanvien` (`id`);

--
-- Các ràng buộc cho bảng `phancongnhanvien`
--
ALTER TABLE `phancongnhanvien`
  ADD CONSTRAINT `phancongnhanvien_ibfk_1` FOREIGN KEY (`maDon`) REFERENCES `dondichvu` (`maDon`) ON DELETE CASCADE,
  ADD CONSTRAINT `phancongnhanvien_ibfk_2` FOREIGN KEY (`maCTDon`) REFERENCES `chitietdondichvu` (`maCTDon`) ON DELETE CASCADE,
  ADD CONSTRAINT `phancongnhanvien_ibfk_3` FOREIGN KEY (`maNhanVien`) REFERENCES `nguoidung` (`maND`);

--
-- Các ràng buộc cho bảng `work_calendar`
--
ALTER TABLE `work_calendar`
  ADD CONSTRAINT `work_calendar_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `nhanvien` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
