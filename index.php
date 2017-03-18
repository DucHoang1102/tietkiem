<?php
	require ("php/view.php");
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
			$result_view = view('tatca');
			if(isset($result_view['total_money'])) $total_money = $result_view['total_money'] . 'k';
			else $total_money = '0k';
		
			if(isset($result_view['total_money_today'])) $total_money_today = $result_view['total_money_today'] . 'k';
			else $total_money_today = 0;
			
			echo 
				"<div id='view-total-money'>
					<span class='cash-money'>Hôm nay</span>
					<span class='cash-money-number' title='50000k'>$total_money_today</span>
					<span class='card-money'>Tháng này</span>
					<span class='card-money-number' title='50000k'>$total_money</span>
				</div>'

				<div id='history-deal'>";
				if ($result_view === 'empty'){
					echo '<span class="empty" style="font-weight:bold; font-style:italic;">Không có dữ liệu <span>';
				}
				else{
					foreach ($result_view as $item_date) {
						if ($item_date['date']){ 
							$date_content = $item_date['date']['date_content'];
							$money_total_in_date = $item_date['date']['all_moneys']; 
							echo 
								"<div class='items'>
									<span class='date-time'>$date_content</span>
									<span class='item-edit'><img src='images/icons/edit-pen-icon.png' alt='edit' ></span><br />";

							if (count($item_date['item']) > 0){
								foreach ($item_date['item'] as $items) {
									$money_number = $items['item_number'];
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
			<span id="box-title">Settings</span>
			<img class="add-input-item" src="images/icons/add-icon.png" alt="add"/>
			<form action="">
				<select name="" id="">
					<option value="a">Hôm qua</option>
					<option value="a" selected>Hôm nay</option>
				</select>
				<div class="warning">
					<img src="images/icons/warning-icon.png" alt="warning"/>
					<span><!--Nội dung thông báo--></span>
				</div>
				<div id="input-items"> 
					<!--Chứa các trường thêm items-->
				</div>
				<input class="huy" type="button" value="Hủy" name="huy"/>
				<input class="ok" type="submit" value="Ok" name="ok"/>
			</form>
		</div>

		<div id="box-view">
			<span class="title">Chọn theo ngày</span>
			<div class="s-view">
				<span><input type="radio" name="view" checked />Tất cả</span>
				<span><input type="radio" name="view"/>Tuần này</span>
				<span><input type="radio" name="view"/>Tháng này</span>
			</div>
			<div class="s-d-view">
				<div class="tu-ngay">
					<span class=""> <input type="radio" name="view"/>Từ ngày - đến ngày</span>
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
				<input class="ok" type="submit" value="Ok" />
			</div>
		</div>
	</div>
</body>
</html>