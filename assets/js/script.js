// ページ内リンクスクロールアニメーション
$('a[href*="#"]').click(function () {
	var elmHash = $(this).attr('href'); 
	var pos = $(elmHash).offset().top;
	$('body,html').animate({scrollTop: pos}, 500);
	return false;
});

// ポップアップ閉じるボタン
$(".btn__popup").click(function() {
    $(".popup__container").addClass("js-close");
});

//送信前ポップアップ
function confirm_test() {
    var select = confirm("投稿を送信しますか？");
    return select;
}
