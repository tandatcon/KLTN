-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 23, 2025 lúc 09:27 PM
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
  `DVT` varchar(15) NOT NULL,
  `thoigiansuachua` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `banggiasc`
--

INSERT INTO `banggiasc` (`maGia`, `maThietBi`, `chitietLoi`, `khoangGia`, `ghiChu`, `created_at`, `DVT`, `thoigiansuachua`) VALUES
(1, 1, 'Không lạnh - hết gas/nạp gas', '350000-550000', '', '2025-11-20 09:14:46', 'lần', 150),
(2, 1, 'Không lạnh - block yếu', '1200000-1800000', '', '2025-11-20 09:14:46', 'lần', 240),
(3, 1, 'Tủ kêu to, rung mạnh', '250000-400000', '', '2025-11-20 09:14:46', 'lần', 90),
(4, 1, 'Ngăn đá không đông', '300000-500000', '', '2025-11-20 09:14:46', 'lần', 120),
(5, 1, 'Đóng tuyết nhiều, xả đá kém', '200000-350000', '', '2025-11-20 09:14:46', 'lần', 90),
(6, 1, 'Đèn trong tủ không sáng', '150000-250000', '', '2025-11-20 09:14:46', 'lần', 45),
(7, 1, 'Thay ron cửa tủ lạnh', '250000-400000', '', '2025-11-20 09:14:46', 'cái', 75),
(8, 1, 'Thay thermostat', '350000-550000', '', '2025-11-20 09:14:46', 'cái', 90),
(9, 1, 'Thay cảm biến nhiệt độ', '300000-450000', '', '2025-11-20 09:14:46', 'cái', 60),
(10, 1, 'Sửa bo mạch Inverter', '800000-1500000', '', '2025-11-20 09:14:46', 'lần', 210),
(11, 1, 'Thay block (lốc) tủ lạnh', '2500000-4500000', '', '2025-11-20 09:14:46', 'cái', 300),
(12, 1, 'Hàn ống gas, xử lý rò rỉ', '400000-600000', '', '2025-11-20 09:14:46', 'lần', 120),
(13, 1, 'Chảy nước dưới ngăn đá', '250000-400000', '', '2025-11-20 09:14:46', 'lần', 90),
(14, 1, 'Quạt dàn lạnh không quay', '350000-550000', '', '2025-11-20 09:14:46', 'lần', 120),
(15, 1, 'Vệ sinh bảo dưỡng tổng quát', '250000-350000', '', '2025-11-20 09:14:46', 'lần', 90),
(16, 2, 'Không lạnh - hết gas', '350000-600000', '', '2025-11-20 09:14:46', 'lần', 120),
(17, 2, 'Máy lạnh chảy nước', '200000-350000', '', '2025-11-20 09:14:46', 'lần', 90),
(18, 2, 'Máy lạnh kêu to, rung', '250000-450000', '', '2025-11-20 09:14:46', 'lần', 105),
(19, 2, 'Thay tụ điện dàn nóng/lạnh', '250000-400000', '', '2025-11-20 09:14:46', 'cái', 75),
(20, 2, 'Thay board dàn lạnh Inverter', '1200000-2200000', '', '2025-11-20 09:14:46', 'cái', 210),
(21, 2, 'Vệ sinh máy lạnh', '180000-280000', '', '2025-11-20 09:14:46', 'lần', 60),
(22, 2, 'Bơm gas R32/R410', '500000-800000', '', '2025-11-20 09:14:46', 'lần', 120),
(23, 2, 'Thay block 1-1.5HP', '3500000-5500000', '', '2025-11-20 09:14:46', 'cái', 360),
(24, 2, 'Sửa bo mạch dàn nóng', '900000-1800000', '', '2025-11-20 09:14:46', 'lần', 240),
(25, 2, 'Báo lỗi H1/F0/E1...', '400000-800000', '', '2025-11-20 09:14:46', 'lần', 150),
(26, 2, 'Thay quạt dàn lạnh', '600000-950000', '', '2025-11-20 09:14:46', 'cái', 150),
(27, 2, 'Thay cảm biến nhiệt độ', '250000-400000', '', '2025-11-20 09:14:46', 'cái', 60),
(28, 2, 'Di dời máy lạnh', '800000-1500000', '', '2025-11-20 09:14:46', 'lần', 240),
(29, 2, 'Hàn ống đồng, xử lý dò gas', '400000-700000', '', '2025-11-20 09:14:46', 'lần', 120),
(30, 2, 'Lắp đặt máy lạnh mới', '700000-1200000', '', '2025-11-20 09:14:46', 'lần', 210),
(31, 3, 'Không lên hình, có tiếng', '400000-800000', '', '2025-11-20 09:14:46', 'lần', 120),
(32, 3, 'Không có tiếng', '300000-600000', '', '2025-11-20 09:14:46', 'lần', 90),
(33, 3, 'Màn hình sọc, nhiễu', '800000-2000000', '', '2025-11-20 09:14:46', 'lần', 180),
(34, 3, 'Thay màn hình 43-55 inch', '3500000-8500000', '', '2025-11-20 09:14:46', 'cái', 240),
(35, 3, 'Thay bo nguồn', '600000-1200000', '', '2025-11-20 09:14:46', 'cái', 120),
(36, 3, 'Thay mainboard', '1500000-4500000', '', '2025-11-20 09:14:46', 'cái', 210),
(37, 3, 'Không nhận remote', '200000-400000', '', '2025-11-20 09:14:46', 'lần', 60),
(38, 3, 'Mất đèn báo nguồn', '250000-450000', '', '2025-11-20 09:14:46', 'lần', 75),
(39, 3, 'Thay đèn nền LED', '800000-1800000', '', '2025-11-20 09:14:46', 'lần', 240),
(40, 3, 'Màn hình loang màu', '1200000-3000000', '', '2025-11-20 09:14:46', 'lần', 210),
(41, 3, 'Thay dây nguồn bên trong', '200000-350000', '', '2025-11-20 09:14:46', 'lần', 60),
(42, 3, 'Treo logo, không khởi động', '500000-1200000', '', '2025-11-20 09:14:46', 'lần', 150),
(43, 3, 'Cập nhật phần mềm', '150000-300000', '', '2025-11-20 09:14:46', 'lần', 45),
(44, 3, 'Thay cổng HDMI/USB', '300000-600000', '', '2025-11-20 09:14:46', 'cái', 90),
(45, 3, 'Vệ sinh bụi bên trong', '150000-250000', '', '2025-11-20 09:14:46', 'lần', 45),
(46, 4, 'Không xả nước', '300000-550000', '', '2025-11-20 09:14:46', 'lần', 105),
(47, 4, 'Kêu to khi vắt', '400000-700000', '', '2025-11-20 09:14:46', 'lần', 150),
(48, 4, 'Thay bi/bạc đạn', '600000-950000', '', '2025-11-20 09:14:46', 'lần', 180),
(49, 4, 'Thay phuộc giảm xóc (cặp)', '350000-600000', '', '2025-11-20 09:14:46', 'cái', 120),
(50, 4, 'Thay board Inverter', '1200000-2500000', '', '2025-11-20 09:14:46', 'cái', 210),
(51, 4, 'Báo lỗi UE/OE/LE', '250000-500000', '', '2025-11-20 09:14:46', 'lần', 90),
(52, 4, 'Thay van cấp nước', '250000-450000', '', '2025-11-20 09:14:46', 'cái', 75),
(53, 4, 'Thay bơm xả', '350000-600000', '', '2025-11-20 09:14:46', 'cái', 90),
(54, 4, 'Thay dây curoa', '200000-350000', '', '2025-11-20 09:14:46', 'cái', 90),
(55, 4, 'Không quay lồng', '500000-1200000', '', '2025-11-20 09:14:46', 'lần', 180),
(56, 4, 'Thay motor', '1200000-2500000', '', '2025-11-20 09:14:46', 'cái', 240),
(57, 4, 'Thay khóa cửa', '300000-550000', '', '2025-11-20 09:14:46', 'cái', 90),
(58, 4, 'Vệ sinh lồng giặt', '200000-350000', '', '2025-11-20 09:14:46', 'lần', 90),
(59, 4, 'Máy giặt cửa trên không vắt', '450000-750000', '', '2025-11-20 09:14:46', 'lần', 150),
(60, 4, 'Máy giặt rung lắc mạnh', '350000-600000', '', '2025-11-20 09:14:46', 'lần', 120),
(61, 5, 'Không nóng nước', '300000-550000', '', '2025-11-20 09:14:46', 'lần', 90),
(62, 5, 'Thay thanh nhiệt', '450000-750000', '', '2025-11-20 09:14:46', 'cái', 105),
(63, 5, 'Thay rơ-le nhiệt', '250000-400000', '', '2025-11-20 09:14:46', 'cái', 60),
(64, 5, 'Thay van một chiều', '200000-350000', '', '2025-11-20 09:14:46', 'cái', 60),
(65, 5, 'Thay bơm trợ lực', '600000-950000', '', '2025-11-20 09:14:46', 'cái', 120),
(66, 5, 'Máy chảy nước', '250000-450000', '', '2025-11-20 09:14:46', 'lần', 90),
(67, 5, 'Thay board điều khiển', '700000-1300000', '', '2025-11-20 09:14:46', 'cái', 150),
(68, 5, 'Vệ sinh bình nóng lạnh', '250000-400000', '', '2025-11-20 09:14:46', 'lần', 90),
(69, 5, 'Thay thanh magie', '300000-500000', '', '2025-11-20 09:14:46', 'cái', 90),
(70, 5, 'Nước nóng yếu', '300000-500000', '', '2025-11-20 09:14:46', 'lần', 90),
(71, 5, 'Máy kêu to', '250000-400000', '', '2025-11-20 09:14:46', 'lần', 75),
(72, 5, 'Báo lỗi E1/E2', '300000-600000', '', '2025-11-20 09:14:46', 'lần', 120),
(73, 5, 'Thay cảm biến nhiệt độ', '250000-400000', '', '2025-11-20 09:14:46', 'cái', 60),
(74, 5, 'Rò điện nhẹ', '350000-650000', '', '2025-11-20 09:14:46', 'lần', 120),
(75, 5, 'Lắp đặt máy nước nóng mới', '400000-700000', '', '2025-11-20 09:14:46', 'lần', 150),
(76, 6, 'Không nóng', '250000-500000', '', '2025-11-20 09:14:46', 'lần', 90),
(77, 6, 'Thay mâm nhiệt bếp từ', '800000-1800000', '', '2025-11-20 09:14:46', 'cái', 150),
(78, 6, 'Thay board bếp từ', '1200000-2500000', '', '2025-11-20 09:14:46', 'cái', 180),
(79, 6, 'Bếp báo lỗi E0/E1', '300000-600000', '', '2025-11-20 09:14:46', 'lần', 105),
(80, 6, 'Thay quạt tản nhiệt', '300000-550000', '', '2025-11-20 09:14:46', 'cái', 90),
(81, 6, 'Mặt kính bị nứt', '900000-2500000', '', '2025-11-20 09:14:46', 'cái', 120),
(82, 6, 'Bếp không nhận nồi', '400000-800000', '', '2025-11-20 09:14:46', 'lần', 120),
(83, 6, 'Thay cuộn dây cảm ứng', '1000000-2000000', '', '2025-11-20 09:14:46', 'cái', 180),
(84, 6, 'Bếp nóng yếu', '300000-550000', '', '2025-11-20 09:14:46', 'lần', 90),
(85, 6, 'Thay nút cảm ứng', '400000-750000', '', '2025-11-20 09:14:46', 'lần', 120),
(86, 6, 'Bếp kêu tạch tạch', '350000-600000', '', '2025-11-20 09:14:46', 'lần', 105),
(87, 6, 'Thay IGBT', '800000-1500000', '', '2025-11-20 09:14:46', 'cái', 180),
(88, 6, 'Vệ sinh bếp từ', '150000-250000', '', '2025-11-20 09:14:46', 'lần', 45),
(89, 6, 'Không lên nguồn', '500000-1200000', '', '2025-11-20 09:14:46', 'lần', 150),
(90, 6, 'Thay biến áp', '600000-1100000', '', '2025-11-20 09:14:46', 'cái', 150),
(91, 7, 'Không nóng thức ăn', '300000-600000', '', '2025-11-20 09:14:46', 'lần', 120),
(92, 7, 'Thay magnetron', '900000-1800000', '', '2025-11-20 09:14:46', 'cái', 180),
(93, 7, 'Thay cầu chì cao áp', '200000-350000', '', '2025-11-20 09:14:46', 'cái', 60),
(94, 7, 'Đĩa quay không xoay', '250000-450000', '', '2025-11-20 09:14:46', 'lần', 90),
(95, 7, 'Thay motor quay đĩa', '300000-550000', '', '2025-11-20 09:14:46', 'cái', 90),
(96, 7, 'Cửa không đóng', '250000-400000', '', '2025-11-20 09:14:46', 'lần', 75),
(97, 7, 'Thay công tắc cửa', '200000-350000', '', '2025-11-20 09:14:46', 'cái', 60),
(98, 7, 'Phát tia lửa bên trong', '400000-800000', '', '2025-11-20 09:14:46', 'lần', 120),
(99, 7, 'Bàn phím không bấm được', '300000-600000', '', '2025-11-20 09:14:46', 'lần', 90),
(100, 7, 'Không lên nguồn', '350000-700000', '', '2025-11-20 09:14:46', 'lần', 120),
(101, 7, 'Thay biến áp cao áp', '800000-1500000', '', '2025-11-20 09:14:46', 'cái', 180),
(102, 7, 'Hẹn giờ không hoạt động', '250000-500000', '', '2025-11-20 09:14:46', 'lần', 90),
(103, 7, 'Quạt thông gió không chạy', '300000-550000', '', '2025-11-20 09:14:46', 'lần', 90),
(104, 7, 'Đèn trong lò không sáng', '150000-300000', '', '2025-11-20 09:14:46', 'lần', 45),
(105, 7, 'Vệ sinh lò vi sóng', '150000-250000', '', '2025-11-20 09:14:46', 'lần', 45),
(106, 8, 'Không quay', '400000-800000', '', '2025-11-20 09:14:46', 'lần', 150),
(107, 8, 'Không nóng', '350000-650000', '', '2025-11-20 09:14:46', 'lần', 120),
(108, 8, 'Thay dây curoa', '200000-400000', '', '2025-11-20 09:14:46', 'cái', 90),
(109, 8, 'Thay thanh nhiệt', '500000-900000', '', '2025-11-20 09:14:46', 'cái', 120),
(110, 8, 'Thay motor', '1200000-2500000', '', '2025-11-20 09:14:46', 'cái', 240),
(111, 8, 'Kêu to khi chạy', '300000-600000', '', '2025-11-20 09:14:46', 'lần', 120),
(112, 8, 'Thay board điều khiển', '800000-1600000', '', '2025-11-20 09:14:46', 'cái', 180),
(113, 8, 'Không lên nguồn', '350000-700000', '', '2025-11-20 09:14:46', 'lần', 120),
(114, 8, 'Cửa không đóng', '250000-450000', '', '2025-11-20 09:14:46', 'lần', 75),
(115, 8, 'Báo lỗi đầy bụi', '200000-400000', '', '2025-11-20 09:14:46', 'lần', 60),
(116, 8, 'Thay cảm biến độ ẩm', '400000-750000', '', '2025-11-20 09:14:46', 'cái', 90),
(117, 8, 'Máy sấy rung lắc', '350000-650000', '', '2025-11-20 09:14:46', 'lần', 120),
(118, 8, 'Thay bi lồng sấy', '600000-1000000', '', '2025-11-20 09:14:46', 'lần', 180),
(119, 8, 'Vệ sinh đường ống xả', '200000-350000', '', '2025-11-20 09:14:46', 'lần', 60),
(120, 8, 'Thay khóa cửa', '300000-550000', '', '2025-11-20 09:14:46', 'cái', 90),
(121, 9, 'Không nấu chín cơm', '150000-350000', '', '2025-11-20 09:14:46', 'lần', 75),
(122, 9, 'Không lên nguồn', '120000-300000', '', '2025-11-20 09:14:46', 'lần', 60),
(123, 9, 'Thay mâm nhiệt', '250000-500000', '', '2025-11-20 09:14:46', 'cái', 90),
(124, 9, 'Thay rơ-le nhiệt', '150000-300000', '', '2025-11-20 09:14:46', 'cái', 60),
(125, 9, 'Nồi cơm điện bị rò điện', '200000-400000', '', '2025-11-20 09:14:46', 'lần', 90),
(126, 9, 'Cơm bị nhão/khét', '150000-350000', '', '2025-11-20 09:14:46', 'lần', 75),
(127, 9, 'Thay board nồi cao tần', '600000-1200000', '', '2025-11-20 09:14:46', 'cái', 150),
(128, 9, 'Van xả hơi không hoạt động', '200000-400000', '', '2025-11-20 09:14:46', 'lần', 75),
(129, 9, 'Thay gioăng nồi', '100000-250000', '', '2025-11-20 09:14:46', 'cái', 45),
(130, 9, 'Nút bấm không nhạy', '150000-300000', '', '2025-11-20 09:14:46', 'lần', 60),
(131, 9, 'Thay cảm biến nhiệt độ', '200000-400000', '', '2025-11-20 09:14:46', 'cái', 75),
(132, 9, 'Nồi bị trào cơm', '150000-300000', '', '2025-11-20 09:14:46', 'lần', 60),
(133, 9, 'Đèn báo không sáng', '100000-250000', '', '2025-11-20 09:14:46', 'lần', 45),
(134, 9, 'Vệ sinh nồi cơm điện', '80000-150000', '', '2025-11-20 09:14:46', 'lần', 30),
(135, 9, 'Thay dây nguồn', '100000-200000', '', '2025-11-20 09:14:46', 'cái', 45),
(136, 10, 'Quạt không quay', '100000-250000', '', '2025-11-20 09:14:46', 'lần', 60),
(137, 10, 'Quạt kêu to', '80000-200000', '', '2025-11-20 09:14:46', 'lần', 45),
(138, 10, 'Thay tụ điện', '80000-180000', '', '2025-11-20 09:14:46', 'cái', 45),
(139, 10, 'Thay motor quạt', '300000-700000', '', '2025-11-20 09:14:46', 'cái', 120),
(140, 10, 'Không đổi tốc độ', '100000-250000', '', '2025-11-20 09:14:46', 'lần', 60),
(141, 10, 'Quạt bị rung lắc', '100000-200000', '', '2025-11-20 09:14:46', 'lần', 60),
(142, 10, 'Thay cánh quạt', '150000-350000', '', '2025-11-20 09:14:46', 'cái', 60),
(143, 10, 'Remote không hoạt động', '150000-300000', '', '2025-11-20 09:14:46', 'lần', 60),
(144, 10, 'Thay board điều khiển', '250000-550000', '', '2025-11-20 09:14:46', 'cái', 90),
(145, 10, 'Quạt cây bị nghiêng', '100000-250000', '', '2025-11-20 09:14:46', 'lần', 60),
(146, 10, 'Đèn quạt không sáng', '80000-150000', '', '2025-11-20 09:14:46', 'lần', 30),
(147, 10, 'Thay ổ bi quạt', '150000-300000', '', '2025-11-20 09:14:46', 'cái', 75),
(148, 10, 'Quạt hơi bị rò điện', '150000-300000', '', '2025-11-20 09:14:46', 'lần', 90),
(149, 10, 'Vệ sinh quạt', '50000-100000', '', '2025-11-20 09:14:46', 'lần', 30),
(150, 10, 'Thay dây nguồn', '80000-150000', '', '2025-11-20 09:14:46', 'cái', 45),
(151, 11, 'Không lên nguồn', '200000-500000', '', '2025-11-20 09:14:46', 'lần', 75),
(152, 11, 'Quạt gió yếu', '250000-550000', '', '2025-11-20 09:14:46', 'lần', 90),
(153, 11, 'Thay màng lọc HEPA', '300000-800000', '', '2025-11-20 09:14:46', 'cái', 60),
(154, 11, 'Thay board điều khiển', '600000-1500000', '', '2025-11-20 09:14:46', 'cái', 150),
(155, 11, 'Báo lỗi đèn đỏ', '200000-450000', '', '2025-11-20 09:14:46', 'lần', 75),
(156, 11, 'Không cảm biến bụi', '300000-600000', '', '2025-11-20 09:14:46', 'lần', 90),
(157, 11, 'Thay cảm biến PM2.5', '400000-800000', '', '2025-11-20 09:14:46', 'cái', 90),
(158, 11, 'Máy kêu to', '200000-400000', '', '2025-11-20 09:14:46', 'lần', 60),
(159, 11, 'Không kết nối wifi', '250000-500000', '', '2025-11-20 09:14:46', 'lần', 75),
(160, 11, 'Màn hình không hiển thị', '300000-700000', '', '2025-11-20 09:14:46', 'lần', 90),
(161, 11, 'Thay motor quạt', '500000-1200000', '', '2025-11-20 09:14:46', 'cái', 150),
(162, 11, 'Đèn UV không sáng', '250000-500000', '', '2025-11-20 09:14:46', 'lần', 75),
(163, 11, 'Vệ sinh máy lọc khí', '150000-300000', '', '2025-11-20 09:14:46', 'lần', 60),
(164, 11, 'Thay ion âm', '400000-800000', '', '2025-11-20 09:14:46', 'cái', 90),
(165, 11, 'Máy báo lỗi đầy màng lọc', '150000-300000', '', '2025-11-20 09:14:46', 'lần', 45);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banggiasuachua`
--

CREATE TABLE `banggiasuachua` (
  `maGia` int(11) NOT NULL,
  `maMau` int(11) NOT NULL,
  `tenLoi` varchar(150) NOT NULL,
  `moTa` varchar(255) DEFAULT NULL,
  `gia` int(11) NOT NULL,
  `thoiGianSua` int(11) NOT NULL DEFAULT 60
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `banggiasuachua`
--

INSERT INTO `banggiasuachua` (`maGia`, `maMau`, `tenLoi`, `moTa`, `gia`, `thoiGianSua`) VALUES
(1, 1, 'Không lạnh', 'Máy chạy nhưng không tỏa hơi lạnh', 350000, 120),
(2, 1, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 250000, 90),
(3, 1, 'Kêu to', 'Máy phát ra tiếng ồn lớn khi hoạt động', 300000, 60),
(4, 1, 'Không khởi động', 'Máy không phản ứng khi bật', 400000, 75),
(5, 1, 'Mất điện đột ngột', 'Máy tự tắt khi đang hoạt động', 320000, 75),
(6, 1, 'Remote không hoạt động', 'Điều khiển từ xa không điều khiển được máy', 150000, 45),
(7, 1, 'Mùi hôi', 'Máy tỏa ra mùi khó chịu khi hoạt động', 280000, 45),
(8, 1, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng, đá', 320000, 90),
(9, 1, 'Quạt không quay', 'Quạt dàn lạnh không hoạt động', 270000, 60),
(10, 1, 'Rò rỉ gas', 'Máy bị rò rỉ gas lạnh', 450000, 75),
(11, 2, 'Không lạnh', 'Máy chạy nhưng không tỏa hơi lạnh', 380000, 120),
(12, 2, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 280000, 90),
(13, 2, 'Kêu to', 'Máy phát ra tiếng ồn lớn khi hoạt động', 320000, 60),
(14, 2, 'Lỗi board inverter', 'Board mạch inverter bị lỗi', 550000, 75),
(15, 2, 'Mất điện đột ngột', 'Máy tự tắt khi đang hoạt động', 350000, 75),
(16, 2, 'Remote không hoạt động', 'Điều khiển từ xa không điều khiển được máy', 180000, 45),
(17, 2, 'Mùi hôi', 'Máy tỏa ra mùi khó chịu khi hoạt động', 300000, 45),
(18, 2, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng, đá', 350000, 90),
(19, 2, 'Cảm biến nhiệt lỗi', 'Cảm biến nhiệt độ hoạt động không chính xác', 420000, 75),
(20, 2, 'Rò rỉ gas', 'Máy bị rò rỉ gas lạnh', 480000, 75),
(21, 3, 'Không lạnh', 'Hệ thống không làm lạnh', 650000, 120),
(22, 3, 'Lỗi board hệ thống', 'Board điều khiển hệ thống VRV lỗi', 850000, 75),
(23, 3, 'Rò rỉ gas hệ thống', 'Rò rỉ gas trên đường ống dẫn', 720000, 75),
(24, 3, 'Mất kết nối dàn lạnh', 'Dàn lạnh không kết nối với dàn nóng', 580000, 75),
(25, 3, 'Quạt dàn nóng không chạy', 'Quạt dàn nóng ngừng hoạt động', 450000, 60),
(26, 3, 'Áp suất gas thấp', 'Hệ thống báo lỗi áp suất gas', 680000, 75),
(27, 3, 'Van tiết lưu hỏng', 'Van tiết lưu hệ thống bị kẹt', 520000, 75),
(28, 3, 'Cảm biến nhiệt lỗi', 'Cảm biến nhiệt hệ thống hoạt động sai', 480000, 75),
(29, 3, 'Máy nén kêu to', 'Máy nén phát ra tiếng ồn bất thường', 750000, 60),
(30, 3, 'Bơm nước ngưng hỏng', 'Bơm nước ngưng không hoạt động', 380000, 75),
(31, 4, 'Không lạnh', 'Dàn cassette không tỏa lạnh', 480000, 120),
(32, 4, 'Chảy nước trần', 'Nước rò rỉ từ dàn cassette xuống trần', 350000, 90),
(33, 4, 'Quạt gió không chạy', 'Quạt dàn cassette không hoạt động', 420000, 60),
(34, 4, 'Lỗi board điều khiển', 'Board mạch điều khiển dàn cassette lỗi', 620000, 75),
(35, 4, 'Cánh đảo gió kẹt', 'Cánh đảo gió bị kẹt không xoay', 280000, 75),
(36, 4, 'Rò rỉ gas', 'Đường gas bị rò rỉ', 550000, 75),
(37, 4, 'Bơm nước ngưng hỏng', 'Bơm nước ngưng không làm việc', 320000, 75),
(38, 4, 'Cảm biến nhiệt hỏng', 'Cảm biến nhiệt độ dàn lạnh lỗi', 450000, 75),
(39, 4, 'Động cơ cánh gió cháy', 'Động cơ điều khiển cánh gió bị cháy', 380000, 75),
(40, 4, 'Ống dẫn nước bị tắc', 'Ống dẫn nước ngưng bị nghẹt', 250000, 75),
(41, 5, 'Không lạnh', 'Máy chạy nhưng không làm lạnh', 400000, 120),
(42, 5, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 300000, 90),
(43, 5, 'Kêu to bất thường', 'Máy phát ra tiếng ồn lớn', 350000, 60),
(44, 5, 'Không khởi động', 'Máy không phản ứng khi bật công tắc', 450000, 75),
(45, 5, 'Tự động tắt', 'Máy tự ngắt sau thời gian ngắn', 380000, 75),
(46, 5, 'Remote hỏng', 'Điều khiển từ xa không hoạt động', 200000, 45),
(47, 5, 'Mùi hôi khó chịu', 'Không khí từ máy có mùi hôi', 320000, 45),
(48, 5, 'Đóng tuyết dàn lạnh', 'Dàn lạnh bị đóng băng nhiều', 350000, 75),
(49, 5, 'Quạt gió không quay', 'Quạt dàn lạnh ngừng hoạt động', 300000, 60),
(50, 5, 'Rò rỉ môi chất lạnh', 'Gas lạnh bị rò rỉ từ hệ thống', 500000, 75),
(51, 6, 'Không lạnh', 'Máy chạy nhưng không tỏa lạnh', 320000, 120),
(52, 6, 'Chảy nước trong nhà', 'Nước rỉ ra từ dàn lạnh', 220000, 90),
(53, 6, 'Máy nén kêu to', 'Máy nén phát ra tiếng ồn lớn', 380000, 60),
(54, 6, 'Không khởi động được', 'Máy không phản ứng khi bật', 350000, 75),
(55, 6, 'Tự động ngắt', 'Máy tự tắt sau vài phút hoạt động', 300000, 75),
(56, 6, 'Remote không dùng được', 'Điều khiển từ xa vô hiệu', 120000, 45),
(57, 6, 'Mùi ẩm mốc', 'Không khí có mùi ẩm mốc', 250000, 75),
(58, 6, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng hoàn toàn', 280000, 90),
(59, 6, 'Quạt dàn lạnh hỏng', 'Quạt dàn lạnh không quay', 260000, 60),
(60, 6, 'Rò gas lạnh', 'Hệ thống bị rò rỉ gas', 420000, 120),
(61, 7, 'Không làm lạnh', 'Máy chạy nhưng không lạnh', 380000, 75),
(62, 7, 'Nước rò rỉ', 'Nước chảy ra từ dàn lạnh', 280000, 75),
(63, 7, 'Tiếng ồn lớn', 'Máy hoạt động ồn ào bất thường', 320000, 75),
(64, 7, 'Lỗi board inverter', 'Board mạch inverter bị hỏng', 520000, 75),
(65, 7, 'Tự động tắt nguồn', 'Máy tự ngắt đột ngột', 350000, 75),
(66, 7, 'Điều khiển hỏng', 'Remote không điều khiển được máy', 150000, 75),
(67, 7, 'Mùi hôi', 'Không khí thổi ra có mùi khó chịu', 300000, 45),
(68, 7, 'Dàn lạnh đóng băng', 'Dàn lạnh bị đóng băng nhiều', 320000, 90),
(69, 7, 'Cảm biến lỗi', 'Cảm biến nhiệt độ hoạt động sai', 400000, 75),
(70, 7, 'Rò rỉ môi chất', 'Gas lạnh bị rò rỉ', 450000, 75),
(71, 8, 'Không lạnh', 'Máy hoạt động nhưng không mát', 300000, 120),
(72, 8, 'Chảy nước', 'Nước nhỏ giọt trong phòng', 200000, 90),
(73, 8, 'Ồn ào khi chạy', 'Máy phát ra tiếng ồn khó chịu', 280000, 75),
(74, 8, 'Không lên nguồn', 'Máy không khởi động được', 320000, 75),
(75, 8, 'Tự ngắt', 'Máy tự động tắt khi đang chạy', 250000, 75),
(76, 8, 'Remote không hoạt động', 'Điều khiển từ xa vô hiệu', 100000, 45),
(77, 8, 'Mùi ẩm mốc', 'Không khí có mùi mốc', 220000, 75),
(78, 8, 'Đóng tuyết', 'Dàn lạnh bị đóng tuyết trắng', 260000, 75),
(79, 8, 'Quạt gió hỏng', 'Quạt dàn lạnh không chạy', 240000, 60),
(80, 8, 'Rò gas', 'Hệ thống gas bị rò rỉ', 380000, 120),
(81, 9, 'Không lạnh', 'Máy chạy nhưng không tỏa lạnh', 420000, 120),
(82, 9, 'Rỉ nước', 'Nước chảy ra từ dàn lạnh', 320000, 75),
(83, 9, 'Kêu to bất thường', 'Máy phát ra âm thanh lớn', 380000, 60),
(84, 9, 'Lỗi mạch inverter', 'Board mạch inverter cao cấp lỗi', 580000, 75),
(85, 9, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 400000, 75),
(86, 9, 'Remote cao cấp hỏng', 'Điều khiển từ xa đặc biệt không dùng được', 180000, 45),
(87, 9, 'Mùi khó chịu', 'Không khí có mùi lạ', 350000, 75),
(88, 9, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng cục bộ', 380000, 90),
(89, 9, 'Cảm biến cao cấp lỗi', 'Cảm biến nhiệt độ chính xác cao hỏng', 450000, 75),
(90, 9, 'Rò rỉ gas', 'Hệ thống gas bị rò rỉ', 480000, 75),
(91, 10, 'Không lạnh', 'Máy hoạt động nhưng không mát', 350000, 120),
(92, 10, 'Chảy nước trong nhà', 'Nước rỉ ra trong phòng', 250000, 90),
(93, 10, 'Tiếng ồn lớn', 'Máy kêu to khi hoạt động', 300000, 75),
(94, 10, 'Không khởi động', 'Máy không phản ứng khi bật', 380000, 75),
(95, 10, 'Tự động tắt', 'Máy tự ngắt sau thời gian ngắn', 320000, 75),
(96, 10, 'Remote không dùng được', 'Điều khiển từ xa không hoạt động', 130000, 45),
(97, 10, 'Mùi hôi', 'Không khí thổi ra có mùi', 280000, 45),
(98, 10, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng', 300000, 90),
(99, 10, 'Quạt không quay', 'Quạt dàn lạnh ngừng hoạt động', 270000, 60),
(100, 10, 'Rò gas lạnh', 'Hệ thống bị rò rỉ gas', 430000, 120),
(101, 11, 'Không lạnh', 'Máy chạy nhưng không tỏa lạnh', 360000, 120),
(102, 11, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 260000, 90),
(103, 11, 'Kêu to', 'Máy phát ra tiếng ồn lớn', 310000, 60),
(104, 11, 'Không khởi động', 'Máy không phản ứng khi bật', 410000, 75),
(105, 11, 'Mất điện đột ngột', 'Máy tự tắt khi đang hoạt động', 330000, 75),
(106, 11, 'Remote không hoạt động', 'Điều khiển từ xa không điều khiển được', 160000, 45),
(107, 11, 'Mùi hôi', 'Máy tỏa ra mùi khó chịu', 290000, 45),
(108, 11, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng, đá', 330000, 90),
(109, 11, 'Quạt không quay', 'Quạt dàn lạnh không hoạt động', 280000, 60),
(110, 11, 'Rò rỉ gas', 'Máy bị rò rỉ gas lạnh', 460000, 75),
(111, 12, 'Không lạnh', 'Máy chạy nhưng không tỏa hơi lạnh', 390000, 120),
(112, 12, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 290000, 90),
(113, 12, 'Kêu to', 'Máy phát ra tiếng ồn lớn khi hoạt động', 340000, 60),
(114, 12, 'Lỗi hệ thống kép', 'Hệ thống làm lạnh kép bị lỗi', 580000, 75),
(115, 12, 'Mất điện đột ngột', 'Máy tự tắt khi đang hoạt động', 360000, 75),
(116, 12, 'Remote không hoạt động', 'Điều khiển từ xa không điều khiển được máy', 190000, 45),
(117, 12, 'Mùi hôi', 'Máy tỏa ra mùi khó chịu khi hoạt động', 320000, 45),
(118, 12, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng, đá', 360000, 90),
(119, 12, 'Cảm biến kép lỗi', 'Hệ thống cảm biến kép hoạt động sai', 440000, 75),
(120, 12, 'Rò rỉ gas', 'Máy bị rò rỉ gas lạnh', 490000, 75),
(121, 13, 'Không lạnh', 'Máy trang trí chạy không lạnh', 420000, 120),
(122, 13, 'Chảy nước', 'Nước rỉ ra từ máy trang trí', 320000, 90),
(123, 13, 'Màn hình lỗi', 'Màn hình hiển thị nghệ thuật bị lỗi', 350000, 75),
(124, 13, 'Không khởi động', 'Máy không phản ứng khi bật', 450000, 75),
(125, 13, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 380000, 75),
(126, 13, 'Remote nghệ thuật hỏng', 'Điều khiển đặc biệt không hoạt động', 220000, 45),
(127, 13, 'Mùi hôi', 'Máy tỏa ra mùi khó chịu', 340000, 45),
(128, 13, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng', 370000, 90),
(129, 13, 'Quạt trang trí không quay', 'Quạt dàn lạnh nghệ thuật hỏng', 320000, 60),
(130, 13, 'Rò rỉ gas', 'Máy bị rò rỉ gas lạnh', 500000, 75),
(131, 14, 'Không lạnh', 'Hệ thống multi V không làm lạnh', 680000, 120),
(132, 14, 'Lỗi board hệ thống', 'Board điều khiển hệ thống multi V lỗi', 880000, 75),
(133, 14, 'Rò rỉ gas hệ thống', 'Rò rỉ gas trên đường ống dẫn multi', 750000, 75),
(134, 14, 'Mất kết nối dàn lạnh', 'Dàn lạnh không kết nối với hệ thống', 620000, 75),
(135, 14, 'Quạt dàn nóng không chạy', 'Quạt dàn nóng ngừng hoạt động', 480000, 60),
(136, 14, 'Áp suất gas thấp', 'Hệ thống báo lỗi áp suất gas multi', 720000, 75),
(137, 14, 'Van tiết lưu hỏng', 'Van tiết lưu hệ thống multi bị kẹt', 560000, 75),
(138, 14, 'Cảm biến nhiệt lỗi', 'Cảm biến nhiệt hệ thống hoạt động sai', 520000, 75),
(139, 14, 'Máy nén kêu to', 'Máy nén phát ra tiếng ồn bất thường', 780000, 60),
(140, 14, 'Bơm nước ngưng hỏng', 'Bơm nước ngưng hệ thống không hoạt động', 420000, 75),
(141, 15, 'Không lạnh', 'Máy chạy nhưng không làm lạnh', 370000, 120),
(142, 15, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 270000, 90),
(143, 15, 'Kêu to bất thường', 'Máy phát ra tiếng ồn lớn', 320000, 60),
(144, 15, 'Không khởi động', 'Máy không phản ứng khi bật công tắc', 420000, 75),
(145, 15, 'Tự động tắt', 'Máy tự ngắt sau thời gian ngắn', 350000, 75),
(146, 15, 'Remote hỏng', 'Điều khiển từ xa không hoạt động', 170000, 45),
(147, 15, 'Mùi hôi khó chịu', 'Không khí từ máy có mùi hôi', 300000, 45),
(148, 15, 'Đóng tuyết dàn lạnh', 'Dàn lạnh bị đóng băng nhiều', 340000, 75),
(149, 15, 'Quạt gió không quay', 'Quạt dàn lạnh ngừng hoạt động', 290000, 60),
(150, 15, 'Rò rỉ môi chất lạnh', 'Gas lạnh bị rò rỉ từ hệ thống', 470000, 75),
(151, 16, 'Không lạnh', 'Công nghệ Wind-Free không làm lạnh', 400000, 120),
(152, 16, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh Wind-Free', 300000, 90),
(153, 16, 'Lỗi công nghệ Wind-Free', 'Hệ thống gió không gió bị lỗi', 450000, 75),
(154, 16, 'Không khởi động', 'Máy không phản ứng khi bật', 430000, 75),
(155, 16, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 370000, 75),
(156, 16, 'Remote đặc biệt hỏng', 'Điều khiển công nghệ Wind-Free không hoạt động', 200000, 45),
(157, 16, 'Mùi hôi', 'Không khí có mùi khó chịu', 330000, 45),
(158, 16, 'Đóng băng dàn lạnh', 'Dàn lạnh Wind-Free bị đóng băng', 360000, 90),
(159, 16, 'Quạt Wind-Free hỏng', 'Hệ thống quạt đặc biệt không hoạt động', 340000, 60),
(160, 16, 'Rò rỉ gas', 'Hệ thống gas bị rò rỉ', 480000, 75),
(161, 17, 'Không lạnh', 'Máy chạy nhưng không tỏa lạnh', 350000, 120),
(162, 17, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 250000, 90),
(163, 17, 'Kêu to', 'Máy phát ra tiếng ồn lớn', 300000, 60),
(164, 17, 'Không khởi động', 'Máy không phản ứng khi bật', 400000, 75),
(165, 17, 'Mất điện đột ngột', 'Máy tự tắt khi đang hoạt động', 320000, 75),
(166, 17, 'Remote không hoạt động', 'Điều khiển từ xa không điều khiển được', 150000, 45),
(167, 17, 'Mùi hôi', 'Máy tỏa ra mùi khó chịu', 280000, 45),
(168, 17, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng, đá', 320000, 90),
(169, 17, 'Quạt không quay', 'Quạt dàn lạnh không hoạt động', 270000, 60),
(170, 17, 'Rò rỉ gas', 'Máy bị rò rỉ gas lạnh', 450000, 75),
(171, 18, 'Không lạnh', 'Dàn cassette không tỏa lạnh', 500000, 120),
(172, 18, 'Chảy nước trần', 'Nước rò rỉ từ dàn cassette xuống trần', 370000, 90),
(173, 18, 'Quạt gió không chạy', 'Quạt dàn cassette không hoạt động', 440000, 60),
(174, 18, 'Lỗi board điều khiển', 'Board mạch điều khiển dàn cassette lỗi', 640000, 75),
(175, 18, 'Cánh đảo gió kẹt', 'Cánh đảo gió bị kẹt không xoay', 300000, 75),
(176, 18, 'Rò rỉ gas', 'Đường gas bị rò rỉ', 570000, 75),
(177, 18, 'Bơm nước ngưng hỏng', 'Bơm nước ngưng không làm việc', 340000, 75),
(178, 18, 'Cảm biến nhiệt hỏng', 'Cảm biến nhiệt độ dàn lạnh lỗi', 470000, 75),
(179, 18, 'Động cơ cánh gió cháy', 'Động cơ điều khiển cánh gió bị cháy', 400000, 75),
(180, 18, 'Ống dẫn nước bị tắc', 'Ống dẫn nước ngưng bị nghẹt', 270000, 75),
(181, 19, 'Không lạnh', 'Máy AR Series không làm lạnh', 380000, 120),
(182, 19, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh AR', 280000, 90),
(183, 19, 'Lỗi tính năng AR', 'Tính năng làm lạnh nhanh AR bị lỗi', 420000, 75),
(184, 19, 'Không khởi động', 'Máy không phản ứng khi bật', 410000, 75),
(185, 19, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 350000, 75),
(186, 19, 'Remote AR hỏng', 'Điều khiển đặc biệt AR không hoạt động', 190000, 45),
(187, 19, 'Mùi hôi', 'Không khí có mùi khó chịu', 320000, 45),
(188, 19, 'Đóng băng dàn lạnh', 'Dàn lạnh AR bị đóng băng', 340000, 90),
(189, 19, 'Quạt AR hỏng', 'Quạt dàn lạnh AR không hoạt động', 310000, 60),
(190, 19, 'Rò rỉ gas', 'Hệ thống gas AR bị rò rỉ', 460000, 75),
(191, 20, 'Không lạnh', 'Máy chạy nhưng không làm lạnh', 360000, 120),
(192, 20, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 260000, 90),
(193, 20, 'Kêu to bất thường', 'Máy phát ra tiếng ồn lớn', 310000, 60),
(194, 20, 'Không khởi động', 'Máy không phản ứng khi bật công tắc', 390000, 75),
(195, 20, 'Tự động tắt', 'Máy tự ngắt sau thời gian ngắn', 330000, 75),
(196, 20, 'Remote hỏng', 'Điều khiển từ xa không hoạt động', 160000, 45),
(197, 20, 'Mùi hôi khó chịu', 'Không khí từ máy có mùi hôi', 290000, 45),
(198, 20, 'Đóng tuyết dàn lạnh', 'Dàn lạnh bị đóng băng nhiều', 320000, 75),
(199, 20, 'Quạt gió không quay', 'Quạt dàn lạnh ngừng hoạt động', 280000, 60),
(200, 20, 'Rò rỉ môi chất lạnh', 'Gas lạnh bị rò rỉ từ hệ thống', 440000, 75),
(201, 21, 'Không lạnh', 'Máy inverter chạy không lạnh', 330000, 120),
(202, 21, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh', 230000, 90),
(203, 21, 'Kêu to', 'Máy phát ra tiếng ồn lớn', 280000, 60),
(204, 21, 'Lỗi board inverter', 'Board mạch inverter Casper bị lỗi', 480000, 75),
(205, 21, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 300000, 75),
(206, 21, 'Remote không hoạt động', 'Điều khiển từ xa không dùng được', 140000, 45),
(207, 21, 'Mùi hôi', 'Không khí có mùi khó chịu', 260000, 45),
(208, 21, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng', 290000, 90),
(209, 21, 'Cảm biến lỗi', 'Cảm biến nhiệt độ hoạt động sai', 370000, 75),
(210, 21, 'Rò rỉ gas', 'Hệ thống gas bị rò rỉ', 420000, 75),
(211, 22, 'Không lạnh', 'Máy chạy nhưng không mát', 280000, 120),
(212, 22, 'Chảy nước', 'Nước nhỏ giọt trong phòng', 180000, 90),
(213, 22, 'Ồn ào khi chạy', 'Máy phát ra tiếng ồn khó chịu', 230000, 75),
(214, 22, 'Không lên nguồn', 'Máy không khởi động được', 270000, 75),
(215, 22, 'Tự ngắt', 'Máy tự động tắt khi đang chạy', 220000, 75),
(216, 22, 'Remote không hoạt động', 'Điều khiển từ xa vô hiệu', 90000, 45),
(217, 22, 'Mùi ẩm mốc', 'Không khí có mùi mốc', 200000, 75),
(218, 22, 'Đóng tuyết', 'Dàn lạnh bị đóng tuyết trắng', 230000, 75),
(219, 22, 'Quạt gió hỏng', 'Quạt dàn lạnh không chạy', 210000, 60),
(220, 22, 'Rò gas', 'Hệ thống gas bị rò rỉ', 350000, 120),
(221, 23, 'Không lạnh', 'Máy Pro Inverter chạy không lạnh', 360000, 120),
(222, 23, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh', 260000, 90),
(223, 23, 'Kêu to', 'Máy phát ra tiếng ồn lớn', 310000, 60),
(224, 23, 'Lỗi board Pro Inverter', 'Board mạch inverter cao cấp bị lỗi', 520000, 75),
(225, 23, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 340000, 75),
(226, 23, 'Remote Pro hỏng', 'Điều khiển cao cấp không hoạt động', 170000, 45),
(227, 23, 'Mùi hôi', 'Không khí có mùi khó chịu', 290000, 45),
(228, 23, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng', 320000, 90),
(229, 23, 'Cảm biến Pro lỗi', 'Cảm biến nhiệt độ cao cấp hỏng', 400000, 75),
(230, 23, 'Rò rỉ gas', 'Hệ thống gas bị rò rỉ', 450000, 75),
(231, 24, 'Không lạnh', 'Máy Deluxe chạy không lạnh', 340000, 120),
(232, 24, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh Deluxe', 240000, 90),
(233, 24, 'Kêu to', 'Máy Deluxe phát ra tiếng ồn', 290000, 60),
(234, 24, 'Không khởi động', 'Máy Deluxe không phản ứng khi bật', 380000, 75),
(235, 24, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 320000, 75),
(236, 24, 'Remote Deluxe hỏng', 'Điều khiển sang trọng không hoạt động', 160000, 45),
(237, 24, 'Mùi hôi', 'Không khí có mùi khó chịu', 270000, 45),
(238, 24, 'Đóng băng dàn lạnh', 'Dàn lạnh Deluxe bị đóng băng', 300000, 90),
(239, 24, 'Quạt Deluxe hỏng', 'Quạt dàn lạnh Deluxe không hoạt động', 260000, 60),
(240, 24, 'Rò rỉ gas', 'Hệ thống gas Deluxe bị rò rỉ', 430000, 75),
(241, 25, 'Không lạnh', 'Máy chạy nhưng không làm lạnh', 300000, 120),
(242, 25, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 200000, 90),
(243, 25, 'Kêu to bất thường', 'Máy phát ra tiếng ồn lớn', 250000, 60),
(244, 25, 'Không khởi động', 'Máy không phản ứng khi bật công tắc', 350000, 75),
(245, 25, 'Tự động tắt', 'Máy tự ngắt sau thời gian ngắn', 280000, 75),
(246, 25, 'Remote hỏng', 'Điều khiển từ xa không hoạt động', 120000, 45),
(247, 25, 'Mùi hôi khó chịu', 'Không khí từ máy có mùi hôi', 230000, 45),
(248, 25, 'Đóng tuyết dàn lạnh', 'Dàn lạnh bị đóng băng nhiều', 260000, 75),
(249, 25, 'Quạt gió không quay', 'Quạt dàn lạnh ngừng hoạt động', 220000, 60),
(250, 25, 'Rò rỉ môi chất lạnh', 'Gas lạnh bị rò rỉ từ hệ thống', 400000, 75),
(251, 26, 'Không lạnh', 'Máy inverter Midea không lạnh', 320000, 120),
(252, 26, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh', 220000, 90),
(253, 26, 'Kêu to', 'Máy phát ra tiếng ồn lớn', 270000, 60),
(254, 26, 'Lỗi board inverter', 'Board mạch inverter Midea bị lỗi', 470000, 75),
(255, 26, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 290000, 75),
(256, 26, 'Remote không hoạt động', 'Điều khiển từ xa không dùng được', 130000, 45),
(257, 26, 'Mùi hôi', 'Không khí có mùi khó chịu', 250000, 45),
(258, 26, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng', 280000, 90),
(259, 26, 'Cảm biến lỗi', 'Cảm biến nhiệt độ hoạt động sai', 360000, 75),
(260, 26, 'Rò rỉ gas', 'Hệ thống gas bị rò rỉ', 410000, 75),
(261, 27, 'Không lạnh', 'Máy chạy nhưng không mát', 270000, 120),
(262, 27, 'Chảy nước', 'Nước nhỏ giọt trong phòng', 170000, 90),
(263, 27, 'Ồn ào khi chạy', 'Máy phát ra tiếng ồn khó chịu', 220000, 75),
(264, 27, 'Không lên nguồn', 'Máy không khởi động được', 260000, 75),
(265, 27, 'Tự ngắt', 'Máy tự động tắt khi đang chạy', 210000, 75),
(266, 27, 'Remote không hoạt động', 'Điều khiển từ xa vô hiệu', 80000, 45),
(267, 27, 'Mùi ẩm mốc', 'Không khí có mùi mốc', 190000, 75),
(268, 27, 'Đóng tuyết', 'Dàn lạnh bị đóng tuyết trắng', 220000, 75),
(269, 27, 'Quạt gió hỏng', 'Quạt dàn lạnh không chạy', 200000, 60),
(270, 27, 'Rò gas', 'Hệ thống gas bị rò rỉ', 340000, 120),
(271, 28, 'Không lạnh', 'Máy Titan chạy không lạnh', 350000, 120),
(272, 28, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh Titan', 250000, 90),
(273, 28, 'Kêu to', 'Máy Titan phát ra tiếng ồn', 300000, 60),
(274, 28, 'Lỗi board Titan', 'Board mạch Titan series bị lỗi', 500000, 75),
(275, 28, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 330000, 75),
(276, 28, 'Remote Titan hỏng', 'Điều khiển Titan không hoạt động', 150000, 45),
(277, 28, 'Mùi hôi', 'Không khí có mùi khó chịu', 280000, 45),
(278, 28, 'Đóng băng dàn lạnh', 'Dàn lạnh Titan bị đóng băng', 310000, 90),
(279, 28, 'Cảm biến Titan lỗi', 'Cảm biến nhiệt độ Titan hỏng', 390000, 75),
(280, 28, 'Rò rỉ gas', 'Hệ thống gas Titan bị rò rỉ', 440000, 75),
(281, 29, 'Không lạnh', 'Máy Ultra chạy không lạnh', 370000, 120),
(282, 29, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh Ultra', 270000, 90),
(283, 29, 'Kêu to', 'Máy Ultra phát ra tiếng ồn', 320000, 60),
(284, 29, 'Lỗi board Ultra', 'Board mạch Ultra series bị lỗi', 520000, 75),
(285, 29, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 350000, 75),
(286, 29, 'Remote Ultra hỏng', 'Điều khiển Ultra không hoạt động', 170000, 45),
(287, 29, 'Mùi hôi', 'Không khí có mùi khó chịu', 300000, 45),
(288, 29, 'Đóng băng dàn lạnh', 'Dàn lạnh Ultra bị đóng băng', 330000, 90),
(289, 29, 'Cảm biến Ultra lỗi', 'Cảm biến nhiệt độ Ultra hỏng', 410000, 75),
(290, 29, 'Rò rỉ gas', 'Hệ thống gas Ultra bị rò rỉ', 460000, 75),
(291, 30, 'Không lạnh', 'Máy chạy nhưng không làm lạnh', 310000, 120),
(292, 30, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 210000, 90),
(293, 30, 'Kêu to bất thường', 'Máy phát ra tiếng ồn lớn', 260000, 60),
(294, 30, 'Không khởi động', 'Máy không phản ứng khi bật công tắc', 360000, 75),
(295, 30, 'Tự động tắt', 'Máy tự ngắt sau thời gian ngắn', 290000, 75),
(296, 30, 'Remote hỏng', 'Điều khiển từ xa không hoạt động', 110000, 45),
(297, 30, 'Mùi hôi khó chịu', 'Không khí từ máy có mùi hôi', 240000, 45),
(298, 30, 'Đóng tuyết dàn lạnh', 'Dàn lạnh bị đóng băng nhiều', 270000, 75),
(299, 30, 'Quạt gió không quay', 'Quạt dàn lạnh ngừng hoạt động', 230000, 60),
(300, 30, 'Rò rỉ môi chất lạnh', 'Gas lạnh bị rò rỉ từ hệ thống', 420000, 75),
(301, 31, 'Không lạnh', 'Máy inverter Toshiba không lạnh', 340000, 120),
(302, 31, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh', 240000, 90),
(303, 31, 'Kêu to', 'Máy phát ra tiếng ồn lớn', 290000, 60),
(304, 31, 'Lỗi board inverter', 'Board mạch inverter Toshiba bị lỗi', 490000, 75),
(305, 31, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 310000, 75),
(306, 31, 'Remote không hoạt động', 'Điều khiển từ xa không dùng được', 140000, 45),
(307, 31, 'Mùi hôi', 'Không khí có mùi khó chịu', 260000, 45),
(308, 31, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng', 300000, 90),
(309, 31, 'Cảm biến lỗi', 'Cảm biến nhiệt độ hoạt động sai', 380000, 75),
(310, 31, 'Rò rỉ gas', 'Hệ thống gas bị rò rỉ', 430000, 75),
(311, 32, 'Không lạnh', 'Máy chạy nhưng không mát', 290000, 120),
(312, 32, 'Chảy nước', 'Nước nhỏ giọt trong phòng', 190000, 90),
(313, 32, 'Ồn ào khi chạy', 'Máy phát ra tiếng ồn khó chịu', 240000, 75),
(314, 32, 'Không lên nguồn', 'Máy không khởi động được', 280000, 75),
(315, 32, 'Tự ngắt', 'Máy tự động tắt khi đang chạy', 230000, 75),
(316, 32, 'Remote không hoạt động', 'Điều khiển từ xa vô hiệu', 90000, 45),
(317, 32, 'Mùi ẩm mốc', 'Không khí có mùi mốc', 210000, 75),
(318, 32, 'Đóng tuyết', 'Dàn lạnh bị đóng tuyết trắng', 240000, 75),
(319, 32, 'Quạt gió hỏng', 'Quạt dàn lạnh không chạy', 220000, 60),
(320, 32, 'Rò gas', 'Hệ thống gas bị rò rỉ', 360000, 120),
(321, 33, 'Không lạnh', 'Máy Daiseikai chạy không lạnh', 360000, 120),
(322, 33, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh Daiseikai', 260000, 90),
(323, 33, 'Kêu to', 'Máy Daiseikai phát ra tiếng ồn', 310000, 60),
(324, 33, 'Lỗi board Daiseikai', 'Board mạch Daiseikai series bị lỗi', 510000, 75),
(325, 33, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 340000, 75),
(326, 33, 'Remote Daiseikai hỏng', 'Điều khiển Daiseikai không hoạt động', 160000, 45),
(327, 33, 'Mùi hôi', 'Không khí có mùi khó chịu', 290000, 45),
(328, 33, 'Đóng băng dàn lạnh', 'Dàn lạnh Daiseikai bị đóng băng', 320000, 90),
(329, 33, 'Cảm biến Daiseikai lỗi', 'Cảm biến nhiệt độ Daiseikai hỏng', 400000, 75),
(330, 33, 'Rò rỉ gas', 'Hệ thống gas Daiseikai bị rò rỉ', 450000, 75),
(331, 34, 'Không lạnh', 'Máy RAS chạy không lạnh', 380000, 120),
(332, 34, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh RAS', 280000, 90),
(333, 34, 'Kêu to', 'Máy RAS phát ra tiếng ồn', 330000, 60),
(334, 34, 'Lỗi board RAS', 'Board mạch RAS series bị lỗi', 530000, 75),
(335, 34, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 360000, 75),
(336, 34, 'Remote RAS hỏng', 'Điều khiển RAS không hoạt động', 180000, 45),
(337, 34, 'Mùi hôi', 'Không khí có mùi khó chịu', 310000, 45),
(338, 34, 'Đóng băng dàn lạnh', 'Dàn lạnh RAS bị đóng băng', 340000, 90),
(339, 34, 'Cảm biến RAS lỗi', 'Cảm biến nhiệt độ RAS hỏng', 420000, 75),
(340, 34, 'Rò rỉ gas', 'Hệ thống gas RAS bị rò rỉ', 470000, 75),
(341, 35, 'Không lạnh', 'Máy chạy nhưng không làm lạnh', 320000, 120),
(342, 35, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 220000, 90),
(343, 35, 'Kêu to bất thường', 'Máy phát ra tiếng ồn lớn', 270000, 60),
(344, 35, 'Không khởi động', 'Máy không phản ứng khi bật công tắc', 370000, 75),
(345, 35, 'Tự động tắt', 'Máy tự ngắt sau thời gian ngắn', 300000, 75),
(346, 35, 'Remote hỏng', 'Điều khiển từ xa không hoạt động', 120000, 45),
(347, 35, 'Mùi hôi khó chịu', 'Không khí từ máy có mùi hôi', 250000, 45),
(348, 35, 'Đóng tuyết dàn lạnh', 'Dàn lạnh bị đóng băng nhiều', 280000, 75),
(349, 35, 'Quạt gió không quay', 'Quạt dàn lạnh ngừng hoạt động', 240000, 60),
(350, 35, 'Rò rỉ môi chất lạnh', 'Gas lạnh bị rò rỉ từ hệ thống', 420000, 75),
(351, 36, 'Không lạnh', 'Máy inverter Sharp không lạnh', 350000, 120),
(352, 36, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh', 250000, 90),
(353, 36, 'Kêu to', 'Máy phát ra tiếng ồn lớn', 300000, 60),
(354, 36, 'Lỗi board inverter', 'Board mạch inverter Sharp bị lỗi', 500000, 75),
(355, 36, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 320000, 75),
(356, 36, 'Remote không hoạt động', 'Điều khiển từ xa không dùng được', 150000, 45),
(357, 36, 'Mùi hôi', 'Không khí có mùi khó chịu', 270000, 45),
(358, 36, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng', 310000, 90),
(359, 36, 'Cảm biến lỗi', 'Cảm biến nhiệt độ hoạt động sai', 390000, 75),
(360, 36, 'Rò rỉ gas', 'Hệ thống gas bị rò rỉ', 440000, 75),
(361, 37, 'Không lạnh', 'Máy chạy nhưng không mát', 300000, 120),
(362, 37, 'Chảy nước', 'Nước nhỏ giọt trong phòng', 200000, 90),
(363, 37, 'Ồn ào khi chạy', 'Máy phát ra tiếng ồn khó chịu', 250000, 75),
(364, 37, 'Không lên nguồn', 'Máy không khởi động được', 290000, 75),
(365, 37, 'Tự ngắt', 'Máy tự động tắt khi đang chạy', 240000, 75),
(366, 37, 'Remote không hoạt động', 'Điều khiển từ xa vô hiệu', 100000, 45),
(367, 37, 'Mùi ẩm mốc', 'Không khí có mùi mốc', 220000, 75),
(368, 37, 'Đóng tuyết', 'Dàn lạnh bị đóng tuyết trắng', 250000, 75),
(369, 37, 'Quạt gió hỏng', 'Quạt dàn lạnh không chạy', 230000, 60),
(370, 37, 'Rò gas', 'Hệ thống gas bị rò rỉ', 370000, 120),
(371, 38, 'Không lạnh', 'Máy Plasmacluster chạy không lạnh', 370000, 120),
(372, 38, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh Plasmacluster', 270000, 90),
(373, 38, 'Kêu to', 'Máy Plasmacluster phát ra tiếng ồn', 320000, 60),
(374, 38, 'Lỗi công nghệ Plasmacluster', 'Hệ thống ion Plasmacluster bị lỗi', 450000, 75),
(375, 38, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 350000, 75),
(376, 38, 'Remote Plasmacluster hỏng', 'Điều khiển Plasmacluster không hoạt động', 170000, 45),
(377, 38, 'Mùi hôi', 'Không khí có mùi khó chịu', 300000, 45),
(378, 38, 'Đóng băng dàn lạnh', 'Dàn lạnh Plasmacluster bị đóng băng', 330000, 90),
(379, 38, 'Cảm biến Plasmacluster lỗi', 'Cảm biến chất lượng không khí hỏng', 410000, 75),
(380, 38, 'Rò rỉ gas', 'Hệ thống gas Plasmacluster bị rò rỉ', 460000, 75),
(381, 39, 'Không lạnh', 'Máy AH chạy không lạnh', 390000, 120),
(382, 39, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh AH', 290000, 90),
(383, 39, 'Kêu to', 'Máy AH phát ra tiếng ồn', 340000, 60),
(384, 39, 'Lỗi board AH', 'Board mạch AH series bị lỗi', 540000, 75),
(385, 39, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 370000, 75),
(386, 39, 'Remote AH hỏng', 'Điều khiển AH không hoạt động', 190000, 45),
(387, 39, 'Mùi hôi', 'Không khí có mùi khó chịu', 320000, 45),
(388, 39, 'Đóng băng dàn lạnh', 'Dàn lạnh AH bị đóng băng', 350000, 90),
(389, 39, 'Cảm biến AH lỗi', 'Cảm biến nhiệt độ AH hỏng', 430000, 75),
(390, 39, 'Rò rỉ gas', 'Hệ thống gas AH bị rò rỉ', 480000, 75),
(391, 40, 'Không lạnh', 'Máy chạy nhưng không làm lạnh', 330000, 120),
(392, 40, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 230000, 90),
(393, 40, 'Kêu to bất thường', 'Máy phát ra tiếng ồn lớn', 280000, 60),
(394, 40, 'Không khởi động', 'Máy không phản ứng khi bật công tắc', 380000, 75),
(395, 40, 'Tự động tắt', 'Máy tự ngắt sau thời gian ngắn', 310000, 75),
(396, 40, 'Remote hỏng', 'Điều khiển từ xa không hoạt động', 130000, 45),
(397, 40, 'Mùi hôi khó chịu', 'Không khí từ máy có mùi hôi', 260000, 45),
(398, 40, 'Đóng tuyết dàn lạnh', 'Dàn lạnh bị đóng băng nhiều', 290000, 75),
(399, 40, 'Quạt gió không quay', 'Quạt dàn lạnh ngừng hoạt động', 250000, 60),
(400, 40, 'Rò rỉ môi chất lạnh', 'Gas lạnh bị rò rỉ từ hệ thống', 430000, 75),
(401, 41, 'Không lạnh', 'Máy inverter Aqua không lạnh', 320000, 120),
(402, 41, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh', 220000, 90),
(403, 41, 'Kêu to', 'Máy phát ra tiếng ồn lớn', 270000, 60),
(404, 41, 'Lỗi board inverter', 'Board mạch inverter Aqua bị lỗi', 470000, 75),
(405, 41, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 290000, 75),
(406, 41, 'Remote không hoạt động', 'Điều khiển từ xa không dùng được', 120000, 45),
(407, 41, 'Mùi hôi', 'Không khí có mùi khó chịu', 240000, 45),
(408, 41, 'Đóng băng dàn lạnh', 'Dàn lạnh bị đóng băng', 280000, 90),
(409, 41, 'Cảm biến lỗi', 'Cảm biến nhiệt độ hoạt động sai', 360000, 75),
(410, 41, 'Rò rỉ gas', 'Hệ thống gas bị rò rỉ', 410000, 75),
(411, 42, 'Không lạnh', 'Máy chạy nhưng không mát', 270000, 120),
(412, 42, 'Chảy nước', 'Nước nhỏ giọt trong phòng', 170000, 90),
(413, 42, 'Ồn ào khi chạy', 'Máy phát ra tiếng ồn khó chịu', 220000, 75),
(414, 42, 'Không lên nguồn', 'Máy không khởi động được', 260000, 75),
(415, 42, 'Tự ngắt', 'Máy tự động tắt khi đang chạy', 210000, 75),
(416, 42, 'Remote không hoạt động', 'Điều khiển từ xa vô hiệu', 80000, 45),
(417, 42, 'Mùi ẩm mốc', 'Không khí có mùi mốc', 190000, 75),
(418, 42, 'Đóng tuyết', 'Dàn lạnh bị đóng tuyết trắng', 220000, 75),
(419, 42, 'Quạt gió hỏng', 'Quạt dàn lạnh không chạy', 200000, 60),
(420, 42, 'Rò gas', 'Hệ thống gas bị rò rỉ', 340000, 120),
(421, 43, 'Không lạnh', 'Máy Deluxe Inverter Aqua không lạnh', 350000, 120),
(422, 43, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh Deluxe', 250000, 90),
(423, 43, 'Kêu to', 'Máy Deluxe phát ra tiếng ồn', 300000, 60),
(424, 43, 'Lỗi board Deluxe', 'Board mạch Deluxe inverter bị lỗi', 490000, 75),
(425, 43, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 330000, 75),
(426, 43, 'Remote Deluxe hỏng', 'Điều khiển Deluxe không hoạt động', 150000, 45),
(427, 43, 'Mùi hôi', 'Không khí có mùi khó chịu', 280000, 45),
(428, 43, 'Đóng băng dàn lạnh', 'Dàn lạnh Deluxe bị đóng băng', 310000, 90),
(429, 43, 'Cảm biến Deluxe lỗi', 'Cảm biến nhiệt độ Deluxe hỏng', 390000, 75),
(430, 43, 'Rò rỉ gas', 'Hệ thống gas Deluxe bị rò rỉ', 440000, 75),
(431, 44, 'Không lạnh', 'Máy AQUA series chạy không lạnh', 340000, 120),
(432, 44, 'Chảy nước', 'Nước rỉ ra từ dàn lạnh AQUA', 240000, 90),
(433, 44, 'Kêu to', 'Máy AQUA phát ra tiếng ồn', 290000, 60),
(434, 44, 'Lỗi board AQUA', 'Board mạch AQUA series bị lỗi', 480000, 75),
(435, 44, 'Tự động tắt', 'Máy tự ngắt khi đang hoạt động', 320000, 75),
(436, 44, 'Remote AQUA hỏng', 'Điều khiển AQUA không hoạt động', 140000, 45),
(437, 44, 'Mùi hôi', 'Không khí có mùi khó chịu', 270000, 45),
(438, 44, 'Đóng băng dàn lạnh', 'Dàn lạnh AQUA bị đóng băng', 300000, 90),
(439, 44, 'Cảm biến AQUA lỗi', 'Cảm biến nhiệt độ AQUA hỏng', 380000, 75),
(440, 44, 'Rò rỉ gas', 'Hệ thống gas AQUA bị rò rỉ', 430000, 75),
(441, 45, 'Không lạnh', 'Máy chạy nhưng không làm lạnh', 300000, 120),
(442, 45, 'Chảy nước', 'Nước nhỏ giọt từ dàn lạnh', 200000, 90),
(443, 45, 'Kêu to bất thường', 'Máy phát ra tiếng ồn lớn', 250000, 60),
(444, 45, 'Không khởi động', 'Máy không phản ứng khi bật công tắc', 350000, 75),
(445, 45, 'Tự động tắt', 'Máy tự ngắt sau thời gian ngắn', 280000, 75),
(446, 45, 'Remote hỏng', 'Điều khiển từ xa không hoạt động', 110000, 45),
(447, 45, 'Mùi hôi khó chịu', 'Không khí từ máy có mùi hôi', 230000, 45),
(448, 45, 'Đóng tuyết dàn lạnh', 'Dàn lạnh bị đóng băng nhiều', 260000, 75),
(449, 45, 'Quạt gió không quay', 'Quạt dàn lạnh ngừng hoạt động', 220000, 60),
(450, 45, 'Rò rỉ môi chất lạnh', 'Gas lạnh bị rò rỉ từ hệ thống', 400000, 75),
(451, 46, 'Không lên nguồn', 'Tivi không phản ứng khi bật nguồn', 450000, 60),
(452, 46, 'Màn hình đen', 'Có âm thanh nhưng không có hình ảnh', 600000, 90),
(453, 46, 'Sọc màn hình', 'Xuất hiện các sọc trên màn hình', 750000, 90),
(454, 46, 'Mất âm thanh', 'Có hình ảnh nhưng không có âm thanh', 350000, 45),
(455, 46, 'Lỗi kết nối wifi', 'Không thể kết nối internet không dây', 280000, 30),
(456, 46, 'Remote không hoạt động', 'Điều khiển từ xa không điều khiển được tivi', 120000, 20),
(457, 46, 'Ứ đọng hình ảnh', 'Hình ảnh bị delay, giật lag', 520000, 50),
(458, 46, 'Lỗi cổng HDMI', 'Cổng HDMI không nhận tín hiệu', 320000, 50),
(459, 46, 'Tự động tắt nguồn', 'Tivi tự tắt sau vài phút hoạt động', 480000, 50),
(460, 46, 'Màu sắc bất thường', 'Hình ảnh bị lệch màu, ám màu', 550000, 50),
(461, 47, 'Không lên nguồn', 'Tivi Crystal UHD không khởi động', 420000, 60),
(462, 47, 'Màn hình tối', 'Hình ảnh quá tối, không rõ nét', 580000, 90),
(463, 47, 'Điểm chết màn hình', 'Xuất hiện điểm đen trên màn hình', 700000, 90),
(464, 47, 'Âm thanh rè', 'Âm thanh bị rè, nhiễu sóng', 330000, 45),
(465, 47, 'Lỗi Smart TV', 'Hệ điều hành Smart TV bị lỗi', 400000, 50),
(466, 47, 'Remote cảm ứng hỏng', 'Điều khiển cảm ứng không hoạt động', 150000, 20),
(467, 47, 'Giật hình', 'Hình ảnh bị giật, nhấp nháy', 500000, 50),
(468, 47, 'Lỗi cổng USB', 'Cổng USB không nhận thiết bị', 300000, 50),
(469, 47, 'Nóng máy', 'Tivi nóng bất thường khi hoạt động', 450000, 50),
(470, 47, 'Mất kết nối Bluetooth', 'Không kết nối được thiết bị Bluetooth', 350000, 30),
(471, 48, 'Không lên nguồn', 'Tivi The Frame không khởi động', 480000, 60),
(472, 48, 'Lỗi chế độ Art Mode', 'Chế độ hiển thị nghệ thuật bị lỗi', 550000, 50),
(473, 48, 'Màn hình không sáng', 'Màn hình tối hoàn toàn', 650000, 90),
(474, 48, 'Âm thanh biến dạng', 'Âm thanh bị méo, biến dạng', 380000, 45),
(475, 48, 'Lỗi kết nối One Connect', 'Hộp kết nối One Connect bị lỗi', 520000, 30),
(476, 48, 'Remote thiết kế hỏng', 'Điều khiển thiết kế đặc biệt không hoạt động', 180000, 20),
(477, 48, 'Hình ảnh nhòe', 'Hình ảnh bị nhòe, không sắc nét', 580000, 50),
(478, 48, 'Lỗi cổng optical', 'Cổng optical audio không hoạt động', 340000, 50),
(479, 48, 'Tự động chuyển chế độ', 'Tivi tự động chuyển chế độ không kiểm soát', 470000, 50),
(480, 48, 'Mất kết nối smartthings', 'Không kết nối được với SmartThings', 420000, 30),
(481, 49, 'Không lên nguồn', 'Tivi OLED không khởi động', 520000, 60),
(482, 49, 'Burn-in màn hình', 'Hiện tượng lưu ảnh trên màn hình OLED', 850000, 90),
(483, 49, 'Màn hình không đồng đều', 'Độ sáng màn hình không đồng nhất', 720000, 90),
(484, 49, 'Âm thanh Dolby Atmos lỗi', 'Công nghệ âm thanh Dolby Atmos bị lỗi', 450000, 45),
(485, 49, 'Lỗi AI Upscaling', 'Công nghệ nâng cấp hình ảnh AI bị lỗi', 600000, 50),
(486, 49, 'Remote cao cấp hỏng', 'Điều khiển cao cấp không hoạt động', 220000, 20),
(487, 49, 'Hình ảnh bị ám màu', 'Màn hình bị ám màu xanh/đỏ/vàng', 680000, 50),
(488, 49, 'Lỗi cổng eARC', 'Cổng eARC không hoạt động', 380000, 50),
(489, 49, 'Tự động điều chỉnh độ sáng', 'Tivi tự điều chỉnh độ sáng không kiểm soát', 550000, 50),
(490, 49, 'Mất kết nối game mode', 'Chế độ game không hoạt động', 480000, 30),
(491, 50, 'Không lên nguồn', 'Tivi không phản ứng khi bật', 400000, 60),
(492, 50, 'Màn hình có vệt sáng', 'Xuất hiện vệt sáng trên màn hình', 550000, 90),
(493, 50, 'Mất tiếng', 'Có hình nhưng không có âm thanh', 320000, 50),
(494, 50, 'Lỗi kết nối mạng', 'Không kết nối được internet', 250000, 30),
(495, 50, 'Remote thường hỏng', 'Điều khiển từ xa thông thường không hoạt động', 100000, 20),
(496, 50, 'Hình ảnh giật lag', 'Hình ảnh bị giật, không mượt', 450000, 50),
(497, 50, 'Lỗi cổng AV', 'Cổng AV không nhận tín hiệu', 280000, 50),
(498, 50, 'Tự động reset', 'Tivi tự động khởi động lại', 420000, 50),
(499, 50, 'Nhiễu sóng hình ảnh', 'Hình ảnh bị nhiễu, hạt', 380000, 50),
(500, 50, 'Mất kết nối ứng dụng', 'Các ứng dụng không hoạt động', 350000, 30),
(501, 51, 'Không lên nguồn', 'Tivi OLED LG không khởi động', 500000, 60),
(502, 51, 'Lỗi pixel chết', 'Xuất hiện pixel chết trên màn hình', 780000, 50),
(503, 51, 'Màn hình ám vàng', 'Màn hình bị ám sắc vàng', 650000, 90),
(504, 51, 'Âm thanh AI không hoạt động', 'Công nghệ âm thanh AI bị lỗi', 420000, 45),
(505, 51, 'Lỗi webOS', 'Hệ điều hành webOS bị treo, lỗi', 480000, 50),
(506, 51, 'Remote Magic không hoạt động', 'Điều khiển Magic Remote hỏng', 200000, 20),
(507, 51, 'Hình ảnh bị bóng ma', 'Hiện tượng bóng ma trên màn hình OLED', 720000, 50),
(508, 51, 'Lỗi cổng HDMI 2.1', 'Cổng HDMI 2.1 không nhận tín hiệu', 350000, 50),
(509, 51, 'Tự động cập nhật lỗi', 'Quá trình cập nhật firmware bị lỗi', 520000, 50),
(510, 51, 'Mất kết nối ThinQ AI', 'Không kết nối được với ThinQ AI', 450000, 30),
(511, 52, 'Không lên nguồn', 'Tivi NanoCell không khởi động', 450000, 60),
(512, 52, 'Lỗi công nghệ NanoCell', 'Công nghệ Nano Color bị lỗi', 580000, 50),
(513, 52, 'Màn hình bị mờ', 'Hình ảnh không sắc nét', 520000, 90),
(514, 52, 'Âm thanh Virtual Surround lỗi', 'Công nghệ âm thanh vòm ảo bị lỗi', 380000, 45),
(515, 52, 'Lỗi kết nối Apple AirPlay', 'Không kết nối được với AirPlay', 320000, 30),
(516, 52, 'Remote tiêu chuẩn hỏng', 'Điều khiển tiêu chuẩn không hoạt động', 150000, 20),
(517, 52, 'Hình ảnh bị nhòe màu', 'Màu sắc bị nhòe, không chuẩn', 480000, 50),
(518, 52, 'Lỗi cổng Ethernet', 'Cổng mạng có dây không hoạt động', 280000, 50),
(519, 52, 'Tự động chuyển nguồn', 'Tivi tự động chuyển nguồn đầu vào', 400000, 50),
(520, 52, 'Mất kết nối HomeKit', 'Không kết nối được với Apple HomeKit', 370000, 30),
(521, 53, 'Không lên nguồn', 'Tivi UHD 4K không khởi động', 420000, 60),
(522, 53, 'Màn hình có đốm đen', 'Xuất hiện đốm đen nhỏ trên màn hình', 550000, 90),
(523, 53, 'Độ tương phản thấp', 'Hình ảnh thiếu độ tương phản', 480000, 50),
(524, 53, 'Âm thanh méo tiếng', 'Âm thanh bị méo ở âm lượng cao', 350000, 45),
(525, 53, 'Lỗi kết nối miracast', 'Không kết nối được screen mirroring', 300000, 30),
(526, 53, 'Remote cơ bản hỏng', 'Điều khiển cơ bản không hoạt động', 120000, 20),
(527, 53, 'Hình ảnh bị noise', 'Hình ảnh bị nhiễu hạt', 450000, 50),
(528, 53, 'Lỗi cổng component', 'Cổng component không nhận tín hiệu', 250000, 50),
(529, 53, 'Tự động giảm âm lượng', 'Âm lượng tự động giảm không kiểm soát', 380000, 50),
(530, 53, 'Mất kết nối Bluetooth', 'Không kết nối được thiết bị Bluetooth', 330000, 30),
(531, 54, 'Không lên nguồn', 'Tivi Smart TV không khởi động', 380000, 60),
(532, 54, 'Màn hình bị sọc ngang', 'Xuất hiện sọc ngang trên màn hình', 500000, 90),
(533, 54, 'Màu sắc nhạt', 'Hình ảnh bị nhạt màu', 420000, 50),
(534, 54, 'Âm thanh yếu', 'Âm thanh quá yếu dù âm lượng max', 320000, 45),
(535, 54, 'Lỗi ứng dụng LG Content Store', 'Không truy cập được LG Content Store', 280000, 50),
(536, 54, 'Remote đơn giản hỏng', 'Điều khiển đơn giản không hoạt động', 100000, 20),
(537, 54, 'Hình ảnh bị rung', 'Hình ảnh bị rung nhẹ', 400000, 50),
(538, 54, 'Lỗi cổng composite', 'Cổng composite không hoạt động', 220000, 50),
(539, 54, 'Tự động chuyển kênh', 'Tivi tự động chuyển kênh ngẫu nhiên', 350000, 50),
(540, 54, 'Mất kết nối wifi', 'Không kết nối được wifi', 300000, 30),
(541, 55, 'Không lên nguồn', 'Tivi không phản ứng khi bật', 350000, 60),
(542, 55, 'Màn hình có điểm sáng', 'Xuất hiện điểm sáng bất thường', 480000, 90),
(543, 55, 'Mất âm thanh một kênh', 'Chỉ có âm thanh một bên loa', 280000, 45),
(544, 55, 'Lỗi kết nối cáp', 'Không nhận tín hiệu từ cáp', 200000, 30),
(545, 55, 'Remote đa năng hỏng', 'Điều khiển đa năng không hoạt động', 90000, 20),
(546, 55, 'Hình ảnh bị trôi', 'Hình ảnh bị trôi ngang/dọc', 380000, 50),
(547, 55, 'Lỗi cổng headphone', 'Cổng tai nghe không hoạt động', 180000, 50),
(548, 55, 'Tự động tắt tiếng', 'Tivi tự động tắt tiếng', 320000, 50),
(549, 55, 'Nhiễu tín hiệu analog', 'Nhiễu khi xem kênh analog', 250000, 50),
(550, 55, 'Mất kết nối dịch vụ LG', 'Không kết nối được dịch vụ LG', 400000, 30),
(551, 56, 'Không lên nguồn', 'Tivi Bravia OLED không khởi động', 550000, 60),
(552, 56, 'Lỗi công nghệ XR Cognitive', 'Công nghệ XR Cognitive Processor bị lỗi', 800000, 50),
(553, 56, 'Màn hình không đồng nhất', 'Độ sáng màu không đồng đều', 680000, 90),
(554, 56, 'Âm thanh Acoustic Surface lỗi', 'Công nghệ loa màng rung bị lỗi', 480000, 45),
(555, 56, 'Lỗi Android TV', 'Hệ điều hành Android TV bị treo', 520000, 50),
(556, 56, 'Remote cảm biến hỏng', 'Điều khiển cảm biến chuyển động hỏng', 250000, 20),
(557, 56, 'Hình ảnh bị lưu nét', 'Hiện tượng lưu ảnh tĩnh lâu', 720000, 50),
(558, 56, 'Lỗi cổng HDMI 2.1 4K120', 'Cổng HDMI tốc độ cao không hoạt động', 400000, 50),
(559, 56, 'Tự động calib màu lỗi', 'Tự động hiệu chỉnh màu bị lỗi', 580000, 50),
(560, 56, 'Mất kết nối Google TV', 'Không kết nối được dịch vụ Google TV', 450000, 30),
(561, 57, 'Không lên nguồn', 'Tivi Bravia XR không khởi động', 580000, 60),
(562, 57, 'Lỗi XR Backlight Master Drive', 'Công nghệ backlight cao cấp bị lỗi', 850000, 50),
(563, 57, 'Màn hình bị bloomming', 'Hiện tượng bloomming ánh sáng', 750000, 90),
(564, 57, 'Âm thanh 3D Surround lỗi', 'Công nghệ âm thanh 3D bị lỗi', 500000, 45),
(565, 57, 'Lỗi kết nối IMAX Enhanced', 'Không kết nối được chuẩn IMAX', 550000, 30),
(566, 57, 'Remote cao cấp hỏng', 'Điều khiển cao cấp không hoạt động', 280000, 20),
(567, 57, 'Hình ảnh bị lag input', 'Độ trễ input cao khi chơi game', 650000, 50),
(568, 57, 'Lỗi cổng eARC/ARC', 'Cổng âm thanh eARC không hoạt động', 420000, 50),
(569, 57, 'Tự động nhận diện cảnh lỗi', 'Tự động nhận diện nội dung bị lỗi', 600000, 50),
(570, 57, 'Mất kết nối Netflix Calibrated', 'Không kết nối được mode Netflix chuyên dụng', 480000, 30),
(571, 58, 'Không lên nguồn', 'Tivi Bravia 4K không khởi động', 480000, 60),
(572, 58, 'Lỗi công nghệ Triluminos', 'Công nghệ màu Triluminos bị lỗi', 620000, 50),
(573, 58, 'Màn hình bị mờ góc', 'Các góc màn hình bị mờ', 550000, 90),
(574, 58, 'Âm thanh ClearAudio+ lỗi', 'Công nghệ âm thanh ClearAudio+ bị lỗi', 380000, 45),
(575, 58, 'Lỗi kết nối Chromecast', 'Không kết nối được Chromecast built-in', 320000, 30),
(576, 58, 'Remote tiêu chuẩn hỏng', 'Điều khiển tiêu chuẩn không hoạt động', 180000, 20),
(577, 58, 'Hình ảnh bị judder', 'Hiện tượng judder khi xem phim', 520000, 50),
(578, 58, 'Lỗi cổng USB 3.0', 'Cổng USB 3.0 không nhận thiết bị', 300000, 50),
(579, 58, 'Tự động giảm độ sáng', 'Độ sáng tự động giảm không kiểm soát', 450000, 50),
(580, 58, 'Mất kết nối Spotify', 'Không kết nối được Spotify', 350000, 30),
(581, 59, 'Không lên nguồn', 'Tivi Android TV không khởi động', 450000, 60),
(582, 59, 'Lỗi Google Assistant', 'Trợ lý ảo Google Assistant không hoạt động', 400000, 50),
(583, 59, 'Màn hình bị sọc dọc', 'Xuất hiện sọc dọc trên màn hình', 580000, 90),
(584, 59, 'Âm thanh DTS không hoạt động', 'Giải mã âm thanh DTS bị lỗi', 350000, 45),
(585, 59, 'Lỗi kết nối AirPlay 2', 'Không kết nối được AirPlay 2', 330000, 30),
(586, 59, 'Remote voice hỏng', 'Điều khiển có tích hợp voice không hoạt động', 200000, 20),
(587, 59, 'Hình ảnh bị tối', 'Hình ảnh quá tối dù chỉnh độ sáng max', 480000, 50),
(588, 59, 'Lỗi cổng digital audio', 'Cổng âm thanh số không hoạt động', 280000, 50),
(589, 59, 'Tự động cài đặt ứng dụng', 'Tự động cài ứng dụng không mong muốn', 420000, 50),
(590, 59, 'Mất kết nối YouTube', 'Ứng dụng YouTube không hoạt động', 380000, 30),
(591, 60, 'Không lên nguồn', 'Tivi không phản ứng khi bật', 400000, 60),
(592, 60, 'Màn hình có đốm màu', 'Xuất hiện đốm màu bất thường', 520000, 90),
(593, 60, 'Âm thanh bị vang', 'Âm thanh bị vang, echo', 300000, 45),
(594, 60, 'Lỗi kết nối antenna', 'Không bắt được kênh kỹ thuật số', 250000, 30),
(595, 60, 'Remote cơ bản hỏng', 'Điều khiển cơ bản không hoạt động', 150000, 20),
(596, 60, 'Hình ảnh bị nhòe chuyển động', 'Hình ảnh bị nhòe khi có chuyển động nhanh', 450000, 50),
(597, 60, 'Lỗi cổng SCART', 'Cổng SCART không hoạt động', 220000, 50),
(598, 60, 'Tự động chuyển input', 'Tự động chuyển đầu vào không kiểm soát', 380000, 50),
(599, 60, 'Nhiễu hình kỹ thuật số', 'Nhiễu khi xem kênh kỹ thuật số', 320000, 50),
(600, 60, 'Mất kết nối ứng dụng Sony', 'Các ứng dụng Sony không hoạt động', 400000, 30),
(601, 61, 'Không lên nguồn', 'Tivi C Series không khởi động', 350000, 60),
(602, 61, 'Màn hình bị mờ tổng thể', 'Hình ảnh mờ toàn màn hình', 480000, 90),
(603, 61, 'Âm thanh bị rè', 'Âm thanh bị rè ở mọi mức âm lượng', 280000, 45),
(604, 61, 'Lỗi kết nối Google Assistant', 'Không kết nối được trợ lý Google', 320000, 30),
(605, 61, 'Remote đơn giản hỏng', 'Điều khiển cơ bản không hoạt động', 100000, 20),
(606, 61, 'Hình ảnh bị delay', 'Hình ảnh chậm hơn âm thanh', 420000, 50),
(607, 61, 'Lỗi cổng HDMI', 'Các cổng HDMI không nhận tín hiệu', 300000, 50),
(608, 61, 'Tự động reset cài đặt', 'Cài đặt bị reset về mặc định', 380000, 50),
(609, 61, 'Nhiễu wifi', 'Kết nối wifi không ổn định', 250000, 30),
(610, 61, 'Mất kết nối ứng dụng', 'Các ứng dụng không thể khởi chạy', 350000, 30),
(611, 62, 'Không lên nguồn', 'Tivi P Series không khởi động', 380000, 60),
(612, 62, 'Lỗi công nghệ HDR Pro', 'Công nghệ HDR nâng cao bị lỗi', 520000, 50),
(613, 62, 'Màn hình bị tối góc', 'Các góc màn hình bị tối hơn', 450000, 90),
(614, 62, 'Âm thanh Dolby Audio lỗi', 'Công nghệ âm thanh Dolby bị lỗi', 350000, 45),
(615, 62, 'Lỗi kết nối miracast', 'Không kết nối được screen mirroring', 280000, 30),
(616, 62, 'Remote thông minh hỏng', 'Điều khiển thông minh không hoạt động', 120000, 20),
(617, 62, 'Hình ảnh bị giật khi chơi game', 'Độ trễ cao khi chơi game', 480000, 50),
(618, 62, 'Lỗi cổng USB 3.0', 'Cổng USB tốc độ cao không nhận thiết bị', 320000, 50),
(619, 62, 'Tự động cập nhật lỗi', 'Quá trình cập nhật firmware bị gián đoạn', 400000, 50),
(620, 62, 'Mất kết nối Roku TV', 'Hệ thống Roku TV không hoạt động', 380000, 30),
(621, 63, 'Không lên nguồn', 'Tivi X Series không khởi động', 420000, 60),
(622, 63, 'Lỗi công nghệ QLED', 'Công nghệ Quantum Dot bị lỗi', 580000, 50),
(623, 63, 'Màn hình bị ám xanh', 'Màn hình bị ám sắc xanh', 520000, 90),
(624, 63, 'Âm thanh Onkyo lỗi', 'Hệ thống âm thanh Onkyo bị lỗi', 400000, 45),
(625, 63, 'Lỗi kết nối Amazon Alexa', 'Không kết nối được trợ lý Alexa', 350000, 30),
(626, 63, 'Remote cảm ứng hỏng', 'Điều khiển cảm ứng không hoạt động', 150000, 20),
(627, 63, 'Hình ảnh bị burn-in nhẹ', 'Hiện tượng lưu ảnh tạm thời', 550000, 50),
(628, 63, 'Lỗi cổng HDMI 2.1', 'Cổng HDMI chuẩn mới không hoạt động', 380000, 50),
(629, 63, 'Tự động calib màu lỗi', 'Tự động hiệu chỉnh màu không chính xác', 450000, 50),
(630, 63, 'Mất kết nối Apple AirPlay', 'Không kết nối được AirPlay', 420000, 30),
(631, 64, 'Không lên nguồn', 'Tivi QLED không khởi động', 450000, 60),
(632, 64, 'Lỗi công nghệ Quantum Dot', 'Công nghệ chấm lượng tử bị lỗi', 620000, 50),
(633, 64, 'Màn hình bị đốm sáng', 'Xuất hiện đốm sáng cục bộ', 580000, 90),
(634, 64, 'Âm thanh Harman Kardon lỗi', 'Hệ thống âm thanh cao cấp bị lỗi', 450000, 45),
(635, 64, 'Lỗi kết nối Google TV', 'Hệ điều hành Google TV bị lỗi', 400000, 30),
(636, 64, 'Remote cao cấp hỏng', 'Điều khiển cao cấp không hoạt động', 180000, 20),
(637, 64, 'Hình ảnh bị mất nét', 'Hình ảnh không sắc nét như thiết kế', 520000, 50),
(638, 64, 'Lỗi cổng eARC', 'Cổng âm thanh eARC không hoạt động', 350000, 50),
(639, 64, 'Tự động điều chỉnh contrast', 'Tự động điều chỉnh độ tương phản lỗi', 480000, 50),
(640, 64, 'Mất kết nối IMAX Enhanced', 'Không kết nối được chuẩn IMAX', 500000, 30),
(641, 65, 'Không lên nguồn', 'Tivi không phản ứng khi bật', 320000, 60),
(642, 65, 'Màn hình có sọc màu', 'Xuất hiện sọc nhiều màu trên màn hình', 450000, 90),
(643, 65, 'Âm thanh bị đứt quãng', 'Âm thanh bị ngắt quãng', 250000, 45),
(644, 65, 'Lỗi kết nối Bluetooth', 'Không kết nối được thiết bị Bluetooth', 220000, 30),
(645, 65, 'Remote tiêu chuẩn hỏng', 'Điều khiển tiêu chuẩn không hoạt động', 80000, 20),
(646, 65, 'Hình ảnh bị nhấp nháy', 'Màn hình nhấp nháy liên tục', 380000, 50),
(647, 65, 'Lỗi cổng component', 'Cổng component không nhận tín hiệu', 200000, 50),
(648, 65, 'Tự động chuyển chế độ âm thanh', 'Tự động thay đổi chế độ âm thanh', 300000, 45),
(649, 65, 'Nhiễu tín hiệu HD', 'Nhiễu khi xem kênh độ nét cao', 280000, 50),
(650, 65, 'Mất kết nối ứng dụng cơ bản', 'Các ứng dụng cơ bản không hoạt động', 320000, 30),
(651, 66, 'Không lên nguồn', 'Mi TV 4 không khởi động', 300000, 60),
(652, 66, 'Màn hình bị mỏng quá dễ vỡ', 'Màn hình siêu mỏng bị nứt', 550000, 90),
(653, 66, 'Âm thanh DTS-HD lỗi', 'Giải mã âm thanh DTS-HD bị lỗi', 320000, 45),
(654, 66, 'Lỗi kết nối PatchWall', 'Giao diện PatchWall bị treo', 280000, 30),
(655, 66, 'Remote Bluetooth hỏng', 'Điều khiển Bluetooth không hoạt động', 130000, 20),
(656, 66, 'Hình ảnh bị lệch tỷ lệ', 'Tỷ lệ hình ảnh không chuẩn', 350000, 50),
(657, 66, 'Lỗi cổng HDMI ARC', 'Cổng HDMI ARC không hoạt động', 250000, 50),
(658, 66, 'Tự động đăng xuất tài khoản', 'Tự động đăng xuất khỏi tài khoản Xiaomi', 300000, 50),
(659, 66, 'Nhiễu kết nối wifi 5GHz', 'Kết nối wifi băng tần 5GHz không ổn định', 220000, 30),
(660, 66, 'Mất kết nối Mi Home', 'Không kết nối được hệ sinh thái Xiaomi', 380000, 30),
(661, 67, 'Không lên nguồn', 'Mi TV Q Series không khởi động', 350000, 60),
(662, 67, 'Lỗi công nghệ Quantum Dot', 'Công nghệ chấm lượng tử bị lỗi', 500000, 50),
(663, 67, 'Màn hình bị hở sáng', 'Hở sáng ở viền màn hình', 420000, 90),
(664, 67, 'Âm thanh Dolby Atmos lỗi', 'Công nghệ âm thanh vòm bị lỗi', 380000, 45),
(665, 67, 'Lỗi kết nối Google Assistant', 'Trợ lý Google không phản hồi', 320000, 30),
(666, 67, 'Remote cảm biến hỏng', 'Điều khiển có cảm biến không hoạt động', 150000, 20),
(667, 67, 'Hình ảnh bị mất chi tiết', 'Mất chi tiết trong vùng tối/sáng', 450000, 50),
(668, 67, 'Lỗi cổng USB-C', 'Cổng USB-C không nhận thiết bị', 280000, 50),
(669, 67, 'Tự động nhận diện nội dung lỗi', 'Tự động điều chỉnh hình ảnh theo nội dung bị lỗi', 400000, 50),
(670, 67, 'Mất kết nối Netflix', 'Ứng dụng Netflix không hoạt động', 350000, 30),
(671, 68, 'Không lên nguồn', 'Mi TV P Series không khởi động', 320000, 60),
(672, 68, 'Lỗi công nghệ MEMC', 'Công nghệ chống giật MEMC bị lỗi', 450000, 50),
(673, 68, 'Màn hình bị ám đỏ', 'Màn hình bị ám sắc đỏ', 380000, 90),
(674, 68, 'Âm thanh Virtual Surround lỗi', 'Âm thanh vòm ảo bị lỗi', 300000, 45),
(675, 68, 'Lỗi kết nối miracast', 'Không kết nối được wireless display', 250000, 30),
(676, 68, 'Remote hồng ngoại hỏng', 'Điều khiển hồng ngoại không hoạt động', 100000, 20),
(677, 68, 'Hình ảnh bị bể khối', 'Hiện tượng bể khối khi xem video nén', 400000, 50),
(678, 68, 'Lỗi cổng Ethernet', 'Cổng mạng có dây không hoạt động', 200000, 50),
(679, 68, 'Tự động cài ứng dụng', 'Tự động cài ứng dụng không mong muốn', 350000, 50),
(680, 68, 'Mất kết nối YouTube Premium', 'Không truy cập được YouTube Premium', 320000, 30);
INSERT INTO `banggiasuachua` (`maGia`, `maMau`, `tenLoi`, `moTa`, `gia`, `thoiGianSua`) VALUES
(681, 69, 'Không lên nguồn', 'Mi TV A Series không khởi động', 280000, 60),
(682, 69, 'Màn hình độ phân giải thấp', 'Hình ảnh không đạt độ nét thiết kế', 350000, 90),
(683, 69, 'Âm thanh cơ bản yếu', 'Âm thanh loa trong yếu', 220000, 45),
(684, 69, 'Lỗi kết nối Android TV cơ bản', 'Hệ điều hành Android TV bản cơ bản bị lỗi', 250000, 30),
(685, 69, 'Remote đơn giản hỏng', 'Điều khiển đơn giản không hoạt động', 80000, 20),
(686, 69, 'Hình ảnh bị delay nhẹ', 'Độ trễ hình ảnh nhận thấy được', 300000, 50),
(687, 69, 'Lỗi cổng HDMI cơ bản', 'Cổng HDMI tiêu chuẩn không hoạt động', 180000, 50),
(688, 69, 'Tự động reset hệ thống', 'Hệ thống tự động khởi động lại', 320000, 50),
(689, 69, 'Nhiễu kết nối wifi 2.4GHz', 'Kết nối wifi băng tần 2.4GHz không ổn định', 200000, 30),
(690, 69, 'Mất kết nối ứng dụng Xiaomi', 'Các ứng dụng Xiaomi không hoạt động', 280000, 30),
(691, 70, 'Không lên nguồn', 'Tivi không phản ứng khi bật', 250000, 60),
(692, 70, 'Màn hình có điểm chết', 'Xuất hiện điểm chết trên màn hình', 400000, 90),
(693, 70, 'Âm thanh bị nhiễu', 'Âm thanh bị nhiễu tạp âm', 200000, 45),
(694, 70, 'Lỗi kết nối cơ bản', 'Không kết nối được thiết bị ngoại vi cơ bản', 180000, 30),
(695, 70, 'Remote giá rẻ hỏng', 'Điều khiển giá rẻ không hoạt động', 60000, 20),
(696, 70, 'Hình ảnh bị mờ tổng thể', 'Hình ảnh mờ toàn màn hình', 320000, 50),
(697, 70, 'Lỗi cổng AV', 'Cổng AV composite không hoạt động', 150000, 50),
(698, 70, 'Tự động tắt sau thời gian', 'Tự động tắt sau thời gian không hoạt động', 280000, 50),
(699, 70, 'Nhiễu tín hiệu RF', 'Nhiễu khi xem kênh analog', 220000, 50),
(700, 70, 'Mất kết nối internet cơ bản', 'Không kết nối được internet', 250000, 30),
(701, 71, 'Không lên nguồn', 'Tivi 4K Android không khởi động', 320000, 60),
(702, 71, 'Màn hình 4K không đạt chuẩn', 'Độ phân giải không đạt 4K thật', 450000, 90),
(703, 71, 'Âm thanh Android TV lỗi', 'Hệ thống âm thanh tích hợp bị lỗi', 280000, 45),
(704, 71, 'Lỗi kết nối Google Play', 'Không truy cập được Google Play Store', 300000, 30),
(705, 71, 'Remote Android hỏng', 'Điều khiển Android TV không hoạt động', 110000, 20),
(706, 71, 'Hình ảnh bị lỗi upscale', 'Lỗi nâng cấp độ phân giải', 380000, 50),
(707, 71, 'Lỗi cổng HDMI 4K', 'Cổng HDMI 4K không nhận tín hiệu', 250000, 50),
(708, 71, 'Tự động đóng ứng dụng', 'Ứng dụng tự động đóng khi đang sử dụng', 350000, 50),
(709, 71, 'Nhiễu hệ thống Android', 'Hệ thống Android bị treo, lag', 320000, 50),
(710, 71, 'Mất kết nối Chromecast', 'Tính năng Chromecast built-in không hoạt động', 300000, 30),
(711, 72, 'Không lên nguồn', 'Smart TV không khởi động', 300000, 60),
(712, 72, 'Màn hình Smart TV cơ bản', 'Hình ảnh không nổi bật', 400000, 90),
(713, 72, 'Âm thanh Smart Sound lỗi', 'Hệ thống âm thanh thông minh bị lỗi', 250000, 45),
(714, 72, 'Lỗi kết nối ứng dụng thông minh', 'Các ứng dụng smart TV không hoạt động', 280000, 30),
(715, 72, 'Remote smart hỏng', 'Điều khiển smart TV không hoạt động', 100000, 20),
(716, 72, 'Hình ảnh bị lỗi kết nối mạng', 'Hình ảnh bị ảnh hưởng bởi kết nối mạng', 350000, 30),
(717, 72, 'Lỗi cổng USB media', 'Cổng USB đọc media không nhận file', 200000, 50),
(718, 72, 'Tự động cập nhật ứng dụng', 'Tự động cập nhật ứng dụng gây lỗi', 320000, 50),
(719, 72, 'Nhiễu kết nối LAN', 'Kết nối mạng có dây không ổn định', 220000, 30),
(720, 72, 'Mất kết nối trình duyệt web', 'Trình duyệt web tích hợp không hoạt động', 280000, 30),
(721, 73, 'Không lên nguồn', 'Tivi LED không khởi động', 280000, 60),
(722, 73, 'Màn hình LED backlight lỗi', 'Đèn nền LED bị hỏng một phần', 380000, 90),
(723, 73, 'Âm thanh stereo cơ bản', 'Âm thanh stereo không cân bằng', 220000, 45),
(724, 73, 'Lỗi kết nối thiết bị ngoại vi', 'Không nhận thiết bị ngoại vi cơ bản', 200000, 30),
(725, 73, 'Remote cơ bản hỏng', 'Điều khiển cơ bản không hoạt động', 80000, 20),
(726, 73, 'Hình ảnh bị mất màu', 'Mất một hoặc nhiều màu cơ bản', 320000, 50),
(727, 73, 'Lỗi cổng VGA', 'Cổng VGA không nhận tín hiệu', 180000, 50),
(728, 73, 'Tự động chuyển chế độ hình ảnh', 'Tự động thay đổi chế độ hình ảnh', 250000, 50),
(729, 73, 'Nhiễu tín hiệu analog', 'Nhiễu khi xem kênh analog', 200000, 50),
(730, 73, 'Mất kết nối đầu thu kỹ thuật số', 'Không kết nối được đầu thu DVB-T2', 300000, 30),
(731, 74, 'Không lên nguồn', 'Tivi Pro Series không khởi động', 350000, 60),
(732, 74, 'Lỗi công nghệ hình ảnh Pro', 'Công nghệ hình ảnh nâng cao bị lỗi', 480000, 50),
(733, 74, 'Màn hình Pro bị lỗi pixel', 'Lỗi pixel trên màn hình cao cấp', 420000, 90),
(734, 74, 'Âm thanh Pro Surround lỗi', 'Hệ thống âm thanh chuyên nghiệp bị lỗi', 320000, 45),
(735, 74, 'Lỗi kết nối đa phương tiện Pro', 'Không kết nối được thiết bị đa phương tiện cao cấp', 300000, 30),
(736, 74, 'Remote Pro hỏng', 'Điều khiển chuyên nghiệp không hoạt động', 130000, 20),
(737, 74, 'Hình ảnh bị lỗi xử lý tín hiệu', 'Lỗi xử lý tín hiệu hình ảnh nâng cao', 400000, 50),
(738, 74, 'Lỗi cổng kết nối Pro', 'Các cổng kết nối chuyên nghiệp không hoạt động', 280000, 30),
(739, 74, 'Tự động tối ưu hóa lỗi', 'Tự động tối ưu hóa hình ảnh/âm thanh bị lỗi', 380000, 50),
(740, 74, 'Mất kết nối dịch vụ Pro', 'Các dịch vụ cao cấp không hoạt động', 350000, 30),
(741, 75, 'Không lên nguồn', 'Tivi không phản ứng khi bật', 250000, 60),
(742, 75, 'Màn hình có vết xước', 'Vết xước trên bề mặt màn hình', 350000, 90),
(743, 75, 'Âm thanh cơ bản bị lỗi', 'Hệ thống âm thanh cơ bản không hoạt động', 180000, 45),
(744, 75, 'Lỗi kết nối cáp thường', 'Không nhận tín hiệu từ cáp thông thường', 150000, 30),
(745, 75, 'Remote đa năng hỏng', 'Điều khiển đa năng không hoạt động', 70000, 20),
(746, 75, 'Hình ảnh bị lỗi màu cơ bản', 'Màu sắc cơ bản không chính xác', 280000, 50),
(747, 75, 'Lỗi cổng kết nối cơ bản', 'Các cổng kết nối cơ bản không hoạt động', 160000, 30),
(748, 75, 'Tự động điều chỉnh cài đặt', 'Tự động thay đổi cài đặt cơ bản', 220000, 50),
(749, 75, 'Nhiễu tín hiệu tổng hợp', 'Nhiễu từ nhiều nguồn tín hiệu', 200000, 50),
(750, 75, 'Mất kết nối tính năng cơ bản', 'Các tính năng cơ bản không hoạt động', 250000, 30),
(751, 76, 'Không lên nguồn', 'Tivi OLED Panasonic không khởi động', 480000, 60),
(752, 76, 'Lỗi công nghệ HCX Pro', 'Bộ xử lý hình ảnh HCX Pro bị lỗi', 650000, 50),
(753, 76, 'Màn hình OLED bị burn-in', 'Hiện tượng lưu ảnh trên panel OLED', 800000, 90),
(754, 76, 'Âm thanh Technics lỗi', 'Hệ thống âm thanh Technics bị lỗi', 450000, 45),
(755, 76, 'Lỗi kết nối My Home Screen', 'Giao diện My Home Screen bị treo', 380000, 30),
(756, 76, 'Remote cảm ứng OLED hỏng', 'Điều khiển cảm ứng cao cấp không hoạt động', 200000, 20),
(757, 76, 'Hình ảnh bị lỗi màu OLED', 'Màu sắc OLED không chính xác', 580000, 50),
(758, 76, 'Lỗi cổng HDMI 4K Pro', 'Cổng HDMI chuyên nghiệp không hoạt động', 350000, 50),
(759, 76, 'Tự động calib màu chuyên nghiệp', 'Tự động hiệu chỉnh màu chuyên nghiệp bị lỗi', 520000, 50),
(760, 76, 'Mất kết nối dịch vụ 4K Pro', 'Các dịch vụ 4K chuyên nghiệp không hoạt động', 480000, 30),
(761, 77, 'Không lên nguồn', 'Tivi 4K LED không khởi động', 420000, 60),
(762, 77, 'Lỗi công nghệ Local Dimming Pro', 'Công nghệ điều khiển đèn nền cục bộ bị lỗi', 550000, 50),
(763, 77, 'Màn hình LED bị hở sáng', 'Hở sáng ở các góc màn hình', 480000, 90),
(764, 77, 'Âm thanh Surround 3D lỗi', 'Công nghệ âm thanh vòm 3D bị lỗi', 380000, 45),
(765, 77, 'Lỗi kết nối Firefox OS', 'Hệ điều hành Firefox OS bị lỗi', 320000, 30),
(766, 77, 'Remote 4K hỏng', 'Điều khiển 4K không hoạt động', 150000, 20),
(767, 77, 'Hình ảnh bị lỗi upscale 4K', 'Lỗi nâng cấp lên 4K', 500000, 50),
(768, 77, 'Lỗi cổng USB 4K media', 'Cổng USB đọc media 4K không nhận file', 300000, 50),
(769, 77, 'Tự động điều chỉnh HDR', 'Tự động điều chỉnh HDR bị lỗi', 450000, 50),
(770, 77, 'Mất kết nối dịch vụ 4K', 'Các dịch vụ nội dung 4K không hoạt động', 400000, 30),
(771, 78, 'Không lên nguồn', 'Smart TV Panasonic không khởi động', 380000, 60),
(772, 78, 'Lỗi công nghệ Smart Viera', 'Công nghệ Smart Viera bị lỗi', 480000, 50),
(773, 78, 'Màn hình Smart bị lag', 'Hình ảnh bị lag khi sử dụng tính năng smart', 420000, 90),
(774, 78, 'Âm thanh Smart Sound lỗi', 'Hệ thống âm thanh thông minh bị lỗi', 320000, 45),
(775, 78, 'Lỗi kết nối ứng dụng Viera', 'Các ứng dụng Viera không hoạt động', 300000, 30),
(776, 78, 'Remote smart Viera hỏng', 'Điều khiển smart Viera không hoạt động', 130000, 20),
(777, 78, 'Hình ảnh bị ảnh hưởng bởi smart features', 'Hình ảnh bị ảnh hưởng khi dùng tính năng smart', 450000, 50),
(778, 78, 'Lỗi cổng kết nối smart', 'Các cổng kết nối smart không hoạt động', 280000, 30),
(779, 78, 'Tự động cập nhật smart features', 'Tự động cập nhật tính năng smart gây lỗi', 400000, 50),
(780, 78, 'Mất kết nối dịch vụ smart', 'Các dịch vụ smart không hoạt động', 350000, 30),
(781, 79, 'Không lên nguồn', 'Tivi JX Series không khởi động', 350000, 60),
(782, 79, 'Lỗi công nghệ JX Engine', 'Bộ xử lý JX Engine bị lỗi', 450000, 50),
(783, 79, 'Màn hình JX bị mờ viền', 'Viền màn hình bị mờ', 380000, 90),
(784, 79, 'Âm thanh JX Surround lỗi', 'Hệ thống âm thanh JX bị lỗi', 280000, 45),
(785, 79, 'Lỗi kết nối tính năng JX', 'Các tính năng đặc trưng JX không hoạt động', 250000, 30),
(786, 79, 'Remote JX hỏng', 'Điều khiển JX series không hoạt động', 110000, 20),
(787, 79, 'Hình ảnh bị lỗi xử lý JX', 'Lỗi xử lý hình ảnh đặc trưng JX', 400000, 50),
(788, 79, 'Lỗi cổng kết nối JX', 'Các cổng kết nối JX không hoạt động', 220000, 30),
(789, 79, 'Tự động tối ưu JX lỗi', 'Tự động tối ưu hóa JX bị lỗi', 350000, 50),
(790, 79, 'Mất kết nối dịch vụ JX', 'Các dịch vụ JX không hoạt động', 320000, 30),
(791, 80, 'Không lên nguồn', 'Tivi không phản ứng khi bật', 300000, 60),
(792, 80, 'Màn hình có đốm tối', 'Xuất hiện đốm tối trên màn hình', 420000, 90),
(793, 80, 'Âm thanh cơ bản bị lỗi', 'Hệ thống âm thanh cơ bản Panasonic bị lỗi', 250000, 45),
(794, 80, 'Lỗi kết nối thiết bị Panasonic', 'Không kết nối được thiết bị Panasonic khác', 280000, 30),
(795, 80, 'Remote Panasonic cơ bản hỏng', 'Điều khiển Panasonic cơ bản không hoạt động', 100000, 20),
(796, 80, 'Hình ảnh bị lỗi xử lý cơ bản', 'Lỗi xử lý hình ảnh cơ bản', 380000, 50),
(797, 80, 'Lỗi cổng kết nối thường', 'Các cổng kết nối thông thường không hoạt động', 200000, 30),
(798, 80, 'Tự động điều chỉnh cài đặt Panasonic', 'Tự động thay đổi cài đặt đặc trưng Panasonic', 320000, 50),
(799, 80, 'Nhiễu hệ thống Panasonic', 'Hệ thống xử lý Panasonic bị nhiễu', 280000, 50),
(800, 80, 'Mất kết nối tính năng Panasonic', 'Các tính năng đặc trưng Panasonic không hoạt động', 300000, 30),
(801, 81, 'Không lạnh', 'Tủ chạy nhưng không làm lạnh', 420000, 120),
(802, 81, 'Đóng tuyết nhiều', 'Tủ bị đóng tuyết dày ở ngăn đá', 350000, 90),
(803, 81, 'Kêu to', 'Tủ phát ra tiếng ồn lớn khi hoạt động', 280000, 60),
(804, 81, 'Chảy nước', 'Nước chảy ra bên ngoài tủ', 220000, 45),
(805, 81, 'Không xả đá', 'Hệ thống xả đá không hoạt động', 380000, 90),
(806, 81, 'Lỗi board điều khiển', 'Board mạch điều khiển bị lỗi', 520000, 75),
(807, 81, 'Cánh cửa hở', 'Cánh cửa không đóng kín', 180000, 75),
(808, 81, 'Quạt không chạy', 'Quạt dàn lạnh không hoạt động', 320000, 60),
(809, 81, 'Rò điện', 'Tủ bị rò rỉ điện', 450000, 75),
(810, 81, 'Mất điện đột ngột', 'Tủ tự ngắt điện khi đang hoạt động', 380000, 75),
(811, 82, 'Không lạnh', 'Tủ chạy không lạnh', 380000, 120),
(812, 82, 'Đóng tuyết ngăn mát', 'Ngăn mát bị đóng tuyết', 300000, 90),
(813, 82, 'Kêu rít', 'Tủ phát ra tiếng rít khi hoạt động', 250000, 75),
(814, 82, 'Nước đọng đáy tủ', 'Nước đọng ở đáy tủ lạnh', 200000, 75),
(815, 82, 'Không đông đá', 'Ngăn đá không đông thực phẩm', 350000, 75),
(816, 82, 'Lỗi rơ-le', 'Rơ-le nhiệt bị hỏng', 280000, 75),
(817, 82, 'Đèn trong không sáng', 'Đèn chiếu sáng bên trong không hoạt động', 120000, 75),
(818, 82, 'Block kêu to', 'Máy nén kêu ồn bất thường', 400000, 60),
(819, 82, 'Rò gas', 'Hệ thống gas bị rò rỉ', 420000, 120),
(820, 82, 'Tự ngắt', 'Tủ tự động ngắt không rõ nguyên nhân', 320000, 75),
(821, 83, 'Không lạnh', 'Tủ Side by Side không làm lạnh', 550000, 120),
(822, 83, 'Đóng tuyết toàn bộ', 'Cả hai ngăn đều bị đóng tuyết', 450000, 90),
(823, 83, 'Kêu rung', 'Tủ rung lắc và kêu to', 380000, 75),
(824, 83, 'Nước rỉ từ icemaker', 'Máy làm đá bị rỉ nước', 320000, 75),
(825, 83, 'Icemaker không hoạt động', 'Máy làm đá tự động ngừng hoạt động', 480000, 75),
(826, 83, 'Lỗi board điện tử', 'Board điều khiển điện tử bị lỗi', 650000, 75),
(827, 83, 'Cửa kính bị hở', 'Cửa kính không đóng khít', 250000, 75),
(828, 83, 'Quạt dàn lạnh kêu', 'Quạt dàn lạnh phát ra tiếng ồn', 350000, 60),
(829, 83, 'Rò gas hệ thống kép', 'Hệ thống gas kép bị rò rỉ', 580000, 120),
(830, 83, 'Mất nguồn đột ngột', 'Tủ mất nguồn liên tục', 420000, 75),
(831, 84, 'Không lạnh', 'Tủ AQUA series không làm lạnh', 400000, 120),
(832, 84, 'Đóng tuyết bất thường', 'Đóng tuyết không đều các ngăn', 320000, 90),
(833, 84, 'Kêu lạch cạch', 'Tiếng kêu lạch cạch từ bên trong', 280000, 75),
(834, 84, 'Nước đọng ngăn rau quả', 'Ngăn rau quả bị đọng nước', 240000, 75),
(835, 84, 'Không giữ nhiệt', 'Nhiệt độ không ổn định', 360000, 75),
(836, 84, 'Lỗi cảm biến nhiệt', 'Cảm biến nhiệt độ hoạt động sai', 380000, 75),
(837, 84, 'Kệ tủ bị gãy', 'Các kệ bên trong bị gãy, nứt', 150000, 75),
(838, 84, 'Quạt ngưng tụ kêu', 'Quạt dàn nóng phát ra tiếng ồn', 300000, 60),
(839, 84, 'Rò gas cục bộ', 'Rò rỉ gas tại các mối nối', 450000, 120),
(840, 84, 'Tự động báo lỗi', 'Hệ thống báo lỗi liên tục', 400000, 75),
(841, 85, 'Không lạnh', 'Tủ chạy nhưng không lạnh', 350000, 120),
(842, 85, 'Đóng tuyết nhẹ', 'Đóng tuyết mỏng trên dàn lạnh', 280000, 90),
(843, 85, 'Kêu vo ve', 'Tiếng kêu vo ve nhẹ', 220000, 75),
(844, 85, 'Nước đọng khay hứng', 'Nước đọng ở khay hứng phía sau', 180000, 75),
(845, 85, 'Làm lạnh chậm', 'Thời gian làm lạnh lâu hơn bình thường', 320000, 75),
(846, 85, 'Lỗi thermostat', 'Bộ điều nhiệt hoạt động không chính xác', 300000, 75),
(847, 85, 'Đèn chiếu sáng chập chờn', 'Đèn trong tủ nhấp nháy', 100000, 75),
(848, 85, 'Block chạy liên tục', 'Máy nén chạy không ngừng', 380000, 75),
(849, 85, 'Rò gas nhẹ', 'Rò rỉ gas với tốc độ chậm', 400000, 120),
(850, 85, 'Tự ngắt ngẫu nhiên', 'Tự động ngắt không theo chu kỳ', 350000, 75),
(851, 86, 'Không lạnh', 'Tủ Inverter Toshiba không làm lạnh', 450000, 120),
(852, 86, 'Đóng tuyết dàn lạnh', 'Dàn lạnh bị đóng tuyết dày', 380000, 90),
(853, 86, 'Kêu rung động', 'Tủ rung lắc mạnh khi hoạt động', 320000, 75),
(854, 86, 'Nước rỉ từ ngăn đá', 'Nước chảy từ ngăn đá xuống ngăn mát', 280000, 75),
(855, 86, 'Không đông đá nhanh', 'Chức năng làm đá nhanh không hoạt động', 400000, 75),
(856, 86, 'Lỗi board inverter', 'Board mạch inverter bị lỗi', 580000, 75),
(857, 86, 'Cửa tủ bị lệch', 'Cửa tủ đóng không khít', 220000, 75),
(858, 86, 'Quạt dàn lạnh không chạy', 'Quạt dàn lạnh ngừng hoạt động', 350000, 60),
(859, 86, 'Rò gas inverter', 'Hệ thống gas inverter bị rò rỉ', 500000, 120),
(860, 86, 'Tự động báo lỗi điện tử', 'Màn hình hiển thị báo lỗi liên tục', 420000, 75),
(861, 87, 'Không lạnh', 'Tủ GR Series không làm lạnh', 420000, 120),
(862, 87, 'Đóng tuyết không đều', 'Đóng tuyết không đồng đều các ngăn', 350000, 90),
(863, 87, 'Kêu lách cách', 'Tiếng kêu lách cách từ máy nén', 300000, 75),
(864, 87, 'Nước đọng ngăn mát', 'Ngăn mát bị đọng nước nhiều', 250000, 75),
(865, 87, 'Làm lạnh yếu', 'Khả năng làm lạnh kém hiệu quả', 380000, 75),
(866, 87, 'Lỗi cảm biến GR', 'Cảm biến nhiệt GR series bị lỗi', 450000, 75),
(867, 87, 'Kệ thủy tinh nứt', 'Kệ thủy tinh bị nứt, vỡ', 180000, 75),
(868, 87, 'Quạt dàn nóng kêu', 'Quạt dàn nóng phát ra tiếng ồn', 320000, 60),
(869, 87, 'Rò gas hệ thống', 'Hệ thống làm lạnh bị rò gas', 480000, 120),
(870, 87, 'Tự ngắt khi đầy tải', 'Tự động ngắt khi chất đầy thực phẩm', 400000, 75),
(871, 88, 'Không lạnh', 'Tủ Glass Series không làm lạnh', 480000, 120),
(872, 88, 'Đóng tuyết mặt kính', 'Mặt kính cửa bị đóng tuyết', 400000, 90),
(873, 88, 'Kêu vù vù', 'Tiếng kêu vù vù liên tục', 350000, 75),
(874, 88, 'Nước rỉ từ dispenser', 'Bộ phận lấy nước bị rỉ nước', 320000, 75),
(875, 88, 'Dispenser không hoạt động', 'Bộ phận lấy nước, đá ngừng hoạt động', 450000, 75),
(876, 88, 'Lỗi board điều khiển kính', 'Board điều khiển mặt kính bị lỗi', 550000, 75),
(877, 88, 'Cửa kính bị mờ', 'Mặt kính cửa bị mờ, mất độ trong', 280000, 75),
(878, 88, 'Quạt làm mát kính hỏng', 'Quạt làm mát mặt kính không hoạt động', 380000, 60),
(879, 88, 'Rò gas hệ thống kính', 'Hệ thống làm lạnh mặt kính bị rò gas', 520000, 120),
(880, 88, 'Tự động báo lỗi cảm biến', 'Cảm biến nhiệt độ báo lỗi sai', 450000, 75),
(881, 89, 'Không lạnh', 'Tủ Multi Door không làm lạnh', 520000, 120),
(882, 89, 'Đóng tuyết nhiều cửa', 'Nhiều ngăn cùng bị đóng tuyết', 450000, 90),
(883, 89, 'Kêu rít từ nhiều vị trí', 'Nhiều vị trí cùng phát ra tiếng rít', 400000, 75),
(884, 89, 'Nước rỉ từ nhiều ngăn', 'Nhiều ngăn cùng bị rỉ nước', 350000, 75),
(885, 89, 'Các cửa không đồng bộ', 'Các cửa đóng mở không đồng bộ', 300000, 75),
(886, 89, 'Lỗi board điều khiển đa cửa', 'Board điều khiển nhiều cửa bị lỗi', 650000, 75),
(887, 89, 'Cửa phụ không đóng kín', 'Các cửa phụ đóng không khít', 250000, 75),
(888, 89, 'Quạt nhiều dàn lạnh hỏng', 'Nhiều quạt dàn lạnh cùng hỏng', 480000, 60),
(889, 89, 'Rò gas hệ thống đa ngăn', 'Hệ thống gas đa ngăn bị rò rỉ', 580000, 120),
(890, 89, 'Tự động báo lỗi đa điểm', 'Hệ thống báo lỗi từ nhiều cảm biến', 500000, 75),
(891, 90, 'Không lạnh', 'Tủ Toshiba không làm lạnh', 400000, 120),
(892, 90, 'Đóng tuyết nhẹ', 'Đóng tuyết mỏng trên dàn lạnh', 320000, 90),
(893, 90, 'Kêu êm nhưng không lạnh', 'Tủ chạy êm nhưng không làm lạnh', 350000, 120),
(894, 90, 'Nước đọng khay', 'Nước đọng ở các khay hứng', 200000, 75),
(895, 90, 'Làm lạnh không đều', 'Nhiệt độ các ngăn không đồng đều', 380000, 75),
(896, 90, 'Lỗi thermostat cơ', 'Bộ điều nhiệt cơ học bị hỏng', 280000, 75),
(897, 90, 'Đèn chiếu sáng cháy', 'Bóng đèn chiếu sáng bị cháy', 80000, 75),
(898, 90, 'Block chạy ngắt quãng', 'Máy nén chạy ngắt quãng không đều', 420000, 75),
(899, 90, 'Rò gas mối hàn', 'Rò rỉ gas tại các mối hàn', 450000, 120),
(900, 90, 'Tự ngắt theo chu kỳ lỗi', 'Tự động ngắt theo chu kỳ không chính xác', 380000, 75),
(901, 91, 'Không lạnh', 'Tủ Inverter Linear không làm lạnh', 480000, 120),
(902, 91, 'Đóng tuyết Linear Cooling', 'Hệ thống làm lạnh Linear bị đóng tuyết', 420000, 90),
(903, 91, 'Kêu rung linear', 'Máy nén Linear phát ra tiếng rung', 380000, 75),
(904, 91, 'Nước rỉ từ hệ thống linear', 'Hệ thống làm lạnh linear bị rỉ nước', 320000, 75),
(905, 91, 'Linear compressor lỗi', 'Máy nén Linear bị hỏng', 650000, 75),
(906, 91, 'Lỗi board linear inverter', 'Board mạch linear inverter bị lỗi', 580000, 75),
(907, 91, 'Cửa tủ linear bị hở', 'Cửa tủ không đóng khít hệ thống linear', 250000, 75),
(908, 91, 'Quạt dàn lạnh linear kêu', 'Quạt hệ thống linear phát ra tiếng ồn', 350000, 60),
(909, 91, 'Rò gas linear', 'Hệ thống gas linear bị rò rỉ', 520000, 120),
(910, 91, 'Tự động báo lỗi linear', 'Hệ thống linear báo lỗi liên tục', 450000, 75),
(911, 92, 'Không lạnh', 'Tủ Side by Side không làm lạnh', 550000, 120),
(912, 92, 'Đóng tuyết hai ngăn', 'Cả hai ngăn đều bị đóng tuyết nặng', 480000, 90),
(913, 92, 'Kêu vang từ hai buồng', 'Tiếng ồn phát ra từ cả hai buồng', 420000, 75),
(914, 92, 'Nước rỉ từ icemaker LG', 'Máy làm đá LG bị rỉ nước', 380000, 75),
(915, 92, 'Icemaker LG không hoạt động', 'Máy làm đá tự động LG ngừng hoạt động', 500000, 75),
(916, 92, 'Lỗi board điều khiển đôi', 'Board điều khiển hai buồng bị lỗi', 700000, 75),
(917, 92, 'Cửa kính LG bị hở', 'Cửa kính side by side không đóng khít', 300000, 75),
(918, 92, 'Quạt đôi không đồng bộ', 'Hai quạt dàn lạnh hoạt động không đồng bộ', 450000, 60),
(919, 92, 'Rò gas hệ thống kép LG', 'Hệ thống gas kép LG bị rò rỉ', 600000, 120),
(920, 92, 'Mất nguồn đột ngột side by side', 'Tủ side by side mất nguồn liên tục', 480000, 75),
(921, 93, 'Không lạnh', 'Tủ DoorCool+ không làm lạnh', 500000, 120),
(922, 93, 'Đóng tuyết hệ thống DoorCool', 'Hệ thống làm lạnh cửa bị đóng tuyết', 450000, 90),
(923, 93, 'Kêu từ hệ thống cửa', 'Tiếng ồn phát ra từ hệ thống làm lạnh cửa', 400000, 75),
(924, 93, 'Nước rỉ từ lỗ thông gió cửa', 'Các lỗ thông gió trên cửa bị rỉ nước', 350000, 75),
(925, 93, 'DoorCool+ không hoạt động', 'Hệ thống làm lạnh cửa ngừng hoạt động', 420000, 75),
(926, 93, 'Lỗi board DoorCool+', 'Board điều khiển hệ thống cửa bị lỗi', 550000, 75),
(927, 93, 'Cửa làm lạnh bị tắc', 'Các lỗ làm lạnh trên cửa bị tắc nghẽn', 280000, 75),
(928, 93, 'Quạt DoorCool+ hỏng', 'Quạt hệ thống làm lạnh cửa không hoạt động', 380000, 60),
(929, 93, 'Rò gas hệ thống cửa', 'Hệ thống gas làm lạnh cửa bị rò rỉ', 500000, 120),
(930, 93, 'Tự động báo lỗi DoorCool', 'Hệ thống DoorCool+ báo lỗi liên tục', 450000, 75),
(931, 94, 'Không lạnh', 'Tủ InstaView không làm lạnh', 520000, 120),
(932, 94, 'Đóng tuyết màn hình', 'Màn hình InstaView bị đóng tuyết', 480000, 90),
(933, 94, 'Kêu từ màn hình', 'Tiếng ồn phát ra từ màn hình InstaView', 420000, 75),
(934, 94, 'Nước rỉ từ khung màn hình', 'Khung màn hình bị rỉ nước', 380000, 75),
(935, 94, 'InstaView không hoạt động', 'Màn hình gõ 2 lần không hoạt động', 450000, 75),
(936, 94, 'Lỗi board InstaView', 'Board điều khiển màn hình bị lỗi', 600000, 75),
(937, 94, 'Màn hình bị nứt', 'Màn hình InstaView bị nứt, vỡ', 350000, 75),
(938, 94, 'Cảm biến gõ hỏng', 'Cảm biến nhận diện gõ không hoạt động', 320000, 75),
(939, 94, 'Rò gas hệ thống cao cấp', 'Hệ thống gas cao cấp bị rò rỉ', 550000, 120),
(940, 94, 'Tự động báo lỗi InstaView', 'Hệ thống InstaView báo lỗi liên tục', 500000, 75),
(941, 95, 'Không lạnh', 'Tủ LG không làm lạnh', 420000, 120),
(942, 95, 'Đóng tuyết nhẹ LG', 'Đóng tuyết mỏng trên dàn lạnh LG', 350000, 90),
(943, 95, 'Kêu êm đặc trưng LG', 'Tiếng kêu đặc trưng của tủ LG', 300000, 75),
(944, 95, 'Nước đọng hệ thống LG', 'Nước đọng trong hệ thống làm lạnh LG', 250000, 75),
(945, 95, 'Làm lạnh LG không ổn định', 'Nhiệt độ dao động không ổn định', 380000, 75),
(946, 95, 'Lỗi cảm biến LG', 'Cảm biến nhiệt độ LG hoạt động sai', 320000, 75),
(947, 95, 'Đèn LED LG cháy', 'Đèn LED chiếu sáng LG bị cháy', 120000, 75),
(948, 95, 'Block LG chạy không ổn định', 'Máy nén LG hoạt động không ổn định', 450000, 75),
(949, 95, 'Rò gas hệ thống LG', 'Hệ thống gas LG bị rò rỉ', 480000, 120),
(950, 95, 'Tự ngắt không rõ nguyên nhân', 'Tủ LG tự động ngắt không rõ lý do', 400000, 75),
(951, 96, 'Không lạnh', 'Tủ Inverter Panasonic không làm lạnh', 450000, 120),
(952, 96, 'Đóng tuyết inverter', 'Hệ thống inverter bị đóng tuyết', 380000, 90),
(953, 96, 'Kêu rung inverter', 'Máy nén inverter phát ra tiếng rung', 320000, 75),
(954, 96, 'Nước rỉ từ hệ thống inverter', 'Hệ thống làm lạnh inverter bị rỉ nước', 280000, 75),
(955, 96, 'Inverter compressor lỗi', 'Máy nén inverter bị hỏng', 600000, 75),
(956, 96, 'Lỗi board inverter Panasonic', 'Board mạch inverter Panasonic bị lỗi', 550000, 75),
(957, 96, 'Cửa tủ inverter bị hở', 'Cửa tủ không đóng khít hệ thống inverter', 220000, 75),
(958, 96, 'Quạt dàn lạnh inverter kêu', 'Quạt hệ thống inverter phát ra tiếng ồn', 350000, 60),
(959, 96, 'Rò gas inverter Panasonic', 'Hệ thống gas inverter bị rò rỉ', 500000, 120),
(960, 96, 'Tự động báo lỗi inverter', 'Hệ thống inverter báo lỗi liên tục', 420000, 75),
(961, 97, 'Không lạnh', 'Tủ Econavi không làm lạnh', 480000, 120),
(962, 97, 'Đóng tuyết hệ thống Econavi', 'Hệ thống tiết kiệm năng lượng bị đóng tuyết', 420000, 90),
(963, 97, 'Kêu từ cảm biến Econavi', 'Cảm biến Econavi phát ra tiếng ồn', 380000, 75),
(964, 97, 'Nước rỉ từ hệ thống thông minh', 'Hệ thống thông minh bị rỉ nước', 320000, 75),
(965, 97, 'Econavi không hoạt động', 'Tính năng tiết kiệm năng lượng không hoạt động', 400000, 75),
(966, 97, 'Lỗi board Econavi', 'Board điều khiển hệ thống thông minh bị lỗi', 520000, 75),
(967, 97, 'Cảm biến cửa hỏng', 'Cảm biến phát hiện đóng mở cửa bị hỏng', 250000, 75),
(968, 97, 'Quạt Econavi hỏng', 'Quạt hệ thống thông minh không hoạt động', 380000, 60),
(969, 97, 'Rò gas hệ thống Econavi', 'Hệ thống gas thông minh bị rò rỉ', 480000, 120),
(970, 97, 'Tự động báo lỗi Econavi', 'Hệ thống Econavi báo lỗi liên tục', 450000, 75),
(971, 98, 'Không lạnh', 'Tủ Side by Side Panasonic không làm lạnh', 580000, 120),
(972, 98, 'Đóng tuyết hai buồng Panasonic', 'Cả hai buồng đều bị đóng tuyết nặng', 500000, 90),
(973, 98, 'Kêu từ hệ thống kép', 'Hệ thống làm lạnh kép phát ra tiếng ồn', 450000, 75),
(974, 98, 'Nước rỉ từ icemaker Panasonic', 'Máy làm đá Panasonic bị rỉ nước', 400000, 75),
(975, 98, 'Icemaker Panasonic không hoạt động', 'Máy làm đá tự động Panasonic ngừng hoạt động', 520000, 75),
(976, 98, 'Lỗi board điều khiển đôi Panasonic', 'Board điều khiển hai buồng Panasonic bị lỗi', 680000, 75),
(977, 98, 'Cửa kính Panasonic bị hở', 'Cửa kính side by side Panasonic không đóng khít', 320000, 75),
(978, 98, 'Quạt đôi Panasonic không đồng bộ', 'Hai quạt dàn lạnh Panasonic hoạt động không đồng bộ', 480000, 60),
(979, 98, 'Rò gas hệ thống kép Panasonic', 'Hệ thống gas kép Panasonic bị rò rỉ', 620000, 120),
(980, 98, 'Mất nguồn side by side Panasonic', 'Tủ side by side Panasonic mất nguồn liên tục', 520000, 75),
(981, 99, 'Không lạnh', 'Tủ NR Series không làm lạnh', 420000, 120),
(982, 99, 'Đóng tuyết NR system', 'Hệ thống làm lạnh NR bị đóng tuyết', 350000, 90),
(983, 99, 'Kêu đặc trưng NR', 'Tiếng kêu đặc trưng của dòng NR', 300000, 75),
(984, 99, 'Nước rỉ từ hệ thống NR', 'Hệ thống làm lạnh NR bị rỉ nước', 250000, 75),
(985, 99, 'Hệ thống NR không ổn định', 'Hệ thống làm lạnh NR hoạt động không ổn định', 380000, 75),
(986, 99, 'Lỗi board NR series', 'Board điều khiển dòng NR bị lỗi', 480000, 75),
(987, 99, 'Cửa tủ NR bị lệch', 'Cửa tủ NR series đóng không khít', 200000, 75),
(988, 99, 'Quạt NR series kêu', 'Quạt hệ thống NR phát ra tiếng ồn', 320000, 60),
(989, 99, 'Rò gas hệ thống NR', 'Hệ thống gas NR bị rò rỉ', 450000, 120),
(990, 99, 'Tự động báo lỗi NR', 'Hệ thống NR báo lỗi liên tục', 400000, 75),
(991, 100, 'Không lạnh', 'Tủ Panasonic không làm lạnh', 380000, 120),
(992, 100, 'Đóng tuyết nhẹ Panasonic', 'Đóng tuyết mỏng trên dàn lạnh Panasonic', 300000, 90),
(993, 100, 'Kêu êm Panasonic', 'Tiếng kêu đặc trưng êm của Panasonic', 250000, 75),
(994, 100, 'Nước đọng hệ thống Panasonic', 'Nước đọng trong hệ thống làm lạnh Panasonic', 200000, 75),
(995, 100, 'Làm lạnh Panasonic không đều', 'Nhiệt độ dao động không đều', 350000, 75),
(996, 100, 'Lỗi cảm biến Panasonic', 'Cảm biến nhiệt độ Panasonic hoạt động sai', 280000, 75),
(997, 100, 'Đèn chiếu sáng Panasonic cháy', 'Đèn chiếu sáng Panasonic bị cháy', 100000, 75),
(998, 100, 'Block Panasonic chạy không ổn', 'Máy nén Panasonic hoạt động không ổn định', 420000, 75),
(999, 100, 'Rò gas hệ thống Panasonic', 'Hệ thống gas Panasonic bị rò rỉ', 450000, 120),
(1000, 100, 'Tự ngắt Panasonic', 'Tủ Panasonic tự động ngắt không rõ lý do', 380000, 75),
(1001, 101, 'Không lạnh', 'Tủ Digital Inverter Samsung không làm lạnh', 480000, 120),
(1002, 101, 'Đóng tuyết digital inverter', 'Hệ thống digital inverter bị đóng tuyết', 420000, 90),
(1003, 101, 'Kêu rung digital', 'Máy nén digital inverter phát ra tiếng rung', 380000, 75),
(1004, 101, 'Nước rỉ từ hệ thống digital', 'Hệ thống làm lạnh digital bị rỉ nước', 320000, 75),
(1005, 101, 'Digital compressor lỗi', 'Máy nén digital inverter bị hỏng', 620000, 75),
(1006, 101, 'Lỗi board digital inverter Samsung', 'Board mạch digital inverter Samsung bị lỗi', 580000, 75),
(1007, 101, 'Cửa tủ digital bị hở', 'Cửa tủ không đóng khít hệ thống digital', 250000, 75),
(1008, 101, 'Quạt dàn lạnh digital kêu', 'Quạt hệ thống digital phát ra tiếng ồn', 380000, 60),
(1009, 101, 'Rò gas digital inverter', 'Hệ thống gas digital inverter bị rò rỉ', 520000, 120),
(1010, 101, 'Tự động báo lỗi digital', 'Hệ thống digital inverter báo lỗi liên tục', 480000, 75),
(1011, 102, 'Không lạnh', 'Tủ Family Hub không làm lạnh', 550000, 120),
(1012, 102, 'Đóng tuyết màn hình cảm ứng', 'Màn hình cảm ứng Family Hub bị đóng tuyết', 500000, 90),
(1013, 102, 'Kêu từ hệ thống thông minh', 'Hệ thống thông minh Family Hub phát ra tiếng ồn', 450000, 75),
(1014, 102, 'Nước rỉ từ camera', 'Camera bên trong bị rỉ nước', 400000, 75),
(1015, 102, 'Family Hub không hoạt động', 'Màn hình cảm ứng thông minh ngừng hoạt động', 520000, 75),
(1016, 102, 'Lỗi board Family Hub', 'Board điều khiển hệ thống thông minh bị lỗi', 700000, 75),
(1017, 102, 'Màn hình cảm ứng bị nứt', 'Màn hình Family Hub bị nứt, vỡ', 350000, 75),
(1018, 102, 'Camera hỏng', 'Camera quan sát thực phẩm bị hỏng', 420000, 75),
(1019, 102, 'Rò gas hệ thống cao cấp', 'Hệ thống gas cao cấp Family Hub bị rò rỉ', 580000, 120),
(1020, 102, 'Tự động báo lỗi Family Hub', 'Hệ thống Family Hub báo lỗi liên tục', 550000, 75),
(1021, 103, 'Không lạnh', 'Tủ Side by Side Samsung không làm lạnh', 600000, 120),
(1022, 103, 'Đóng tuyết hai buồng Samsung', 'Cả hai buồng đều bị đóng tuyết nặng', 520000, 90),
(1023, 103, 'Kêu từ hệ thống kép Samsung', 'Hệ thống làm lạnh kép Samsung phát ra tiếng ồn', 480000, 75),
(1024, 103, 'Nước rỉ từ icemaker Samsung', 'Máy làm đá Samsung bị rỉ nước', 420000, 75),
(1025, 103, 'Icemaker Samsung không hoạt động', 'Máy làm đá tự động Samsung ngừng hoạt động', 550000, 75),
(1026, 103, 'Lỗi board điều khiển đôi Samsung', 'Board điều khiển hai buồng Samsung bị lỗi', 720000, 75),
(1027, 103, 'Cửa kính Samsung bị hở', 'Cửa kính side by side Samsung không đóng khít', 350000, 75),
(1028, 103, 'Quạt đôi Samsung không đồng bộ', 'Hai quạt dàn lạnh Samsung hoạt động không đồng bộ', 500000, 60),
(1029, 103, 'Rò gas hệ thống kép Samsung', 'Hệ thống gas kép Samsung bị rò rỉ', 650000, 120),
(1030, 103, 'Mất nguồn side by side Samsung', 'Tủ side by side Samsung mất nguồn liên tục', 580000, 75),
(1031, 104, 'Không lạnh', 'Tủ RT Series không làm lạnh', 420000, 120),
(1032, 104, 'Đóng tuyết RT system', 'Hệ thống làm lạnh RT bị đóng tuyết', 350000, 90),
(1033, 104, 'Kêu đặc trưng RT', 'Tiếng kêu đặc trưng của dòng RT', 300000, 75),
(1034, 104, 'Nước rỉ từ hệ thống RT', 'Hệ thống làm lạnh RT bị rỉ nước', 250000, 75),
(1035, 104, 'Hệ thống RT không ổn định', 'Hệ thống làm lạnh RT hoạt động không ổn định', 380000, 75),
(1036, 104, 'Lỗi board RT series', 'Board điều khiển dòng RT bị lỗi', 480000, 75),
(1037, 104, 'Cửa tủ RT bị lệch', 'Cửa tủ RT series đóng không khít', 200000, 75),
(1038, 104, 'Quạt RT series kêu', 'Quạt hệ thống RT phát ra tiếng ồn', 320000, 60),
(1039, 104, 'Rò gas hệ thống RT', 'Hệ thống gas RT bị rò rỉ', 450000, 120),
(1040, 104, 'Tự động báo lỗi RT', 'Hệ thống RT báo lỗi liên tục', 400000, 75),
(1041, 105, 'Không lạnh', 'Tủ Samsung không làm lạnh', 400000, 120),
(1042, 105, 'Đóng tuyết nhẹ Samsung', 'Đóng tuyết mỏng trên dàn lạnh Samsung', 320000, 90),
(1043, 105, 'Kêu êm Samsung', 'Tiếng kêu đặc trưng êm của Samsung', 280000, 75),
(1044, 105, 'Nước đọng hệ thống Samsung', 'Nước đọng trong hệ thống làm lạnh Samsung', 220000, 75),
(1045, 105, 'Làm lạnh Samsung không đều', 'Nhiệt độ dao động không đều', 360000, 75),
(1046, 105, 'Lỗi cảm biến Samsung', 'Cảm biến nhiệt độ Samsung hoạt động sai', 300000, 75),
(1047, 105, 'Đèn chiếu sáng Samsung cháy', 'Đèn chiếu sáng Samsung bị cháy', 120000, 75),
(1048, 105, 'Block Samsung chạy không ổn', 'Máy nén Samsung hoạt động không ổn định', 440000, 75),
(1049, 105, 'Rò gas hệ thống Samsung', 'Hệ thống gas Samsung bị rò rỉ', 480000, 120),
(1050, 105, 'Tự ngắt Samsung', 'Tủ Samsung tự động ngắt không rõ lý do', 420000, 75),
(1051, 106, 'Không lạnh', 'Tủ Inverter Sharp không làm lạnh', 450000, 120),
(1052, 106, 'Đóng tuyết inverter Sharp', 'Hệ thống inverter Sharp bị đóng tuyết', 380000, 90),
(1053, 106, 'Kêu rung inverter Sharp', 'Máy nén inverter Sharp phát ra tiếng rung', 320000, 75),
(1054, 106, 'Nước rỉ từ hệ thống inverter Sharp', 'Hệ thống làm lạnh inverter Sharp bị rỉ nước', 280000, 75),
(1055, 106, 'Inverter compressor Sharp lỗi', 'Máy nén inverter Sharp bị hỏng', 580000, 75),
(1056, 106, 'Lỗi board inverter Sharp', 'Board mạch inverter Sharp bị lỗi', 520000, 75),
(1057, 106, 'Cửa tủ inverter Sharp bị hở', 'Cửa tủ không đóng khít hệ thống inverter Sharp', 230000, 75),
(1058, 106, 'Quạt dàn lạnh inverter Sharp kêu', 'Quạt hệ thống inverter Sharp phát ra tiếng ồn', 350000, 60),
(1059, 106, 'Rò gas inverter Sharp', 'Hệ thống gas inverter Sharp bị rò rỉ', 480000, 120),
(1060, 106, 'Tự động báo lỗi inverter Sharp', 'Hệ thống inverter Sharp báo lỗi liên tục', 420000, 75),
(1061, 107, 'Không lạnh', 'Tủ J-Tech Inverter không làm lạnh', 480000, 120),
(1062, 107, 'Đóng tuyết J-Tech', 'Hệ thống J-Tech bị đóng tuyết', 420000, 90),
(1063, 107, 'Kêu từ công nghệ J-Tech', 'Công nghệ J-Tech phát ra tiếng ồn', 380000, 75),
(1064, 107, 'Nước rỉ từ hệ thống J-Tech', 'Hệ thống làm lạnh J-Tech bị rỉ nước', 320000, 75),
(1065, 107, 'J-Tech compressor lỗi', 'Máy nén J-Tech bị hỏng', 600000, 75),
(1066, 107, 'Lỗi board J-Tech', 'Board điều khiển công nghệ J-Tech bị lỗi', 550000, 75),
(1067, 107, 'Cửa tủ J-Tech bị hở', 'Cửa tủ không đóng khít hệ thống J-Tech', 250000, 75),
(1068, 107, 'Quạt J-Tech hỏng', 'Quạt hệ thống J-Tech không hoạt động', 380000, 60),
(1069, 107, 'Rò gas J-Tech', 'Hệ thống gas J-Tech bị rò rỉ', 500000, 120),
(1070, 107, 'Tự động báo lỗi J-Tech', 'Hệ thống J-Tech báo lỗi liên tục', 450000, 75),
(1071, 108, 'Không lạnh', 'Tủ Side by Side Sharp không làm lạnh', 550000, 120),
(1072, 108, 'Đóng tuyết hai buồng Sharp', 'Cả hai buồng đều bị đóng tuyết nặng', 480000, 90),
(1073, 108, 'Kêu từ hệ thống kép Sharp', 'Hệ thống làm lạnh kép Sharp phát ra tiếng ồn', 420000, 75),
(1074, 108, 'Nước rỉ từ icemaker Sharp', 'Máy làm đá Sharp bị rỉ nước', 380000, 75),
(1075, 108, 'Icemaker Sharp không hoạt động', 'Máy làm đá tự động Sharp ngừng hoạt động', 500000, 75),
(1076, 108, 'Lỗi board điều khiển đôi Sharp', 'Board điều khiển hai buồng Sharp bị lỗi', 650000, 75),
(1077, 108, 'Cửa kính Sharp bị hở', 'Cửa kính side by side Sharp không đóng khít', 300000, 75),
(1078, 108, 'Quạt đôi Sharp không đồng bộ', 'Hai quạt dàn lạnh Sharp hoạt động không đồng bộ', 450000, 60),
(1079, 108, 'Rò gas hệ thống kép Sharp', 'Hệ thống gas kép Sharp bị rò rỉ', 580000, 120),
(1080, 108, 'Mất nguồn side by side Sharp', 'Tủ side by side Sharp mất nguồn liên tục', 500000, 75),
(1081, 109, 'Không lạnh', 'Tủ SJ Series không làm lạnh', 420000, 120),
(1082, 109, 'Đóng tuyết SJ system', 'Hệ thống làm lạnh SJ bị đóng tuyết', 350000, 90),
(1083, 109, 'Kêu đặc trưng SJ', 'Tiếng kêu đặc trưng của dòng SJ', 300000, 75),
(1084, 109, 'Nước rỉ từ hệ thống SJ', 'Hệ thống làm lạnh SJ bị rỉ nước', 250000, 75),
(1085, 109, 'Hệ thống SJ không ổn định', 'Hệ thống làm lạnh SJ hoạt động không ổn định', 380000, 75),
(1086, 109, 'Lỗi board SJ series', 'Board điều khiển dòng SJ bị lỗi', 480000, 75),
(1087, 109, 'Cửa tủ SJ bị lệch', 'Cửa tủ SJ series đóng không khít', 200000, 75),
(1088, 109, 'Quạt SJ series kêu', 'Quạt hệ thống SJ phát ra tiếng ồn', 320000, 60),
(1089, 109, 'Rò gas hệ thống SJ', 'Hệ thống gas SJ bị rò rỉ', 450000, 120),
(1090, 109, 'Tự động báo lỗi SJ', 'Hệ thống SJ báo lỗi liên tục', 400000, 75),
(1091, 110, 'Không lạnh', 'Tủ Sharp không làm lạnh', 380000, 120),
(1092, 110, 'Đóng tuyết nhẹ Sharp', 'Đóng tuyết mỏng trên dàn lạnh Sharp', 300000, 90),
(1093, 110, 'Kêu êm Sharp', 'Tiếng kêu đặc trưng êm của Sharp', 250000, 75),
(1094, 110, 'Nước đọng hệ thống Sharp', 'Nước đọng trong hệ thống làm lạnh Sharp', 200000, 75),
(1095, 110, 'Làm lạnh Sharp không đều', 'Nhiệt độ dao động không đều', 350000, 75),
(1096, 110, 'Lỗi cảm biến Sharp', 'Cảm biến nhiệt độ Sharp hoạt động sai', 280000, 75),
(1097, 110, 'Đèn chiếu sáng Sharp cháy', 'Đèn chiếu sáng Sharp bị cháy', 100000, 75),
(1098, 110, 'Block Sharp chạy không ổn', 'Máy nén Sharp hoạt động không ổn định', 420000, 75),
(1099, 110, 'Rò gas hệ thống Sharp', 'Hệ thống gas Sharp bị rò rỉ', 450000, 120),
(1100, 110, 'Tự ngắt Sharp', 'Tủ Sharp tự động ngắt không rõ lý do', 380000, 75),
(1101, 111, 'Không vắt', 'Máy giặt xong nhưng không vắt', 320000, 90),
(1102, 111, 'Không xả nước', 'Máy không xả nước sau khi giặt', 280000, 60),
(1103, 111, 'Kêu to khi vắt', 'Máy phát ra tiếng ồn lớn khi vắt', 350000, 45),
(1104, 111, 'Không cấp nước', 'Máy không lấy nước vào', 250000, 60),
(1105, 111, 'Lỗi board điều khiển', 'Board mạch điều khiển bị lỗi', 580000, 90),
(1106, 111, 'Rò rỉ nước', 'Nước rò rỉ từ đáy máy', 220000, 30),
(1107, 111, 'Không khởi động', 'Máy không phản ứng khi bật', 420000, 50),
(1108, 111, 'Mất cân bằng', 'Máy bị lệch, rung lắc mạnh', 180000, 50),
(1109, 111, 'Cửa không mở', 'Cửa máy giặt bị kẹt, không mở được', 200000, 50),
(1110, 111, 'Mùi hôi', 'Máy tỏa ra mùi khó chịu khi hoạt động', 150000, 25),
(1111, 112, 'Không vắt', 'Máy Twin Wash không vắt', 350000, 90),
(1112, 112, 'Không xả nước', 'Máy không xả nước hệ thống kép', 300000, 60),
(1113, 112, 'Kêu to hệ thống đôi', 'Cả hai hệ thống đều phát ra tiếng ồn', 400000, 45),
(1114, 112, 'Không cấp nước đôi', 'Cả hai ngăn không nhận nước', 280000, 60),
(1115, 112, 'Lỗi board Twin Wash', 'Board điều khiển hệ thống kép bị lỗi', 650000, 90),
(1116, 112, 'Rò rỉ nước từ hai ngăn', 'Nước rò rỉ từ cả hai buồng giặt', 250000, 30),
(1117, 112, 'Không khởi động Twin', 'Hệ thống Twin Wash không khởi động', 480000, 50),
(1118, 112, 'Mất cân bằng đôi', 'Cả hai buồng đều bị rung lắc', 220000, 50),
(1119, 112, 'Cửa phụ không mở', 'Cửa buồng giặt phụ bị kẹt', 180000, 50),
(1120, 112, 'Mùi hôi hệ thống kép', 'Cả hai buồng đều có mùi hôi', 180000, 25),
(1121, 113, 'Không vắt', 'Máy TurboWash không vắt', 380000, 90),
(1122, 113, 'Không xả nước nhanh', 'Chức năng giặt nhanh không xả nước', 320000, 60),
(1123, 113, 'Kêu to khi giặt nhanh', 'Máy ồn khi sử dụng TurboWash', 420000, 45),
(1124, 113, 'Không cấp nước Turbo', 'Không cấp nước cho chế độ giặt nhanh', 300000, 60),
(1125, 113, 'Lỗi board TurboWash', 'Board điều khiển TurboWash bị lỗi', 600000, 90),
(1126, 113, 'Rò rỉ nước vòi phun', 'Vòi phun Turbo bị rò rỉ nước', 280000, 30),
(1127, 113, 'Không khởi động Turbo', 'Chế độ giặt nhanh không hoạt động', 450000, 50),
(1128, 113, 'Rung lắc mạnh Turbo', 'Máy rung mạnh khi giặt nhanh', 250000, 45),
(1129, 113, 'Cửa không khóa Turbo', 'Cửa không tự động khóa khi giặt nhanh', 220000, 50),
(1130, 113, 'Mùi hôi sau giặt nhanh', 'Quần áo có mùi sau khi giặt nhanh', 200000, 25),
(1131, 114, 'Không vắt', 'Máy standard không vắt', 280000, 90),
(1132, 114, 'Không xả nước', 'Máy standard không xả nước', 240000, 60),
(1133, 114, 'Kêu to standard', 'Máy standard phát ra tiếng ồn', 320000, 45),
(1134, 114, 'Không cấp nước', 'Máy standard không nhận nước', 200000, 60),
(1135, 114, 'Lỗi board standard', 'Board điều khiển standard bị lỗi', 480000, 90),
(1136, 114, 'Rò rỉ nước standard', 'Nước rò rỉ từ máy standard', 180000, 30),
(1137, 114, 'Không khởi động standard', 'Máy standard không phản ứng', 380000, 50),
(1138, 114, 'Mất cân bằng standard', 'Máy standard bị rung lắc', 150000, 50),
(1139, 114, 'Cửa không mở standard', 'Cửa máy standard bị kẹt', 160000, 50),
(1140, 114, 'Mùi hôi standard', 'Máy standard có mùi hôi', 120000, 25),
(1141, 115, 'Không vắt', 'Máy giặt LG không vắt', 300000, 90),
(1142, 115, 'Không xả nước', 'Máy LG không xả nước', 260000, 60),
(1143, 115, 'Kêu to', 'Máy LG phát ra tiếng ồn', 340000, 45),
(1144, 115, 'Không cấp nước', 'Máy LG không nhận nước', 220000, 60),
(1145, 115, 'Lỗi board LG', 'Board mạch LG bị lỗi', 520000, 90),
(1146, 115, 'Rò rỉ nước LG', 'Nước rò rỉ từ máy LG', 200000, 30),
(1147, 115, 'Không khởi động LG', 'Máy LG không phản ứng', 400000, 50),
(1148, 115, 'Mất cân bằng LG', 'Máy LG bị rung lắc', 170000, 50),
(1149, 115, 'Cửa không mở LG', 'Cửa máy LG bị kẹt', 180000, 50),
(1150, 115, 'Mùi hôi LG', 'Máy LG có mùi hôi', 140000, 25),
(1151, 116, 'Không vắt', 'Máy UltimateCare không vắt', 350000, 90),
(1152, 116, 'Không xả nước', 'Máy không xả nước UltimateCare', 300000, 60),
(1153, 116, 'Kêu to UltimateCare', 'Máy UltimateCare phát ra tiếng ồn', 400000, 45),
(1154, 116, 'Không cấp nước Ultimate', 'Máy không nhận nước UltimateCare', 280000, 60),
(1155, 116, 'Lỗi board UltimateCare', 'Board điều khiển UltimateCare bị lỗi', 620000, 90),
(1156, 116, 'Rò rỉ nước Ultimate', 'Nước rò rỉ từ máy UltimateCare', 250000, 30),
(1157, 116, 'Không khởi động Ultimate', 'Máy UltimateCare không khởi động', 480000, 50),
(1158, 116, 'Mất cân bằng Ultimate', 'Máy UltimateCare bị rung lắc', 200000, 50),
(1159, 116, 'Cửa không mở Ultimate', 'Cửa máy UltimateCare bị kẹt', 220000, 50),
(1160, 116, 'Mùi hôi UltimateCare', 'Máy UltimateCare có mùi hôi', 180000, 25),
(1161, 117, 'Không vắt', 'Máy Inverter Electrolux không vắt', 380000, 90),
(1162, 117, 'Không xả nước inverter', 'Máy inverter không xả nước', 320000, 60),
(1163, 117, 'Kêu to inverter', 'Máy inverter Electrolux ồn', 420000, 45),
(1164, 117, 'Không cấp nước inverter', 'Máy inverter không nhận nước', 300000, 60),
(1165, 117, 'Lỗi board inverter Electrolux', 'Board inverter Electrolux bị lỗi', 580000, 90),
(1166, 117, 'Rò rỉ nước inverter', 'Nước rò rỉ từ máy inverter', 280000, 30),
(1167, 117, 'Không khởi động inverter', 'Máy inverter không khởi động', 450000, 50),
(1168, 117, 'Mất cân bằng inverter', 'Máy inverter bị rung lắc', 220000, 50),
(1169, 117, 'Cửa không mở inverter', 'Cửa máy inverter bị kẹt', 240000, 50),
(1170, 117, 'Mùi hôi inverter', 'Máy inverter có mùi hôi', 200000, 25),
(1171, 118, 'Không vắt', 'Máy WaveTouch không vắt', 400000, 90),
(1172, 118, 'Không xả nước WaveTouch', 'Máy không xả nước công nghệ sóng', 350000, 60),
(1173, 118, 'Kêu to WaveTouch', 'Máy WaveTouch phát ra tiếng ồn', 450000, 45),
(1174, 118, 'Không cấp nước Wave', 'Máy không nhận nước WaveTouch', 320000, 60),
(1175, 118, 'Lỗi board WaveTouch', 'Board điều khiển WaveTouch bị lỗi', 600000, 90),
(1176, 118, 'Rò rỉ nước Wave', 'Nước rò rỉ từ máy WaveTouch', 300000, 30),
(1177, 118, 'Không khởi động Wave', 'Máy WaveTouch không khởi động', 500000, 50),
(1178, 118, 'Mất cân bằng Wave', 'Máy WaveTouch bị rung lắc', 250000, 50),
(1179, 118, 'Cửa không mở Wave', 'Cửa máy WaveTouch bị kẹt', 260000, 50),
(1180, 118, 'Mùi hôi WaveTouch', 'Máy WaveTouch có mùi hôi', 220000, 25),
(1181, 119, 'Không vắt', 'Máy EWW series không vắt', 320000, 90),
(1182, 119, 'Không xả nước EWW', 'Máy EWW không xả nước', 280000, 60),
(1183, 119, 'Kêu to EWW', 'Máy EWW phát ra tiếng ồn', 360000, 45),
(1184, 119, 'Không cấp nước EWW', 'Máy EWW không nhận nước', 240000, 60),
(1185, 119, 'Lỗi board EWW', 'Board điều khiển EWW bị lỗi', 520000, 90),
(1186, 119, 'Rò rỉ nước EWW', 'Nước rò rỉ từ máy EWW', 220000, 30),
(1187, 119, 'Không khởi động EWW', 'Máy EWW không phản ứng', 420000, 50),
(1188, 119, 'Mất cân bằng EWW', 'Máy EWW bị rung lắc', 180000, 50),
(1189, 119, 'Cửa không mở EWW', 'Cửa máy EWW bị kẹt', 200000, 50),
(1190, 119, 'Mùi hôi EWW', 'Máy EWW có mùi hôi', 160000, 25),
(1191, 120, 'Không vắt', 'Máy giặt Electrolux không vắt', 300000, 90),
(1192, 120, 'Không xả nước', 'Máy Electrolux không xả nước', 260000, 60),
(1193, 120, 'Kêu to', 'Máy Electrolux phát ra tiếng ồn', 340000, 45),
(1194, 120, 'Không cấp nước', 'Máy Electrolux không nhận nước', 220000, 60),
(1195, 120, 'Lỗi board Electrolux', 'Board mạch Electrolux bị lỗi', 500000, 90),
(1196, 120, 'Rò rỉ nước Electrolux', 'Nước rò rỉ từ máy Electrolux', 200000, 30),
(1197, 120, 'Không khởi động Electrolux', 'Máy Electrolux không phản ứng', 400000, 50),
(1198, 120, 'Mất cân bằng Electrolux', 'Máy Electrolux bị rung lắc', 170000, 50),
(1199, 120, 'Cửa không mở Electrolux', 'Cửa máy Electrolux bị kẹt', 180000, 50),
(1200, 120, 'Mùi hôi Electrolux', 'Máy Electrolux có mùi hôi', 140000, 25),
(1201, 121, 'Không vắt', 'Máy AddWash không vắt', 350000, 90),
(1202, 121, 'Không xả nước AddWash', 'Máy không xả nước cửa phụ', 300000, 60),
(1203, 121, 'Kêu to AddWash', 'Máy AddWash phát ra tiếng ồn', 400000, 45),
(1204, 121, 'Không cấp nước AddWash', 'Máy không nhận nước cửa phụ', 280000, 60),
(1205, 121, 'Lỗi board AddWash', 'Board điều khiển AddWash bị lỗi', 580000, 90),
(1206, 121, 'Rò rỉ nước cửa phụ', 'Nước rò rỉ từ cửa phụ AddWash', 250000, 30),
(1207, 121, 'Không khởi động AddWash', 'Máy AddWash không khởi động', 450000, 50),
(1208, 121, 'Mất cân bằng AddWash', 'Máy AddWash bị rung lắc', 200000, 50),
(1209, 121, 'Cửa phụ không mở', 'Cửa phụ AddWash bị kẹt', 180000, 50),
(1210, 121, 'Mùi hôi AddWash', 'Máy AddWash có mùi hôi', 160000, 25),
(1211, 122, 'Không vắt', 'Máy BubbleShot không vắt', 380000, 90),
(1212, 122, 'Không xả nước bong bóng', 'Máy không xả nước công nghệ bong bóng', 320000, 60),
(1213, 122, 'Kêu to BubbleShot', 'Máy BubbleShot phát ra tiếng ồn', 420000, 45),
(1214, 122, 'Không tạo bong bóng', 'Công nghệ bong bóng không hoạt động', 350000, 50),
(1215, 122, 'Lỗi board BubbleShot', 'Board điều khiển BubbleShot bị lỗi', 600000, 90),
(1216, 122, 'Rò rỉ nước bong bóng', 'Nước rò rỉ từ hệ thống bong bóng', 280000, 30),
(1217, 122, 'Không khởi động Bubble', 'Máy BubbleShot không khởi động', 480000, 50),
(1218, 122, 'Mất cân bằng Bubble', 'Máy BubbleShot bị rung lắc', 220000, 50),
(1219, 122, 'Cửa không mở Bubble', 'Cửa máy BubbleShot bị kẹt', 240000, 50),
(1220, 122, 'Mùi hôi BubbleShot', 'Máy BubbleShot có mùi hôi', 200000, 25),
(1221, 123, 'Không vắt', 'Máy Digital Inverter không vắt', 400000, 90),
(1222, 123, 'Không xả nước digital', 'Máy digital inverter không xả nước', 350000, 60),
(1223, 123, 'Kêu to digital inverter', 'Máy digital inverter phát ra tiếng ồn', 450000, 45),
(1224, 123, 'Không cấp nước digital', 'Máy digital inverter không nhận nước', 320000, 60),
(1225, 123, 'Lỗi board digital inverter', 'Board digital inverter bị lỗi', 650000, 90),
(1226, 123, 'Rò rỉ nước digital', 'Nước rò rỉ từ máy digital inverter', 300000, 30),
(1227, 123, 'Không khởi động digital', 'Máy digital inverter không khởi động', 500000, 50),
(1228, 123, 'Mất cân bằng digital', 'Máy digital inverter bị rung lắc', 250000, 50),
(1229, 123, 'Cửa không mở digital', 'Cửa máy digital inverter bị kẹt', 260000, 50),
(1230, 123, 'Mùi hôi digital inverter', 'Máy digital inverter có mùi hôi', 220000, 25),
(1231, 124, 'Không vắt', 'Máy QuickDrive không vắt', 420000, 90),
(1232, 124, 'Không xả nước nhanh', 'Máy không xả nước chế độ nhanh', 380000, 60),
(1233, 124, 'Kêu to QuickDrive', 'Máy QuickDrive phát ra tiếng ồn', 480000, 45),
(1234, 124, 'Không cấp nước nhanh', 'Máy không nhận nước chế độ nhanh', 350000, 60),
(1235, 124, 'Lỗi board QuickDrive', 'Board điều khiển QuickDrive bị lỗi', 680000, 90),
(1236, 124, 'Rò rỉ nước Quick', 'Nước rò rỉ từ máy QuickDrive', 320000, 30),
(1237, 124, 'Không khởi động Quick', 'Máy QuickDrive không khởi động', 520000, 50),
(1238, 124, 'Mất cân bằng Quick', 'Máy QuickDrive bị rung lắc', 280000, 50),
(1239, 124, 'Cửa không mở Quick', 'Cửa máy QuickDrive bị kẹt', 300000, 50),
(1240, 124, 'Mùi hôi QuickDrive', 'Máy QuickDrive có mùi hôi', 240000, 25),
(1241, 125, 'Không vắt', 'Máy giặt Samsung không vắt', 320000, 90),
(1242, 125, 'Không xả nước', 'Máy Samsung không xả nước', 280000, 60),
(1243, 125, 'Kêu to', 'Máy Samsung phát ra tiếng ồn', 360000, 45),
(1244, 125, 'Không cấp nước', 'Máy Samsung không nhận nước', 240000, 60),
(1245, 125, 'Lỗi board Samsung', 'Board mạch Samsung bị lỗi', 540000, 90),
(1246, 125, 'Rò rỉ nước Samsung', 'Nước rò rỉ từ máy Samsung', 220000, 30),
(1247, 125, 'Không khởi động Samsung', 'Máy Samsung không phản ứng', 420000, 50),
(1248, 125, 'Mất cân bằng Samsung', 'Máy Samsung bị rung lắc', 180000, 50),
(1249, 125, 'Cửa không mở Samsung', 'Cửa máy Samsung bị kẹt', 200000, 50),
(1250, 125, 'Mùi hôi Samsung', 'Máy Samsung có mùi hôi', 160000, 25),
(1251, 126, 'Không vắt', 'Máy Inverter Toshiba không vắt', 350000, 90),
(1252, 126, 'Không xả nước inverter', 'Máy inverter Toshiba không xả nước', 300000, 60),
(1253, 126, 'Kêu to inverter Toshiba', 'Máy inverter Toshiba phát ra tiếng ồn', 400000, 45),
(1254, 126, 'Không cấp nước inverter', 'Máy inverter Toshiba không nhận nước', 280000, 60),
(1255, 126, 'Lỗi board inverter Toshiba', 'Board inverter Toshiba bị lỗi', 580000, 90),
(1256, 126, 'Rò rỉ nước inverter', 'Nước rò rỉ từ máy inverter Toshiba', 250000, 30),
(1257, 126, 'Không khởi động inverter', 'Máy inverter Toshiba không khởi động', 450000, 50),
(1258, 126, 'Mất cân bằng inverter', 'Máy inverter Toshiba bị rung lắc', 200000, 50),
(1259, 126, 'Cửa không mở inverter', 'Cửa máy inverter Toshiba bị kẹt', 220000, 50),
(1260, 126, 'Mùi hôi inverter Toshiba', 'Máy inverter Toshiba có mùi hôi', 180000, 25),
(1261, 127, 'Không vắt', 'Máy Diamond Drum không vắt', 380000, 90),
(1262, 127, 'Không xả nước diamond', 'Máy diamond drum không xả nước', 320000, 60),
(1263, 127, 'Kêu to diamond drum', 'Máy diamond drum phát ra tiếng ồn', 420000, 45),
(1264, 127, 'Không cấp nước diamond', 'Máy diamond drum không nhận nước', 300000, 60),
(1265, 127, 'Lỗi board diamond drum', 'Board điều khiển diamond drum bị lỗi', 600000, 90),
(1266, 127, 'Rò rỉ nước diamond', 'Nước rò rỉ từ máy diamond drum', 280000, 30),
(1267, 127, 'Không khởi động diamond', 'Máy diamond drum không khởi động', 480000, 50),
(1268, 127, 'Mất cân bằng diamond', 'Máy diamond drum bị rung lắc', 220000, 50),
(1269, 127, 'Cửa không mở diamond', 'Cửa máy diamond drum bị kẹt', 240000, 50),
(1270, 127, 'Mùi hôi diamond drum', 'Máy diamond drum có mùi hôi', 200000, 25),
(1271, 128, 'Không vắt', 'Máy AW series không vắt', 320000, 90),
(1272, 128, 'Không xả nước AW', 'Máy AW series không xả nước', 280000, 60),
(1273, 128, 'Kêu to AW series', 'Máy AW series phát ra tiếng ồn', 360000, 45),
(1274, 128, 'Không cấp nước AW', 'Máy AW series không nhận nước', 240000, 60),
(1275, 128, 'Lỗi board AW series', 'Board điều khiển AW series bị lỗi', 520000, 90),
(1276, 128, 'Rò rỉ nước AW', 'Nước rò rỉ từ máy AW series', 220000, 30),
(1277, 128, 'Không khởi động AW', 'Máy AW series không phản ứng', 420000, 50),
(1278, 128, 'Mất cân bằng AW', 'Máy AW series bị rung lắc', 180000, 50),
(1279, 128, 'Cửa không mở AW', 'Cửa máy AW series bị kẹt', 200000, 50),
(1280, 128, 'Mùi hôi AW series', 'Máy AW series có mùi hôi', 160000, 25),
(1281, 129, 'Không vắt', 'Máy TW series không vắt', 340000, 90),
(1282, 129, 'Không xả nước TW', 'Máy TW series không xả nước', 300000, 60),
(1283, 129, 'Kêu to TW series', 'Máy TW series phát ra tiếng ồn', 380000, 45),
(1284, 129, 'Không cấp nước TW', 'Máy TW series không nhận nước', 260000, 60),
(1285, 129, 'Lỗi board TW series', 'Board điều khiển TW series bị lỗi', 540000, 90),
(1286, 129, 'Rò rỉ nước TW', 'Nước rò rỉ từ máy TW series', 240000, 30),
(1287, 129, 'Kh không khởi động TW', 'Máy TW series không phản ứng', 440000, 50),
(1288, 129, 'Mất cân bằng TW', 'Máy TW series bị rung lắc', 190000, 50),
(1289, 129, 'Cửa không mở TW', 'Cửa máy TW series bị kẹt', 210000, 50),
(1290, 129, 'Mùi hôi TW series', 'Máy TW series có mùi hôi', 170000, 25),
(1291, 130, 'Không vắt', 'Máy giặt Toshiba không vắt', 300000, 90),
(1292, 130, 'Không xả nước', 'Máy Toshiba không xả nước', 260000, 60),
(1293, 130, 'Kêu to', 'Máy Toshiba phát ra tiếng ồn', 340000, 45),
(1294, 130, 'Không cấp nước', 'Máy Toshiba không nhận nước', 220000, 60),
(1295, 130, 'Lỗi board Toshiba', 'Board mạch Toshiba bị lỗi', 500000, 90),
(1296, 130, 'Rò rỉ nước Toshiba', 'Nước rò rỉ từ máy Toshiba', 200000, 30),
(1297, 130, 'Không khởi động Toshiba', 'Máy Toshiba không phản ứng', 400000, 50);
INSERT INTO `banggiasuachua` (`maGia`, `maMau`, `tenLoi`, `moTa`, `gia`, `thoiGianSua`) VALUES
(1298, 130, 'Mất cân bằng Toshiba', 'Máy Toshiba bị rung lắc', 170000, 50),
(1299, 130, 'Cửa không mở Toshiba', 'Cửa máy Toshiba bị kẹt', 180000, 50),
(1300, 130, 'Mùi hôi Toshiba', 'Máy Toshiba có mùi hôi', 140000, 25),
(1301, 131, 'Không vắt', 'Máy Inverter Aqua không vắt', 320000, 90),
(1302, 131, 'Không xả nước inverter', 'Máy inverter Aqua không xả nước', 280000, 60),
(1303, 131, 'Kêu to inverter Aqua', 'Máy inverter Aqua phát ra tiếng ồn', 360000, 45),
(1304, 131, 'Không cấp nước inverter', 'Máy inverter Aqua không nhận nước', 240000, 60),
(1305, 131, 'Lỗi board inverter Aqua', 'Board inverter Aqua bị lỗi', 520000, 90),
(1306, 131, 'Rò rỉ nước inverter', 'Nước rò rỉ từ máy inverter Aqua', 220000, 30),
(1307, 131, 'Không khởi động inverter', 'Máy inverter Aqua không khởi động', 420000, 50),
(1308, 131, 'Mất cân bằng inverter', 'Máy inverter Aqua bị rung lắc', 180000, 50),
(1309, 131, 'Cửa không mở inverter', 'Cửa máy inverter Aqua bị kẹt', 200000, 50),
(1310, 131, 'Mùi hôi inverter Aqua', 'Máy inverter Aqua có mùi hôi', 160000, 25),
(1311, 132, 'Không vắt', 'Máy A-QDD không vắt', 340000, 90),
(1312, 132, 'Không xả nước QDD', 'Máy A-QDD không xả nước', 300000, 60),
(1313, 132, 'Kêu to A-QDD', 'Máy A-QDD phát ra tiếng ồn', 380000, 45),
(1314, 132, 'Không cấp nước QDD', 'Máy A-QDD không nhận nước', 260000, 60),
(1315, 132, 'Lỗi board A-QDD', 'Board điều khiển A-QDD bị lỗi', 540000, 90),
(1316, 132, 'Rò rỉ nước QDD', 'Nước rò rỉ từ máy A-QDD', 240000, 30),
(1317, 132, 'Không khởi động QDD', 'Máy A-QDD không khởi động', 440000, 50),
(1318, 132, 'Mất cân bằng QDD', 'Máy A-QDD bị rung lắc', 190000, 50),
(1319, 132, 'Cửa không mở QDD', 'Cửa máy A-QDD bị kẹt', 210000, 50),
(1320, 132, 'Mùi hôi A-QDD', 'Máy A-QDD có mùi hôi', 170000, 25),
(1321, 133, 'Không vắt', 'Máy standard Aqua không vắt', 280000, 90),
(1322, 133, 'Không xả nước standard', 'Máy standard Aqua không xả nước', 240000, 60),
(1323, 133, 'Kêu to standard Aqua', 'Máy standard Aqua phát ra tiếng ồn', 320000, 45),
(1324, 133, 'Không cấp nước standard', 'Máy standard Aqua không nhận nước', 200000, 60),
(1325, 133, 'Lỗi board standard Aqua', 'Board standard Aqua bị lỗi', 480000, 90),
(1326, 133, 'Rò rỉ nước standard', 'Nước rò rỉ từ máy standard Aqua', 180000, 30),
(1327, 133, 'Không khởi động standard', 'Máy standard Aqua không phản ứng', 380000, 50),
(1328, 133, 'Mất cân bằng standard', 'Máy standard Aqua bị rung lắc', 150000, 50),
(1329, 133, 'Cửa không mở standard', 'Cửa máy standard Aqua bị kẹt', 160000, 50),
(1330, 133, 'Mùi hôi standard Aqua', 'Máy standard Aqua có mùi hôi', 120000, 25),
(1331, 134, 'Không vắt', 'Máy AQUA series không vắt', 300000, 90),
(1332, 134, 'Không xả nước AQUA', 'Máy AQUA series không xả nước', 260000, 60),
(1333, 134, 'Kêu to AQUA series', 'Máy AQUA series phát ra tiếng ồn', 340000, 45),
(1334, 134, 'Không cấp nước AQUA', 'Máy AQUA series không nhận nước', 220000, 60),
(1335, 134, 'Lỗi board AQUA series', 'Board điều khiển AQUA series bị lỗi', 500000, 90),
(1336, 134, 'Rò rỉ nước AQUA', 'Nước rò rỉ từ máy AQUA series', 200000, 30),
(1337, 134, 'Không khởi động AQUA', 'Máy AQUA series không phản ứng', 400000, 50),
(1338, 134, 'Mất cân bằng AQUA', 'Máy AQUA series bị rung lắc', 170000, 50),
(1339, 134, 'Cửa không mở AQUA', 'Cửa máy AQUA series bị kẹt', 180000, 50),
(1340, 134, 'Mùi hôi AQUA series', 'Máy AQUA series có mùi hôi', 140000, 25),
(1341, 135, 'Không vắt', 'Máy giặt Aqua không vắt', 260000, 90),
(1342, 135, 'Không xả nước', 'Máy Aqua không xả nước', 220000, 60),
(1343, 135, 'Kêu to', 'Máy Aqua phát ra tiếng ồn', 300000, 45),
(1344, 135, 'Không cấp nước', 'Máy Aqua không nhận nước', 180000, 60),
(1345, 135, 'Lỗi board Aqua', 'Board mạch Aqua bị lỗi', 460000, 90),
(1346, 135, 'Rò rỉ nước Aqua', 'Nước rò rỉ từ máy Aqua', 160000, 30),
(1347, 135, 'Không khởi động Aqua', 'Máy Aqua không phản ứng', 360000, 50),
(1348, 135, 'Mất cân bằng Aqua', 'Máy Aqua bị rung lắc', 130000, 50),
(1349, 135, 'Cửa không mở Aqua', 'Cửa máy Aqua bị kẹt', 140000, 50),
(1350, 135, 'Mùi hôi Aqua', 'Máy Aqua có mùi hôi', 100000, 25),
(1351, 136, 'Không vắt', 'Máy Inverter Panasonic không vắt', 350000, 90),
(1352, 136, 'Không xả nước inverter', 'Máy inverter Panasonic không xả nước', 300000, 60),
(1353, 136, 'Kêu to inverter Panasonic', 'Máy inverter Panasonic phát ra tiếng ồn', 400000, 45),
(1354, 136, 'Không cấp nước inverter', 'Máy inverter Panasonic không nhận nước', 280000, 60),
(1355, 136, 'Lỗi board inverter Panasonic', 'Board inverter Panasonic bị lỗi', 580000, 90),
(1356, 136, 'Rò rỉ nước inverter', 'Nước rò rỉ từ máy inverter Panasonic', 250000, 30),
(1357, 136, 'Không khởi động inverter', 'Máy inverter Panasonic không khởi động', 450000, 50),
(1358, 136, 'Mất cân bằng inverter', 'Máy inverter Panasonic bị rung lắc', 200000, 50),
(1359, 136, 'Cửa không mở inverter', 'Cửa máy inverter Panasonic bị kẹt', 220000, 50),
(1360, 136, 'Mùi hôi inverter Panasonic', 'Máy inverter Panasonic có mùi hôi', 180000, 25),
(1361, 137, 'Không vắt', 'Máy NA series không vắt', 320000, 90),
(1362, 137, 'Không xả nước NA', 'Máy NA series không xả nước', 280000, 60),
(1363, 137, 'Kêu to NA series', 'Máy NA series phát ra tiếng ồn', 360000, 45),
(1364, 137, 'Không cấp nước NA', 'Máy NA series không nhận nước', 240000, 60),
(1365, 137, 'Lỗi board NA series', 'Board điều khiển NA series bị lỗi', 520000, 90),
(1366, 137, 'Rò rỉ nước NA', 'Nước rò rỉ từ máy NA series', 220000, 30),
(1367, 137, 'Không khởi động NA', 'Máy NA series không phản ứng', 420000, 50),
(1368, 137, 'Mất cân bằng NA', 'Máy NA series bị rung lắc', 180000, 50),
(1369, 137, 'Cửa không mở NA', 'Cửa máy NA series bị kẹt', 200000, 50),
(1370, 137, 'Mùi hôi NA series', 'Máy NA series có mùi hôi', 160000, 25),
(1371, 138, 'Không vắt', 'Máy ActivFoam không vắt', 380000, 90),
(1372, 138, 'Không xả nước foam', 'Máy ActivFoam không xả nước bọt', 320000, 60),
(1373, 138, 'Kêu to ActivFoam', 'Máy ActivFoam phát ra tiếng ồn', 420000, 45),
(1374, 138, 'Không tạo bọt', 'Công nghệ tạo bọt không hoạt động', 350000, 50),
(1375, 138, 'Lỗi board ActivFoam', 'Board điều khiển ActivFoam bị lỗi', 600000, 90),
(1376, 138, 'Rò rỉ nước foam', 'Nước rò rỉ từ hệ thống tạo bọt', 280000, 30),
(1377, 138, 'Không khởi động foam', 'Máy ActivFoam không khởi động', 480000, 50),
(1378, 138, 'Mất cân bằng foam', 'Máy ActivFoam bị rung lắc', 220000, 50),
(1379, 138, 'Cửa không mở foam', 'Cửa máy ActivFoam bị kẹt', 240000, 50),
(1380, 138, 'Mùi hôi ActivFoam', 'Máy ActivFoam có mùi hôi', 200000, 25),
(1381, 139, 'Không vắt', 'Máy Top Load không vắt', 300000, 90),
(1382, 139, 'Không xả nước top load', 'Máy top load không xả nước', 260000, 60),
(1383, 139, 'Kêu to top load', 'Máy top load phát ra tiếng ồn', 340000, 45),
(1384, 139, 'Không cấp nước top load', 'Máy top load không nhận nước', 220000, 60),
(1385, 139, 'Lỗi board top load', 'Board điều khiển top load bị lỗi', 500000, 90),
(1386, 139, 'Rò rỉ nước top load', 'Nước rò rỉ từ máy top load', 200000, 30),
(1387, 139, 'Không khởi động top load', 'Máy top load không phản ứng', 400000, 50),
(1388, 139, 'Mất cân bằng top load', 'Máy top load bị rung lắc', 170000, 50),
(1389, 139, 'Nắp không mở top load', 'Nắp máy top load bị kẹt', 180000, 50),
(1390, 139, 'Mùi hôi top load', 'Máy top load có mùi hôi', 140000, 25),
(1391, 140, 'Không vắt', 'Máy giặt Panasonic không vắt', 280000, 90),
(1392, 140, 'Không xả nước', 'Máy Panasonic không xả nước', 240000, 60),
(1393, 140, 'Kêu to', 'Máy Panasonic phát ra tiếng ồn', 320000, 45),
(1394, 140, 'Không cấp nước', 'Máy Panasonic không nhận nước', 200000, 60),
(1395, 140, 'Lỗi board Panasonic', 'Board mạch Panasonic bị lỗi', 480000, 90),
(1396, 140, 'Rò rỉ nước Panasonic', 'Nước rò rỉ từ máy Panasonic', 180000, 30),
(1397, 140, 'Không khởi động Panasonic', 'Máy Panasonic không phản ứng', 380000, 50),
(1398, 140, 'Mất cân bằng Panasonic', 'Máy Panasonic bị rung lắc', 150000, 50),
(1399, 140, 'Cửa không mở Panasonic', 'Cửa máy Panasonic bị kẹt', 160000, 50),
(1400, 140, 'Mùi hôi Panasonic', 'Máy Panasonic có mùi hôi', 120000, 25);

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
-- Cấu trúc bảng cho bảng `chitietdondichvu`
--

CREATE TABLE `chitietdondichvu` (
  `maCTDon` int(11) NOT NULL,
  `maDon` int(11) NOT NULL,
  `id_nhanvien` int(11) DEFAULT NULL,
  `maThietBi` int(11) NOT NULL,
  `maHang` int(11) DEFAULT NULL,
  `maMau` int(11) NOT NULL,
  `phienban` text NOT NULL,
  `motaTinhTrang` varchar(255) NOT NULL,
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

INSERT INTO `chitietdondichvu` (`maCTDon`, `maDon`, `id_nhanvien`, `maThietBi`, `maHang`, `maMau`, `phienban`, `motaTinhTrang`, `trangThai`, `minhchung_den`, `minhchung_thietbi`, `minhchunghoanthanh`, `chuandoanKTV`, `baoGiaSC`, `gioBatDau`, `gioKetThuc`, `quyetDinhSC`, `lyDoTC`) VALUES
(39, 30, 4, 7, NULL, 0, 'Pa 1', 'Không hoạt động ', 0, '', '', '', '', 0, '', '', 0, ''),
(40, 30, 8, 2, NULL, 0, 'La 1', 'Không lạnh', 0, '', '', '', '', 0, '', '', 0, ''),
(41, 31, 4, 5, NULL, 0, 'A05', 'Nước không nóng', 0, '', '', '', '', 0, '', '', 0, ''),
(42, 32, 4, 1, NULL, 5, 'A03 2018', 'Rò rỉ nước, lạnh yếu', 2, 'minhchung_32_42_arrival_1761731897_6901e539cbcb8.jpeg', 'minhchung_32_42_device_1761731918_6901e54ea50af.jpeg', 'minhchung_32_42_completion_1762512623_690dceef747a7.png', 'abc', 1403333, '2025-11-24 02:57:58', '', 1, ''),
(43, 32, 4, 2, NULL, 0, 'A0', 'Không hoạt độbg, ', 3, 'minhchung_32_43_arrival_1761737766_6901fc261c7e7.jpeg', 'minhchung_32_43_device_1762438630_690cade63f2e9.jpg', '', 'abc', 700000, '2025-11-06 22:55:52', '2025-11-06 23:04:22', 1, ''),
(44, 33, NULL, 2, NULL, 0, 'AAAA', '123', 0, '', '', '', '', 0, '', '', 0, ''),
(45, 34, NULL, 2, NULL, 0, 'AAAA', 'ssasa', 0, '', '', '', '', 0, '', '', 0, ''),
(46, 35, NULL, 11, NULL, 0, 'AAAA', 'sasa', 0, '', '', '', '', 0, '', '', 0, ''),
(47, 36, NULL, 10, NULL, 0, 'sasa', 'sasa', 0, '', '', '', '', 0, '', '', 0, ''),
(48, 37, NULL, 10, NULL, 0, 'AAA1', 'saas', 0, '', '', '', '', 0, '', '', 0, ''),
(49, 38, NULL, 11, NULL, 0, 'hhh', 'ddd', 0, '', '', '', '', 0, '', '', 0, ''),
(50, 39, NULL, 10, NULL, 0, 'AAAA', 'aaas', 0, '', '', '', '', 0, '', '', 0, ''),
(51, 40, NULL, 11, NULL, 0, 'sss', 'sss', 0, '', '', '', '', 0, '', '', 0, ''),
(52, 41, NULL, 10, NULL, 0, 'aaaa', 'ssds', 0, '', '', '', '', 0, '', '', 0, ''),
(53, 42, NULL, 9, NULL, 0, 'Sony 911', '123', 0, '', '', '', '', 0, '', '', 0, ''),
(54, 43, NULL, 12, NULL, 0, 'â', 'â', 0, '', '', '', '', 0, '', '', 0, ''),
(55, 43, NULL, 11, NULL, 0, 'â', 'â', 0, '', '', '', '', 0, '', '', 0, ''),
(56, 44, NULL, 12, NULL, 0, 'Sony 911', '121', 0, '', '', '', '', 0, '', '', 0, ''),
(57, 44, NULL, 8, NULL, 0, '121', '121', 0, '', '', '', '', 0, '', '', 0, ''),
(58, 45, NULL, 2, NULL, 0, 'A', 'Â', 0, '', '', '', '', 0, '', '', 0, ''),
(59, 46, NULL, 11, NULL, 0, 'Sony 911', 'aa', 0, '', '', '', '', 0, '', '', 0, ''),
(60, 46, NULL, 10, NULL, 0, 'â', 'â', 0, '', '', '', '', 0, '', '', 0, ''),
(61, 47, 4, 11, NULL, 0, 'A00', 'hư òi', 0, '', '', '', '', 0, '', '', 0, ''),
(62, 47, NULL, 1, NULL, 0, 'hiha', '0909', 0, '', '', '', '', 0, '', '', 0, ''),
(63, 48, NULL, 11, NULL, 0, '123', '123', 0, '', '', '', '', 0, '', '', 0, ''),
(64, 49, NULL, 11, NULL, 0, 'aA', 'aA', 0, '', '', '', '', 0, '', '', 0, ''),
(65, 50, NULL, 1, NULL, 0, 'AA', 'AAA', 0, '', '', '', '', 0, '', '', 0, ''),
(66, 51, NULL, 11, NULL, 0, 'aa', 'aa', 0, '', '', '', '', 0, '', '', 0, ''),
(67, 52, 4, 12, NULL, 0, 'aa', 'aaa', 0, '', '', '', '', 0, '', '', 0, ''),
(68, 53, 3, 11, NULL, 0, 'aa', 'aa', 0, '', '', '', '', 0, '', '', 0, ''),
(69, 54, 3, 2, NULL, 0, 'aa', 'aa', 0, '', '', '', '', 0, '', '', 0, ''),
(70, 55, 4, 10, NULL, 0, '123', '123', 0, '', '', '', '', 0, '', '', 0, ''),
(71, 56, 4, 10, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(72, 57, 4, 8, NULL, 0, '123', '123', 0, '', '', '', '', 0, '', '', 0, ''),
(73, 58, 4, 11, NULL, 0, '123', '123', 0, '', '', '', '', 0, '', '', 0, ''),
(74, 59, 3, 2, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(75, 59, 3, 10, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(76, 59, 3, 11, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(77, 59, 8, 1, NULL, 0, '2', '2', 0, '', '', '', '', 0, '', '', 0, ''),
(78, 59, 8, 10, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(79, 59, 8, 11, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(80, 60, NULL, 8, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(81, 60, NULL, 10, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(82, 60, NULL, 12, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(83, 60, NULL, 9, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(84, 60, NULL, 8, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(85, 60, NULL, 11, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(86, 61, 4, 11, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(87, 61, 4, 11, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(88, 61, NULL, 11, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(89, 61, NULL, 11, NULL, 0, '2', '2', 0, '', '', '', '', 0, '', '', 0, ''),
(99, 65, NULL, 10, NULL, 0, '1', '11', 0, '', '', '', '', 0, '', '', 0, ''),
(100, 65, NULL, 1, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(101, 65, NULL, 10, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(102, 66, NULL, 1, NULL, 0, '1', '1', 0, '', '', '', '', 0, '', '', 0, ''),
(103, 67, NULL, 2, NULL, 0, 'AAAA', 'hư bo mạch không hoạt động được luôn', 0, '', '', '', '', 0, '', '', 0, ''),
(104, 68, NULL, 2, NULL, 0, 'AAA1 Panas', 'Không lạnh, điều khiển lên xuống độ không được', 0, '', '', '', '', 0, '', '', 0, ''),
(105, 69, NULL, 3, NULL, 0, 'A123 999', 'Sọc màng hình 15 sọc', 1, '', '', '', '', 0, '', '', 0, ''),
(106, 70, NULL, 3, NULL, 0, '212', 'caidiasnn ịadoajdo maokdoakda ', 1, '', '', '', '', 0, '', '', 0, ''),
(107, 72, NULL, 12, NULL, 0, 'AAA1', 'khoong hoat dongg ne', 1, '', '', '', '', 0, '', '', 0, ''),
(108, 73, NULL, 12, NULL, 0, 'hhh', 'abccni nsdnos mldmopa mdalmdla', 1, '', '', '', '', 0, '', '', 0, ''),
(109, 74, NULL, 6, NULL, 0, 'AAA1', 'khong hoat dong', 1, '', '', '', '', 0, '', '', 0, ''),
(110, 74, NULL, 10, NULL, 0, 'AAA1', 'Không hoạt động', 1, '', '', '', '', 0, '', '', 0, ''),
(111, 75, NULL, 3, NULL, 0, '2121', 'aajsoajosj joajodajdoajdoa', 1, '', '', '', '', 0, '', '', 0, ''),
(112, 76, NULL, 11, NULL, 0, '1212', 'qsqsqs dsdsdada', 3, 'minhchung_76_112_arrival_1763583921_691e27b1bbe96.jpg', 'minhchung_76_112_device_1763641973_691f0a75ad997.jpg', 'minhchung_76_112_completion_1763643374_691f0feeb4c74.jpg', 'adada', 333333, '2025-11-20 19:46:14', '2025-11-20 19:58:25', 1, ''),
(113, 77, NULL, 1, NULL, 0, 'â', '11', 1, '', '', '', '', 0, '', '', 0, ''),
(114, 81, NULL, 3, NULL, 0, 'asas', 'a1111111111111111111111111111', 1, '', '', '', '', 0, '', '', 0, ''),
(115, 82, NULL, 2, NULL, 0, 'Panasonic A00', 'Không hoạt động, có tiếng kêu', 3, 'minhchung_82_115_arrival_1763659325_691f4e3d48e92.jpg', 'minhchung_82_115_device_1763659331_691f4e4308e20.jpg', 'minhchung_82_115_completion_1763659843_691f5043546f7.jpg', 'Máy không hoạt động', 420000, '2025-11-21 00:28:23', '2025-11-21 00:30:58', 1, ''),
(116, 83, NULL, 11, NULL, 0, 'A05', 'không hoạt động', 1, '', '', '', '', 0, '', '', 0, ''),
(117, 84, NULL, 5, 30, 149, '', 'Không hoạt động', 1, '', '', '', '', 0, '', '', 0, ''),
(118, 85, NULL, 1, 9, 44, '', 'không hoạt động', 1, '', '', '', '', 0, '', '', 0, ''),
(119, 86, NULL, 1, 4, 20, '', 'không lên hình', 1, '', '', '', '', 0, '', '', 0, ''),
(120, 86, NULL, 2, 12, 56, '', 'không lên hình', 1, '', '', '', '', 0, '', '', 0, '');

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
  `loai` varchar(15) NOT NULL,
  `thoigian` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietsuachua`
