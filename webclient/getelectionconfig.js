function GetElectionConfig(url, serverkeys, gotConfigObject, gotConfigMethod) {
	this.url = url;
	this.serverkey = serverkeys;
	this.onGotConfigObject = gotConfigObject;
	this.onGotConfigMethod = gotConfigMethod;
//	var kw = new URI(null, null);

	var pos = url.indexOf('?');
	if (pos > -1) {
		q = url.substring(pos + 1) || null;
	}
	var query = URI.parseQuery(q); // tools/url/
	if (!query || !query.confighash) return false; // TODO throw some error
//	var sigsok = verifySigs(query); // TODO implement this
	this.reqestElectionConfig();
	
}

GetElectionConfig.prototype = {
		reqestElectionConfig: function () {
			var me = this;
			myXmlSend(this.url, '', me, this.handleXmlAnswer);
		},

		handleXmlAnswer: function (xml) {
			var config = parseServerAnswer(xml);
			// TODO verify the hash
			// TODO verify config sigs
			this.onGotConfigMethod.call(this.onGotConfigObject, config);
		}
};

/**
 * static methods
 * @returns {String}
 */
GetElectionConfig.getMainContent = function(gotConfigObject, gotConfigMethod) {
	var maincontent = 
		'<div id="divElectionUrl">'+
		'<form>'+
		'Wahl-URL: '+
		'<input style="width:60em" name="electionUrl" id="electionUrlId" type="text" value="http://www.webhod.ra/vvvote2/backend/getelectionconfig.php?confighash=34b71852f90d9c469530d27743da27c34b6795a30c7ef38cb016c613b134d76b">'+
		'<input type="button" name="getelectionconfig" value="Hole Wahlunterlagen"'+ 
		   'onclick="'+
		      'var a = document.getElementById(\'electionUrlId\');' + 
		      'new GetElectionConfig(a.value, null, ' + gotConfigObject + ', ' + gotConfigMethod + ');' +
		      'return false;">'+
		'</form>'+
		'</div>';
	return maincontent;
};


