var colors = {
	iran: "#83c2ba",
	russia: "#ebdb8b",
	china: "#a791b4",
	cuba: "#85c5e3",
	cve: "#C9B7AD"
}
var spheres = {
	cve: {
		comprisedOf: ['Russia'],
		influences: [
			'Afghanistan','Armenia','Azerbaijan','Bahrain','Bangladesh','Brunei','Cambodia','Taiwan','East Timor','Indonesia','Iran','Iraq','Israel','Palestine','Japan','Jordan','Kazakhstan','Kuwait','Kyrgyzstan','Laos','Lebanon','Malaysia','Maldives','Myanmar','North Korea','Oman','Pakistan','Philippines','Qatar','Russia','Saudi Arabia','Singapore','South Korea','Sri Lanka','Syria','Tajikistan','Thailand','Taiwan','Turkey','Turkmenistan','United Arab Emirates','Uzbekistan','Vietnam','Yemen',
			'Papua New Guinea',
			'Albania','Andorra','Austria','Belarus','Belgium','Bosnia and Herzegovina','Bulgaria','Croatia','Cyprus','Czech Republic','Denmark','Estonia','Finland','France','Georgia','Germany','Greece','Hungary','Ireland','Italy','Latvia','Liechtenstein','Lithuania','Luxembourg','Republic of Macedonia','Malta','Moldova','Monaco','Montenegro','Netherlands','Norway','Poland','Portugal','Romania','Russia','San Marino','Serbia','Slovakia','Slovenia','Spain','Sweden','Switzerland','Turkey','Ukraine','United Kingdom','Vatican City',
			'Egypt','Morocco','Libya','Algeria','Mali','Mauritania', 'Chad', 'Western Sahara', 'Sudan','Eritrea','Ethiopia','Somalia', 'Cameroon', 'CÃ´te d\'Ivoire', 'Benin', 'Ghana','Togo', 'Liberia', 'Niger', 'Burkina Faso', 'Senegal', 'Nigeria', 'Guinea', 'Sierra Leone', 'Gambia'
		],
		color: colors['cve'],
		color2: colors['cve'],
		label: "Countering Violent Extremism"
	},
	iran: {
		comprisedOf: ['Iran'],
		influences: ['Armenia','Azerbaijan','Turkey','Syria','Jordan','Israel','Afghanistan','Pakistan','Cyprus'],
		color: colors['iran'],
		color2: colors['iran'],
		label: "Iran"
	},
	russia: {
		comprisedOf: ['Russia', 'Svalbard and Jan Mayen'],
		influences: ['Estonia','Latvia','Lithuania','Belarus','Moldova','Syria','Kazakhstan','Uzbekistan','Turkmenistan','Tajikistan','Kyrgyzstan','Azerbaijan','Armenia','Georgia'],
		color: colors['russia'],
		color2: colors['russia'],
		label: "Russia"
	},
	china: {
		comprisedOf: ['China'],
		influences: [
			'Argentina','Bolivia','Brazil','Chile','Colombia','Ecuador','French Guiana','Guyana','Paraguay','Peru','Suriname','Uruguay','Venezuela',
			'Angola','Benin','Botswana','Burkina Faso','Burundi','Cameroon','Cape Verde','Central African Republic','Chad','Comoros','Republic of Congo','Democratic Republic of Congo','CÃ´te d\'Ivoire','Djibouti','Equatorial Guinea','Eritrea','Ethiopia','Gabon','Gambia','Ghana','Guinea','Guinea-Bissau','Kenya','Lesotho','Liberia','Madagascar','Malawi','Mali','Mauritania','Mauritius','Mozambique','Namibia','Niger','Nigeria','Rwanda','São Tomé and Príncipe','Senegal','Seychelles','Sierra Leone','Somalia','South Africa','South Sudan','Sudan','Swaziland','Tanzania','Togo','Uganda','Western Sahara','Zambia','Zimbabwe',
			'Costa Rica','El Salvador','Guatemala','Honduras','Mexico','Nicaragua','Panama'],
		color: colors['china'],
		color2: colors['china'],
		label: "China"
	},
	cuba: {
		comprisedOf: ['Cuba'],
		influences: ['Venezuela','Colombia','Ecuador','Peru','Bolivia'],
		color: colors['cuba'],
		color2: colors['cuba'],
		label: "Cuba"
	},
};
fullCountryList = AmCharts.maps.worldLow.svg.g.path;
cMap = {};
for (var i = 0; i < fullCountryList.length; i++) {
	c = fullCountryList[i];
	cMap[c.title] = c.id;
}
sMap = {};
for (var key in spheres) {
	if (spheres.hasOwnProperty(key)) {
		s = spheres[key];
		var sphereCountries = s.comprisedOf.concat(s.influences);
		for (var i=0; i < sphereCountries.length; i++) {
			var countryName = sphereCountries[i];
			if (cMap.hasOwnProperty(countryName)) {
				var countryID = cMap[countryName];
				sMap[countryID] = key;
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
    	var selectedSphere = jQuery(this).val();
    	setActiveSphere(selectedSphere);
    });


});

function getAreas(selectedSphere) {
	var areas = [];
	for (var key in spheres) {
		if (spheres.hasOwnProperty(key)) {
			s = spheres[key];
			if (selectedSphere == "all" || selectedSphere == key) {
				var sphereCountries = s.comprisedOf.concat(s.influences);
				for (var i=0; i < sphereCountries.length; i++) {
					var countryName = sphereCountries[i];
					if (cMap.hasOwnProperty(countryName)) {
						var countryID = cMap[countryName];
						var a = {
							id: countryID,
							title: countryName,
							color: s.color,
							groupId: key,
							customData:s.label
						}
						areas.push(a);
					} else {
						console.log("no match for " + countryName);
					}
				}
			}
		}
	}
	return areas;
}

function setActiveSphere(s) {
	map.dataProvider.areas = getAreas(s);
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
				areas:getAreas('all')
			},
			areasSettings: {
				autoZoom: false,
				selectable:true,
					"rollOverOutlineColor": "#FFFFFF",
					"rollOverColor": "#CC0000",
					"alpha": 1,
					"unlistedAreasAlpha": 0.6,
					"balloonText": "[[customData]] Hot Spot"
			},
		} );

		map.addListener("clickMapObject", function (event) {
			sphere = sMap[event.mapObject.id];
			window.location = bbgConfig.template_directory_uri + "../../../hot-spots/" + sphere;
		});
	});


})(jQuery,bbgConfig, entities);

		// for (var i=0; i < s.influences.length; i++) {
		// 	var countryName = s.influences[i];
		// 	if (cMap.hasOwnProperty(countryName)) {
		// 		var countryID = cMap[countryName];
		// 		sMap[countryID] = key;
		// 		var a = {
		// 			id: countryID,
		// 			title: countryName,
		// 			color: s.color2,
		// 			groupId: key,
		// 			customData:s.label
		// 		}
		// 		areas.push(a);
		// 	} else {
		// 		console.log("no match for " + countryName);
		// 	}
		// }