<?php
	function all_money_list_view () {
		// Tổng tiền theo truy suất của người dùng: Tổng tuần này, tổng tháng
		// này,...

		$vidu = array('1', '2', '3', '4');
		$tong = 0;
		foreach ($vidu as $value) {
			$tong = $tong + $value;
		}
		echo $tong;
	}
	all_money_list_view();
?>