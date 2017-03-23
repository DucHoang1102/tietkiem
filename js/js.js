$(document).ready(function(){
	// js phần mềm thu chi

var validate = {
	boxSetting: function (){
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
			var moneyNumber = $(inputItem).children('input.input-money').val();
			var moneyContent = $(inputItem).children('input.input-content').val();
			if(isNumber(moneyNumber) === false){
				$($(inputItem).children('input.input-money')).focus();
				return false;
			}
			if (emptyData(moneyNumber, moneyContent) === false) {
				$($(inputItem).children('input.input-content')).focus();
				return false;
			}
		}
		return true;
	},
	boxView: function () {
		// Validate hộp thoại #boxView phần date-to-date (Từ ngày đến ngày)
		// "Từ ngày" luôn luôn nhỏ hơn "Đến ngày"
		var dateA = $('#box-view .date-A').val();
		var dateB = $('#box-view .date-B').val();
		var dateToDate_checked = $('#box-view input[name=view]:checked').val();
		
		if ((dateToDate_checked == "date-to-date") && (dateA == "")){
			// Trường hợp người dùng để trống ô .dateA (Từ ngày)
			$('#box-view .warning').show(100);
			return false;
		}
		if ((dateToDate_checked == "date-to-date") && (dateA > dateB)) {
			// Trường hợp sai: "Từ ngày" lớn hơn "Đến ngày" && nút tick radio
			// tại "Từ ngày - đến ngày"
			$('#box-view .warning').show(100);
			return false;
		}
		return true;
	}
};
var box = {
	showAndHidden: function ($box) { 
		// Định nghĩa hiển thị và ẩn box chung
		// Tạo nền đen cho box
		var blackBackground = $('<div id="black-background"></div>');
		blackBackground.insertBefore($box);

		// Hiển thị hộp thoại
		$($box).show(300);

		// Kích hoạt nút hủy làm ẩn hộp thoại chung
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
				<input class="input-money" type="text" name="${this.countSTT}-input-money" value="${moneyNumber}" maxlength="6"/>
				<span><img src="images/icons/arrow-icon.png" alt="arrow-icon"/></span>
				<input class="input-content" type="text" name="${this.countSTT}-input-content" value="${moneyContent}" maxlength="25"/>
				<img class="close" src="images/icons/close-icon.png" alt="close"/>
			</div>`
			$('#input-items').append(inputItem);

			return false;	
		},

		clickAddInputItem: function () {
			$this = this;
			$('#box-setting .add-input-item').click(function () {
				$this.inputItem();
				return false;
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
		},

		today: function () {
			// Người dùng chỉ có thể thiết lập Thêm mới chi tiêu cho thời 
			// gian từ hôm nay trở về trước.
			// Thêm ngày hôm nay cho 2 thuộc tính Max và Value của thành phần
			// input date
			var d = new Date();
			var dd = d.getDate();
			var mm = d.getMonth() + 1;// + 1 vì month = (0->11)
			var yyyy = d.getFullYear();
			var today = `${yyyy}-0${mm}-${dd}`;
			return today;
		}
	},

	addItems: function () {
		// Kích hoạt nút thêm mới item ngoài trang chủ
		$('#functions .item-add').click(function () {
			functions.boxSetting.deleteAllItems();
			$('#box-setting .add-input-item').click();
			$('#box-setting .input-item .close').hide();// Ẩn nut xóa item

			// Thiết lập thời gian ngày hôm nay
			var today = functions.boxSetting.today();
			$('#box-setting .date-select').attr({'max':today, 'min':0});
			$('#box-setting .date-select').val(today);

			// Sửa title hộp thoại
			$('#box-setting #box-title').text('Thêm mới chi tiêu');
			// Sửa name của input submit
			$('#box-setting input.ok').attr({'name':'ok-add-items'});

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
			// Chức năng sửa là cập nhật lại toàn bộ nội dung trong database
			functions.boxSetting.deleteAllItems();

			var itemEdit = $(this).parent();
			var date = itemEdit.find('.date-time').text();
			if (date === "Hôm nay"){
				var date = functions.boxSetting.today();
			}
			else{
				var date = date.slice(6,11) + '-' + date.slice(3,5) + '-' + date.slice(0,2);
			} 
			var moneyItems = itemEdit.children('.money-item');

			for(var item of moneyItems){
				// Lắp item vào hộp thoại
				var moneyNumber = $(item ).find('.money-number').text();
				var moneyContent = $(item ).find('.money-content').text();
				functions.boxSetting.inputItem(moneyNumber, moneyContent);
			}

			// Sửa title hộp thoại
			$('#box-setting #box-title').text('Sửa chi tiêu');
			// Sửa name của input subnmit 
			$('#box-setting input.ok').attr({'name':'edit-items'});
			// Lắp ngày vào hộp thoạis
			$('#box-setting input[type=date]').attr({'max': date, 'min': date});
			$('#box-setting input[type=date]').val(date);
			box.showAndHidden('#box-setting');
			return false;
		});
	},

	viewSelect: function () {
		// Hộp thoại tùy chọn hiển thị
		// Nếu tick các tùy chọn khác, input date chọn ngày sẽ bị disabled
		// ngược lại nếu tick "Từ ngày - đến ngày" input date sẽ remove disabled
		var inputDisabled = $('.s-d-view input[type=date]');
		$('.s-view span input').click(function () {
			if(!$(inputDisabled).prop('disabled')){
				$(inputDisabled).attr('disabled', 'disabled');
				return false;
			}
		});
		$('.s-d-view input[type=radio]').click(function () {
			if($(inputDisabled).prop('disabled')){
				$(inputDisabled).removeAttr('disabled');
				return false;
			}
		});

		// Thiết lập date-to-date, chỉ được phép tìm kiếm: ngày hôm nay trở về
		// trước. Max, value của input date mặc định là ngày hôm nay
		var today = functions.boxSetting.today();
		$('#box-view .date-A').attr({'max':today}); // Từ ngày
		$('#box-view .date-B').attr({'max':today, 'value':today}); // Đến ngày

		$('#functions .view-select').click(function () {
			box.showAndHidden('#box-view');
			return false;
		});
	},

	active: function () {
		this.addItems();
		this.editItems();
		this.viewSelect();
		this.boxSetting.clickAddInputItem();
		this.boxSetting.clickDeleteItem();
		$('#box-setting .ok').click(function () {
			return validate.boxSetting();
		});
		$('#box-view .ok').click(function () {
			return validate.boxView();
		});
	},
}//end functions{}

functions.active();

});
