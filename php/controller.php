<?php
// Điều hướng những hành động của người dùng
// Cụ thể ở đây là Xem người dùng bấm nút gì để điều hướng cho phù hợp
// Xác định một số biến toàn cục. Chúng ta phải chú ý về phạm vi các cách làm 
// việc của chúng tránh bị lỗi sau này
// Dựa vào biến $_POST để xác định hành động của người dùng

require ("php/view.php");
require ("php/add.php");

$view_type = 'tatca'; // Kiểu hiển thị: tất cả, tuần này, tháng này,...
$title_view_total_money = 'Tất cả';
$result_view = Null; // Danh sách chứa items để hiển thị
// Nếu người dùng bấm OK hộp thoại chọn kiểu xem:
// tất cả, tuần này, tháng này,...
if (isset($_POST['ok-box-view'])){

	if (isset($_POST['view']))
	{
		$view_type = $_POST['view'];
		if ($view_type === 'thangnay') $title_view_total_money = 'Tháng này';
		else if ($view_type === 'tuannay') $title_view_total_money = 'Tuần này';
		else if ($view_type === 'date-to-date') {
			$title_view_total_money = 'Ngày đến ngày';
		}
		else $view_type = 'tatca';
	} 
}

// Nếu người dùng bấm nút OK hộp thoại thêm mới chi tiêu
if (isset($_POST['ok-add-items'])) {
	add($_POST);
}

// CẤU HÌNH SAU KHI ĐÃ CÓ CHƯC NĂNG SỬA ITEMS
if (isset($_POST['edit-items'])){

}

// HIỂN THỊ DỮ LIỆU RA TRANG CHỦ
$result_view = view($view_type);
?>