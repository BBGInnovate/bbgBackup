  jQuery( document ).ready(function() {
    jQuery("input[type=radio][name=requestType]").change(function() {
      var newVal = jQuery(this).val();
      console.log('newval is ' + newVal);
      if (newVal=='oneTimeUse') {
        jQuery('#directInfo').hide();
        jQuery('#contentRequestForm').show();
      } else {
        jQuery('#directInfo').show();
        jQuery('#contentRequestForm').hide();
      }
    });
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
        errorMsg += "Please enter a name\n";
      }
      if (selectedNetwork == "") {
        errorMsg += "Please select a network\n";
      }
      if (email == "") {
        errorMsg += "Please enter your email address\n";
      }
      if (audience == "") {
        errorMsg += "Please enter the target audience\n"
      }
      if ( ! (textChecked || photosChecked || videoChecked || audioChecked) ) {
        errorMsg += "Please select one or more content types you're interested in\n";
      }
      if (link != "") {
        errorMsg += "Please enter a link to the content you're interested in from a BBG website\n";
      }
      if (!awareThirdYes) {
        errorMsg += "Please acknolwedge that you are aware that our content often contains material from third parties such as the Associated Press (AP), Agence France-Presse (AFP) and Reuters.\n"
      }
      if (!awareContentYes) {
        errorMsg += "Please acknolwedge that you are aware that we require our content to be aired or used in a way in which its source is made clear.\n"
      }
      if (errorMsg == "") {
        jQuery('#fscf_form4').submit();
      } else {
        alert(errorMsg);
      }
      return false;
     // 
    });
  });