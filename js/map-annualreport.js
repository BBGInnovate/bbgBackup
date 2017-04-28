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
	for (var i=0; i < freeNotFree.length; i++) {
		var o = freeNotFree[i];
		var bbgStatus = o[0];
		var countryName = o[1];
		var freedomScore = o[2];
		var freedomStatus = o[3];
		
		if (bbgStatus != "Not targeted") {
			if (cMap.hasOwnProperty(countryName.toLowerCase())) {
				var countryID = cMap[countryName.toLowerCase()]; 
				var colorFill = COLOR_NOT_FREE;
				var freedomLabel = "Not Free";
				if (freedomStatus == "F") {
					colorFill = COLOR_FREE;
					freedomLabel = "Free";
				} else if (freedomStatus == "PF") {
					colorFill = COLOR_PARTIALLY_FREE;
					freedomLabel = "Partially Free";
				}
				var a = {
					id: countryID,
					title: "<div style='font-size:14px; font-weight:bold;'>" + countryName + "</div>" + freedomLabel + "<BR>" + freedomScore + "/100",
					color: colorFill,
					alpha: 1
				}
				areas.push(a);
			}
		}
	}
	
	return areas;
}

(function ($) {

	jQuery(document).ready(function() {
		//target
		targetSVG = "M9,0C4.029,0,0,4.029,0,9s4.029,9,9,9s9-4.029,9-9S13.971,0,9,0z M9,15.93 c-3.83,0-6.93-3.1-6.93-6.93S5.17,2.07,9,2.07s6.93,3.1,6.93,6.93S12.83,15.93,9,15.93 M12.5,9c0,1.933-1.567,3.5-3.5,3.5S5.5,10.933,5.5,9S7.067,5.5,9,5.5 S12.5,7.067,12.5,9z";
		targetSVGScale = 1;

		//exclamation circle 
		// targetSVG = "M896 128q209 0 385.5 103t279.5 279.5 103 385.5-103 385.5-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103zm128 1247v-190q0-14-9-23.5t-22-9.5h-192q-13 0-23 10t-10 23v190q0 13 10 23t23 10h192q13 0 22-9.5t9-23.5zm-2-344l18-621q0-12-10-18-10-8-24-8h-220q-14 0-24 8-10 6-10 18l17 621q0 10 10 17.5t24 7.5h185q14 0 23.5-7.5t10.5-17.5z";
		// targetSVGScale = 0.01;
		//targetSVG = "M1152 640q0-106-75-181t-181-75-181 75-75 181 75 181 181 75 181-75 75-181zm256 0q0 109-33 179l-364 774q-16 33-47.5 52t-67.5 19-67.5-19-46.5-52l-365-774q-33-70-33-179 0-212 150-362t362-150 362 150 150 362z";
		targetSVG = "M896 1088q66 0 128-15v655q0 26-19 45t-45 19h-128q-26 0-45-19t-19-45v-655q61 15 128 15zm0-1088q212 0 362 150t150 362-150 362-362 150-362-150-150-362 150-362 362-150zm0 224q14 0 23-9t9-23-9-23-23-9q-146 0-249 103t-103 249q0 14 9 23t23 9 23-9 9-23q0-119 84.5-203.5t203.5-84.5z";
		targetSVG = "M896 1088q66 0 128-15v655q0 26-19 45t-45 19h-128q-26 0-45-19t-19-45v-655q61 15 128 15zm0-1088q212 0 362 150t150 362-150 362-362 150-362-150-150-362 150-362 362-150zm0 224q14 0 23-9t9-23-9-23-23-9q-146 0-249 103t-103 249q0 14 9 23t23 9 23-9 9-23q0-119 84.5-203.5t203.5-84.5z";
		targetSVGScale = 0.01; 

		//targetSVG = "M896 1088q66 0 128-15v655q0 26-19 45t-45 19h-128q-26 0-45-19t-19-45v-655q61 15 128 15zm0-1088q212 0 362 150t150 362-150 362-362 150-362-150-150-362 150-362 362-150zm0 224q14 0 23-9t9-23-9-23-23-9q-146 0-249 103t-103 249q0 14 9 23t23 9 23-9 9-23q0-119 84.5-203.5t203.5-84.5z";

		var images = new Array();
		for (var i = 0; i < threats.length; i++) {
			var t = threats[i];
			var img = {
				svgPath: targetSVG,
				zoomLevel: 5,
				scale: targetSVGScale,
				selectable:false,
				//title: t.name + " - " + t.description,
				latitude: t.latitude,
				longitude: t.longitude
			}
			images.push(img);
		}

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
				areas:getAreas("all"),
				images: images,
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