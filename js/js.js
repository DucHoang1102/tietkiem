$(document).ready(function(){
	// js phần mềm thu chi

var validate = function (){
	function warning(box, content, swit){
		var warningBox = $(box).find('.warning');
		if(swit === true){
			warningBox.find('span').text(content);
			warningBox.show(100);
		}
		return;
	}
	function emptyData (moneyNumber, moneyContent) {
		if (moneyNumber === "" || moneyContent === ""){
			warning('#box-setting', 'Nhập nội dung tiêu tiền!', true);
			return false;
		}
	}
	function isNumber(moneyNumber) {
		var moneyNumber = parseInt(moneyNumber);
		if (isNaN(moneyNumber)){
			warning('#box-setting', 'Vui lòng điền số tiền!', true);
			return false;
		}
	}
	var inputItems = $('#input-items .input-item');
	for(var inputItem of inputItems){
		var moneyNumber = $(inputItem).children('input[name=input-money]').val();
		var moneyContent = $(inputItem).children('input[name=input-content]').val();
		if(isNumber(moneyNumber) === false){
			$($(inputItem).children('input[name=input-money]')).focus();
			return false;
		}
		if (emptyData(moneyNumber, moneyContent) === false) {
			$($(inputItem).children('input[name=input-content]')).focus();
			return false;
		}
	}
	return true;
}

var box = {
	showAndHidden: function ($box) {
		// Định nghĩa hiển thị và ẩn box
		// Tạo nền đen cho box
		var blackBackground = $('<div id="black-background"></div>');
		blackBackground.insertBefore($box);

		// Hiển thị hộp thoại
		$($box).show(300);

		// Kích hoạt nút hủy làm ẩn hộp thoại
		$('input.huy').click(function () {
			$($box).hide();
			$('#black-background').remove();
			functions.boxSetting.countSTT = 0;
			return false;
		});
	}
}// end box{}

var functions = {
	boxSetting : {
		countSTT: 0,//Đếm số thứ tự khi thêm inputItem,
		inputItem: function (moneyNumber, moneyContent) {

			if (typeof moneyNumber === "undefined") var moneyNumber = "";
			if (typeof moneyContent === "undefined") var moneyContent = "";

			this.countSTT = this.countSTT + 1;
			var inputItem = 
			`<div class="input-item"> 
				<span class="serial">${this.countSTT}.</span>
				<input type="text" name="input-money" value="${moneyNumber}"/>
				<span><img src="images/icons/arrow-icon.png" alt="arrow-icon"/></span>
				<input type="text" name="input-content" value="${moneyContent}"/>
				<img class="close" src="images/icons/close-icon.png" alt="close"/>
			</div>`
			$('#input-items').append(inputItem);

			return false;	
		},

		clickAddInputItem: function () {
			$this = this;
			$('#box-setting .add-input-item').click(function () {
				$this.inputItem();
			});
		},

		clickDeleteItem: function () {
			// Tại sao phải sử dụng hàm on() => thành phần .close chưa được
			// tạo nếu sử dụng hàm click() sẽ báo lỗi.
			$('#input-items').on('click', '.input-item .close', function () {
				$(this).parent().hide(200,null, function () {
					// Tại sao là $(this) mà ko phải $(this).parent()
					// $(this) - ko phải là đối tượng click mà đối 
					// tượng trước hide() (sử dụng hide)
					$(this).remove();
				});
				return false;
			});
		},

		deleteAllItems: function () {
			// Giúp hộp thoại trở về là mặc định
			var isInputItems = $('#input-items div').hasClass('input-item');
			if(isInputItems === true){
				$('#input-items div.input-item').remove();
			}
			$('#box-setting .warning').hide()//Ẩn tất cả thông báo
		}
	},

	addItems: function () {
		// Kích hoạt nút thêm mới item ngoài trang chủ
		$('#functions .item-add').click(function () {
			functions.boxSetting.deleteAllItems();
			$('#box-setting .add-input-item').click();
			$('#box-setting .input-item .close').hide();// Ẩn nut xóa item
			box.showAndHidden('#box-setting');
			return false;
		});
	},

	editItems: function () {
		// Kích hoạt nút sửa trong các ngày đã có
		$('#history-deal .item-edit').click(function () {
			// Khi người dùng click nút sửa trong từng ngày cụ thể	
			// Lấy dữ liệu của ngày đó nắp vào các input để cho 
			// người dùng sửa
			functions.boxSetting.deleteAllItems();

			var moneyItems = $(this).parent().children('.money-item');
			for(var item of moneyItems){
				var moneyNumber = $(item ).find('.money-number').text();
				var moneyContent = $(item ).find('.money-content').text();
				functions.boxSetting.inputItem(moneyNumber, moneyContent);
			}

			box.showAndHidden('#box-setting');
			return false;
		});
	},

	viewSelect: function () {
		var inputDisabled = $('.s-d-view input[type=date]');
		$('.s-view span input').click(function () {
			if(!$(inputDisabled).prop('disabled')){
				$(inputDisabled).attr('disabled', 'disabled');
			}
		});
		$('.s-d-view input[type=radio]').click(function () {
			if($(inputDisabled).prop('disabled')){
				$(inputDisabled).removeAttr('disabled');
			}
		});

		$('#functions .view-select').click(function () {
			box.showAndHidden('#box-view');
		});
	},

	active: function () {
		this.addItems();
		this.editItems();
		this.viewSelect();
		this.boxSetting.clickAddInputItem();
		this.boxSetting.clickDeleteItem();
		$('#box-setting .ok').click(function () {
			return validate();
		});
	},
}//end functions{}

functions.active();

});
