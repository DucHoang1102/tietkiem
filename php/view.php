<?php
function connnect_database() {
		//Kết nối database tietkiem
		$conn = new mysqli('localhost', 'root', '', 'tietkiem2');
		if($conn->connect_error){
			die('Kết nối database bị lỗi');
		}	
		$conn->set_charset('utf8');
		return $conn;
};


function view($view_by) {
	$conn = connnect_database();

	function converse_date($date, &$result) {
		// Chuyển định dạng y-m-d - > d-m-Y
		// Nếu là ngày hôm nay thay giá tri ngày bằng giá trị "Hôm nay"
		// Nếu có giá trị  hôm nay, lưu tổng tiền vào array $result cho dễ truy
		// xuất
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		$today = date('Y-m-d');
		if ($date['date_content'] === $today){
			$date['date_content'] = "Hôm nay";
			$result['total_money_today'] = (INT)$date['all_moneys'];
		}else{
			$date['date_content'] = 
			date('d-m-Y', strtotime($date['date_content']));
		}
		return $date;
	}

	function list_view($view_by, $conn){
		// Lưu danh sách date cần view vào biến $result
		// Cấu trúc dữ liệu kết quả trả về là mảng kết hợp
		// Có 4 kiểu: 1.Tất cả - 2.Tuần này - 3.Tháng này - 4.Từ ngày đến ngày
		$result = array();
		$total_money = 0;
		$sql = Null;
		date_default_timezone_set('Asia/Ho_Chi_Minh');

		if ($view_by === 'tuannay') {

		}
		elseif ($view_by === 'thangnay') {
			// Trả về 1 chuỗi dạng 12016 - id bảng tháng này trong cơ sở dữ 
			// liệu. Lấy tất cả các tháng trùng với tháng ngày hôm nay
			$thismonth_id = date('n').date('Y');
			$sql = "
				SELECT *
				FROM thoigian
				WHERE thoigian.thismonth_id = $thismonth_id
			";
		}
		elseif ($view_by === 'tungaydenngay') {
			# code...
		}
		else{
			$sql = "
				SELECT *
				FROM thoigian
			";
		}

		 $dates = $conn->query($sql);

		if ($dates->num_rows > 0){
			// Trường hợp có dư liệu trả về
			while ($date = $dates->fetch_assoc()) {
				// Dữ liệu trong một ngày gồm:
				/* 
				$date_items  = array(
									'date' => ngày,
									'item' => Các item chi tiêu trong ngày
								)
				$result sẽ lưu nhiều $date_items tùy theo truy xuất của 
				người dùng (tất cả, tuần này, tháng này).
				*/

				$date_items = array();

				$date_items['date'] = converse_date($date, $result);

				$result_item = items($date['date_id'], $conn);
				$date_items['item'] = $result_item;

				$total_money = $total_money + $date['all_moneys']; 

				array_push($result, $date_items);
			}
			$result['total_money'] = $total_money;
			return $result;
		}

		else{
			// Trường hợp không có dữ liêu ngày
			return 'empty';
		}
	}

	function items($date_id, $conn){
		// Truy xuất item (chi tiêu trong ngày) theo date_id
		// Gộp các item vào biến array $result_items
		$result_items = array();
		$sql = 
		"
			SELECT *
			FROM items
			WHERE items.date_id = $date_id
		";

		$items = $conn->query($sql);

		if($items->num_rows > 0){
			// Trường hợp có dữ liệu chi tiêu
			while ($item = $items->fetch_assoc()) {
				array_push($result_items, $item);
			}
			return $result_items;
		}
		else{
			// Trường hợp ko có dữ liệu item(chi tiêu trong ngày), return trống
			return $result_items;
		}
	}
	return list_view($view_by, $conn);
	// Ngắt kết nối database
	$conn->close();
}
//echo "<pre>";
//print_r(view('tatca'));
?>