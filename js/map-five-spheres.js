var entities={
	"Russia": {
	    countries: ['Russia', 'Ukraine']
	},
	"China": {
	    countries: ['China']
	},
	"Cuba": {
	    countries: ['Cuba']
	},
	"Iran": {
	    countries: ['Iran']
	},
	"Violent Extremism": {
	    countries: ['Syria', 'Pakistan', 'Afghanistan']
	},
	"Subsaharan Africa": {
	    countries: ['Nigeria', 'Libya']
	}
};



(function ($,bbgConfig, entities) {
	bbgConfig.template_directory_uri = 'https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/';

	

	$(document).ready(function() {

		function setActiveSphere(sphere) {
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
		}

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
					//console.log("countries " + countries);
					setActiveSphere('Subsaharan Africa');


				})
				.fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ", " + error;
					alert("We're sorry, we were unable to load the map data at this time.  Please check back shortly. (" + err + ")");
				});
		}


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
			dragMap:false,
			areasSettings: {
				autoZoom: false,
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
				maxZoomLevel: 4,
				minZoomLevel:4,
				zoomControlEnabled: false,
				panControlEnabled: false,
				homeButtonEnabled: false
				//,buttonSize:0,
			},
			zoomOnDoubleClick: false,
			panEventsEnabled: false,
			preventDragOut: true,
			
			zoomDuration:0,
			backgroundZoomsToTop: false //water zooms out
		} );

		grabData('bbg');
	});

})(jQuery,bbgConfig, entities);