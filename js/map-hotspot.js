var COLOR_HOVER = "#CC0000";
var colors = {
	iran: "#83c2ba",
	russia: "#ebdb8b",
	china: "#a791b4",
	cuba: "#85c5e3",
	cve: "#C9B7AD"
}
var spheres = {
	iran: {
		comprisedOf: ['Iran'],
		influences: ['Armenia','Azerbaijan','Turkey','Syria','Jordan','Israel','Afghanistan','Pakistan','Cyprus'],
		color: colors['iran'],
		label: "Iran"
	},
	russia: {
		comprisedOf: ['Russia', 'Svalbard and Jan Mayen'],
		influences: ['Estonia','Latvia','Lithuania','Belarus','Moldova','Syria','Kazakhstan','Uzbekistan','Turkmenistan','Tajikistan','Kyrgyzstan','Azerbaijan','Armenia','Georgia'],
		color: colors['russia'],
		label: "Russia"
	},
	cuba: {
		comprisedOf: ['Cuba'],
		influences: ['Venezuela','Colombia','Ecuador','Peru','Bolivia'],
		color: colors['cuba'],
		label: "Cuba"
	},
	cve: {
		comprisedOf: ['Russia'],
		influences: [
			'Afghanistan','Armenia','Azerbaijan','Bahrain','Bangladesh','Brunei','Cambodia','Taiwan','East Timor','Indonesia','Iran','Iraq','Israel','Palestine','Japan','Jordan','Kazakhstan','Kuwait','Kyrgyzstan','Laos','Lebanon','Maldives','Myanmar','North Korea','Oman','Pakistan','Philippines','Qatar','Russia','Saudi Arabia','Singapore','South Korea','Sri Lanka','Syria','Tajikistan','Thailand','Taiwan','Turkey','Turkmenistan','United Arab Emirates','Uzbekistan','Vietnam','Yemen',
			'Papua New Guinea',
			'Albania','Andorra','Belarus','Bosnia and Herzegovina','Bulgaria','Croatia','Cyprus','Czech Republic','Denmark','Estonia','Finland','Georgia','Greece','Hungary','Latvia','Lithuania','Republic of Macedonia','Malta','Moldova','Montenegro','Norway','Poland','Romania','Russia','San Marino','Serbia','Slovakia','Slovenia','Sweden','Turkey','Ukraine','Vatican City',
			'Egypt','Morocco','Libya','Algeria','Mali','Mauritania', 'Chad', 'Western Sahara', 'Sudan','Eritrea','Ethiopia','Somalia', 'Cameroon', 'CÃ´te d\'Ivoire', 'Benin', 'Ghana','Togo', 'Liberia', 'Niger', 'Burkina Faso', 'Senegal', 'Nigeria', 'Guinea', 'Sierra Leone', 'Gambia'
		],
		color: colors['cve'],
		label: "Countering Violent Extremism"
	},
	china: {
		comprisedOf: ['China'],
		influences: [
			'Argentina','Bolivia','Brazil','Chile','Colombia','Ecuador','French Guiana','Guyana','Paraguay','Peru','Suriname','Uruguay','Venezuela',
			'Angola','Benin','Botswana','Burkina Faso','Burundi','Cameroon','Cape Verde','Central African Republic','Chad','Comoros','Republic of Congo','Democratic Republic of Congo','CÃ´te d\'Ivoire','Djibouti','Equatorial Guinea','Eritrea','Ethiopia','Gabon','Gambia','Ghana','Guinea','Guinea-Bissau','Kenya','Lesotho','Liberia','Madagascar','Malawi','Mali','Mauritania','Mauritius','Mozambique','Namibia','Niger','Nigeria','Rwanda','São Tomé and Príncipe','Senegal','Seychelles','Sierra Leone','Somalia','South Africa','South Sudan','Sudan','Swaziland','Tanzania','Togo','Uganda','Western Sahara','Zambia','Zimbabwe',
			'Costa Rica','El Salvador','Guatemala','Honduras','Mexico','Nicaragua','Panama'],
		color: colors['china'],
		label: "China"
	},
};
var fullCountryList = AmCharts.maps.worldLow.svg.g.path;
var activeSphere = "all";

cMap = {}; //this allows us to look a country's ID up from its title, so that the data can originally be entered as country titles
cMapByID = {};
for (var i = 0; i < fullCountryList.length; i++) {
	c = fullCountryList[i];
	c.spheres = [];
	cMap[c.title] = c.id;
	cMapByID[c.id] = c;
}

sMap = {};  //this maps countryIDs to spheres so that when you click a country, it takes you to a sphere.
sAreas = {};
for (var key in spheres) {
	if (spheres.hasOwnProperty(key)) {
		s = spheres[key];
		sAreas[key] = [];
		var sphereCountries = s.comprisedOf.concat(s.influences);
		for (var i=0; i < sphereCountries.length; i++) {
			var countryName = sphereCountries[i];
			if (cMap.hasOwnProperty(countryName)) {
				var countryID = cMap[countryName];
				sAreas[key].push(countryID);
				if (! (sMap.hasOwnProperty(countryID))) {
					sMap[countryID] = key;	
				}
				cMapByID[countryID].spheres.push(key);
			}
		}
	}
}

