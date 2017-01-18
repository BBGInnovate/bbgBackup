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

    /* deliberately using a class rather than ID in case we have two on the same page */
    if (jQuery('.internal_links_list').length) {
        jQuery('.internalLink').click(function() {
            url=jQuery(this).parent().find('.internal_links_list').val();
            window.open(url,'_self');
        });
    }

    if (jQuery("div[data-name='committee_members'] select").length) {
        console.log('found the field');
        //jQuery("div[data-name='committee_members'] select").chosen();
    }

    /*** Add client side validation to the content request form.  We use Fast Secure Contact Form to generate it ***/
    if (jQuery("form#fscf_form4").length) {
        jQuery("input[type=submit]").attr('onclick','').unbind('click');
        jQuery("input[type=submit]").click(function(e) {
          e.preventDefault();
          var errorMsg = "";
          var name = jQuery('#fscf_name4').val();
          var selectedNetwork = jQuery('#fscf_mail_to4').val();
          var email = jQuery('#fscf_email4').val();
          var audience = jQuery('#fscf_field4_4').val(); 
          var textChecked = jQuery('#fscf_field4_5_1').attr('checked');
          var photosChecked = jQuery('#fscf_field4_5_2').attr('checked');
          var videoChecked = jQuery('#fscf_field4_5_3').attr('checked');
          var audioChecked = jQuery('#fscf_field4_5_4').attr('checked');
          var link = jQuery('#fscf_field4_6').val();
          var awareThirdYes = jQuery('#fscf_field4_8_1').prop('checked');
          var awareContentYes = jQuery('#fscf_field4_9_1').prop('checked');
          if (name == "") {
            errorMsg += "Please enter a name\n\n";
          }
          if (selectedNetwork == "") {
            errorMsg += "Please select a network\n\n";
          }
          if (email == "") {
            errorMsg += "Please enter your email address\n\n";
          }
          if (audience == "") {
            errorMsg += "Please enter the target audience\n\n"
          }
          if ( ! (textChecked || photosChecked || videoChecked || audioChecked) ) {
            errorMsg += "Please select one or more content types you're interested in\n\n";
          }
          if (link == "") {
            errorMsg += "Please enter a link to the content you're interested in from a BBG website\n\n";
          }
          if (!awareThirdYes) {
            errorMsg += "Please acknowledge that you are aware that our content often contains material from third parties such as the Associated Press (AP), Agence France-Presse (AFP) and Reuters.\n\n"
          }
          if (!awareContentYes) {
            errorMsg += "Please acknowledge that you are aware that we require our content to be aired or used in a way in which its source is made clear.\n\n"
          }
          if (errorMsg == "") {
            jQuery('#fscf_form4').submit();
          } else {
            alert(errorMsg);
          }
          return false;
         // 
        });
    }
});