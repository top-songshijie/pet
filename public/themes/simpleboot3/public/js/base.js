// JavaScript Document
var Length = document.documentElement.clientWidth;
var baseWidth = Length <= 1024 ? Length : 1024 < Length ? 750 : '';
document.documentElement.style.fontSize = baseWidth / 750 * 100 + 'px';

	// 接送时间
	function data() {
		var _this = this;
		weui.datePicker({
			start: new Date(),
			end: new Date().getFullYear(),
			defaultValue: [new Date().getFullYear(), new Date().getMonth() + 1, new Date().getDate()],
			onConfirm: function(result) {
				// 二级调用：时间
				show_expect_time_picker(result);
				$('.weui-picker').on('animationend webkitAnimationEnd', function() {
					show_expect_time_picker(result);
				});
			},
			className: 'order_day'
		})
	}
	// 时间组件选择时分
	function show_expect_time_picker(date) {
		var hours = [],
			minites = [],
			symbol = [{
				label: ':',
				value: 0
			}];
		var d = new Date();
		var my_hours = d.getHours();
		var my_minutes = d.getMinutes();
		var date = date[0].value + '年' + (date[1].value>9?date[1].value:('0'+date[1].value)) + '月' + (date[2].value>9?date[2].value:('0'+date[2].value)) + '日';
		if (!hours.length) {
			for (var i = my_hours; i < 24; i++) {
				var hours_item = {};
				hours_item.label = ('' + i).length === 1 ? '0' + i : '' + i;
				hours_item.value = i;
				hours.push(hours_item);
			}
		}
		if (!minites.length) {
			for (var j = 0; j < 60; j++) {
				var minites_item = {};
				minites_item.label = ('' + j).length === 1 ? '0' + j : '' + j;
				minites_item.value = j;
				minites.push(minites_item);
			}
		}
		weui.picker(hours, symbol, minites, {
			defaultValue: [new Date().getHours(), 0, my_minutes],
			onConfirm: function(result) {
				var time = result[0].label + ':' + result[2].label;
				var expect_date = date + ' ' + time;
				var timestamp = Date.parse(new Date());
				var choosetamp = Date.parse(expect_date);
				console.log(expect_date)
				$('.all_time').val(expect_date);
			},
			className: 'mytime'
		});
	}

// $(document).ready(function() {
// 	//更换宠物下拉
// 	$('#change_pet').on('click', function () {
//         weui.picker([{
//             label: '柯基',
//             value: 0
//         }, {
//             label: '茶杯犬',
//             value: 1
//         },{
//             label: '贵宾',
//             value: 2
//         },{
//             label: '博美',
//             value: 3
//         },{
//             label: '金毛',
//             value: 4
//         },{
//             label: '哈士奇',
//             value: 5
//         },{
//             label: '比熊',
//             value: 6
//         },{
//             label: '萨摩',
//             value: 7
//         },{
//             label: '松狮',
//             value: 8
//         }, ], {
//             onChange: function (result) {
//                 console.log(result);
//                 $('.pet_add').show();
//                 $('.weui-mask,.weui-picker__action').on('click',function(){
// 					$('.pet_add').hide();
// 				})
//             },
//             onConfirm: function (result) {
//             	$('.pet_add').hide();
//                 $('.animal_name').text(result[0].label);
//             }
//         });
//     });
// });