--

INSERT INTO `chitietsuachua` (`maCTSuaChua`, `maDon`, `maThietBi`, `loiSuaChua`, `chiPhi`, `created_at`, `updated_at`, `maCTDon`, `loai`, `thoigian`) VALUES
(51, 32, '', 'Bảo dưỡng định kỳ tủ lạnh', 300000.00, '2025-10-29 10:02:24', '2025-10-29 10:02:24', 42, 'Báo giá', 0),
(52, 32, '', 'gan them ốc', 10000.00, '2025-10-29 10:02:24', '2025-10-29 10:02:24', 42, 'Báo giá', 0),
(53, 32, '', 'Chảy nước trong tủ - thông ống thoát nước', 150000.00, '2025-10-29 10:03:36', '2025-10-29 10:03:36', 42, 'Phát sinh', 0),
(54, 32, '', 'Bảo dưỡng máy lạnh định kỳ', 3000000.00, '2025-10-29 11:37:53', '2025-10-29 11:37:53', 43, 'Báo giá', 0),
(55, 32, '', 'Máy chạy không lạnh - thay block', 1.00, '2025-10-29 11:39:32', '2025-10-29 11:39:32', 43, 'Phát sinh', 0),
(56, 32, '', 'Bảo dưỡng máy lạnh định kỳ', 300000.00, '2025-11-06 14:40:47', '2025-11-06 14:40:47', 43, 'Báo giá', 0),
(57, 32, '', 'Máy chạy không lạnh - nạp gas', 700000.00, '2025-11-06 15:44:21', '2025-11-06 15:44:21', 43, 'Báo giá', 0),
(58, 76, '', 'Remote không điều khiển', 200000.00, '2025-11-19 20:25:41', '2025-11-19 20:25:41', 112, 'Báo giá', 0),
(59, 76, '', 'Máy không bật được', 180000.00, '2025-11-19 20:57:14', '2025-11-19 20:57:14', 112, 'Phát sinh', 0),
(60, 76, '', 'Đèn UV không sáng', 333333.00, '2025-11-19 20:59:51', '2025-11-19 20:59:51', 112, 'Phát sinh', 0),
(61, 76, '', 'Đèn UV không sáng', 333333.00, '2025-11-19 21:07:57', '2025-11-19 21:07:57', 112, 'Phát sinh', 0),
(62, 76, '', 'Máy không bật được', 222222.00, '2025-11-19 21:09:34', '2025-11-19 21:09:34', 112, 'Phát sinh', 0),
(63, 76, '', 'Máy không bật được', 222222.00, '2025-11-19 21:10:11', '2025-11-19 21:10:11', 112, 'Phát sinh', 0),
(64, 76, '', 'Máy không bật được', 222222.00, '2025-11-19 21:12:14', '2025-11-19 21:12:14', 112, 'Phát sinh', 0),
(65, 76, '', 'Máy không bật được', 333333.00, '2025-11-19 21:15:04', '2025-11-19 21:15:04', 112, 'Phát sinh', 0),
(66, 76, '', 'Máy không bật được', 222222.00, '2025-11-19 21:16:24', '2025-11-19 21:16:24', 112, 'Phát sinh', 0),
(67, 76, '', 'Không ra gió', 333333.00, '2025-11-19 21:18:11', '2025-11-19 21:18:11', 112, 'Phát sinh', 0),
(68, 76, '', 'Máy không bật được', 222222.00, '2025-11-19 21:18:59', '2025-11-19 21:18:59', 112, 'Phát sinh', 0),
(69, 76, '', 'Mùi hôi khó chịu', 222222.00, '2025-11-19 21:20:32', '2025-11-19 21:20:32', 112, 'Phát sinh', 0),
(70, 76, '', 'Máy không bật được', 222222.00, '2025-11-19 21:22:22', '2025-11-19 21:22:22', 112, 'Phát sinh', 0),
(71, 76, '', 'Quạt gió không quay', 333333.00, '2025-11-19 21:23:21', '2025-11-19 21:23:21', 112, 'Phát sinh', 0),
(72, 76, '', 'Quạt gió không quay', 333333.00, '2025-11-19 21:23:21', '2025-11-19 21:23:21', 112, 'Phát sinh', 0),
(73, 76, '', 'Motor quạt kêu to', 333333.00, '2025-11-19 21:24:21', '2025-11-19 21:24:21', 112, 'Phát sinh', 1),
(74, 76, '', 'Board điều khiển lỗi', 777777.00, '2025-11-19 21:37:56', '2025-11-19 21:37:56', 112, 'Báo giá', 2),
(75, 76, '', 'Vệ sinh máy lọc khí', 200000.00, '2025-11-20 09:27:24', '2025-11-20 09:27:24', 112, 'Báo giá', 60),
(76, 76, '', 'Vệ sinh máy lọc khí', 200000.00, '2025-11-20 09:49:02', '2025-11-20 09:49:02', 112, 'Báo giá', 60),
(77, 76, '', 'Đèn UV không sáng', 300000.00, '2025-11-20 09:58:22', '2025-11-20 09:58:22', 112, 'Báo giá', 75),
(78, 76, '', 'gắn vít', 1000.00, '2025-11-20 09:58:22', '2025-11-20 09:58:22', 112, 'Báo giá', 2),
(79, 76, '', 'Không lên nguồn', 333333.00, '2025-11-20 10:11:58', '2025-11-20 10:11:58', 112, 'Phát sinh', 75),
(80, 76, '', 'Quạt gió yếu', 333333.00, '2025-11-20 10:17:27', '2025-11-20 10:17:27', 112, 'Phát sinh', 90),
(81, 76, '', 'Thay ion âm', 555555.00, '2025-11-20 12:42:10', '2025-11-20 12:42:10', 112, 'Báo giá', 90),
(82, 76, '', 'Đèn UV không sáng', 333333.00, '2025-11-20 12:44:03', '2025-11-20 12:44:03', 112, 'Báo giá', 75),
(86, 32, '', 'Không khởi động', 450000.00, '2025-11-23 19:57:33', '2025-11-23 19:57:33', 42, 'Báo giá', 75),
(87, 32, '', 'Tự động tắt', 380000.00, '2025-11-23 19:57:33', '2025-11-23 19:57:33', 42, 'Báo giá', 75),
(88, 32, '', 'abc', 2222.00, '2025-11-23 19:57:33', '2025-11-23 19:57:33', 42, 'Báo giá', 1),
(89, 32, '', 'qqwqw', 111111.00, '2025-11-23 20:25:28', '2025-11-23 20:25:28', 42, 'Phát sinh', 1111);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia_ktv`
--

CREATE TABLE `danhgia_ktv` (
  `id` int(11) NOT NULL,
  `maDon` int(11) NOT NULL,
  `maKH` int(11) NOT NULL,
  `maKTV` int(11) NOT NULL,
  `diemDanhGia` int(11) NOT NULL CHECK (`diemDanhGia` between 1 and 5),
  `noiDungDanhGia` text DEFAULT NULL,
  `chuyenMon` tinyint(4) DEFAULT 0,
  `thaiDo` tinyint(4) DEFAULT 0,
  `dungGio` tinyint(4) DEFAULT 0,
  `hieuQua` tinyint(4) DEFAULT 0,
  `thoiGianDanhGia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhgia_ktv`
--

INSERT INTO `danhgia_ktv` (`id`, `maDon`, `maKH`, `maKTV`, `diemDanhGia`, `noiDungDanhGia`, `chuyenMon`, `thaiDo`, `dungGio`, `hieuQua`, `thoiGianDanhGia`) VALUES
(3, 71, 15, 4, 5, 'Oke', 1, 1, 1, 0, '2025-11-20 16:33:52'),
(4, 74, 15, 4, 3, 'oke ddos', 1, 1, 1, 1, '2025-11-20 16:35:41'),
(5, 76, 15, 4, 3, 'aaaa', 0, 1, 1, 0, '2025-11-20 16:37:20'),
(6, 82, 15, 26, 5, 'Oke', 0, 1, 1, 1, '2025-11-20 17:43:17');

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
  `maKhungGio` int(11) NOT NULL,
  `nhanVienTaoDon` int(11) NOT NULL,
  `thanhToan` int(11) NOT NULL,
  `tongTien` text NOT NULL,
  `diemSuDung` int(11) NOT NULL,
  `ngayThanhToan` text NOT NULL,
  `tienGiamGia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dondichvu`
--

INSERT INTO `dondichvu` (`maDon`, `maKH`, `diemhen`, `ngayTao`, `ngayDat`, `gioDat`, `ghiChu`, `trangThai`, `noiSuaChua`, `maKTV`, `maKhungGio`, `nhanVienTaoDon`, `thanhToan`, `tongTien`, `diemSuDung`, `ngayThanhToan`, `tienGiamGia`) VALUES
(31, 7, 'Số 1 Nguyễn Văn Bảo, Gò Vấp\r\n', '2025-10-20 17:56:37', '2025-10-21\r\n', 'chieu', 'Tới đúng hẹn', 0, 0, 0, 0, 0, 0, '', 0, '', 0),
(32, 7, '41 Lê Lợi, Phường 4, Gò Vấp', '2025-11-03 14:59:54', '2025-11-7', 'sang', 'Gọi điện cho tôi trước khi đến', 3, 0, 4, 0, 0, 1, '4460001.00', 0, '2025-11-19 22:12:13', 0),
(55, 7, 'aaa, Phường Thạnh Xuân, Quận 12, TP Hồ Chí Minh', '2025-11-11 15:34:43', '2025-11-14', '1', '123', 3, 0, 0, 1, 0, 0, '', 0, '', 0),
(67, 15, 'q, Phường Thạnh Xuân, Quận 12, TP Hồ Chí Minh', '2025-11-14 15:17:43', '2025-11-15', '', 'gọi trước khi đến', 0, 0, 0, 1, 0, 0, '', 0, '', 0),
(68, 15, '12, Phường Thạnh Xuân, Quận 12, TP Hồ Chí Minh', '2025-11-14 16:24:04', '2025-11-15', '', 'hehe', 1, 12, 4, 2, 0, 0, '', 0, '', 0),
(69, 15, '123, Phường Đông Hưng Thuận, Quận 12, TP Hồ Chí Minh', '2025-11-16 15:17:35', '2025-11-17', '', '123', 1, 123, 8, 2, 0, 0, '', 0, '', 0),
(70, 15, '1, Phường Thạnh Lộc, Quận 12, TP Hồ Chí Minh', '2025-11-16 15:21:30', '2025-11-17', '', 'abc', 1, 0, 8, 1, 0, 0, '', 0, '', 0),
(71, 7, '', '2025-11-17 16:25:05', '2025-11-17', '', 'Array', 1, 1, 99, 0, 0, 0, '', 0, '', 0),
(72, 15, 'hihi', '2025-11-18 09:46:18', '2025-11-18', '', 'abc', 1, 0, 3, 5, 0, 0, '', 0, '', 0),
(73, 15, '123, Lê Lợi, Gò Vấp, TP Hồ Chí Minh', '2025-11-18 10:16:28', '2025-11-18', '', 'abc', 1, 0, 3, 5, 0, 0, '', 0, '', 0),
(74, 24, '41/27, Phường 14, Quận Gò Vấp, TP Hồ Chí Minh, TP Hồ Chí Minh', '2025-11-18 11:14:25', '2025-11-19', '', 'gọi điện trước khi tới', 1, 0, 4, 1, 0, 0, '', 0, '', 0),
(75, 15, '123, Lê Lợi, Gò Vấp, TP Hồ Chí Minh', '2025-11-18 15:39:13', '2025-11-20', '', 'sas', 1, 0, 8, 1, 0, 0, '', 0, '', 0),
(76, 15, '123, Lê Lợi, Gò Vấp, TP Hồ Chí Minh', '2025-11-18 15:39:42', '2025-11-20', '', 'wqwqw', 4, 0, 4, 2, 0, 3, '7288216', 15, '2025-11-20 22:23:23', 15000),
(77, 15, '', '2025-11-19 17:11:24', '2025-11-20', '', 'Array', 1, 1, 0, 0, 0, 0, '', 0, '', 0),
(78, 15, '', '2025-11-19 17:14:47', '2025-11-20', '', 'Array', 1, 1, 0, 0, 0, 0, '', 0, '', 0),
(79, 15, '', '2025-11-19 17:18:24', '2025-11-20', '', 'Array', 1, 1, 0, 0, 0, 0, '', 0, '', 0),
(80, 15, '', '2025-11-19 17:20:20', '2025-11-20', '', 'Array', 1, 1, 0, 0, 0, 0, '', 0, '', 0),
(81, 15, '', '2025-11-19 17:27:26', '2025-11-20', '', '', 1, 1, 0, 0, 13, 0, '', 0, '', 0),
(83, 36, '123, Phường 12, Quận Gò Vấp, TP Hồ Chí Minh', '2025-11-21 11:38:55', '2025-11-21', '', '', 1, 0, 26, 1, 0, 0, '', 0, '', 0),
(84, 15, '123, Lê Lợi, Gò Vấp, TP Hồ Chí Minh', '2025-11-23 16:22:21', '2025-11-24', '', 'abc', 1, 123, 27, 1, 0, 0, '', 0, '', 0),
(85, 15, '123, Lê Lợi, Gò Vấp, TP Hồ Chí Minh', '2025-11-23 16:24:14', '2025-11-24', '', 'Không có', 1, 123, 27, 1, 0, 0, '', 0, '', 0),
(86, 15, '123, Lê Lợi, Gò Vấp, TP Hồ Chí Minh', '2025-11-23 16:50:20', '2025-11-24', '', '', 1, 0, 27, 1, 0, 0, '', 0, '', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hangsanxuat`
--

