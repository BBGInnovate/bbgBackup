(function ($,bbgConfig, entities) {

	// find out if the user is on a mobile device or not (used for zoomDuration)
	var isMobile = isMobileDevice();

	$(document).ready(function() {

		var defaultEntity='bbg'; //might fill this from a global JS var later.
		//bbgConfig={};
		//bbgConfig.template_directory_uri = 'https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/';
		/* keep countries,map as global variables else they won't be available in the ajax callback */
		countries=[];
		//fakeDetail="Following economic and political turmoil during President Boris YELTSIN's term (1991-99), Russia shifted toward a centralized authoritarian state under the leadership of President Vladimir PUTIN (2000-2008, 2012-present) in which the regime seeks to legitimize its rule through managed elections, populist appeals, a foreign policy focused on enhancing the country's geopolitical influence, and commodity-based economic growth. Russia faces a largely subdued rebel movement in Chechnya and some other surrounding regions, although violence still occurs throughout the North Caucasus.";
		fakeDetail="xxxx";

		// hide the countries dropdown list if user isnt on a mobile device

		// this is now hidden with media query
		/*
		 if (!isMobile) {
		 $('#country-list').hide();
		 }
		 */

		/*
		 $color-primary-alt:          #02bfe7; //blue
		 $color-primary-alt-dark:     #00a6d2;
		 $color-primary-alt-darkest:  #046b99;
		 $color-primary-alt-light:    #9bdaf1; // lighten($color-primary-alt, 60%)
		 $color-primary-alt-lightest: #e1f3f8; // lighten($color-primary-alt, 90%)
		 */

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
				//selectedColor: "#7A1A21",
				selectedColor: "#DDDDDD",
				//rollOverColor: "#891E25",
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


		//if someone clicks a country that's already selected, zoom out.
		map.addListener("clickMapObject", function (event) {
			if (!isMobile) {
				map.zoomDuration = .2;
			}


			// only make AJAX calls for stuff we have data for
			for (var i = 0; i < countries.length; i++) {
				if (event.mapObject.id === countries[i].code && countries[i].region_ids) {
					if (window.selectedCountryID && window.selectedCountryID==event.mapObject.id) {
						window.selectedCountryID = "";
						event.chart.zoomToGroup(countries);
					} else {
						window.selectedCountryID = event.mapObject.id;
						getCountryDetails(event.mapObject.title);
						//getAPIDataCallback(event.mapObject.title);
					}
				}

			}


			// if it's a country that's covered (if it has region_ids)
			if (event.mapObject.region_ids) {
				// hide any entity details if shown
				$('.entity-details').hide();

				$('#country-name').text(event.mapObject.title);

				// set the country list value to the same as the map selection
				$('#country-list').val(event.mapObject.id);
			}
		});


		// zoom in on the new entity once it's updated
		map.addListener('dataUpdated', function (event) {
			event.chart.zoomToGroup(countries);

			// this will prevent the countries that are not covered from zooming in on click
			for (var i = 0; i < map.dataProvider.areas.length; i++) {
				//console.log(map.dataProvider.areas[i]);
				if (map.dataProvider.areas[i].region_ids == null) {
					map.dataProvider.areas[i].autoZoomReal = false;
				}
			}
		});




		$('#entity').on('change', function () {
			//map.zoomToLongLat(1, 14.0671, 10.7988, false);
			map.zoomDuration = 0;

			var entity = $(this).val();
			grabData(entity);

			//scrollToMap();
		});


		$('.entity-buttons button').on('click', function () {

			var entity = $(this).text().toLowerCase();
			$('.entity-buttons button').removeClass('selected');
			$(this).addClass('selected');

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
				/* there shouldn't be any here */
				setColors('#9F1D26', "#891E25", "#7A1A21");
			}
//
			var fullName = entities[entity].fullName;

			// reset buttons
			$('.entity-buttons button').removeClass('active');

			// hide any country details divs
			$('.country-details').hide();

			// show the entity details div
			$('.entity-details').html('<p>' + entities[entity].description + '<p>' + 'Website: <a target="_blank" href='+entities[entity].url+'>'+entities[entity].url+'</a>').show();

			// add the active class so it looks like it's selected
			$(this).addClass('active');

			// added this in to fix the zoom duration when clicking from entity to entity
			map.zoomDuration = 0;

			window.selectedCountryID = '';

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

		$('#submit').on('click', function () {
			var url = $('#subgroup-list option:selected').data('href');

			window.open(url, '_blank');
		});

		// preview the subgroups on the map
		$('#view-on-map').on('click', function () {
			var subgroupId = $('#subgroup-list').val();

			var url = bbgConfig.template_directory_uri + 'api.php?endpoint=api/countries?subgroup=' + subgroupId;

			getCountries(url);
		});

		//load our initial data
		//grabData(defaultEntity);

		// click on the first element of entity-buttons class (BBG)
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

	var colorBase = '#0071bc',
		colorRollOver = "#205493"
		colorSelected = "#112e51";

	function setColors(base, rollover, selected){
		colorBase = base;
		colorRollOver = rollover;
		colorSelected = selected;
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
						/*
						country.color = '#9F1D26';
						country.rollOverColor = "#891E25";
						country.selectedColor = "#7A1A21";
						*/
						country.color = colorBase;
						country.rollOverColor = colorRollOver;
						country.selectedColor = colorSelected;

						// NO COVERAGE here
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
				$('#country-name').text(headerText);
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
		$('.detail').empty();
		$('.groups-and-subgroups').empty();
		$('.languages-served').empty();

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

				$('.languages-served').text(languagesString);


				var groupAndSubgroupList = '';
				for (key in subgroupMap) {

					// Append the Group Name (VOA, RFA, etc.)
					groupAndSubgroupList += '<div class="'+groupMap[key].name+'-block">';
					groupAndSubgroupList += '<h3><a target="_blank" href="'+groupMap[key].url+'">'+groupMap[key].name+'</a></h3>';
					groupAndSubgroupList += '<ul>';

					// Loop through the corresponding subgroups and list out the Subgroup name
					for (var i = 0; i < subgroupMap[key].length; i++) {
						// if there's a URL, use href with the list item
						if (subgroupMap[key][i].url) {
							groupAndSubgroupList += '<li><a target="_blank" href="'+subgroupMap[key][i].url+'">'+subgroupMap[key][i].name+'</a></li>';
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
				$('.groups-and-subgroups').html(groupAndSubgroupList);

				// grab the subgroup block by the entity name that's selected
				//var entityName = $('#entity').val().toUpperCase();
				var entityName = $('.entity-buttons .active').text().toUpperCase();

				// prepend it in the list so it's prioritized based on entity selected
				$('.groups-and-subgroups').prepend($('.' + entityName + '-block'));

				$('.country-details').show();
				var countryDesc = ''; //'Country Desc Updated ' + (new Date()).getTime() + ' ' + fakeDetail;
				$('.detail').html(countryDesc);

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
			var country = countriesDropdown[i];
			countryListString += '<option value="'+country.code+'">'+country.name+'</option>';
		}

		$('#country-list').html(countryListString);

	}

	// this function will return true if the user is on a mobile device or false otherwise
	function isMobileDevice () {
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
			return true;
		} else {
			return false;
		}
	}

	function getArticleByEntity (entity) {
		if (entity === 'VOA') {
			return 'a';
		} else {
			return 'an';
		}
	}

})(jQuery,bbgConfig, entities);