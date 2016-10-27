var spheres = {
	cuba: {
		comprisedOf: ['Cuba'],
		influences: ['Venezuela','Colombia','Ecuador','Peru','Bolivia'],
		color: '#db8383',
		color2: '#c57575'
	},
	iran: {
		comprisedOf: ['Iran'],
		influences: ['Armenia','Azerbaijan','Turkey','Syria','Jordan','Israel','Afghanistan','Pakistan','Cyprus'],
		color: "#83c2ba",
		color2: "#689b94" 
	},
	russia: {
		comprisedOf: ['Russia', 'Svalbard and Jan Mayen'],
		influences: ['Estonia','Latvia','Lithuania','Belarus','Moldova','Syria','Kazakhstan','Uzbekistan','Turkmenistan','Tajikistan','Kyrgyzstan','Azerbaijan','Armenia','Georgia'],
		color: "#ebdb8b",
		color2: "#d3c57d"
	}
};
fullCountryList = AmCharts.maps.worldLow.svg.g.path;

cMap = {};

for (var i = 0; i < fullCountryList.length; i++) {
	c = fullCountryList[i];
	cMap[c.title] = c.id;
}



// areas:[
// 				  { "id": "AU", "color": "#CC0000" },
// 				  { "id": "US", "color": "#00CC00" },
// 				  { "id": "FR", "color": "#0000CC" }
// 				]
areas = [];
sMap = {};
for (var key in spheres) {
	if (spheres.hasOwnProperty(key)) {
		s = spheres[key];
		for (var i=0; i < s.comprisedOf.length; i++) {
			var countryName = s.comprisedOf[i];
			if (cMap.hasOwnProperty(countryName)) {
				var countryID = cMap[countryName];
				sMap[countryID] = key;
				var a = {
					id: countryID,
					title: countryName,
					color: s.color,
					groupId: key
				}
				areas.push(a);
			} else {
				console.log("no match for " + countryName);
			}
		}
		for (var i=0; i < s.influences.length; i++) {
			var countryName = s.influences[i];
			if (cMap.hasOwnProperty(countryName)) {
				var countryID = cMap[countryName];
				sMap[countryID] = key;
				var a = {
					id: countryID,
					title: countryName,
					color: s.color2,
					groupId: key
				}
				areas.push(a);
			} else {
				console.log("no match for " + countryName);
			}
		}
	}
}


(function ($,bbgConfig, entities) {

	// find out if the user is on a mobile device or not (used for zoomDuration)
	var isMobile = isMobileDevice();

	var colorBase = '#0071bc',
		colorRollOver = '#205493',
		colorSelected = '#112e51';

	$(document).ready(function() {

		var defaultEntity='bbg'; //might fill this from a global JS var later.
		countries=[];
		fakeDetail="xxxx";

		map = AmCharts.makeChart( "chartdiv", {
  			theme: "light",
			projection:"eckert3",
			type: "map",
			dataProvider: {
				map: "worldLow",
				areas:areas
			},
			areasSettings: {
				autoZoom: false,
				selectable:true,
				"rollOverOutlineColor": "#C00",
				"alpha": 1,
    "unlistedAreasAlpha": 0.4,
    "balloonText": "[[title]]"

			},
		} );

		//if someone clicks a country that's already selected, zoom out.
		map.addListener("clickMapObject", function (event) {
			sphere = sMap[event.mapObject.id];
			console.log("go to " + sphere);
			window.location = bbgConfig.template_directory_uri + "../../../hot-spots/" + sphere;
			
		});

		grabData('BBG');

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

// for (var i = 0; i < data.countries.length; i++) {
// 						var country = data.countries[i];
// 						var countryCode = data.countries[i].code;
// 						country.id = countryCode;
// 						// if the country has region_ids array, BBG has coverage there
// 						if (country.region_ids) {
// 							country.color = '#9F1D26';
// 							country.rollOverColor = "#891E25";
// 							country.selectedColor = "#7A1A21";


// 						// NO COVERAGE here
// 						} else {
// 							// this is the default color for non-BBG covered countries
// 							country.color = '#DDDDDD';
// 							country.rollOverColor = "#B7B7B7";

// 							// these zoom levels will prevent zoom on countries that are not covered
// 							country.zoomLatitude = map.zoomLatitude();
// 							country.zoomLongitude = map.zoomLongitude();
// 							country.zoomLevel = map.zoomLevel();

// 						}


// 						countries.push(country);

// 					}