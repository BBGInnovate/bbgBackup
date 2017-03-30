jQuery( document ).ready(function() {
  jQuery("input[type=submit]").attr('onclick','').unbind('click');
  jQuery("input[type=submit]").click(function(e) {
    e.preventDefault();
    var errorMsg = "";
    var email = jQuery('#fscf_email2').val();
    if (email == "") {
      errorMsg += "NOTE: because you did not include an email address, we will be unable to reach you for clarification or follow up if needed.  Do you wish to submit your comment anonymously?\n\n";
    }
    if (errorMsg == "") {
      jQuery('#fscf_form2').submit();
    } else {
      if (confirm(errorMsg)  == true) {

      } else {
        jQuery('#fscf_form2').submit();
      };
    }
    return false;
   // 
  });
});