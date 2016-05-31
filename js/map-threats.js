jQuery(document).ready( function() {
	//Create maps
	init();
	
});
var geojson = [];

/*threats */
var public_spreadsheet_url = 'https://docs.google.com/spreadsheets/d/1JzULIRzp4Meuat8wxRwO8LUoLc8K2dB6HVfHWjepdqo/pubhtml'

/*sample*/
//var public_spreadsheet_url = 'https://docs.google.com/spreadsheet/pub?hl=en_US&hl=en_US&key=0AmYzu_s7QHsmdDNZUzRlYldnWTZCLXdrMXlYQzVxSFE&output=html';

function init() {
	// because we're using orderby: 'name' it will sort by the 'name' column
	// and reverse: true will reverse that sort, so it should be in reverse
	// alphabetical order
	Tabletop.init( { key: public_spreadsheet_url,
	                 callback: showInfo,
	                 simpleSheet: true,
	                 orderby: 'Name',
	                 reverse: true } );
}

function showInfo(data) {
	// data comes through as a simple array since simpleSheet is turned on
	document.getElementById("food").innerHTML = "<strong>Test:</strong> " + [ data[0].Country, data[1].Country, data[2].Name ].join(", ");
	//console.log(data);

/*}

function createGeojson(){*/
	//var myGeojson = new Object();
	var myGeojson = [];
	myGeojson[0] = new Object();

	myGeojson[0].type = "FeatureCollection";
	myGeojson[0].features = [];

	var dataLength = 4; //data.length;

	for (var i = 0; i < dataLength; i++){
		//myGeojson[0].features[i] = {"type" : "Feature"};
		myGeojson[0].features[i] = {"type" : "Feature", "geometry" : {}, "properties" : {}};
		myGeojson[0].features[i].geometry = {"type" : "Point", "coordinates" : [data[i].Longitude, data[i].Latitude]};

		myGeojson[0].features[i].properties = {"title" : data[i].Name, "description" : data[i].Description, "marker-color" : "#F7941E", "marker-size" : "large", "marker-symbol" : "building"};

		/*
		myGeojson[0].features[i].properties = {"title" : data[i].Name};
		myGeojson[0].features[i].properties = {"description" : data[i].Description};
		myGeojson[0].features[i].properties = {"marker-color" : "#F7941E"};
		myGeojson[0].features[i].properties = {"marker-size" : "large"};
		myGeojson[0].features[i].properties = {"marker-symbol" : "building"};
		*/
	}

	console.log(myGeojson);
	console.log("xxxx");
	createMap();
}


function createMap(){
	L.mapbox.accessToken = 'pk.eyJ1IjoidmlzdWFsam91cm5hbGlzdCIsImEiOiIwODQxY2VlNDRjNTBkNWY1Mjg2OTk3NWIzMmJjMGJhMSJ9.ZjwAspfFYSc4bijF6XS7hw';

	var geojson = [
	{
		"type": "FeatureCollection",
		"features": [
		{
			"type": "Feature",
			"geometry": {
				"type": "Point",
				"coordinates": [
					-77.016556,
					38.887226
				]
			},
			"properties": {
				"title": "Africa Rizing HQ",
				"description": "description could go here.",
				"marker-color": "#F7941E",
				"marker-size": "large",
				"marker-symbol": "building"
			}
		},

		{
			"type": "Feature",
			"geometry": {
				"type": "Point",
				"coordinates": [
				  -0.200000,
				  5.550000
				]
			},
			"properties": {
				"title": "Adam Martin (<a href='http://twitter.com/'>@adamjmartin</a>) — Accra, Ghana",
				"description": "<img src='http://54.243.239.169/brian/africa.rizing/images/mugshot_adamjmartin.jpg' style='width: 30%; float: left; margin-right: 10px; '> #BOS #DCA #ACC Tweets on #beisbol #media #tech dir. of tech & innovation @BBGInnovate former #pubmedia @NPRTechTeam and @NPRNews always RadioBoston dot Com",
				"marker-color": "#FBB040",
				"marker-size": "large"
			}
		},

		{
			"type": "Feature",
			"geometry": {
			"type": "Point",
			"coordinates": [
			  -17.366029,
			  14.764504
			]
			},
			"properties": {
			"title": "Mel Bailey (<a href='twitter.com/'>@MelB4freePress</a>) — Dakar, Senegal",
			"description": "<img src='http://54.243.239.169/brian/africa.rizing/images/mugshot_melb4freepress.jpg' style='width: 30%; float: left; margin-right: 10px;'> Digital Media Specialist @VOA_News in #Dakar Formerly @NBCNews, @NYU Alumna mes tweets n'engage que moi ",
			"marker-color": "#FBB040",
			"marker-size": "large"
			}
		},

		{
			"type": "Feature",
			"geometry": {
			"type": "Point",
			"coordinates": [
			  3.379206,
			  6.524379
			]
			},
			"properties": {
			"title": "Victoria Okoye (<a href='http://twitter.com/'>@victoria_okoye</a>) — Lagos, Nigeria",
			"description": "<img src='http://54.243.239.169/brian/africa.rizing/images/mugshot_victoria_okoye.jpg' style='width: 30%; float: left; margin-right: 10px ''>Dreamer, writer, urban planner, @WIEGOGlobal urban advocate. Tweeting #urban development, #design, #publicspaces, #streetculture, etc. Carl Jung fan.",
			"marker-color": "#FBB040",
			"marker-size": "large"
			}
		}
	  ]
	}

	];
	console.log(geojson);

	//Create the map.
	var map = L.mapbox.map('map-threats', 'visualjournalist.mnbadlih', {
		scrollWheelZoom: false
	});

	//Add the pins to the map.
	var myLayer = L.mapbox.featureLayer().addTo(map);
	myLayer.setGeoJSON(geojson);




	//Check the width of the browser.
	//If the browser is wider than X pixels, recenter the map to include Washington DC.
	function centerMap(){
		var w = window.innerWidth;
		if (w>550){
			//Fit the map to the markers.
			map.fitBounds(myLayer.getBounds());
		}else if (w>450){
			//Center and zoom the map to west Africa.
			map.setView([8, -5], 4);
		}else{
			map.setView([8, -5], 3);
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


	//createGeojson();

}
