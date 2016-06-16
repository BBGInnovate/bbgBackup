jQuery(function () {

	var defaultEntity='voa'; //might fill this from a global JS var later.
	//bbgConfig={};
	//bbgConfig.template_directory_uri = 'https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/';
	/* keep countries,map as global variables else they won't be available in the ajax callback */
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
			selectedColor: "#7A1A21",
			rollOverColor: "#891E25",
			rollOverOutlineColor: "#FFFFFF",
			selectable: true
		},
		zoomDuration:0.2,
		backgroundZoomsToTop: true, //water zooms out
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
	});

	// zoom in on the new entity once it's updated
	map.addListener('dataUpdated', function (event) {
		event.chart.zoomToGroup(countries);
	});
	

	jQuery('#entity').on('change', function () {
		var entity = jQuery(this).val();
		grabData(entity);
	});

	//load our initial data
	grabData(defaultEntity);
});

function grabData(entity) {
	jQuery('#loading').show();
	jQuery.getJSON(bbgConfig.template_directory_uri+'/api.php?endpoint=api/countries/?group='+entity)
		.done(function( data ) {
			countries=[];
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
			map.dataProvider.areas = countries;
				map.validateData();

			jQuery('#loading').hide();
		})
		.fail(function( jqxhr, textStatus, error ) {
			var err = textStatus + ", " + error;
			alert("We're sorry, we were unable to load the map data at this time.  Please check back shortly. (" + err + ")");
		});
}

function getCountryDetails (countryName) {
	jQuery('.detail').empty();

	var groups = [];
	var subgroups = [];
	var languages = [];

	jQuery.when(
		jQuery.getJSON(bbgConfig.template_directory_uri+"/api.php?endpoint=api/groups/?country=" + countryName, function( data ) {
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