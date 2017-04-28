var fullCountryList = AmCharts.maps.worldLow.svg.g.path;
var activeSphere = "all";

var COLOR_FREE = "#5EAB9F";
var COLOR_PARTIALLY_FREE = "#B5A052";
var COLOR_NOT_FREE = "#AF4048";



cMap = {}; //this allows us to look a country's ID up from its title, so that the data can originally be entered as country titles
cMapByID = {};
for (var i = 0; i < fullCountryList.length; i++) {
	c = fullCountryList[i];
	c.spheres = [];
	cMap[c.title.toLowerCase()] = c.id;
	cMapByID[c.id] = c;
}

function getAreas() { 
	var areas = [];
	// for (var i=0; i < freeNotFree.length; i++) {
	// 	var o = freeNotFree[i];
	// 	var bbgStatus = o[0];
	// 	var countryName = o[1];
	// 	var freedomScore = o[2];
	// 	var freedomStatus = o[3];
		
	// 	if (bbgStatus != "Not targeted") {
	// 		if (cMap.hasOwnProperty(countryName.toLowerCase())) {
	// 			var countryID = cMap[countryName.toLowerCase()]; 
	// 			var colorFill = COLOR_NOT_FREE;
	// 			var freedomLabel = "Not Free";
	// 			if (freedomStatus == "F") {
	// 				colorFill = COLOR_FREE;
	// 				freedomLabel = "Free";
	// 			} else if (freedomStatus == "PF") {
	// 				colorFill = COLOR_PARTIALLY_FREE;
	// 				freedomLabel = "Partially Free";
	// 			}
	// 			var a = {
	// 				id: countryID,
	// 				title: "<div style='font-size:14px; font-weight:bold;'>" + countryName + "</div>" + freedomLabel + "<BR>" + freedomScore + "/100",
	// 				color: colorFill,
	// 				alpha: 1
	// 			}
	// 			areas.push(a);
	// 		}
	// 	}
	// }
	
	return areas;
}

(function ($) {

	jQuery(document).ready(function() {
		map = AmCharts.makeChart( "chartdiv", {
			theme: "light",
			projection:"eckert3",
			type: "map",
			zoomOnDoubleClick : false,
			imagesSettings: {
				// rollOverColor: "#089282",
				// rollOverScale: 3,
				// selectedScale: 3,
				// selectedColor: "#089282",
				color: "#13564e"
			},
			balloon: {
				fillAlpha: 1,
				fillColor: "#CCCCCC"
			},
			dataProvider: {
				map: "worldLow",
			},
			areasSettings: {
				autoZoom: false,
				alpha: 1,
				unlistedAreasAlpha: 0.55,
				selectable: false,
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
		// map.on('click', function() {
		// 	return false; 
		// });

		jQuery('.free').css('background-color', COLOR_FREE);
		jQuery('.partially-free').css('background-color', COLOR_PARTIALLY_FREE);
		jQuery('.not-free').css('background-color', COLOR_NOT_FREE);

	});

})(jQuery);

	// map.balloonLabelFunction = function (area, map) {
		// 	var txt = "";
		// 	if (activeSphere != "all") {
		// 		txt= spheres[activeSphere].label + " Hot Spot";
		// 	} else {
		// 		var sphere = spheres[sMap[area.id]];
		//     	if (sphere) {
		//     		txt = sphere.label + " Hot Spot";
		//     	}
		// 	}
	 //    	return txt;
	 //    };