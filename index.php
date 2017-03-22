<?php
	require ("php/controller.php");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<title>Quản lý thu chi cá nhân</title>
	<link rel="stylesheet" href="css/style.css"/>
	<script type="text/Javascript" src="js/jquery/jquery-3.1.1.min.js"></script>
	<script type="text/Javascript" src="js/js.js"></script>
</head>
<body>
	<div id="contains">
		<?php
			// Biến $result_view nằm trong php/controller.php
			if(isset($result_view['total_money'])) $total_money = $result_view['total_money'] . 'k';
			else $total_money = '0k';
		
			if(isset($result_view['total_money_today'])) $total_money_today = $result_view['total_money_today'] . 'k';
			else $total_money_today = '0k';

			echo 
				"<div id='view-total-money'>
					<span class='cash-money'>Hôm nay</span>
					<span class='cash-money-number'>$total_money_today</span>
					<span class='card-money'>$title_view_total_money</span>
					<span class='card-money-number'>$total_money</span>
				</div>

				<div id='history-deal'>";
				if ($result_view === 'empty'){
					echo '<span class="empty" style="font-weight:bold; font-style:italic;">Không có dữ liệu <span>';
				}
				else{
					foreach ($result_view as $item_date) {
						if ($item_date['date']){ 
							$date_content = $item_date['date']['date_content'];
							$money_total_in_date = $item_date['date']['all_moneys'].'k'; 
							echo 
								"<div class='items'>
									<span class='date-time'>$date_content</span>
									<span class='item-edit'><img src='images/icons/edit-pen-icon.png' alt='edit' ></span><br />";

							if (count($item_date['item']) > 0){
								foreach ($item_date['item'] as $items) {
									$money_number = $items['item_number'].'k';
									$money_content = $items['item_content'];
									echo 
										"<div class='money-item'> 
											<span class='money-number'>$money_number</span>
											<span><img src='images/icons/arrow-icon.png' alt='arrow-icon'width='15px' /></span>
											<span class='money-content'>$money_content</span>
										</div>";
								}
							}
							echo 
								"<div class='money-total'>
									<span class='money-number'>$money_total_in_date</span>
									<span><img src='images/icons/arrow-icon.png' alt='arrow-icon' width='15px' /></span>
									<span class='money-content'>Tổng</span>
								</div>
							</div>";
						}
					}
				}
			?>
		</div>

		<div id="functions">
			<span class="item-add"><img src="images/icons/item-add-icon.png" alt="item add"></span>
			<span class="view-select"><img src="images/icons/view-icon.png" alt="view"></span>
		</div>

		<div id="box-setting">
			<!--Hộp thoại thêm, sửa, xóa item-->
			<span id="box-title">Thêm mới chi tiêu</span>
			<img class="add-input-item" src="images/icons/add-icon.png" alt="add"/>
			<form action="" method="POST">
				<input type="date" class="date-select" name="data-select">
				<div class="warning">
					<img src="images/icons/warning-icon.png" alt="warning"/>
					<span><!--Nội dung thông báo--></span>
				</div>
				<div id="input-items"> 
					<!--Chứa các trường thêm items-->
				</div>
				<input class="huy" type="button" value="Hủy" name="huy"/>
				<input class="ok" type="submit" value="Ok" name="ok-add-items"/>
			</form>
		</div>

		<div id="box-view">
			<form action="" method="POST" name="box-view">
				<span class="title">Chọn theo ngày</span>
				<div class="s-view">
					<span><input type="radio" name="view" value="tatca" checked />Tất cả</span>
					<span><input type="radio" name="view" value="tuannay"/>Tuần này</span>
					<span><input type="radio" name="view" value="thangnay"/>Tháng này</span>
				</div>
				<div class="s-d-view">
					<div class="tu-ngay">
						<span class=""> <input type="radio" name="view" value="date-to-date"/>Từ ngày - đến ngày</span>
						<input type="date" disabled/>
					</div>
					<img src="images/icons/arrow-icon.png" alt="arrow-icon" width="20px" />
					<div class="den-ngay">
						<span class="">Đến ngày</span>
						<input type="date" disabled/>
					</div>
				</div>
				<div>
					<input class="huy" type="button" value="Hủy" />
					<input class="ok" type="submit" value="Ok" name="ok-box-view"/>
				</div>
			</form>
		</div>
	</div>
</body>
</html>