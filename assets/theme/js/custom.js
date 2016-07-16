/**
 * Name:  Real Estate CMS Pro
 *
 * Author: Ramazan APAYDIN
 *         apaydinweb@gmail.com
 *
 * Website: http://ramazanapaydin.com
 *
 * Created:  04.15.2013
 */

$(document).ready(function() {
    /* ----- Jquery Uniform Form Element ------ */
    $(".singleimage").colorbox({rel: 'singleimage', slideshow: true, scalePhotos: true, maxHeight: "100%", maxWidth: "100%"});

    /* ----- Jquery Uniform Form Element ------ */
    $("input[type='checkbox'],input[type='radio']").uniform();
});

function getTwitter(uname, tcount) {
    //Twitter Last Twett
    var username = uname;
    var count = tcount;
    var url = site_url + "assets/theme/lib/twitter.php?screen_name=" + username + "&count=" + count;

    $.getJSON(url, function(data) {
        $.each(data, function(key, value) {
            $('.twitter-list').append('<div class="span4"><div class="row"><div class="span1"><i class="icon-twitter"></i></div><div class="span3">' + value.text + '</div></div></div>');
        });
    });
}