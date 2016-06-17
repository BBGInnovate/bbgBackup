(function ($,bbgConfig) {
	var defaultEntity='bbg'; //might fill this from a global JS var later.
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
		map.zoomDuration = .2;
	});


	$('#entity').on('change', function () {
		//map.zoomToLongLat(1, 14.0671, 10.7988, false);
		map.zoomDuration = 0;


		var entity = $(this).val();
		grabData(entity);


	});

	//load our initial data
	grabData(defaultEntity);

	function grabData(entity) {
		var url = '';
		// this is an organization (BBG)
		if (entity === 'bbg') {
			url = bbgConfig.template_directory_uri + 'api.php?endpoint=api/countries/?region_country=1';
		// if there's an entity (group), get it by entity
		} else {
			url = bbgConfig.template_directory_uri + 'api.php?endpoint=api/countries/?group='+entity;
		}

		$('#loading').show();
		$.getJSON(url)
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

				$('#loading').hide();
			})
			.fail(function( jqxhr, textStatus, error ) {
				var err = textStatus + ", " + error;
				alert("We're sorry, we were unable to load the map data at this time.  Please check back shortly. (" + err + ")");
			});
	}

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

		});
	}
})(jQuery,bbgConfig);

