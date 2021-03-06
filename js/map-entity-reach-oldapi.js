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
	if (entity === 'VOA') {
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
		
		/* keep countries,map as global vars else they won't be available in ajax callbacks */
		countries=[];
		
		map = AmCharts.makeChart( "chartdiv", {
			type: "map",
			borderColor: 'red',
			theme: "light",
			projection:"eckert3",
			dataProvider: {
				map: "worldLow",
				areas: countries
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


		map.addListener('positionChanged', function () {

			// hide the tooltip if the position of the map changes (user drags the map)
			$('.country-label-tooltip').hide();

		});

		map.addListener('zoomCompleted', function (event) {

			// if this was not an entity selected (country), show the tooltip
			if (hideCountryLabel === false && event.chart.zoomLevel() > 1) {
				$('.country-label-tooltip').show();
			}

			if (hideServiceLabel === false) {
				$('.service-label').show();
			}

		});

		//if someone clicks a country that's already selected, zoom out.
		map.addListener("clickMapObject", function (event) {

			hideCountryLabel = false;
			hideServiceLabel = true;

			// hide the service label
			// hide the tooltip for now, it will be reshown after animation is done
			// hide the div until it loads
			$('.service-label').hide();
			$('.usa-width-one-third').hide();
			$('.other-subgroups').hide();
			$('.country-label-tooltip').hide();

			$('.groups-and-subgroups').show();
			$('.other-subgroups').show();

			setCountryLabelPosition(event.chart.selectedObject.name);

			if (!isMobile) {
				map.zoomDuration = .2;
			}

			// only make AJAX calls for stuff we have data for
			for (var i = 0; i < countries.length; i++) {
				if (event.mapObject.id === countries[i].code && countries[i].region_ids) {
					if (window.selectedCountryID && window.selectedCountryID==event.mapObject.id) {
						
						//we had a country selected, and they clicked it again. reset
						window.selectedCountryID = "";
						event.chart.zoomToGroup(countries);
						resetView();

					} else {

						//a country was not previously selected and now one has been clicked
						window.selectedCountryID = event.mapObject.id;
						//alert('get country details');
						getCountryDetails(event.mapObject.title);
					}
				}
			}

			// if it's a country that's covered (if it has region_ids)
			if (event.mapObject.region_ids) {
				// hide any entity details if shown
				$('.entity-details').hide();

				updateCountryName(event.mapObject.title);

				// set the country list value to the same as the map selection
				$('#country-list').val(event.mapObject.id);
			}
		});


		// zoom in on the new entity once it's updated
		map.addListener('dataUpdated', function (event) {
			event.chart.zoomToGroup(countries);

			// this will prevent the countries that are not covered from zooming in on click
			for (var i = 0; i < map.dataProvider.areas.length; i++) {
				if (map.dataProvider.areas[i].region_ids == null) {
					map.dataProvider.areas[i].autoZoomReal = false;
				}
			}
		});

		function setDisplayMode(displayMode) {
			if ( displayMode == "entity") {
				
				window.selectedCountryID = '';

				hideCountryLabel = true;
				hideServiceLabel = true;
	
				$('.country-label-tooltip, .service-label').hide();
				$('.usa-width-one-third, .country-details, .languages-served-block').hide(); //right panel
				$('.other-subgroups').empty(); // remove the other subgroups info
				$('.subgroup-block button').hide(); // hide the subgroup 'view on map' and 'go' buttons


			} else if ( displayMode == "country" ) {

			}
		}

		function setHighlightedEntity(btnObj) {
			$('.entity-buttons button').removeClass('selected');
			$('.entity-buttons button').removeClass('active');

			$(btnObj).addClass('selected');
			$(btnObj).addClass('active');
			
		}

		function setBaseColors() {
			var buttonColor = $('.selected').css('background-color');
			colorBase = buttonColor;
			colorRollOver = shadeColor(buttonColor, -30);
			colorSelected = shadeColor(buttonColor, -50);
		}

		function updateEntityInfo(entity) {
			var en = entities[entity];
			updateCountryName(en.fullName);
			setBaseColors();	//update global vars that are used elsewhere to color the map
			var entityDetailsStr = '<p>' + en.description + '<p>' + 'Website: <a target="_blank" href="'+en.url+'">'+en.url+'</a>';
			$('.entity-details').html(entityDetailsStr).show();

			// if the entity is OCB, hide the subgroup block since there's only one service in there
			if (entity === 'ocb' || entity === 'bbg') {
				$('.subgroup-block').hide();
			} else {
				$('.subgroup-block').show(); // re-show in case it's a different entity
			}
		}

		$('.entity-buttons button').on('click', function () {

			setHighlightedEntity(this); //highlight the appropriate button

			setDisplayMode("entity");
			var entity = $(this).text().toLowerCase();
			updateEntityInfo(entity);
			
			

			// added this in to fix the zoom duration when clicking from entity to entity
			map.zoomDuration = 0;
			grabData(entity);
			
			//scrollToMap();
		});

		// this event listener is for select countries through the drop-down (for mobile devices)
		$('#country-list').on('change', function () {
			var countryCode = $(this).val();

			var mapObject = map.getObjectById(countryCode);
			map.clickMapObject(mapObject);

			scrollToMap();

		});

		//listener for when a list of websites is available in a dropdown and user picks one
		$('#submit').on('click', function () {
			var url = $('#subgroup-list option:selected').data('href');

			window.open(url, '_blank');
		});

		// when someone clicks "view on map" for VOA Spanish, show all the VOA Spanish countries
		$('#view-on-map').on('click', function () {
			var subgroupId = $('#subgroup-list').val();

			hideCountryLabel = true;
			hideServiceLabel = false;

			$('.groups-and-subgroups').hide();
			$('.other-subgroups').hide();
			$('.service-label').text($("#subgroup-list>option:selected").html());

			var url = bbgConfig.template_directory_uri + 'api.php?endpoint=api/countries?subgroup=' + subgroupId;
			getCountries(url);
			var subgroupUrl = bbgConfig.template_directory_uri + 'api.php?endpoint=api/languages?subgroup=' + subgroupId;
			getSupportedLanguagesBySubgroup(subgroupUrl);
		});


		$('#subgroup-list').on('change', function () {
			var subgroupID = $(this).val();
			if (subgroupID > 0) {
				$('.subgroup-block button').show();
			} else {
				$('.subgroup-block button').hide();
			}
		});

		// click on the first element of entity-buttons class (BBG) which will kick off our display.
		$('.entity-buttons :first').click();

	});

	/* Do we need this scroll? */
	function scrollToMap() {
		// scroll to map viewport
		/*
		 $('html, body').animate({
		 scrollTop: $('.entry-title').offset().top
		 }, 500);
		 */
	}

	// this function will set the endpoint based on the entity and then go fetch the countries
	function grabData(entity) {

		var url = '';
		// this is an organization (BBG)
		if (entity === 'bbg') {
			url = bbgConfig.template_directory_uri + 'api.php?endpoint=api/countries/?region_country=1';
			// if there's an entity (group), get it by entity

			$('.subgroup-block').hide();
		} else {
			url = bbgConfig.template_directory_uri + 'api.php?endpoint=api/countries/?group='+entity;

			getSubgroupsForEntity(entity);

			$('.subgroup-block').show();
		}

		$('#loading').show();

		getCountries(url);


	}

	// this is the first ajax request made when the widget loads to populate all the countries BBG covers
	function getCountries (url) {
		$.getJSON(url)
			.done(function( data ) {
				countries=[];
				for (var i = 0; i < data.countries.length; i++) {
					var country = data.countries[i];
					var countryCode = data.countries[i].code;
					country.id = countryCode;

					// if the country has region_ids array, BBG has coverage there
					if (country.region_ids) {
						country.color = colorBase;
						country.rollOverColor = colorRollOver;
						country.selectedColor = colorSelected;
					} else { 
						// this is the default color for non-BBG covered countries
						country.color = '#DDDDDD';
						country.rollOverColor = "#B7B7B7";
					}

					countries.push(country);


				}


				addCountriesToDropdown(countries);

				map.dataProvider.areas = countries;
				map.validateData();

				// this is for selecting from dropdown
				//selectedEntity = $('#entity').val();

				selectedEntity = $('.entity-buttons .active').text().toLowerCase();
				entityDesc='';
				headerText='';
				if (entities[selectedEntity] != null) {
					entityDesc=entities[selectedEntity].description;
					headerText=entities[selectedEntity].fullName;
				}
				updateCountryName(headerText);
				$('.detail').html(entityDesc);

				$('#loading').hide();
			})
			.fail(function( jqxhr, textStatus, error ) {
				var err = textStatus + ", " + error;
				alert("We're sorry, we were unable to load the map data at this time.  Please check back shortly. (" + err + ")");
			});
	}

	// grabs the details for a country when selected
	function getCountryDetails (countryName) {
		$('.detail, .groups-and-subgroups, .other-subgroups, .languages-served').empty();

		var groups = [];
		var subgroups = [];
		var languages = [];

		// create a map of groups and subgroups
		var groupMap = {};
		var subgroupMap = {};

		$.when(
			$.getJSON(bbgConfig.template_directory_uri+"api.php?endpoint=api/groups/?country=" + countryName, function( data ) {
				groups = data.groups;
			}),

			$.getJSON( bbgConfig.template_directory_uri+"api.php?endpoint=api/subgroups/?country=" + countryName, function( data ) {
				subgroups = data.subgroups;
			}),

			$.getJSON( bbgConfig.template_directory_uri+"api.php?endpoint=api/languages/?country=" + countryName, function( data ) {
				languages = data.languages;
			})

		).then(function() {

				for (var i = 0; i < groups.length; i++) {
					// map out the group_id to the group name
					groupMap[groups[i].id] = {
						name: groups[i].name,
						url: groups[i].website_url
					};

					// instantiate the subgroup map with an empty array that is mapped to the group_id
					subgroupMap[groups[i].id] = [];
				}

				for (var i = 0; i < subgroups.length; i++) {
					// push the list of subgroups (as JSON objects) to the subgroupMap based on the group_id
					subgroupMap[subgroups[i].group_id].push(
						{
							name: subgroups[i].name,
							url: subgroups[i].website_url
						}
					);
				}

				var languagesString = generateLanguagesSupportedString(languages);

				$('.languages-served').text(languagesString);
				$('.languages-served-block').show();


				var groupAndSubgroupList = '';
				var groupsAndSubgroups = $('.groups-and-subgroups');

				for (key in subgroupMap) {

					// Append the Group Name (VOA, RFA, etc.)
					groupAndSubgroupList += '<div class="'+groupMap[key].name+'-block">';
					groupAndSubgroupList += '<h3><a target="_blank" href="'+groupMap[key].url+'">'+groupMap[key].name+'</a></h3>';
					groupAndSubgroupList += '<ul class="bbg__map-area__list">';

					// Loop through the corresponding subgroups and list out the Subgroup name
					for (var i = 0; i < subgroupMap[key].length; i++) {
						// if there's a URL, use href with the list item
						if (subgroupMap[key][i].url) {
							groupAndSubgroupList += '<li class="bbg__map-area__list-item"><a target="_blank" href="'+subgroupMap[key][i].url+'">'+subgroupMap[key][i].name+'</a></li>';
							// if no URL, just use regular list item
						} else {
							groupAndSubgroupList += '<li>'+subgroupMap[key][i].name+'</li>';
						}
					}

					groupAndSubgroupList += '</ul>';
					//groupAndSubgroupList += '<br>';
					groupAndSubgroupList += '</div>';

				}

				// populate the HTML element with the dynamically generated string
				groupsAndSubgroups.html(groupAndSubgroupList);

				// grab the subgroup block by the entity name that's selected
				//var entityName = $('#entity').val().toUpperCase();
				var entityName = $('.entity-buttons .active').text().toUpperCase();


				// for entity specific selections, the groups are listed out with their subgroups
				// in priority of what is selected
				if (entityName !== 'BBG') {
					// prepend it in the list so it's prioritized based on entity selected
					var primaryEntityBlock = $('.' + entityName + '-block');

					// set the other subgroups to the rest of the data (replace all with the primary entity block
					// to get the difference of the rest
					$('.other-subgroups').html(groupsAndSubgroups.html().replace(primaryEntityBlock.html(), ''));

					groupsAndSubgroups.html(primaryEntityBlock);
				}



				var countryDesc = ''; //'Country Desc Updated ' + (new Date()).getTime() + ' ' + fakeDetail;
				$('.detail').html(countryDesc);

				$('.country-details').show();
			});
	}

	// this function will make an ajax request to get the subgroups and then dynamically populate the dropdown
	// for the purpose of loading the language services in a different tab
	function getSubgroupsForEntity (entity) {
		// reset the list
		$('#subgroup-list').empty();

		$.getJSON(bbgConfig.template_directory_uri + 'api.php?endpoint=api/subgroups?group=' + entity)
			.done(function( data ) {
				var subgroupListString = '';
				var selectedEntity = $('.entity-buttons .active').text();
				var article = getArticleByEntity(selectedEntity);
				subgroupListString += '<option value="0">Select ' +article + ' ' + selectedEntity + ' service...</option>';
				for (var i = 0; i < data.subgroups.length; i++) {
					var subgroup = data.subgroups[i];
					subgroupListString += '<option value="'+subgroup.id+'" data-href="'+subgroup.website_url+'">'+subgroup.name+'</option>';
				}

				$('#subgroup-list').html(subgroupListString);

			})
			.fail(function( jqxhr, textStatus, error ) {
				var err = textStatus + ", " + error;
				alert("We're sorry, we were unable to load the data at this time.  Please check back shortly. (" + err + ")");
			});
	}

	// this function will dynamically generate the dropdown list for the countries (on mobile devices)
	function addCountriesToDropdown (countriesDropdown) {
		$('#country-list').empty();
		var countryListString = '';

		countryListString += '<option value="0">Select a country...</option>';
		for (var i = 0; i < countriesDropdown.length; i++) {
			// if we have data for it
			if (countriesDropdown[i].region_ids) {
				var country = countriesDropdown[i];
				countryListString += '<option value="'+country.code+'">'+country.name+'</option>';
			}
		}

		$('#country-list').html(countryListString);

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
		$('#subgroup-list').val(0);

		// resets current selected object to nothing;
		map.selectObject();

		hideCountryLabel = true;
	}

	//small function make sure we hit every label on the map
	function updateCountryName(newName) {
		$('#country-name').text(newName);
		$('#country-name-panel').text(newName);
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

	function getSupportedLanguagesBySubgroup(subgroupUrl) {

		$.ajax({
			url: subgroupUrl,
			success: function (result) {
				var languagesString = generateLanguagesSupportedString(result.languages);

				$('.languages-served').text(languagesString);
				$('.languages-served-block').show();
			}
		});
	}


	$(document).ajaxStop(function () {
		//$('#country-name').text($('#country-name').text().replace('Loading ', '').replace(' ...', ''));
		$('.usa-width-one-third').show();

		// don't show subgroups if a service is the focal point
		if (hideServiceLabel === true) {
			$('.other-subgroups').show();
		}
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
*/