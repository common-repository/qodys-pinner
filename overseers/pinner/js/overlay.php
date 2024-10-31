<?php
if (@$_GET['PDebug'] != 'Y')
{
    @header('Content-Type: application/x-javascript');
}

$description_text = $_GET['description'];
$page_url = $_GET['url'];
?>
(function() {

	window.PinIt = window.PinIt || { loaded:false };

	if (window.PinIt.loaded) return;

	window.PinIt.loaded = true;

	function async_load(){

		var s = document.createElement("script");

		s.type = "text/javascript";

		s.async = true;

		if (window.location.protocol == "https:")

			s.src = "https://assets.pinterest.com/js/pinit.js";

		else

			s.src = "http://assets.pinterest.com/js/pinit.js";

		var x = document.getElementsByTagName("script")[0];

		x.parentNode.insertBefore(s, x);

	}

	if (window.attachEvent)

		window.attachEvent("onload", async_load);

	else

		window.addEventListener("load", async_load, false);

})();

jQuery(document).ready(function (a) {
	
	jQuery('#qody_pinner_content_container img').each( function(e) {
		
		jQuery(this).addClass( 'qody_pinner' );
		jQuery(this).attr( 'alt', "<?php echo urlencode( $description_text ); ?>" );
		
	} );

        var d = {
            img_class: "qody_pinner",
            page_url: "<?php echo $page_url; ?>"
        }, c = a.extend(d, c);
		

        a("img." + d.img_class).each(function() {
            var b = a(this);
            b.attr("src");
            b.parent().is("a") && b.unwrap();
            b.wrap('<div class="qody_pinner_wrapper"/>');
            var e = this.height, f = this.width;
            b.attr("height") && (e = b.attr("height"));
            b.attr("width") && (f = b.attr("width"));
            var c = encodeURI(b.attr("src")), g = (b.attr("alt")), h = encodeURI(d.page_url), i = (e - 20) / 2, j = (f - 43) / 2;
            !1 == d.alt_tags && (g = "");
            b.parent().css({
                width: f + "px",
                height: e + "px"
            });
            b.parent().append('<span><div style="position:absolute; top:' + i + "px; left:" + j + 'px;"><a href="http://pinterest.com/pin/create/button/?url=' + h + "&media=" + c + "&description=" + g + '" class="pin-it-button" count-layout="none">Pin It</a></span></div>');
            a(".qody_pinner_wrapper img").mouseenter(function() {
                a(this).parent().children("span").css({
                    display: "block"
                });
                a(this).stop().animate({
                    opacity: "0.2"
                }, 300, null, function() {
                    a(this).parent().children("span").css({
                        "z-index": "999"
                    });
                });
            });
            a(".qody_pinner_wrapper span").mouseleave(function() {
                a(this).css("z-index", "1");
                a(this).stop().parent().children("img").animate({
                    opacity: "1"
                }, 300);
                a(this).css("display", "none");
            });
        });
} );



