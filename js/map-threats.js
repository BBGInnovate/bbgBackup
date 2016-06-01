jQuery(document).ready( function() {
	//Load the data from Google Spreadsheet with TabletopJS
	//Parse the data into GeoJSON
	//Create the map with Mapbox
	init();
});

var geojson = [];

/*threats spreadsheet */
var public_spreadsheet_url = 'https://docs.google.com/spreadsheets/d/1JzULIRzp4Meuat8wxRwO8LUoLc8K2dB6HVfHWjepdqo/pubhtml'

function init() {
	// because we're using orderby: 'Date' it will sort by the 'Date' column
	// and reverse: true would reverse that sort, so that it could be in reverse
	// chronological order
	Tabletop.init( { key: public_spreadsheet_url,
	                 callback: showInfo,
	                 simpleSheet: true,
	                 orderby: 'Date',
	                 reverse: true } );
}

function showInfo(data) {
	// data comes through as a simple array since simpleSheet is turned on
	geojson[0] = new Object();

	geojson[0].type = "FeatureCollection";
	geojson[0].features = [];

	var dataLength = data.length;
	var description, lat, lng, pinColor, title;

	for (var i = 0; i < dataLength; i++){
		description = data[i].Description + " <span class='bbg__map-profile__date'>(" + data[i].Date + ")</span>";
		lat = Number(data[i].Latitude);
		lng = Number(data[i].Longitude);

		if (data[i].Status == "Threatened") {
			pinColor = "#900";
		} else if (data[i].Status == "Missing"){
			pinColor = "#999";
		} else if (data[i].Status == "Killed") {
			pinColor = "#000";
		} else {
			pinColor = "#333";
		}

		if (data[i].Mugshot && data[i].Mugshot != "") {
			description = "<img src='" + data[i].Mugshot + "' class='bbg__map-profile__mugshot'/>" + description;
		}

		if (data[i].Link && data[i].Link != "") {
			title = "<h5 class='bbg__map-profile__title'><a href='" + data[i].Link + "'>" + data[i].Name + "</a> (" + data[i].Country + ")</h5><div class='bbg__map-profile__network " + data[i].Network + "'></div>";
		} else {
			title = "<h5 class='bbg__map-profile__title'>" + data[i].Name + " (" + data[i].Country + ")</h5><div class='bbg__map-profile__network " + data[i].Network + "'></div>";
		}


		geojson[0].features[i] = {"type" : "Feature", "geometry" : {}, "properties" : {}};
		geojson[0].features[i].geometry = {"type" : "Point", "coordinates" : [lng, lat]};
		geojson[0].features[i].properties = {"title" : title, "description" : description, "country" : data[i].Country, "date" : data[i].Date, "link" : data[i].Link, "network" : data[i].Network, "marker-color" : pinColor, "marker-size" : "medium", "marker-symbol" : ""};
	}

	console.log("This JSON is from the google spreadsheet");
	console.log("https://docs.google.com/spreadsheets/d/1JzULIRzp4Meuat8wxRwO8LUoLc8K2dB6HVfHWjepdqo/pubhtml");
	console.log(geojson);

	//createMemorial(data);




	var wall = "";
	var journalist = "";
	var dataLength2 = data.length;
	var mugshot = "";
	var altTag = "";

	for (var i = 0; i < dataLength2; i++){
		console.log("i: "+i);
		mugshot = ""
		if (data[i].Mugshot && data[i].Mugshot!= ""){
			mugshot = data[i].Mugshot;
			alt = "Photo of " + data[i].Name;
		} else {
			mugshot = "http://placehold.it/300x400";
			alt ="";
		}
		if (data[i].Status == "Killed"){
			journalist = "";
			console.log("i (killed): "+i);
			journalist +='<div class="bbg__profile-grid__profile usa-width-one-sixth">';
			journalist +='<img src="' + mugshot + '" alt="' + altTag + '" class="bbg__profile-grid__profile__mugshot"/>';
			journalist +='<h4 class="bbg__profile-grid__profile__name">' + data[i].Name + '</h4>';
			journalist +='<h5 class="bbg__profile-grid__profile__dates">Killed ' + data[i].Date + '</h5>';
			journalist +='<p class="bbg__profile-grid__profile__description"></p>';
			journalist +='</div>';

			wall += journalist;
		}
	}
	console.log(wall);
	document.getElementById("memorialWall").innerHTML = wall;

	createMap();
}


function createMap(){
	L.mapbox.accessToken = 'pk.eyJ1IjoidmlzdWFsam91cm5hbGlzdCIsImEiOiIwODQxY2VlNDRjNTBkNWY1Mjg2OTk3NWIzMmJjMGJhMSJ9.ZjwAspfFYSc4bijF6XS7hw';

	//Create the map.
	var map = L.mapbox.map('map-threats', 'visualjournalist.mnbadlih', {
		scrollWheelZoom: false
	});

	//Add the pins to the map.
	var myLayer = L.mapbox.featureLayer().addTo(map);
	myLayer.setGeoJSON(geojson);

	//Check the width of the browser.
	function centerMap(){
		var w = window.innerWidth;
		if (w>900){
			//Fit the map to the markers.
			map.fitBounds(myLayer.getBounds());
		}else if (w>600){
			//Center and zoom the map
			map.setView([30, 35], 3);
		}else{
			map.setView([30, 55], 2);
		}
	}
	centerMap();


	//Resize YouTube videos proportionately
	function resizeStuffOnResize(){
	  waitForFinalEvent(function(){
			centerMap();
	  }, 500, "some unique string");
	}

	//Wait for the window resize to 'end' before executing a function---------------
	var waitForFinalEvent = (function () {
		var timers = {};
		return function (callback, ms, uniqueId) {
			if (!uniqueId) {
				uniqueId = "Don't call this twice without a uniqueId";
			}
			if (timers[uniqueId]) {
				clearTimeout (timers[uniqueId]);
			}
			timers[uniqueId] = setTimeout(callback, ms);
		};
	})();

	window.addEventListener('resize', function(event){
		resizeStuffOnResize();
	});

	resizeStuffOnResize();
}

