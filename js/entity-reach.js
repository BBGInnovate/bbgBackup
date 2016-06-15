jQuery(function () {
	// inititalize with VOA
	grabData('voa');

	jQuery('#entity').on('change', function () {
		var entity = jQuery(this).val();
		grabData(entity);
	});
});

function grabData(entity) {
	jQuery('#loading').show();

	jQuery.getJSON('https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/api.php?endpoint=api/countries/?group='+entity)
		.done(function( data ) {
			var countries = [];
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

			var map = AmCharts.makeChart( "chartdiv", {
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
				/*
				 legend: {
				 width: "100%",
				 marginRight: 27,
				 marginLeft: 27,
				 equalWidths: false,
				 backgroundAlpha: 0.5,
				 backgroundColor: "#FFFFFF",
				 borderColor: "#ffffff",
				 borderAlpha: 1,
				 top: 400,
				 left: 0,
				 horizontalGap: 2,
				 verticalGap: 12,
				 data: legendData
				 },
				 */
				"export": {
					"enabled": true
				}

			} );

			jQuery('#loading').hide();

			map.addListener("clickMapObject", function (event) {
				//	getAPIData(event.mapObject.title);
				getAPIDataCallback(event.mapObject.title);
			});

			map.addListener('homeButtonClicked', function (event) {
				jQuery('.country-details').hide();
				jQuery('.detail').empty();
			});
		})
		.fail(function( jqxhr, textStatus, error ) {
			var err = textStatus + ", " + error;
			alert("Request Failed: " + err);
		});
}
