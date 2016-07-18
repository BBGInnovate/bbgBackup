(function ($,bbgConfig, entities) {

	// find out if the user is on a mobile device or not (used for zoomDuration)
	var isMobile = isMobileDevice();

	$(document).ready(function() {

		$.growl({ title: "Welcome", message: "Click on a sphere of influence to see the countries affiliated." });

		var defaultEntity='bbg'; //might fill this from a global JS var later.

		countries=[];


		map = AmCharts.makeChart( "chartdiv", {
			type: "map",
			borderColor: "red",
			theme: "light",
			projection:"eckert3",
			dataProvider: {
				map: "worldLow",
				//areas: countries
				areas: []
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





		$('.entity-buttons button').on('click', function () {
			$('.entity-buttons button').removeClass('active');

			var sphere = $(this).text();

			var sphereCountries = entities[sphere].countries;

			var sphereCountriesGroup = [];

			for (var i = 0; i < countries.length; i++) {
				for (var j = 0; j < sphereCountries.length; j++) {
					if (countries[i].name === sphereCountries[j]) {
						sphereCountriesGroup.push(countries[i]);
					}
				}
			}

			map.dataProvider.areas = sphereCountriesGroup;
			map.validateData();

			map.zoomToGroup(sphereCountriesGroup);

			$(this).addClass('active');

			$('#sphere-name').text(sphere);

			//scrollToMap();
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

		} else {
			url = bbgConfig.template_directory_uri + 'api.php?endpoint=api/countries/?group='+entity;

		}


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
						country.color = '#9F1D26';
						country.rollOverColor = "#891E25";
						country.selectedColor = "#7A1A21";


					// NO COVERAGE here
					} else {
						// this is the default color for non-BBG covered countries
						country.color = '#DDDDDD';
						country.rollOverColor = "#B7B7B7";

						// these zoom levels will prevent zoom on countries that are not covered
						country.zoomLatitude = map.zoomLatitude();
						country.zoomLongitude = map.zoomLongitude();
						country.zoomLevel = map.zoomLevel();

					}


					countries.push(country);

				}


			})
			.fail(function( jqxhr, textStatus, error ) {
				var err = textStatus + ", " + error;
				alert("We're sorry, we were unable to load the map data at this time.  Please check back shortly. (" + err + ")");
			});
	}



	// this function will return true if the user is on a mobile device or false otherwise
	function isMobileDevice () {
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
			return true;
		} else {
			return false;
		}
	}

})(jQuery,bbgConfig, entities);