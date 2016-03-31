jQuery(document).ready(function() { 
  var footerAccordion = function() {
    if (window.innerWidth < 600) {

      jQuery('.usa-footer-big nav ul').addClass('hidden');

      jQuery('.usa-footer-big nav .usa-footer-primary-link').unbind('click');

      jQuery('.usa-footer-big nav .usa-footer-primary-link').bind('click', function() {
        if (! jQuery(this).parent().hasClass('hidden')) {
          jQuery(this).parent().addClass('hidden');
        } else {
          jQuery(this).parent().removeClass('hidden')
          .siblings().addClass('hidden');  
        }
      });
    } else {

      jQuery('.usa-footer-big nav ul').removeClass('hidden');

      jQuery('.usa-footer-big nav .usa-footer-primary-link').unbind('click');
    }
  };

  footerAccordion();

  jQuery(window).resize(footerAccordion);
});