
jQuery.getJSON( bbgConfig.template_directory_uri+"/api.php?endpoint=api/countries/?region_country=1" )
	.done(function( data ) {
		var countries = [];
		for (var i = 0; i < data.countries.length; i++) {
			var country = data.countries[i];

			var countryCode = data.countries[i].code;


			country.id = countryCode;
			country.color = '#DDDDDD';

			if (country.region_ids) {
				country.color = '#9F1D26';

			}

			countries.push(country);
		}

		var map = AmCharts.makeChart( "chartdiv", {

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

				selectable: true
			},


			/*
			 legend: {
			 width: "100%",
			 marginRight: 27,
			 marginLeft: 27,
			 equalWidths: false,
			 backgroundAlpha: 0.5,
			 backgroundColor: "#FFFFFF",
			 borderColor: "#ffffff",
			 borderAlpha: 1,
			 top: 400,
			 left: 0,
			 horizontalGap: 2,
			 verticalGap: 12,
			 data: legendData
			 },
			 */
			"export": {
				"enabled": true
			}

		} );



		jQuery('#loading').hide();


		map.addListener("clickMapObject", function (event) {
			//	getAPIData(event.mapObject.title);
			getAPIDataCallback(event.mapObject.title);
		});

		map.addListener('homeButtonClicked', function (event) {
			jQuery('.country-details').hide();

			jQuery('.detail').empty();
		});
	})
	.fail(function( jqxhr, textStatus, error ) {
		var err = textStatus + ", " + error;
		alert("Request Failed: " + err);
	});




/*
function getColorByRegion (regions) {
	if (regions) {
		for (var i = 0; i < regions.length; i++) {
			var regionId = regions[i];

			if (regionsMap[regionId]) {
				return regionsMap[regionId].color;
			} else {
				return 'yellow';
			}
		}
	}
};
	*/



function getAPIDataCallback (countryName) {
	jQuery('.detail').empty();

	var groups = [];
	var subgroups = [];
	var languages = [];

	jQuery.when(
		jQuery.getJSON( bbgConfig.template_directory_uri+"/api.php?endpoint=api/groups/?country=" + countryName, function( data ) {
			groups = data.groups;
		}),

		jQuery.getJSON( bbgConfig.template_directory_uri+"/api.php?endpoint=api/subgroups/?country=" + countryName, function( data ) {
			subgroups = data.subgroups;
		}),

		jQuery.getJSON( bbgConfig.template_directory_uri+"/api.php?endpoint=api/languages/?country=" + countryName, function( data ) {
			languages = data.languages;
		})

	).then(function() {

		for (var i = 0; i < groups.length; i++) {
			jQuery('#groups').append('<li><a target="_blank" href="'+groups[i].website_url+'">'+groups[i].name+'</a></li>');
		}

		for (var i = 0; i < subgroups.length; i++) {
			jQuery('#subgroups').append('<li><a target="_blank" href="'+subgroups[i].website_url+'">'+subgroups[i].name+'</a></li>');
		}

		for (var i = 0; i < languages.length; i++) {
			jQuery('#languages').append('<li>'+languages[i].name+'</li>');
		}



		jQuery('.country-details').show();

	});
}