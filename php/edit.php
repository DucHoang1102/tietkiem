<?php 
// Hàm edit gồm 2 hàm: 
// Hàm 1: Xóa toàn bộ item cũ của ngày, cập nhật lại tổng = 0
// Hàm 2: Cập nhật toàn bộ item mới, sử dụng hàm items của add.php
function edit($_POST_EDIT) {
	$conn = connnect_database();

	function delete_all($date_id, $conn) {
		// Xóa toàn bộ item có cùng ngày
		$sql_delete = "
			DELETE FROM items
			WHERE items.date_id = $date_id
		";
		$conn->query($sql_delete);

		// Cập nhật tổng tiền của ngày = 0
		$_sql_update_all_moneys = "
			UPDATE thoigian
			SET all_moneys = 0
			WHERE thoigian.date_id = $date_id
		";
		$conn->query($_sql_update_all_moneys);
	}

	// Date để sửa và conveser date -> date_id
	$date = $_POST_EDIT['date-select'];
	$date_id = substr($date, 0,4).substr($date, 5,2).substr($date, 8, 2);

	// Xóa hết item trong ngày và cập nhật tổng tiền = 0
	delete_all($date_id, $conn);
	// THêm các item mới
	add($_POST_EDIT);
	
	$conn->close();
}

?>