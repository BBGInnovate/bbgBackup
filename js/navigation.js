/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens and enables tab
 * support for dropdown menus.
 */
( function() {
	var container, button, menu, links, subMenus;

	container = document.getElementById( 'site-navigation' );
	if ( ! container ) {
		return;
	}

	button = container.getElementsByTagName( 'button' )[0];
	if ( 'undefined' === typeof button ) {
		return;
	}

	menu = container.getElementsByTagName( 'ul' )[0];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	menu.setAttribute( 'aria-expanded', 'false' );
	if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
		menu.className += ' nav-menu';
	}

	button.onclick = function() {
		if ( -1 !== container.className.indexOf( 'toggled' ) ) {
			container.className = container.className.replace( ' toggled', '' );
			button.setAttribute( 'aria-expanded', 'false' );
			menu.setAttribute( 'aria-expanded', 'false' );
		} else {
			container.className += ' toggled';
			button.setAttribute( 'aria-expanded', 'true' );
			menu.setAttribute( 'aria-expanded', 'true' );
		}
	};

	// Get all the link elements within the menu.
	links    = menu.getElementsByTagName( 'a' );
	subMenus = menu.getElementsByTagName( 'ul' );

	// Set menu items with submenus to aria-haspopup="true".
	for ( var i = 0, len = subMenus.length; i < len; i++ ) {
		var s = subMenus[i];
		s.parentNode.setAttribute( 'aria-haspopup', 'true' );
	}

	/**
	 * BEGIN BBG CUSTOM SECOND LEVEL NAVIGATION CODE
	 */
	function levelTwoNav() {
		
		//allow jQuery click events to bubble up - fixes body nav click issue on iOS
		//TODO: check if Android works by default
		/iP/i.test(navigator.userAgent) && jQuery('*').css('cursor', 'pointer');
		
		/*
		jQuery("li.menu-item-has-children ul a").click(function(e) {
			e.stopPropagation(); //we do this so that the preventDefault() below doesn't affect subnav items
		});
		jQuery("li.menu-item-has-children ul a").keydown(function(e) {
			e.stopPropagation(); //same as above
		});
		*/

		// see http://stackoverflow.com/questions/7394796/jquery-click-event-how-to-tell-if-mouse-was-clicked-or-enter-key-was-pressed
		/* don't enable hover on mobile breakpoints */
		jQuery("li.menu-item-has-children").hover(function(e) {
			if (window.innerWidth >=900) {
				jQuery(this).find("ul.sub-menu").css('display','block');	
				e.stopPropagation();
				e.preventDefault();
			}
		}, function(e) {
			if (window.innerWidth >=900) {
				jQuery(this).find("ul.sub-menu").hide();
				e.stopPropagation();
				e.preventDefault();
			}
		});

		/* enable the carat with the keyboard */
		jQuery("li.menu-item-has-children a.navToggler").keydown(function(e) {
			/**** enter key on caret toggles the menu at all viewports ****/
			if(e.keyCode == 13) {
				window.enterPressHover=true;
				if (jQuery(this).parent().find("ul.sub-menu").is(':visible')) {
					jQuery(this).parent().find("ul.sub-menu").hide();	
				} else {
					jQuery("ul.sub-menu").hide();
					jQuery(this).parent().find("ul.sub-menu").css('display','block');	
				}
				e.stopPropagation();
				e.preventDefault();
			} else {
				/* tabbing key on caret going backwards hides all */
				if (window.innerWidth >=900) {
					if (e.which == 9 && e.shiftKey) {
						jQuery('ul.sub-menu').hide();
					}
				}
			}
		});
		jQuery("li.menu-item-has-children a.navToggler").click(function(e) {
			if (window.innerWidth < 900) {
				var displayVal=jQuery(this).parent().find(".sub-menu").css('display');
				if (displayVal != 'none') {
					jQuery(this).parent().find("ul.sub-menu").hide();
				} else {
					jQuery("ul.sub-menu").hide();
					jQuery(this).parent().find("ul.sub-menu").css('display','block');
				}
				e.stopPropagation();
				e.preventDefault();
			}
		});
		
		/* clicking on the body should hide all subnav items */
		
		jQuery(document).on('click', function(e){
			jQuery(this).find("ul.sub-menu").hide();
		});
	

		/* tabbing off last child should hide it */
		jQuery('li.menu-item-has-children ul li:last-child').keydown(function (e) {
		    if (window.innerWidth >=900) {
				if (e.which == 9 && e.shiftKey) {
					//they are going backwards from the last item in the list up. ... keep it.
				} else if (e.which == 9) {
					jQuery('ul.sub-menu').hide();
				}
			}
		});

		
		
	}
	levelTwoNav();
	/**
	 * END BBG CUSTOM SECOND LEVEL NAVIGATION CODE
	 */

	

	// Each time a menu link is focused or blurred, toggle focus.
	for ( i = 0, len = links.length; i < len; i++ ) {
		links[i].addEventListener( 'focus', toggleFocus, true );
		links[i].addEventListener( 'blur', toggleFocus, true );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		var self = this;

		// Move up through the ancestors of the current link until we hit .nav-menu.
		while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

			// On li elements toggle the class .focus.
			if ( 'li' === self.tagName.toLowerCase() ) {
				if ( -1 !== self.className.indexOf( 'focus' ) ) {
					self.className = self.className.replace( ' focus', '' );
				} else {
					self.className += ' focus';
				}
			}

			self = self.parentElement;
		}
	}
} )();