CREATE TABLE `hangsanxuat` (
  `maHang` int(11) NOT NULL,
  `tenHang` varchar(100) NOT NULL,
  `maThietBi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hangsanxuat`
--

INSERT INTO `hangsanxuat` (`maHang`, `tenHang`, `maThietBi`) VALUES
(1, 'Daikin', 1),
(2, 'Panasonic', 1),
(3, 'LG', 1),
(4, 'Samsung', 1),
(5, 'Casper', 1),
(6, 'Midea', 1),
(7, 'Toshiba', 1),
(8, 'Sharp', 1),
(9, 'Aqua', 1),
(10, 'Samsung', 2),
(11, 'LG', 2),
(12, 'Sony', 2),
(13, 'TCL', 2),
(14, 'Xiaomi', 2),
(15, 'Casper', 2),
(16, 'Panasonic', 2),
(17, 'Aqua', 3),
(18, 'Toshiba', 3),
(19, 'LG', 3),
(20, 'Panasonic', 3),
(21, 'Samsung', 3),
(22, 'Sharp', 3),
(23, 'LG', 4),
(24, 'Electrolux', 4),
(25, 'Samsung', 4),
(26, 'Toshiba', 4),
(27, 'Aqua', 4),
(28, 'Panasonic', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hosokythuatvien`
--

CREATE TABLE `hosokythuatvien` (
  `maHoSo` int(11) NOT NULL,
  `maKTV` int(11) NOT NULL,
  `maLLV` int(11) NOT NULL,
  `tbDanhGia` float NOT NULL,
  `soLuongDanhGia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hosokythuatvien`
--

INSERT INTO `hosokythuatvien` (`maHoSo`, `maKTV`, `maLLV`, `tbDanhGia`, `soLuongDanhGia`) VALUES
(1, 4, 1, 3.7, 3),
(2, 8, 1, 5, 1),
(3, 26, 1, 5, 1),
(4, 27, 2, 5, 1),
(5, 28, 1, 5, 1),
(6, 29, 2, 5, 1),
(7, 30, 1, 5, 1),
(8, 31, 2, 5, 1),
(9, 32, 1, 5, 1),
(10, 33, 2, 5, 1),
(11, 34, 1, 5, 1),
(12, 35, 2, 5, 1),
(13, 36, 1, 5, 1);

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
-- Cấu trúc bảng cho bảng `lichlamviec`
--

CREATE TABLE `lichlamviec` (
  `maLLV` int(11) NOT NULL,
  `tenLich` varchar(255) NOT NULL,
  `loaiLich` varchar(50) DEFAULT NULL,
  `ngayLamViec` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `ngayNghi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `moTa` text DEFAULT NULL,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngayCapNhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lichlamviec`
--

INSERT INTO `lichlamviec` (`maLLV`, `tenLich`, `loaiLich`, `ngayLamViec`, `ngayNghi`, `moTa`, `ngayTao`, `ngayCapNhat`) VALUES
(1, 'Lich 1 (Lam T3-T7)', '1: Lich 1', '2,3,4,5,6', '0,1', 'Lam viec tu Thu 3 den Thu 7, nghi Chu nhat va Thu 2', '2025-10-20 09:26:12', '2025-11-18 15:21:46'),
(2, 'Lich 2 (Lam T2-T5,CN)', '2: Lich 2', '1,2,3,4,7', '5,6', 'Lam viec Thu 2 den Thu 5 va Chu nhat, nghi Thu 6 va Thu 7', '2025-10-20 09:26:12', '2025-11-21 10:46:44');

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
(18, 4, '2025-11-15', '2025-10-29', '2025-10-29', 1.0, 'abc', 3, NULL, NULL, NULL, '2025-10-26 01:41:05'),
(19, 4, '', '2025-11-22', '2025-11-22', 1.0, '123', 0, NULL, NULL, NULL, '2025-11-21 02:12:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mausanpham`
--

CREATE TABLE `mausanpham` (
  `maMau` int(11) NOT NULL,
  `tenMau` varchar(150) NOT NULL,
  `maHang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `mausanpham`
--

INSERT INTO `mausanpham` (`maMau`, `tenMau`, `maHang`) VALUES
(1, 'FT Series', 1),
(2, 'Inverter FTK Series', 1),
(3, 'VRV System Series', 1),
(4, 'Cassette Series', 1),
(5, 'Mẫu khác', 1),
(6, 'CU/CS Series', 2),
(7, 'Deluxe Inverter', 2),
(8, 'Standard Series', 2),
(9, 'Premium Inverter', 2),
(10, 'Mẫu khác', 2),
(11, 'Standard Inverter', 3),
(12, 'Dual Cool Inverter', 3),
(13, 'Art Cool Gallery', 3),
(14, 'Multi V Series', 3),
(15, 'Mẫu khác', 3),
(16, 'Wind-Free Inverter', 4),
(17, 'Standard Inverter', 4),
(18, 'Cassette Series', 4),
(19, 'AR Series', 4),
(20, 'Mẫu khác', 4),
(21, 'Inverter Series', 5),
(22, 'Standard Series', 5),
(23, 'Pro Inverter', 5),
(24, 'Deluxe Series', 5),
(25, 'Mẫu khác', 5),
(26, 'Inverter Series', 6),
(27, 'Standard Series', 6),
(28, 'Titan Series', 6),
(29, 'Ultra Series', 6),
(30, 'Mẫu khác', 6),
(31, 'Inverter Series', 7),
(32, 'Standard Series', 7),
(33, 'Daiseikai Series', 7),
(34, 'RAS Series', 7),
(35, 'Mẫu khác', 7),
(36, 'Inverter Series', 8),
(37, 'Standard Series', 8),
(38, 'Plasmacluster Series', 8),
(39, 'AH Series', 8),
(40, 'Mẫu khác', 8),
(41, 'Inverter Series', 9),
(42, 'Standard Series', 9),
(43, 'Deluxe Inverter', 9),
(44, 'AQUA Series', 9),
(45, 'Mẫu khác', 9),
(46, 'QLED 4K Series', 10),
(47, 'Crystal UHD Series', 10),
(48, 'The Frame Series', 10),
(49, 'OLED Series', 10),
(50, 'Mẫu khác', 10),
(51, 'OLED Series', 11),
(52, 'NanoCell Series', 11),
(53, 'UHD 4K Series', 11),
(54, 'Smart TV Series', 11),
(55, 'Mẫu khác', 11),
(56, 'Bravia OLED Series', 12),
(57, 'Bravia XR Series', 12),
(58, 'Bravia 4K Series', 12),
(59, 'Android TV Series', 12),
(60, 'Mẫu khác', 12),
(61, 'C Series', 13),
(62, 'P Series', 13),
(63, 'X Series', 13),
(64, 'QLED Series', 13),
(65, 'Mẫu khác', 13),
(66, 'Mi TV 4 Series', 14),
(67, 'Mi TV Q Series', 14),
(68, 'Mi TV P Series', 14),
(69, 'Mi TV A Series', 14),
(70, 'Mẫu khác', 14),
(71, '4K Android Series', 15),
(72, 'Smart TV Series', 15),
(73, 'LED Series', 15),
(74, 'Pro Series', 15),
(75, 'Mẫu khác', 15),
(76, '4K OLED Series', 16),
(77, '4K LED Series', 16),
(78, 'Smart TV Series', 16),
(79, 'JX Series', 16),
(80, 'Mẫu khác', 16),
(81, 'Inverter Series', 17),
(82, 'Standard Series', 17),
(83, 'Side by Side', 17),
(84, 'AQUA Series', 17),
(85, 'Mẫu khác', 17),
(86, 'Inverter Series', 18),
(87, 'GR Series', 18),
(88, 'Glass Series', 18),
(89, 'Multi Door Series', 18),
(90, 'Mẫu khác', 18),
(91, 'Inverter Linear Series', 19),
(92, 'Side by Side Series', 19),
(93, 'DoorCool+ Series', 19),
(94, 'InstaView Series', 19),
(95, 'Mẫu khác', 19),
(96, 'Inverter Series', 20),
(97, 'Econavi Series', 20),
(98, 'Side by Side Series', 20),
(99, 'NR Series', 20),
(100, 'Mẫu khác', 20),
(101, 'Digital Inverter Series', 21),
(102, 'Family Hub Series', 21),
(103, 'Side by Side Series', 21),
(104, 'RT Series', 21),
(105, 'Mẫu khác', 21),
(106, 'Inverter Series', 22),
(107, 'J-Tech Inverter', 22),
(108, 'Side by Side Series', 22),
(109, 'SJ Series', 22),
(110, 'Mẫu khác', 22),
(111, 'Inverter Direct Drive', 23),
(112, 'Twin Wash Series', 23),
(113, 'TurboWash Series', 23),
(114, 'Standard Series', 23),
(115, 'Mẫu khác', 23),
(116, 'UltimateCare Series', 24),
(117, 'Inverter Series', 24),
(118, 'WaveTouch Series', 24),
(119, 'EWW Series', 24),
(120, 'Mẫu khác', 24),
(121, 'AddWash Series', 25),
(122, 'BubbleShot Series', 25),
(123, 'Digital Inverter Series', 25),
(124, 'QuickDrive Series', 25),
(125, 'Mẫu khác', 25),
(126, 'Inverter Series', 26),
(127, 'Diamond Drum Series', 26),
(128, 'AW Series', 26),
(129, 'TW Series', 26),
(130, 'Mẫu khác', 26),
(131, 'Inverter Series', 27),
(132, 'A-QDD Series', 27),
(133, 'Standard Series', 27),
(134, 'AQUA Series', 27),
(135, 'Mẫu khác', 27),
(136, 'Inverter Series', 28),
(137, 'NA Series', 28),
(138, 'ActivFoam Series', 28),
(139, 'Top Load Series', 28),
(140, 'Mẫu khác', 28);

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
  `diaChi` varchar(125) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `login_method` varchar(100) DEFAULT NULL,
  `maVaiTro` int(11) DEFAULT NULL,
  `trangThaiHD` int(11) NOT NULL,
  `ngaytao` text NOT NULL,
  `diemtichluy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`maND`, `hoTen`, `sdt`, `email`, `password`, `google_id`, `avatar`, `diaChi`, `created_at`, `login_method`, `maVaiTro`, `trangThaiHD`, `ngaytao`, `diemtichluy`) VALUES
(3, 'Kỹ thuật viên A', '0999999999', NULL, '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '', '2025-09-27 16:15:56', '', 3, 1, '', 0),
(4, 'Kỹ thuật viên B', '0000000000', NULL, '$2y$10$KFWXVALnKdOB51JQrrZySuq.17gihvGFh3SBn5nRfkS3CxzVVdqYO', NULL, NULL, '', '2025-09-27 16:24:53', 'normal', 3, 1, '', 0),
(7, 'Dat Nguyen', '0999999998', 'datnguyen.iclod@gmail.com', NULL, '108069514814137194980', NULL, '34, Phường Đa Kao, Quận 1, TP Hồ Chí Minh', '2025-09-28 11:50:40', 'google', 1, 1, '', 201),
(8, 'Tấn Đạt Nguyễn', '0999999995', 'nguyentandat250703@gmail.com', NULL, '112112315522809127983', NULL, '', '2025-09-28 11:52:47', 'google', 3, 1, '', 0),
(13, 'Nguyen A', '0999999991', NULL, '$2y$10$KFWXVALnKdOB51JQrrZySuq.17gihvGFh3SBn5nRfkS3CxzVVdqYO', NULL, NULL, '', '2025-10-07 17:34:01', 'normal', 2, 1, '', 0),
(14, 'Quản lý A', '0111111111', 'quanlya@gmail.com', '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '', '2025-10-12 12:34:57', 'normal', 4, 1, '', 0),
(15, 'Nguyễn Hi', '0909090909', 'test@example.com', '$2y$10$xk1EadxdLZsrJA1yTuQise8vmFmuMChdNcQVwxU8Asodyt0WxGsSC', NULL, NULL, '123, Lê Lợi, Gò Vấp, TP Hồ Chí Minh', '2025-11-05 18:38:49', 'normal', 1, 1, '', 50),
(16, 'Nguyễn Hi', '0909090908', NULL, '$2y$10$gQIxd9psBE/Zt8K03Yk96e3UrmE6Xuvdx344mavt3Rorc7Q7aVCsu', NULL, NULL, '', '2025-11-05 18:40:07', 'normal', 1, 0, '', 15),
(18, 'Nguyen Hi', '0333333333', '', '$2y$10$adp3XxiD6/MhZb.h9eamRuf6AgNqB5m5biEpuhWg4UNpC65bmKKFO', NULL, NULL, '', '2025-11-15 10:07:24', 'normal', 1, 1, '2025-11-15 17:07:24', 0),
(19, 'Trần Minh Hùng', '0912345678', 'tranminhhung@gmail.com', '123456', NULL, NULL, '123 Nguyễn Văn Lượng, P.10, Gò Vấp, TP.HCM', '2025-11-18 09:03:22', NULL, 6, 1, '', 0),
(20, 'Nguyễn Thị Mai', '0923456789', 'nguyenthimai@gmail.com', '123456', NULL, NULL, '45 Lê Quang Định, P.14, Bình Thạnh, TP.HCM', '2025-11-18 09:03:22', NULL, 6, 1, '', 0),
(21, 'Lê Văn Nam', '0934567890', 'levannam@gmail.com', '123456', NULL, NULL, '78 Quang Trung, P.11, Gò Vấp, TP.HCM', '2025-11-18 09:03:22', NULL, 6, 1, '', 0),
(22, 'Phạm Thu Hà', '0945678901', 'phamthuha@gmail.com', '123456', NULL, NULL, '56 Xô Viết Nghệ Tĩnh, P.25, Bình Thạnh, TP.HCM', '2025-11-18 09:03:22', NULL, 6, 1, '', 0),
(23, 'Hoàng Đức Anh', '0956789012', 'hoangducanh@gmail.com', '123456', NULL, NULL, '234 Phan Văn Trị, P.7, Gò Vấp, TP.HCM', '2025-11-18 09:03:22', NULL, 6, 1, '', 0),
(24, 'Nguyễn Tấn Hùng', '0393123123', 'tanhung@gmail.com', '$2y$10$7kGKqCYnOA4DkUDK.56Pk.ZoLk4d4vql8TuT20kACHxxI248gukra', NULL, NULL, '123, Phường 14, Quận 5, TP Hồ Chí Minh', '2025-11-18 10:45:27', 'normal', 1, 1, '2025-11-18 17:45:27', 0),
(25, 'oke la', '0999999994', 'abc@gmail.com', '$2y$10$Z69ybO/7oD9ajbK//7bE3OiRg4SW7MqeUMfv0RIbuSQvJA7xdbegC', NULL, NULL, '123, TP Hồ Chí Minh', '2025-11-19 16:50:51', NULL, 1, 1, '', 0),
(26, 'Nguyễn Văn An', '0901111111', 'nguyenvanan@gmail.com', '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '123 Nguyễn Văn Linh, Quận 7, TP.HCM', '2025-11-20 16:57:33', 'normal', 3, 1, '', 0),
(27, 'Trần Thị Bình', '0902222222', 'tranthibinh@gmail.com', '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '456 Lê Văn Việt, Quận 9, TP.HCM', '2025-11-20 16:57:33', 'normal', 3, 1, '', 0),
(28, 'Lê Văn Cường', '0903333333', 'levancuong@gmail.com', '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '789 Nguyễn Thị Minh Khai, Quận 1, TP.HCM', '2025-11-20 16:57:33', 'normal', 3, 1, '', 0),
(29, 'Phạm Thị Dung', '0904444444', 'phamthidung@gmail.com', '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '321 Cách Mạng Tháng 8, Quận 3, TP.HCM', '2025-11-20 16:57:33', 'normal', 3, 1, '', 0),
(30, 'Hoàng Văn Em', '0905555555', 'hoangvanem@gmail.com', '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '654 Lý Thường Kiệt, Quận 10, TP.HCM', '2025-11-20 16:57:33', 'normal', 3, 1, '', 0),
(31, 'Vũ Thị Phương', '0906666666', 'vuthiphuong@gmail.com', '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '987 Trường Chinh, Quận Tân Bình, TP.HCM', '2025-11-20 16:57:33', 'normal', 3, 1, '', 0),
(32, 'Đặng Văn Giang', '0907777777', 'dangvangiang@gmail.com', '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '147 Phan Văn Trị, Quận Gò Vấp, TP.HCM', '2025-11-20 16:57:33', 'normal', 3, 1, '', 0),
(33, 'Bùi Thị Hạnh', '0908888888', 'buithihanh@gmail.com', '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '258 Quang Trung, Quận Gò Vấp, TP.HCM', '2025-11-20 16:57:33', 'normal', 3, 1, '', 0),
(34, 'Mai Văn Hùng', '0909999999', 'maivanhung@gmail.com', '$2y$10$uUTV4uARxC0sZHkbrRmCNOAt9lsDozekSJDNByA/3XcTn5SboPTVG', NULL, NULL, '369 Lê Đức Thọ, Quận Gò Vấp, TP.HCM', '2025-11-20 16:57:33', 'normal', 3, 1, '', 0),
(35, 'Lý Thị Kim', '0901010101', 'lythikim@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, '741 Nguyễn Oanh, Quận Gò Vấp, TP.HCM', '2025-11-20 16:57:33', 'normal', 3, 1, '', 0),
(36, 'Nguyễn Văn Nam', '0933333333', 'vannam@gmail.com', '$2y$10$DDK26kT796M5UpYWt27CRetFSztY2AWKEyeu4BMrSEgVlnhYYbaVu', NULL, NULL, '123, Phường 12, Quận Gò Vấp, TP Hồ Chí Minh', '2025-11-21 11:36:37', 'normal', 1, 1, '2025-11-21 18:36:37', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `temp_payments`
--

CREATE TABLE `temp_payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `points_used` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `temp_payments`
--

INSERT INTO `temp_payments` (`id`, `order_id`, `customer_id`, `amount`, `points_used`, `created_at`, `updated_at`) VALUES
(19, 32, 7, 4387001.00, 73, '2025-11-19 22:17:17', '2025-11-19 22:17:17');

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
(1, 'Máy lạnh'),
(2, 'Tivi'),
(3, 'Tủ lạnh'),
(4, 'Máy giặt');

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
(3, 'Kỹ thuật viên lưu động'),
(6, 'Kỹ thuật viên tại chỗ'),
(5, 'Kỹ thuật viên trưởng'),
(2, 'Nhân viên'),
(4, 'Quản lý');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `banggiasc`
--
ALTER TABLE `banggiasc`
  ADD PRIMARY KEY (`maGia`);

--
-- Chỉ mục cho bảng `banggiasuachua`
--
ALTER TABLE `banggiasuachua`
  ADD PRIMARY KEY (`maGia`);

--
-- Chỉ mục cho bảng `bangkhunggio`
--
ALTER TABLE `bangkhunggio`
  ADD PRIMARY KEY (`maKhungGio`),
  ADD UNIQUE KEY `soThuTu` (`soThuTu`);

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
-- Chỉ mục cho bảng `danhgia_ktv`
--
ALTER TABLE `danhgia_ktv`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `maDon` (`maDon`);

--
-- Chỉ mục cho bảng `dondichvu`
--
ALTER TABLE `dondichvu`
  ADD PRIMARY KEY (`maDon`);

--
-- Chỉ mục cho bảng `hangsanxuat`
--
ALTER TABLE `hangsanxuat`
  ADD PRIMARY KEY (`maHang`);

--
-- Chỉ mục cho bảng `hosokythuatvien`
--
ALTER TABLE `hosokythuatvien`
  ADD PRIMARY KEY (`maHoSo`);

--
-- Chỉ mục cho bảng `hskythuatvien`
--
ALTER TABLE `hskythuatvien`
  ADD PRIMARY KEY (`maHS`);

--
-- Chỉ mục cho bảng `lichlamviec`
--
ALTER TABLE `lichlamviec`
  ADD PRIMARY KEY (`maLLV`);

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
-- Chỉ mục cho bảng `mausanpham`
--
ALTER TABLE `mausanpham`
  ADD PRIMARY KEY (`maMau`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`maND`),
  ADD UNIQUE KEY `phone` (`sdt`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`);

--
-- Chỉ mục cho bảng `temp_payments`
--
ALTER TABLE `temp_payments`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `banggiasc`
--
ALTER TABLE `banggiasc`
  MODIFY `maGia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT cho bảng `banggiasuachua`
--
ALTER TABLE `banggiasuachua`
  MODIFY `maGia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1401;

--
-- AUTO_INCREMENT cho bảng `bangkhunggio`
--
ALTER TABLE `bangkhunggio`
  MODIFY `maKhungGio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `chitietdondichvu`
--
ALTER TABLE `chitietdondichvu`
  MODIFY `maCTDon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT cho bảng `chitietsuachua`
--
ALTER TABLE `chitietsuachua`
  MODIFY `maCTSuaChua` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT cho bảng `danhgia_ktv`
--
ALTER TABLE `danhgia_ktv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `dondichvu`
--
ALTER TABLE `dondichvu`
  MODIFY `maDon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT cho bảng `hangsanxuat`
--
ALTER TABLE `hangsanxuat`
  MODIFY `maHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `hosokythuatvien`
--
ALTER TABLE `hosokythuatvien`
  MODIFY `maHoSo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `hskythuatvien`
--
ALTER TABLE `hskythuatvien`
  MODIFY `maHS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `lichlamviec`
--
ALTER TABLE `lichlamviec`
  MODIFY `maLLV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `lichphancong`
--
ALTER TABLE `lichphancong`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `lichxinnghi`
--
ALTER TABLE `lichxinnghi`
  MODIFY `maLichXN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `mausanpham`
--
ALTER TABLE `mausanpham`
  MODIFY `maMau` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `maND` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `temp_payments`
--
ALTER TABLE `temp_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `thietbi`
--
ALTER TABLE `thietbi`
  MODIFY `maThietBi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `vaitro`
--
ALTER TABLE `vaitro`
  MODIFY `maVaiTro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietsuachua`
--
ALTER TABLE `chitietsuachua`
  ADD CONSTRAINT `chitietsuachua_ibfk_1` FOREIGN KEY (`maDon`) REFERENCES `dondichvu` (`maDon`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`maVaiTro`) REFERENCES `vaitro` (`maVaiTro`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
