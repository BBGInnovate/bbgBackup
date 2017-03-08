// this function will return true if the user is on a mobile device or false otherwise
function isMobileDevice () {
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
		return true;
	} else {
		return false;
	}
}

//Select "a" VOA Country vs Select "an" OCB country
function getArticleByEntity (entity) {
	if (entity.toLowerCase() === 'voa') {
		return 'a';
	} else {
		return 'an';
	}
}

function shadeColor(color, percent) {
	var range = color.substring(4, color.length - 1);
	var rangeArr = range.split(',');

	var r = parseInt(rangeArr[0]) + percent;
	var g = parseInt(rangeArr[1]) + percent;
	var b = parseInt(rangeArr[2]) + percent;

	var newColor = 'rgb(' + r + ',' + g + ',' + b  + ')';
	return newColor;
}


(function ($,bbgConfig, entities) {

	// find out if the user is on a mobile device or not (used for zoomDuration)
	var isMobile = isMobileDevice();

	// this global variable is used to determine whether or not the current item selected is an entity or country
	var hideCountryLabel = true;
	var hideServiceLabel = true;

	/* note that these colors are changed later */
	var colorBase = '#0071bc',
		colorRollOver = '#205493',
		colorSelected = '#112e51';

	$(document).ready(function() {

		var defaultEntity='bbg'; //might fill this from a global JS var later.
		
		/* keep activeCountries & map as global vars else they won't be available in ajax callbacks */
		activeCountries=[];
		
		/* create an ordered array of country names - nice to have later */
		fullCountryList = Object.keys(countriesByName);
		
		/* create a lookup for our country data based on the ammap code */
		countriesByID = [];
		for ( i=0; i<fullCountryList.length; i++) {
			var cname = fullCountryList[i];
			var countryID = countriesByName[cname].ammapCode;
			countriesByID[countryID] = countriesByName[cname];
		}
		
		map = AmCharts.makeChart( "chartdiv", {
			type: "map",
			borderColor: 'red',
			theme: "light",
			projection:"eckert3",
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
				balloonText: '[[title]]'
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
			zoomDuration:0,
			backgroundZoomsToTop: false //water zooms out
		} );


		// hide the tooltip if the position of the map changes (user drags the map)
		map.addListener('positionChanged', function () {
			$('.country-label-tooltip').hide();
		});

		// some UI tips related to country / service labels when zoom completes
		map.addListener('zoomCompleted', function (event) {
			if (currentDisplayMode == "country" &&  event.chart.zoomLevel() > 1) {
				$('.country-label-tooltip').show();
			}
			
			if (hideServiceLabel === false) {
				$('.service-label').show();
			}
		});

		window.selectedCountryID = '';

		//if someone clicks a country that's already selected, zoom out.
		map.addListener("clickMapObject", function (event) {

			hideServiceLabel = false;
			hideCountryLabel = false;
			setCountryLabelPosition(event.chart.selectedObject.name);

			if (!isMobile) {
				map.zoomDuration = .2;
			}

			if (window.selectedCountryID && window.selectedCountryID==event.mapObject.id) {
				//we had a country selected, and they clicked it again. reset
				window.selectedCountryID = "";
				event.chart.zoomToGroup(activeCountries);
				resetView();

			} else {
				//a country was not previously selected and now one has been clicked
				displayCountry(event.mapObject.id);
			}
		});

		// zoom in on the new entity once it's updated
		map.addListener('dataUpdated', function (event) {
			event.chart.zoomToGroup(activeCountries);

			// prevent countries that are not covered from zooming in on click
			// for (var i = 0; i < map.dataProvider.areas.length; i++) {
			// 	if (map.dataProvider.areas[i].region_ids == null) {
			// 		map.dataProvider.areas[i].autoZoomReal = false;
			// 	}
			// }

		});

		function setDisplayMode(displayMode) {
			
			currentDisplayMode = displayMode;

			if ( displayMode == "entity") {
				
				$('.country-label-tooltip').hide();
				$('#country-name').hide();
				$('#entityDisplay').show();
				$('#countryDisplay').hide();

				window.selectedCountryID = '';

	
			} else if ( displayMode == "country" ) {
				
				$('.country-label-tooltip').show();
				$('#country-name').show();
				$('#entityDisplay').hide();
				$('#countryDisplay').show();


			}
		}

		function setHighlightedEntity(entity) {
			$('.entity-buttons button').removeClass('selected active');
			$('.entity-buttons button.'+entity).addClass('selected active');
		}

		function setBaseColors() {
			var buttonColor = $('.selected').css('background-color');
			colorBase = buttonColor;
			colorRollOver = shadeColor(buttonColor, -30);
			colorSelected = shadeColor(buttonColor, -50);
		}

		function updateEntityInfo(entity) {
			
		}

		function displayEntity(entity) {
			selectedEntity = entity;
			setHighlightedEntity(entity);
			setBaseColors();	//update global vars that are used to color the map

			/**** loop through all of the countries we have, and if they're a part of this network, add them to our activecountries array */
			activeCountries=[];
			for (var i = 0; i < fullCountryList.length; i++) {
				var countryName = fullCountryList[i];
				if (entity=="bbg" || entitiesByName[entity].countries.hasOwnProperty(countryName)) {
					var countryCode = countriesByName[countryName].ammapCode;
					country = {};
					country.name = countryName;
					country.countryName = countryName;
					country.id = countryCode;
					country.color = colorBase;
					country.rollOverColor = colorRollOver;
					country.selectedColor = colorSelected;
					country.selectable = true;
					activeCountries.push(country);
				}
			}

			map.dataProvider.areas = activeCountries;
			map.validateData();

			var en = entities[entity];
			$('#entityName').html(en.fullName);

			var entityDetailsStr = '<p>' + en.description + '<p>' + 'Website: <a target="_blank" href="'+en.url+'">'+en.url+'</a>';
			$('.entity-details').html(entityDetailsStr).show();


			if (entity != "bbg") {
				$('#service-list').empty();
				var subgroupListString = '';
				var article = getArticleByEntity(selectedEntity);
				subgroupListString += '<option value="0">Select ' +article + ' ' + selectedEntity.toUpperCase() + ' service...</option>';
				for (var i = 0; i < entitiesByName[entity].services.length; i++) {
					var srv = entitiesByName[entity].services[i];
					var srvo = servicesByName[srv];
					subgroupListString += '<option value="'+srvo.siteUrl+'" data-href="'+srvo.siteUrl+'">'+srvo.serviceName+'</option>';
				}
				$('#service-list').html(subgroupListString);
			}
			

			setDisplayMode('entity');
		}

		function displayCountry(selectedCountryID) {
			window.selectedCountryID = selectedCountryID;
			countryName = countriesByID[selectedCountryID].countryName;

			$('h4.country-label-tooltip #country-name').html(countryName);	
			$('#countryDisplay h2#countryName').html(countryName);
			
			var networks = countriesByName[countryName].networks;
			var s = '';

			var newSortOrder = [];
			var firstItemIndex = -1;
			//we alternate the order of the networks if an entity is selected

			var desiredNetworkOrder = ["voa","rferl","ocb","rfa","mbn"];
			for (var j=0; j < desiredNetworkOrder.length; j++) {
				if (desiredNetworkOrder[j] == selectedEntity) {
					var temp = desiredNetworkOrder[j];
					desiredNetworkOrder[j] = desiredNetworkOrder[0];
					desiredNetworkOrder[0]= temp;
				}
			}

			for (var j=0; j<desiredNetworkOrder.length; j++) {
				for (var k=0; k < networks.length; k++) {
					if (networks[k].networkName.toLowerCase() == desiredNetworkOrder[j]) {
						newSortOrder.push(k);
					}
				}
			}

			for (var i=0; i < newSortOrder.length; i++) {
				var sortedIndex = newSortOrder[i];
				var n = networks[sortedIndex];
				s += '<h3><a target="_blank" href="' + n.siteUrl + '">' + n.networkName + '</a></h3>';
				s += '<ul class="bbg__map-area__list">';
				for (var j=0; j < n.services.length; j++) {
					var srv = n.services[j];
					var srvo = servicesByName[srv];

					s += '<li class="bbg__map-area__list-item"><a target="_blank" href="' + srvo.siteUrl + '">' + srvo.serviceName+'</a></li>';
				}
				s += '</ul>';
			}

			$('.service-block').html(s);

			setDisplayMode('country');
		}

		function resetView () {
			// show entity stuff here
			$('.usa-width-one-third').show();

			// slight delay for purpose of better UI appearance with zoom duration
			setTimeout(function() {
				$('.entity-details').show();
				var selectedEntity = $('.entity-buttons .active').text().toLowerCase();
				$('#country-name-panel').text(entities[selectedEntity].fullName);
			}, 100);

			$('.country-details').hide();

			// reset the subgroup list
			$('#service-list').val(0);

			// resets current selected object to nothing;
			map.selectObject();

			hideCountryLabel = true;
		}

		// these countries require special left position adjustments
		function setCountryLabelPosition(country) {
			var clp = {};
			clp['Chile'] = '19';
			clp['Croatia'] = '22.5';
			clp['Vietnam'] = '27';
			var leftPos = '25';
			if (clp.hasOwnProperty(country)) {
				leftPos = clp[country];
			}
			$('.country-label-tooltip').css('left', leftPos + '%');
		}

		$('.entity-buttons button').on('click', function () {
			var entity = $(this).text().toLowerCase();
			displayEntity(entity);
		});

		$('#submit').on('click', function () {
			var url = $('#service-list option:selected').data('href');

			window.open(url, '_blank');
		});

		// click on the first element of entity-buttons class (BBG) which will kick off our display.
		$('.entity-buttons :first').click();

	});

	
	

	
})(jQuery,bbgConfig, entities);

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