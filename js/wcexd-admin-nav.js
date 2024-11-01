//WOOCOMMERCE EXPORTER PER DANEA - PREMIUM | SCRIPT PER MENU DI NAVIGAZIONE

$(document).ready(function () {
	var $contents = $('.wcexd-admin')
	 $("h2#wcexd-admin-menu a").click(function () {
        var $this = $(this);
        $contents.hide();
        $("#" + $this.data("link")).fadeIn(200);
        $('h2#wcexd-admin-menu a.nav-tab-active').removeClass("nav-tab-active");
        $this.addClass('nav-tab-active');
    }).first().click();
});