jQuery( document ).ready(function() {
 	//color the legend
    jQuery('.china').css('background-color', colors['china']);
    jQuery('.cuba').css('background-color', colors['cuba']);
    jQuery('.iran').css('background-color', colors['iran']);
    jQuery('.russia').css('background-color', colors['russia']);
    jQuery('.cve').css('background-color', colors['cve']);

    jQuery('#mapFilters input').click(function(e) {
    	setActiveSphere(jQuery(this).val());
    });
    jQuery('#hotSpotPicker').change(function(e) {
    	setActiveSphere(jQuery(this).val());
    });


});

function getAreas(aSphere) {
	var areas = [];
	
	if (aSphere == "all") {
		var areas = [];
		for (var i = 0; i < fullCountryList.length; i++) {
			c = fullCountryList[i];
			if (sMap[c.id]) {
				var a = {
					id: c.id,
					title: c.title,
					color: colors[c.spheres[0]]
				}
				areas.push(a);	
			}
		}
	} else {
		for (var key in spheres) {
			if (spheres.hasOwnProperty(key)) {
				s = spheres[key];
				if (aSphere == key) {
					var sphereCountries = s.comprisedOf.concat(s.influences);
					for (var i=0; i < sphereCountries.length; i++) {
						var countryName = sphereCountries[i];
						if (cMap.hasOwnProperty(countryName)) {
							var countryID = cMap[countryName];
							var a = {
								id: countryID,
								title: countryName,
								color: s.color,
								customData:s.label
							}
							areas.push(a);
						} 
					}
				}
			}
		}
	}

	
	return areas;
}

function setActiveSphere(s) {
	activeSphere=s;
	var newAreas = getAreas(s);
	map.dataProvider.areas = newAreas;
	map.validateData();
}

(function ($,bbgConfig, entities) {

	jQuery(document).ready(function() {

		map = AmCharts.makeChart( "chartdiv", {
  			theme: "light",
			projection:"eckert3",
			type: "map",
			dataProvider: {
				map: "worldLow",
				areas:getAreas("all")
			},
			// backgroundColor:"#AAAAAA",
			// backgroundAlpha: 1,
			areasSettings: {
				autoZoom: false,
				alpha: 1,
				unlistedAreasAlpha: 0.6,
				color:"#CCCCCC",
				selectable: true
			},
		} );
		map.zoomControl = {
			zoomControlEnabled: false,
			panControlEnabled: false,
			homeButtonEnabled: false
		};

		map.addListener("clickMapObject", function (event) {
			sphere = sMap[event.mapObject.id];
			window.location = bbgConfig.template_directory_uri + "../../../hot-spots/" + sphere;
		});

		map.balloonLabelFunction = function (area, map) {      
			var txt = "";
			if (activeSphere != "all") {
				txt= spheres[activeSphere].label + " Hot Spot";
			} else {
				var sphere = spheres[sMap[area.id]];
		    	if (sphere) {
		    		txt = sphere.label + " Hot Spot";
		    	}	
			}
			
	    	return txt;
	    };

		map.addListener("rollOutMapObject", function (event) {
			var countryID = event.mapObject.id;
			var primarySphere = sMap[countryID];
			if (primarySphere) {
				if (activeSphere != "all") {
					primarySphere = activeSphere;
				}
				var c = cMapByID[countryID];
				event.mapObject.color = colors[c.spheres[0]];
				event.mapObject.validate();
				var s = spheres[primarySphere];
				var sphereCountries = s.comprisedOf.concat(s.influences);
				for (var i=0; i < sphereCountries.length; i++) {
					var countryName = sphereCountries[i];
					if (cMap.hasOwnProperty(countryName)) {
						var countryID = cMap[countryName];
						var c2 = cMapByID[countryID];
						var mapObject = map.getObjectById(countryID);
						if (mapObject) {
							
							if (activeSphere == "all") {
								mapObject.color = colors[c2.spheres[0]];
								mapObject.validate();
							} else {
								mapObject.color = colors[activeSphere];
								mapObject.validate();
							}

						}
					}
				}
			}
			
		});
		map.addListener("rollOverMapObject", function (event) {
			var countryID = event.mapObject.id;
			var primarySphere = sMap[countryID];

			if (primarySphere) {
				if (activeSphere != "all") {
					primarySphere = activeSphere;
				}
				var c = cMapByID[countryID];
				event.mapObject.color = COLOR_HOVER;
				event.mapObject.validate();
				
				//loop through all countries that are in this country's primary sphere
				var s = spheres[primarySphere];
				var sphereCountries = s.comprisedOf.concat(s.influences);
				for (var i=0; i < sphereCountries.length; i++) {
					var countryName = sphereCountries[i];
					if (cMap.hasOwnProperty(countryName)) {
						var countryID = cMap[countryName];
						var mapObject = map.getObjectById(countryID);
						if (mapObject) {
							mapObject.color = COLOR_HOVER;
							mapObject.validate();
						}
					}
				}
			}
		});
	});


})(jQuery,bbgConfig, entities);
