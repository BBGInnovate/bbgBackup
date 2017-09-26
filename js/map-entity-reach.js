// this function will return true if the user is on a mobile device or false otherwise
function isMobileDevice() {
	if ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent ) ) {
		return true;
	} else {
		return false;
	}
}
//Select "a" VOA Country vs Select "an" OCB country
function getArticleByEntity( entity ) {
	if ( entity.toLowerCase() === 'voa' ) {
		return 'a';
	} else {
		return 'an';
	}
}

function shadeColor( color, percent ) {
	var range = color.substring( 4, color.length - 1 );
	var rangeArr = range.split( ',' );
	var r = parseInt( rangeArr[ 0 ] ) + percent;
	var g = parseInt( rangeArr[ 1 ] ) + percent;
	var b = parseInt( rangeArr[ 2 ] ) + percent;
	var newColor = 'rgb(' + r + ',' + g + ',' + b + ')';
	return newColor;
}
( function ( $, bbgConfig, entities ) {
	// find out if the user is on a mobile device or not (used for zoomDuration)
	var isMobile = isMobileDevice();
	// this global variable is used to determine whether or not the current item selected is an entity or country
	var hideCountryLabel = true;
	var hideServiceLabel = true;
	/* note that these colors are changed later */
	var colorBase = '#0071bc',
		colorRollOver = '#205493',
		colorSelected = '#112e51';
	//the colors for the entities are 'inherited' from their buttons, but curren time doesn't have a button, so we set its base color here
	var CURRENT_TIME_MAIN_COLOR = "rgb(27, 153, 214)"; //#ec1e58 (236, 30, 88) is Current Time red, #1b99d6 (27, 153, 214) is the blue in their avatar
	$( document ).ready( function () {
		var defaultEntity = 'bbg'; //might fill this from a global JS var later.
		/* keep activeCountries & map as global vars else they won't be available in callbacks */
		activeCountries = [];
		/* create an ordered array of country names - nice to have later */
		fullCountryList = Object.keys( countriesByName );
		/* create a lookup for our country data based on the ammap code
		   as well as a lookup for countries by language service  */
		countriesByID = [];
		currentTimeOnly = {};
		for ( i = 0; i < fullCountryList.length; i++ ) {
			var cname = fullCountryList[ i ];
			var countryID = countriesByName[ cname ].ammapCode;
			countriesByID[ countryID ] = countriesByName[ cname ];
			var networks = countriesByName[ cname ].networks;
			for ( var j = 0; j < networks.length; j++ ) {
				var n = networks[ j ];
				//console.log(Object.keys(n));
				if ( n.networkName.toLowerCase() == "rferl" && n.services.length == 1 && n.services[ 0 ] == "RFERL Current Time" ) {
					currentTimeOnly[ cname.toLowerCase() ] = 1;
				}
				for ( var k = 0; k < n.services.length; k++ ) {
					var serviceName = n.services[ k ];
					servicesByName[ serviceName ].countries[ cname ] = 1;
				}
			}
		}
		balloonText = '[[title]]';
		if ( isMobile ) {
			balloonText = '';
		}
		map = AmCharts.makeChart( "chartdiv", {
			type: "map",
			borderColor: 'red',
			theme: "light",
			projection: "eckert3",
			dataProvider: {
				map: "worldLow",
				areas: activeCountries
			},
			areasSettings: {
				autoZoom: true,
				color: "#DDDDDD",
				colorSolid: "#7A1A21",
				selectedColor: "#DDDDDD",
				rollOverOutlineColor: "#FFFFFF",
				selectable: true,
				balloonText: balloonText
			},
			balloon: {
				adjustBorderColor: true,
				color: "#000000",
				cornerRadius: 4,
				fillColor: "#FFFFFF"
			},
			zoomControl: {
				maxZoomLevel: 6
			},
			zoomDuration: 0,
			backgroundZoomsToTop: false //water zooms out
		} );
		// hide the tooltip if the position of the map changes (user drags the map)
		map.addListener( 'positionChanged', function () {
			$( '.country-label-tooltip' ).hide();
		} );
		// some UI tips related to country / service labels when zoom completes
		map.addListener( 'zoomCompleted', function ( event ) {
			//console.log("zoomCompleted");
			if ( !isMobile ) {
				map.zoomDuration = 0.2;
			}
			if ( hideCountryLabel === false && currentDisplayMode == "country" && event.chart.zoomLevel() > 1 ) {
				$( '.country-label-tooltip' ).show();
				hideCountryLabel = true;
			}
			if ( hideServiceLabel === false ) {
				$( '.service-label' ).show();
				hideServiceLabel = true;
			}
		} );
		window.selectedCountryID = '';
		//if someone clicks a country that's already selected, zoom out.
		map.addListener( "clickMapObject", function ( event ) {
			if ( window.selectedCountryID && window.selectedCountryID == event.mapObject.id ) {
				//we had a country selected, and they clicked it again. reset
				window.selectedCountryID = "";
				event.chart.zoomToGroup( activeCountries );
				setDisplayMode( 'entity' );
				$( '#service-list' ).val( 0 );
				map.selectObject();
			} else {
				//a country was not previously selected and now one has been clicked
				displayCountry( event.mapObject.id );
				// set the country list value to the same as the map selection
				$( '#country-list' ).val( event.mapObject.id );
			}
		} );
		// zoom in on the new entity once it's updated
		map.addListener( 'dataUpdated', function ( event ) {
			if ( currentDisplayMode == 'entity' ) {
				map.zoomDuration = 0;
			}
			event.chart.zoomToGroup( activeCountries );
			// prevent countries that are not covered from zooming in on click
			// for (var i = 0; i < map.dataProvider.areas.length; i++) {
			// 	if (map.dataProvider.areas[i].region_ids == null) {
			// 		map.dataProvider.areas[i].autoZoomReal = false;
			// 	}
			// }
		} );

		function setDisplayMode( displayMode ) {
			currentDisplayMode = displayMode;
			$( '.service-label' ).hide();
			$( '.country-label-tooltip' ).hide();

			if ( displayMode == "entity" ) {
				$( '#country-name' ).hide();
				$( '#entityDisplay' ).show();
				$( '#countryDisplay' ).hide();
				window.selectedCountryID = '';
			} else if ( displayMode == "country" ) {
				$( '#country-name' ).show();
				$( '#entityDisplay' ).hide();
				$( '#countryDisplay' ).show();
			}
		}

		function setHighlightedEntity( entity ) {
			$( '.entity-buttons button' ).removeClass( 'selected active' );
			$( '.entity-buttons button.' + entity ).addClass( 'selected active' );
		}

		function setBaseColors() {
			//We set the color for an entity based on the color of it's button that picks it
			//we then provide slightly darkened (mathematically) colors for rollover and selection
			var buttonColor = $( '.selected' ).css( 'background-color' );
			colorBase = buttonColor;
			colorRollOver = shadeColor( buttonColor, -30 );
			colorSelected = shadeColor( buttonColor, -50 );
		}

		function getCountryObj( countryName ) {
			var countryColor = colorBase;
			var countryRolloverColor = colorRollOver;
			var countrySelectedColor = colorSelected;

			if ( selectedEntity.toLowerCase() == "rferl" && currentTimeOnly.hasOwnProperty( countryName.toLowerCase() ) ) {
				countryColor = CURRENT_TIME_MAIN_COLOR;
				countrySelectedColor = shadeColor( countryColor, -50 );
				countryRolloverColor = shadeColor( countryColor, -30 );
			}

			return {
				id: countriesByName[ countryName ].ammapCode,
				countryCode: countriesByName[ countryName ].ammapCode,
				name: countryName,
				countryName: countryName,
				color: countryColor,
				rollOverColor: countryRolloverColor,
				selectedColor: countrySelectedColor,
				selectable: true
			}
		}

		function updateActiveCountries( list ) {
			activeCountries = [];

			for ( var i = 0; i < list.length; i++ ) {
				var countryName = list[ i ];
				activeCountries.push( getCountryObj( countryName ) );
			}

			addCountriesToDropdown( activeCountries );
			map.dataProvider.areas = activeCountries;
			map.validateData();
		}

		function displayEntity( entity ) {
			selectedEntity = entity;
			setHighlightedEntity( entity );
			setBaseColors(); //update global vars that are used to color the map

			/**** pick our list of countries to highlight  */
			var clist = fullCountryList;

			if ( entity != "bbg" ) {
				clist = Object.keys( entitiesByName[ entity ].countries );
			}

			updateActiveCountries( clist );

			var en = entities[ entity ];
			var entityDetailsStr = '<p>' + en.description + '</p>';
			// set variable for logos
			var imgSrc = template_directory_uri + '/img/logo_' + entity + '--circle-200.png';
			//entityDetailsStr += 'Website: <a target="_blank" href="'+en.url+'">'+en.url+'</a>';

			var globalStr = "";

			if ( entity == "voa" ) {
				//globalStr += "<p>VOA’s <a target='_blank' href='https://www.voanews.com'>English-language News Center</a> and VOA’s <a target='_blank' href='https://learningenglish.voanews.com/'>Learning English programming</a> are available worldwide.</p>";
				globalStr += '<ul style="margin-bottom: 0;"><li><a target="_blank" href="https://www.voanews.com/">English-language News Center</a></li>';
				globalStr += '<li><a target="_blank" href="https://learningenglish.voanews.com/">Learning English programming</a></li></ul>';
			}

			if ( entity == "voa" || entity == "rferl" ) {
				// globalStr += '<li><a href="https://www.currenttime.tv">Current Time</a></li>';

				globalStr += '<div id="entityDisplay" class="bbg__map__entity--small">';
					globalStr += '<div id="entityLogo" class="bbg__map__entity-logo__container--small"><a href="https://www.currenttime.tv" tabindex="-1"><div class="bbg__map__entity-logo__image--small" style="background-image: url(' + template_directory_uri + '/img/logo_ct--circle-40.png);"></div></a></div>';

					globalStr += '<div class="bbg__map__entity-text">';
						globalStr += '<h2 id="entityName" class="bbg__map__entity-text__name--small"><a href="https://www.currenttime.tv" tabindex="-1">Current Time</a></h2>';
						globalStr += '<p>A 24/7 Russian-language digital network, led by Radio Free Europe/Radio Liberty (RFE/RL) in cooperation with the Voice of America (VOA), that provides news and information to Russian speakers worldwide.</p>';
					globalStr += '</div>';
				globalStr += '</div>';
			}

			if (globalStr != "") {
				globalStr = "<h3 class='bbg__map__entity-global'>Global Content</h3>" + globalStr ;
			}
			$( '#globalBlock' ).html( globalStr );


			$( '#entityLogo' ).html( "<a href='" +  en.url + "' tabindex='-1'><div class='bbg__map__entity-logo__image' style='background-image: url(" + imgSrc + ");'></div></a>" );
			$( '#entityName' ).html( "<a href='" + en.url + "'>" + en.fullName + "</a>" );
			$( '#entityDescription' ).html( entityDetailsStr ).show();

			if ( entity != "bbg" ) {

				$( '#service-list' ).empty();
				var subgroupListString = '';
				var article = getArticleByEntity( selectedEntity );

				subgroupListString += '<option value="0">Select ' + article + ' ' + selectedEntity.toUpperCase() + ' service...</option>';

				for ( var i = 0; i < entitiesByName[ entity ].services.length; i++ ) {
					var srv = entitiesByName[ entity ].services[ i ];
					var srvo = servicesByName[ srv ];
					subgroupListString += '<option value="' + srv + '" data-href="' + srvo.siteUrl + '">' + srvo.serviceName + '</option>';
				}

				$( '#service-list' ).html( subgroupListString );
				$( '#serviceDropdownBlock button' ).hide();
				$( '#serviceDropdownBlock' ).show();
			} else {
				$( '#serviceDropdownBlock' ).hide();
			}
			setDisplayMode( 'entity' );
		}

		function addCountriesToDropdown( countriesDropdown ) {
			$( '#country-list' ).empty();
			var countryListString = '';
			countryListString += '<option value="0">Select a country...</option>';

			for ( var i = 0; i < countriesDropdown.length; i++ ) {
				var country = countriesDropdown[ i ];
				countryListString += '<option value="' + country.id + '">' + country.name + '</option>';
			}

			$( '#country-list' ).html( countryListString );
		}

		function displayCountry( selectedCountryID ) {
			window.selectedCountryID = selectedCountryID;
			countryName = countriesByID[ selectedCountryID ].countryName;
			setCountryLabelPosition( countryName );
			hideCountryLabel = false;

			$( 'h4.country-label-tooltip #country-name' ).html( countryName );
			$( '#countryDisplay h2#countryName' ).html( countryName );

			var networks = countriesByName[ countryName ].networks;
			var s = '';
			var newSortOrder = [];
			var firstItemIndex = -1;
			//we alternate the order of the networks if an entity is selected
			var desiredNetworkOrder = [ "voa", "rferl", "ocb", "rfa", "mbn" ];

			for ( var j = 0; j < desiredNetworkOrder.length; j++ ) {
				if ( desiredNetworkOrder[ j ] == selectedEntity ) {
					var temp = desiredNetworkOrder[ j ];
					desiredNetworkOrder[ j ] = desiredNetworkOrder[ 0 ];
					desiredNetworkOrder[ 0 ] = temp;
				}
			}

			for ( var j = 0; j < desiredNetworkOrder.length; j++ ) {
				for ( var k = 0; k < networks.length; k++ ) {
					if ( networks[ k ].networkName.toLowerCase() == desiredNetworkOrder[ j ] ) {
						newSortOrder.push( k );
					}
				}
			}

			for ( var i = 0; i < newSortOrder.length; i++ ) {
				var sortedIndex = newSortOrder[ i ];
				var n = networks[ sortedIndex ];
				s += '<h3><a target="_blank" href="' + n.siteUrl + '">' + n.networkName + '</a></h3>';
				s += '<ul class="bbg__map-area__list">';
				for ( var j = 0; j < n.services.length; j++ ) {
					var srv = n.services[ j ];
					var srvo = servicesByName[ srv ];
					s += '<li class="bbg__map-area__list-item"><a target="_blank" href="' + srvo.siteUrl + '">' + srvo.serviceName + '</a></li>';
				}
				s += '</ul>';
			}

			$( '.service-block' ).html( s );
			setDisplayMode( 'country' );
		}
		// these countries require special left position adjustments
		function setCountryLabelPosition( country ) {
			var clp = {};
			clp[ 'Chile' ] = '19';
			clp[ 'Croatia' ] = '22.5';
			clp[ 'Vietnam' ] = '27';
			var leftPos = '25';

			if ( clp.hasOwnProperty( country ) ) {
				leftPos = clp[ country ];
			}

			$( '.country-label-tooltip' ).css( 'left', leftPos + '%' );
		}
		$( '.entity-buttons button' ).on( 'click', function () {
			var entity = $( this ).text().toLowerCase();
			map.zoomDuration = 0;
			displayEntity( entity );
		} );
		// this event listener is for select countries through the drop-down (for mobile devices)
		$( '#country-list' ).on( 'change', function () {
			var countryCode = $( this ).val();

			if ( countryCode != "0" ) {
				var mapObject = map.getObjectById( countryCode );
				map.clickMapObject( mapObject );
			}
			//scrollToMap();
		} );
		$( '#service-list' ).on( 'change', function () {
			var subgroupID = $( this ).val();

			if ( subgroupID == 0 ) {
				$( '#serviceDropdownBlock button' ).hide();
			} else {
				$( '#serviceDropdownBlock button' ).show();
			}
		} );
		// when someone clicks "view on map" for VOA Spanish, show all the VOA Spanish countries
		$( '#view-on-map' ).on( 'click', function () {
			var serviceName = $( '#service-list' ).val();
			$( '.service-label' ).html( serviceName );
			hideServiceLabel = false;
			var countryList = Object.keys( servicesByName[ serviceName ].countries );
			//console.log("show language service " + serviceName + " which are " + countryList);
			updateActiveCountries( countryList );
		} );
		$( '#submit' ).on( 'click', function () {
			var url = $( '#service-list option:selected' ).data( 'href' );
			window.open( url, '_blank' );
		} );
		// click on the first element of entity-buttons class (BBG) which will kick off our display.
		$( '.entity-buttons :first' ).click();
	} );
} )( jQuery, bbgConfig, entities );
/*
 $color-primary-alt:          #02bfe7; //blue
 $color-primary-alt-dark:     #00a6d2;
 $color-primary-alt-darkest:  #046b99;
 $color-primary-alt-light:    #9bdaf1; // lighten($color-primary-alt, 60%)
 $color-primary-alt-lightest: #e1f3f8; // lighten($color-primary-alt, 90%)

//bbgConfig={};
//bbgConfig.template_directory_uri = 'https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/';


/*
	 if (entity == 'bbg'){
	 //setColors(base, rollover, selected)
	 setColors('#9F1D26', "#891E25", "#7A1A21");
	 } else if (entity == 'voa'){
	 setColors('#0071bc', "#205493", "#112e51");
	 } else if (entity == 'rfa'){
	 setColors('#4aa564', "#94bfa2", "#2e8540");
	 } else if (entity == 'rferl'){
	 setColors('#FF9214', "#FFAF17", "#CC7510");
	 } else if (entity == 'ocb'){
	 setColors('#653792', "#42245F", "#42245F");
	 } else if (entity == 'mbn'){
	 setColors('#ee433d', "#EE4223", "#BB3530");
	 } else {
	 // there shouldn't be any here
	 setColors('#9F1D26', "#891E25", "#7A1A21");
	 }
 */
