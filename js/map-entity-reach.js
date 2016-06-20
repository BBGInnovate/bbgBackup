(function ($,bbgConfig) {

	// find out if the user is on a mobile device or not (used for zoomDuration)
	var isMobile = isMobileDevice();

	$(document).ready(function() {

		var defaultEntity='bbg'; //might fill this from a global JS var later.
		bbgConfig={};
		bbgConfig.template_directory_uri = 'https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/';
		/* keep countries,map as global variables else they won't be available in the ajax callback */
		countries=[];
		fakeDetail="Following economic and political turmoil during President Boris YELTSIN's term (1991-99), Russia shifted toward a centralized authoritarian state under the leadership of President Vladimir PUTIN (2000-2008, 2012-present) in which the regime seeks to legitimize its rule through managed elections, populist appeals, a foreign policy focused on enhancing the country's geopolitical influence, and commodity-based economic growth. Russia faces a largely subdued rebel movement in Chechnya and some other surrounding regions, although violence still occurs throughout the North Caucasus.";

		// hide the countries dropdown list if user isnt on a mobile device
		if (!isMobile) {
			$('#country-list').hide();
		}

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
				selectedColor: "#7A1A21",
				rollOverColor: "#891E25",
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


			if (window.selectedCountryID && window.selectedCountryID==event.mapObject.id) {
				window.selectedCountryID = "";
				event.chart.zoomToGroup(countries);
			} else {
				window.selectedCountryID = event.mapObject.id;
				getCountryDetails(event.mapObject.title);
				//getAPIDataCallback(event.mapObject.title);
			}

			$('#country-name').text(event.mapObject.title);
		});


		// zoom in on the new entity once it's updated
		map.addListener('dataUpdated', function (event) {

			//setTimeout(function () {
			event.chart.zoomToGroup(countries);
			//}, 1000);


		});

		map.addListener('click', function (event) {

			if (!isMobile) {
				map.zoomDuration = .2;
			}
		});


		$('#entity').on('change', function () {
			//map.zoomToLongLat(1, 14.0671, 10.7988, false);
			map.zoomDuration = 0;


			var entity = $(this).val();
			grabData(entity);


		});

		// this event listener is for select countries through the drop-down (for mobile devices)
		$('#country-list').on('change', function () {
			var countryCode = $(this).val();

			var mapObject = map.getObjectById(countryCode);
			map.clickMapObject(mapObject);

		});

		$('#submit').on('click', function () {
			var url = $('#subgroup-list option:selected').data('href');

			window.open(url, '_blank');
		});


		//load our initial data
		grabData(defaultEntity);


	});



	// this function will set the endpoint based on the entity and then go fetch the countries
	function grabData(entity) {

		var url = '';
		// this is an organization (BBG)
		if (entity === 'bbg') {
			url = bbgConfig.template_directory_uri + 'api.php?endpoint=api/countries/?region_country=1';
			// if there's an entity (group), get it by entity
		} else {
			url = bbgConfig.template_directory_uri + 'api.php?endpoint=api/countries/?group='+entity;

			getSubgroupsForEntity(entity);
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

					// this is the default color for non-BBG covered countries
					country.color = '#DDDDDD';

					// if the country has region_ids array, BBG has coverage there
					if (country.region_ids) {
						country.color = '#9F1D26';
					}

					countries.push(country);

					// if the user is on a mobile device, build out the country list dropdown
					if (isMobile) {
						addCountryToDropdown(country);
					}
				}
				map.dataProvider.areas = countries;
				map.validateData();
				var entityDesc = 'Entity Desc Updated ' + (new Date()).getTime() + ' ' + fakeDetail;
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

				for (key in subgroupMap) {

					// Append the Group Name (VOA, RFA, etc.)
					$('.groups-and-subgroups').append('<h3><a target="_blank" href="'+groupMap[key].url+'">'+groupMap[key].name+'</a></h3>');
					$('.groups-and-subgroups').append('<ul>');

					// Loop through the corresponding subgroups and list out the Subgroup name
					for (var i = 0; i < subgroupMap[key].length; i++) {
						// if there's a URL, use href with the list item
						if (subgroupMap[key][i].url) {
							$('.groups-and-subgroups').append('<li><a target="_blank" href="'+subgroupMap[key][i].url+'">'+subgroupMap[key][i].name+'</a></li>');
							// if no URL, just use regular list item
						} else {
							$('.groups-and-subgroups').append('<li>'+subgroupMap[key][i].name+'</li>');
						}
					}

					$('.groups-and-subgroups').append('</ul>');
					$('.groups-and-subgroups').append('<br>');
				}
				$('.country-details').show();
				var countryDesc = 'Country Desc Updated ' + (new Date()).getTime() + ' ' + fakeDetail;
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
				for (var i = 0; i < data.subgroups.length; i++) {
					var subgroup = data.subgroups[i];
					$('#subgroup-list').append('<option value="'+subgroup.id+'" data-href="'+subgroup.website_url+'">'+subgroup.name+'</option>');
				}
			})
			.fail(function( jqxhr, textStatus, error ) {
				var err = textStatus + ", " + error;
				alert("We're sorry, we were unable to load the data at this time.  Please check back shortly. (" + err + ")");
			});
	}

	// this function will dynamically generate the dropdown list for the countries (on mobile devices)
	function addCountryToDropdown (country) {
		$('#country-list').append('<option value="'+country.code+'">'+country.name+'</option>');
	}

	// this function will return true if the user is on a mobile device or false otherwise
	function isMobileDevice () {
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
			return true;
		} else {
			return false;
		}
	}

})(jQuery,bbgConfig);