function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+ d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	console.log("cookie setter");
}

function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) { //list of cookies
			var c = ca[i];// this cookie
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

var cookieCheck = getCookie('splashDismissed');
document.cookie = "splashDismissed=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
console.log("cooke? " + cookieCheck);

if (cookieCheck < 1) {
	// eraseCookie('splashDismissed');
	displaySplash();
}
// console.log(cookieCheck);

function displaySplash() {
	var splashBox = document.createElement('div');
	splashBox.setAttribute('id', 'splash-bg');

	var textBox = document.createElement('div');
	textBox.setAttribute('id', 'text-box');
	textBox.className = "bbg__quotation";

	var quote = document.createElement('h2');
	quote.setAttribute('class', 'bbg__quotation-text--large');
	var quoteText = document.createTextNode('"We grieve their loss and will honor their dedication and commitment to their community."');

	var byLine = document.createElement('p');
	byLine.setAttribute('class', 'bbg__quotation-attribution__text');
	var byLineText = document.createTextNode('Read more about the death of two RFE/RL journalists in Afghanistan.');
	var anchorTag = document.createElement('a');
	anchorTag.setAttribute('class', 'ck-set');
	anchorTag.setAttribute('href','https://www.bbg.gov/2018/04/30/bbg-ceos-statement-on-rferl-journalists-killed-in-kabul-suicide-bombings/');

	var closeBu = document.createElement('div');
	closeBu.setAttribute('id', 'close-splash');
	closeBu.setAttribute('class', 'ck-set');
	var x = document.createTextNode('X');

	anchorTag.append(byLineText);
	quote.append(quoteText);
	closeBu.append(x);
	textBox.append(closeBu);
	textBox.append(quote);
	textBox.append(anchorTag);
	splashBox.append(textBox);
	document.body.append(splashBox);

	var cookieSetters = document.getElementsByClassName("ck-set");

	for (i = 0; i < cookieSetters.length; i++) {
		cookieSetters[i].addEventListener("click", function() {
			document.body.removeChild(splashBox)
			setCookie('splashDismissed', 1, 365);
		});
	}
}