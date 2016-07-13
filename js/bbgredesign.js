/**
 * bbgWPtheme.js
 *
 * Custom JS for the bbgWPtheme
 *
 *
 */
jQuery(document).ready(function() {
    //
    // create social networking pop-ups
    // link selector and pop-up window size
    var shareConfig = {
        Width: 500,
        Height: 500
    };

    // add handler links
    var shareLink = document.querySelectorAll('li.bbg__article-share__link a');
    for (var a = 0; a < shareLink.length; a++) {
        shareLink[a].onclick = PopupHandler;
    }

    // create popup
    function PopupHandler(e) {

        /*you could tweet the highlighted/selected text by encoding and concatenating it with the URL
        var text = "";
        if (window.getSelection) {
            text = window.getSelection().toString();
        } else if (document.selection && document.selection.type != "Control") {
            text = document.selection.createRange().text;
        }
        console.log(text);
        */

        e = (e ? e : window.event);

        //changed e.target.parentNode to e.target when i removed the <img/> tag
        //var t = (e.target.parentNode ? e.target.parentNode : e.srcElement);
        var t = (e.target ? e.target : e.srcElement);
        //logger(t)


        // popup position
        var px = Math.floor(((screen.availWidth || 1024) - shareConfig.Width) / 2),
            py = Math.floor(((screen.availHeight || 700) - shareConfig.Height) / 2);

        // open popup
        var popup = window.open(t.parentElement.href, "social",
            "width="+shareConfig.Width+",height="+shareConfig.Height+
            ",left="+px+",top="+py+
            ",location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1");
        if (popup) {
            popup.focus();
            if (e.preventDefault) e.preventDefault();
            e.returnValue = false;
        }

        return !!popup;
    }

    if (jQuery('#entityUrlGo').length && jQuery('#entity_sites').length) {
        jQuery('#entityUrlGo').click(function() {
            url=jQuery('#entity_sites').val();
            window.open(url,'_blank');
        });
    }

    /* used on the 2-column page, dropdown nav for sidebar */
    // file downloads
    if (jQuery('#downloadFile').length && jQuery('#file_download_list').length) {
        jQuery('#downloadFile').click(function() {
            url=jQuery(this).parent().find('#file_download_list').val();
            window.open(url,'_blank');
        });
    }

    if (jQuery('#internalLink').length && jQuery('#internal_links_list').length) {
        jQuery('#internalLink').click(function() {
            url=jQuery(this).parent().find('#internal_links_list').val();
            window.open(url,'_self');
        });
    }



    if (jQuery("div[data-name='committee_members'] select").length) {
        console.log('found the field');
        //jQuery("div[data-name='committee_members'] select").chosen();
    }

    /*
    if( typeof acf.add_action !== 'undefined' ) {
        acf.add_action('load', function( $el ){
            console.log('ACF loaded ');
        });
    }
    */



});