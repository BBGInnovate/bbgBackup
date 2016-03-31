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
		
		jQuery("li.menu-item-has-children ul a").click(function(e) {
			e.stopPropagation(); //we do this so that the preventDefault() below doesn't affect subnav items
		});

		/* clicking any top level nav item with children should show its children and hide all others */
		// see http://stackoverflow.com/questions/7394796/jquery-click-event-how-to-tell-if-mouse-was-clicked-or-enter-key-was-pressed
		jQuery("li.menu-item-has-children").on('click', function(e, enterKeyPressed) {
			if (window.innerWidth >=600) {
				if (jQuery(this).find("ul").hasClass('showChildren')) {
					
					//without this line, if you click a parent nav item 2x, it stays focused.
					if (!enterKeyPressed) {
						jQuery(this).find("a").blur();  
					}
					
					jQuery(this).addClass("hidden");
					jQuery(this).find("ul").removeClass('showChildren');
				} else {
					/* hide any open menus before showing the newly clicked one */
					jQuery('.showChildren').removeClass('showChildren');
					jQuery(this).find("ul").addClass('showChildren');
					jQuery(this).removeClass("hidden");
				}
				e.stopPropagation();
				e.preventDefault();
			}
		});
		jQuery("li.menu-item-has-children").keydown(function(e) {
		  if(e.keyCode == 13) {
		    jQuery(this).trigger("click", true);
		    e.preventDefault();
		  }
		});

		
		/* clicking on the body should hide all subnav items */
		jQuery(document).on('click', function(e){
			jQuery('.showChildren').toggleClass('showChildren');
			jQuery("li.menu-item-has-children").addClass('hidden');
		});
		/* clicking on the body should hide all subnav items */
		jQuery(document).ready(function(e){
			jQuery("li.menu-item-has-children").addClass('hidden');
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
