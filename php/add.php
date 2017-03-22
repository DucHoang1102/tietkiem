<?php 
function add($_POST_ADD) {
	$conn = connnect_database();

	// 3 hàm tương tự như 3 bảng trong csdl
	function items_table ($date, $items, $total_money, $conn) {
		// Dữ liệu sẽ được đưa đầu tiên vào đây và từ đây lưu vào bảng items
		// Mỗi lần người dùng cập nhật dữ liệu item: thêm, sửa, xóa
		// đồng thời cũng cập nhật lại trường all_moneys trong bảng thoigian

		$thoigian = thoigian_table($date, $conn);
		$total_money = $thoigian["all_moneys"] + $total_money;
		$date_id = $thoigian["date_id"];
		$sql_thoigian = "
			UPDATE thoigian 
			SET all_moneys = \"$total_money\"
			WHERE thoigian.date_id = $date_id
		";// Cập nhật tổng tiền cho bảng thoigian

		$conn->query($sql_thoigian);
		if($conn->error) die("Bị lỗi items_table: ". $conn->error);

		$date_id = $thoigian["date_id"];

		foreach ($items as $value) {
			$item_number = $value[0];
			$item_content = $value[1];
			$sql_items = "
				INSERT INTO items(item_number, item_content, date_id)
				VALUES(\"$item_number\", \"$item_content\", \"$date_id\");
			";// Cập nhật item chi tiêu cho bảng items
			$conn->query($sql_items);
			if($conn->error) die("Bị lỗi items_table: " . $conn->error);
		}
	}

	function thoigian_table ($date, $conn) {
		// Nhận vào date (ngày)
		// Kiểm tra ngày đã tồn tại trong csdl hay chưa bằng date_id (Khóa chính)

		// Cấu trúc date_id = 20170318 (y-m-d). Sử dụng cấu trúc này, những ngày
		// mới hơn sẽ ở đầu tiên phù hợp khi hiển thị dữ liệu, không mất công
		// converse lại nữa. Mặt khác chúng ta có thể chủ động tao khóa chính
		// date_id

		// Nếu chưa có thì tạo record - Lưu  ý bảng thời gian có chứa khóa ngoại
		// từ bảng thismonth. Kiểm tra dữ liệu trong bảng này trước
		// Sau cùng trả lại date_id và all_money(tổng tiền) trong csdl

		$date_id = substr($date, 0,4).substr($date, 5,2).substr($date, 8, 2);
		$sql = "
			SELECT date_id, all_moneys
			FROM thoigian
			WHERE thoigian.date_id = $date_id
		";
		
		$check = $conn->query($sql);

		if($check->num_rows > 0) {
			// Trường hợp ngày đã tồn tại(trong bảng thoigian)
			return $check->fetch_assoc();
		}
		else {
			// Trường hợp ngày chư tồn tại. Ta phải tạo mới ngày

			/* 
			+. Trước tiên kiểm tra record $thismonth_id(khóa ngoại đã tồn tại chưa) trong bảng thismonth.
		    +. Cấu trúc $thismonth_id = 201701, 201702, 201703,...
		    */
			$thismonth_id = (INT)substr($date, 0, 4).substr($date, 5, 2);
			$thismonth_id = thismonth_table($thismonth_id, $conn);


			$sql = "
				INSERT INTO thoigian(date_id, date_content, all_moneys, thismonth_id)
				VALUES($date_id, \"$date\", '0', \"$thismonth_id\");
			";
			$conn->query($sql);
			if ($conn->error) {
				die("Bị lỗi thoigian_table");
			}
			return array(
				'date_id' => $date_id,
				'all_moneys' => 0
			);
		}
	}

	function thismonth_table ($thismonth_id, $conn) {
		// Nhận vào $thismonth_id kiểm tra trong bảng thismonth
		// Nếu chưa có thì tạo record
		// Trả về $thismonth_id

		// Xử dụng connnect_database() của view.php
		$sql = "
			SELECT *
			FROM thismonth
			WHERE thismonth_id = $thismonth_id
		";
		$check = $conn->query($sql);
		if ($check->num_rows > 0) {
			// Trương hợp đã tồn tại thismonth_id

		}
		else{
			// Trường hợp chưa có sẽ tạo một record với thismonth_id - thismonth_content
			$thismonth_content = 'Tháng '.substr($thismonth_id, 4, 2).', '
									.substr($thismonth_id, 0, 4);
			$sql = "
			INSERT INTO thismonth(thismonth_id, thismonth_content)
			VALUES($thismonth_id, \"$thismonth_content\");
			";
			$conn->query($sql);
			if ($conn->error) die("Bị lỗi thismonth_table");
		}

		return $thismonth_id;
	}
 
	// Tại bảng thêm chi tiêu (add item)
	// Tách input của người dùng thành từng cặp input-money - input-content
	// <input name = "1-input-money"/> - <input name = "1-input-content"/>
	//<input  name = "2-input-money"/> - <input name = "2-input-content"/>
	$i = 1;
	$date = $_POST_ADD['data-select'];
	$items = array();
	$total_money = 0;

	foreach ($_POST_ADD as $key => $value) {
		if (isset($_POST_ADD[$i."-input-money"]) && 
			isset($_POST_ADD[$i."-input-content"]))
		{
			$input_money = $_POST_ADD[$i."-input-money"];
			$input_content = $_POST_ADD[$i."-input-content"];
			$item = array (
				$input_money,
				$input_content
			);
			$total_money = $total_money + $input_money;
			array_push($items, $item);
		};
		$i++;
	}
	items_table($date, $items, $total_money, $conn);

	$conn->close();
}
?>
