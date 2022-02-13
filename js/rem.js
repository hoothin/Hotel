(function(){
	window.onload = initPage;
	window.onresize = initPage;
	function initPage() {
		getRem(750, 100)
		if (window.initSwiper) {
			window.initSwiper();
		}
	}
	function getRem(pwidth, prem) {
		var html = document.getElementsByTagName("html")[0];
		var oWidth = document.body&&document.body.clientWidth || document.documentElement.clientWidth;
		html.style.fontSize = oWidth / pwidth * prem + "px";
	}
	getRem(750,100);
}())
