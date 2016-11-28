var COLOR_HOVER = "#CC0000"; //the color that highlights a hot spot when you roll over a country
var COLOR_ACTIVE = "#FFFFFF"; //background color when a button in the hot spot bar is active

var hideUnselectedCountries = true;

var color_secondary_darkest = "#981b1e";
var color_primary = "#0071bc";
var color_green = "#2e8540";
var color_orange = "orange";
var color_ocb = "#653792";
var color_mbn = "#BB3530";

//primary colors for our various hot spots
var lisaFrankColors = {
	iran: color_green,
	russia: color_orange,
	china: color_mbn,
	cuba: color_ocb,
	cve: color_primary,
	all: color_secondary_darkest
}
var pastelColors = {
	iran: "#83c2ba",
	russia: "#ebdb8b",
	china: "#a791b4",
	cuba: "#85c5e3",
	cve: "#C9B7AD",
	all: "#999999"
}
var normalColors = {
	iran: "#CC99B3",
	russia: "#ebdb8b",
	china: "#2B9ED4",
	cuba: "#71B771",
	cve: "#E67300",
	all: "#999999"
}

colors = normalColors;

//define each sphere an dthe countires it is comprised of and influences
var spheres = {
	iran: {
		comprisedOf: ['Iran'],
		influences: ['Armenia','Azerbaijan','Turkey','Syria','Jordan','Israel','Afghanistan','Pakistan','Cyprus','Yemen'],
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
			'Afghanistan','Armenia','Azerbaijan','Bahrain','Bangladesh','Brunei','Cambodia','East Timor','Indonesia','Iran','Iraq','Israel','Palestine','Japan','Jordan','Kazakhstan','Kuwait','Kyrgyzstan','Laos','Lebanon','Maldives','Myanmar','North Korea','Oman','Pakistan','Philippines','Qatar','Russia','Saudi Arabia','Singapore','South Korea','Sri Lanka','Syria','Tajikistan','Thailand','Turkey','Turkmenistan','United Arab Emirates','Uzbekistan','Vietnam','Yemen',
			'Papua New Guinea',
			'Albania','Andorra','Belarus','Bosnia and Herzegovina','Bulgaria','Croatia','Cyprus','Czech Republic','Estonia','Georgia','Greece','Hungary','Latvia','Lithuania','Republic of Macedonia','Malta','Moldova','Montenegro','Poland','Romania','Russia','San Marino','Serbia','Slovakia','Slovenia','Turkey','Ukraine','Vatican City',
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
			'Costa Rica','El Salvador','Guatemala','Honduras','Mexico','Nicaragua','Panama','India', 'Bangladesh','Bhutan', 'Nepal', 'Thailand', 'Myanmar', 'Vietnam', 'Lao People\'s Democratic Republic', 'North Korea', 'South Korea'],
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
fullSphereCountryList = [];

for (var key in spheres) {
	if (spheres.hasOwnProperty(key)) {
		s = spheres[key];
		sAreas[key] = [];
		var sphereCountries = s.comprisedOf.concat(s.influences);
		for (var i=0; i < sphereCountries.length; i++) {
			var countryName = sphereCountries[i];
			if (cMap.hasOwnProperty(countryName)) {
				var countryID = cMap[countryName];
				fullSphereCountryList.push(countryID);
				sAreas[key].push(countryID);
				if (! (sMap.hasOwnProperty(countryID))) {
					sMap[countryID] = key;
				}
				cMapByID[countryID].spheres.push(key);
			}
		}
	}
}

function getAreas(aSphere) {
	var areas = [];
	for (var key in spheres) {
		if (spheres.hasOwnProperty(key)) {
			s = spheres[key];
			if (aSphere == key || aSphere == "all") {
				var sphereCountries = s.comprisedOf.concat(s.influences);
				for (var i=0; i < sphereCountries.length; i++) {
					var countryName = sphereCountries[i];
					if (cMap.hasOwnProperty(countryName)) {
						var countryID = cMap[countryName];
						//if you're only showing a particular sphere, color it for the sphere
						//if you're showing all spheres, color it by the 'primary' sphere
						var newColor = s.color;
						if (aSphere == "all") {
							var c = cMapByID[countryID];
							newColor = colors[c.spheres[0]];
						}
						var isComprised = (i < s.comprisedOf.length);
						var alpha = 1;
						if ( (aSphere != "all" && aSphere != "cve") && !isComprised) {
							alpha = 0.7;
							///console.log('comprised!!!!');
						}
						var a = {
							id: countryID,
							title: countryName,
							color: newColor,
							alpha: alpha
						}
						areas.push(a);
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

function initMobileLegend() {
	//assign colors to the mobile legend.  Only happens once, on load.
	jQuery('.china').css('background-color', colors['china']);
	jQuery('.cuba').css('background-color', colors['cuba']);
	jQuery('.iran').css('background-color', colors['iran']);
	jQuery('.russia').css('background-color', colors['russia']);
	jQuery('.cve').css('background-color', colors['cve']);
}

function resetButton(btnName) {
	var defaultButtonBG = colors[btnName];
	var defaultButtonTextColor = "#FFF";
	var btn = jQuery('.' + btnName);
	if (btnName == activeSphere) {
		btn.css('color',defaultButtonBG);
		btn.css('background-color',COLOR_ACTIVE);
	} else {
		btn.css('color',defaultButtonTextColor);
		btn.css('background-color',defaultButtonBG);
		btn.css('border-color',defaultButtonBG);
	}
}

function resetButtons(btnLeaveAlone) {
	//reset all buttons to their original color
	jQuery('.entity-buttons button').each(function (index, value) {
		var val = jQuery(this).val();
		resetButton(val);
	});
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
			areasSettings: {
				autoZoom: false,
				alpha: 1,
				unlistedAreasAlpha: 0.55,
				selectable: true,
				selectedColor: undefined,
				outlineThickness: 0.1
			},
			zoomControl:  {
				zoomControlEnabled: false,
				panControlEnabled: false,
				homeButtonEnabled: false
			},
			dragMap:false
		});

		map.addListener("clickMapObject", function (event) {
			var sphere= false;
			console.log('activeSphere is ' + activeSphere);
			//if we're in the 'all' view, take the most relevant sphere
			if (activeSphere == "all") {
				sphere = sMap[event.mapObject.id];
			} else {
			//else if our view is restricted to a sphere, use that
				sphere = activeSphere;
			}
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
				var usedIDs = {};
				for (var i=0; i < sphereCountries.length; i++) {
					var countryName = sphereCountries[i];
					if (cMap.hasOwnProperty(countryName)) {
						var countryID = cMap[countryName];
						var c2 = cMapByID[countryID];
						usedIDs[countryID] = 1;
						var mapObject = map.getObjectById(countryID);
						if (mapObject) {
							if (activeSphere == "all") {
								mapObject.color = colors[c2.spheres[0]];
								mapObject.alpha = 1;
								mapObject.validate();
							} else {
								mapObject.color = colors[activeSphere];
								mapObject.validate();
							}
						}
					}
				}

				if (hideUnselectedCountries && activeSphere == 'all') {
					for (var i=0; i < fullSphereCountryList.length; i++) {
						var countryID = fullSphereCountryList[i];
						if (! (usedIDs.hasOwnProperty(countryID))) {
							var c2 = cMapByID[countryID];
							var mapObject = map.getObjectById(countryID);
							if (mapObject) {
								//console.log('hide ' + countryID);
								mapObject.color = colors[c2.spheres[0]];
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
				var s = spheres[primarySphere];

				event.mapObject.color = s.color;
				event.mapObject.validate();

				//loop through all countries that are in this country's primary sphere
				var sphereCountries = s.comprisedOf.concat(s.influences);
				var usedIDs = {};
				for (var i=0; i < sphereCountries.length; i++) {
					var countryName = sphereCountries[i];
					if (cMap.hasOwnProperty(countryName)) {
						var countryID = cMap[countryName];
						usedIDs[countryID] = 1;
						var mapObject = map.getObjectById(countryID);
						if (mapObject) {
							if (hideUnselectedCountries) {
								mapObject.color = s.color;
							} else {
								mapObject.color = COLOR_HOVER;
							}
							var isComprised = (i < s.comprisedOf.length);
							var alpha = 1;
							if ( (primarySphere != "all" && primarySphere != "cve") && !isComprised) {
								alpha = 0.7;
								///console.log('comprised!!!!');
							}
							mapObject.alpha=alpha;

							mapObject.validate();
						}
					}
				}

				if (hideUnselectedCountries && activeSphere == 'all') {
					for (var i=0; i < fullSphereCountryList.length; i++) {
						var countryID = fullSphereCountryList[i];
						if (! (usedIDs.hasOwnProperty(countryID))) {

							var mapObject = map.getObjectById(countryID);
							if (mapObject) {
								// /console.log('hide ' + countryID);
								mapObject.color = "#ececec";
								mapObject.validate();
							}

						}
					}
				}
			}
		});

	    jQuery('#hotSpotPicker').change(function(e) {
	    	setActiveSphere(jQuery(this).val());
	    });

		jQuery('.entity-buttons button').mouseenter(function(e) {
			resetButtons();

			var val = jQuery(this).val();
			if (val != activeSphere) {
				jQuery(this).css('color',colors[val]);
				jQuery(this).css('background-color','#FFFFFF');
			}
		});

		jQuery('.entity-buttons button').mouseleave(function(e) {
			resetButtons();

			var val = jQuery(this).val();
			if (val != activeSphere) {
				jQuery(this).css('color','#FFFFFF');
				jQuery(this).css('background-color',colors[val]);
			}

		});

		jQuery('.entity-buttons button').click(function(e) {
			var val = jQuery(this).val();
			setActiveSphere(val);
			resetButtons();


			jQuery(this).css('color',colors[val]);
			jQuery(this).css('background-color',COLOR_ACTIVE);


		});
	    resetButtons();
	    jQuery('.entity-buttons button.all').trigger( "click" );
	    initMobileLegend();

	});

})(jQuery,bbgConfig, entities);