/*
// put this back if we ever go back to having a 'select' box at mobile for entities
$('#entity').on('change', function () {
	//map.zoomToLongLat(1, 14.0671, 10.7988, false);
	map.zoomDuration = 0;

	var entity = $(this).val();
	grabData(entity);

	//scrollToMap();
});

// this is for selecting from dropdown
//selectedEntity = $('#entity').val();

// Do we need this scroll?
/*
	function scrollToMap() {
		// scroll to map viewport
		 $('html, body').animate({
		 scrollTop: $('.entry-title').offset().top
		 }, 500);

	}

	 else {
				// this is the default color for non-BBG covered countries
				country.color = '#DDDDDD';
				country.rollOverColor = "#B7B7B7";
			}
*/
/*
function generateLanguagesSupportedString(languages) {

	var languagesString = '';

	for (var i = 0; i < languages.length; i++) {

		// if there's only one language, just show that language itself
		if (languages.length === 1) {
			languagesString = languages[i].name;

			// if there's two languages, concatenate the two together with ' and ' in between
		} else if (languages.length === 2) {
			languagesString = languages[0].name + ' and ' + languages[1].name;

			// if there's more than 2, comma separate them
		} else {
			// if it's not the last language, concatenate the language with a comma and a space
			if (i !== (languages.length - 1)) {
				languagesString += languages[i].name + ', ';

				// if it's the last one, cut off the last comma from the previous concatenation and add the word and
				// along with the last language
			} else {
				languagesString = languagesString.substring(0, languagesString.length - 2) + ', and ' + languages[i].name;
			}
		}

	}

	return languagesString;
}
*/