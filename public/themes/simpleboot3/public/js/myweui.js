
var mo = function(e) {
			e.preventDefault();
		};

		/***禁止滑动***/
		function stop() {
			document.body.style.overflow = 'hidden';
			document.addEventListener("touchmove", mo, false); //禁止页面滑动
		}

		/***取消滑动限制***/
		function move() {
			document.body.style.overflow = ''; //出现滚动条
			document.removeEventListener("touchmove", mo, false);
		}
function toast(that) {
	var $toast = $('#toast');
	stop()
	$toast.find(".weui-toast__content").html(that)
	if($toast.css('display') != 'none') return;
	$toast.fadeIn(100);
	setTimeout(function() {
		$toast.fadeOut(100);
		move()
	}, 2000);
}

function toastsuccess(that) {
	var $toast = $('#toastsuccess');
	stop()
	$toast.find(".weui-toast__content").html(that)
	if($toast.css('display') != 'none') return;
	$toast.fadeIn(100);
	setTimeout(function() {
		$toast.fadeOut(100);
		move()
	}, 2000);
}

$('.js_dialog').on('click', '.weui-dialog__btn', function() {
	$(this).parents('.js_dialog').fadeOut(200);
	move()
});
