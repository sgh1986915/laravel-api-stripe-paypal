/*
JSSM @VERSION (JavaScript State Manager)
Copyright (c) 2008 Nathan Hammond

Licensed under the MIT License (LICENSE.txt)

To report bugs or get the lates version, visit the website:
http://trac.nathanhammond.com/jssm/

$Date: 2008-09-25 15:42:15 -0700 (Thu, 25 Sep 2008) $
$Revision: 4 $
*/

/*
Object jssm
Supplies history storage and browser abstraction layer for managing history.
*/
var jssm = {
	created: false, // Identifies whether the inline form elements have been created yet.
	rid: 0, // Uniquely identifies the hash change.
	zerolength: false, // The "zero" for where this site appears in the history stack.
	pointer: false, // A reference to where we are in the history stack.
	interval: 100, // The interval at which polling happens.
	stackelem: false, // The form element where the history data is stored.
	lengthelem: false, // The form element to store the page's zero length.
	pointerelem: false, // The form element to store the page's history pointer.
	iframe: false, // The iframe used by IE to register history events.
	stack: [], // The in-memory version of what is in the history stack.
	settings: {
		formid: 'jssmform',
		stackid: 'jssmstack',
		lengthid: 'jssmlength',
		pointerid: 'jssmpointer',
		iframeid: 'jssmiframe',

		blankurl: '/blank.html',

		basetitle: '',
		titleseparator: ' : '
	},
	functions: {
		pageload: false,

		beforeload: false,
		load: false,
		afterload: false,

		beforeunload: false,
		unload: false,
		afterunload: false
	},

	/*
	jssm.inline(Object params)
	Builds the necessary HTML elements for tracking state and makes them appear on the page. Must be called inline.
	*/
	inline: function (params) {
		if (this.created) { return; }

		this.created = true;
		this.settings = jQuery.fn.extend(this.settings, params);

		document.write('<div style="display: none;">');
		document.write('<form id="' + this.settings.formid + '" action="" method="post"><div><textarea name="' + this.settings.stackid + '" id="' + this.settings.stackid + '"></textarea><input type="text" id="' + this.settings.lengthid + '" name="' + this.settings.lengthid + '" /><input type="text" id="' + this.settings.pointerid + '" name="' + this.settings.pointerid + '" /></div></form>');
		// Only need the iframe trick in IE, < IE8.
		if ( jQuery.browser.msie && jQuery.browser.version < 8 ) {
			document.write('<iframe id="' + this.settings.iframeid + '" src="' + this.settings.blankurl + '?' + this.getHash() + '"></iframe>');
		}
		document.write('</div>');

		// Only need the image trick for Opera. Can't be display: none;.
		if ( jQuery.browser.opera ) {
			document.write('<img style="position: absolute; left: -999em; top: -999em;" width="1" height="1"  src="javascript:location.href=\'javascript:jssm.fixOpera();\';" />');
		}
	},

	/*
	jssm.init(String type)
	Function called to initialize JSSM. It gets called twice and, depending upon which browser you're in and which event triggered it (identified by the type argument), it executes.
	*/
	init: function (type) {
		if (jQuery.browser.msie && type == 'ready') { return; }
		if (!jQuery.browser.msie && type == 'load') { return; }

		jQuery(window).bind('hashchange', jssm.hashchange);

		// Store references to all of the important elements on the page.
		this.stackelem = document.getElementById(this.settings.stackid);
		this.lengthelem = document.getElementById(this.settings.lengthid);
		this.pointerelem = document.getElementById(this.settings.pointerid);
		this.iframe = document.getElementById(this.settings.iframeid);

		// Opera needs to focus each element before it can write to it.
		if ( jQuery.browser.opera ) {
			this.stackelem.focus();
			this.lengthelem.focus();
			this.pointerelem.focus();
			window.focus(); // Reset to avoid typing into field.
		}

		// Set the initialized state to match either the form's values or to be new.
		if ( this.lengthelem.value ) {
			// They've already been here.
			this.zerolength = this.lengthelem.value;
			this.load();
		} else {
			// First visit to the page.
			this.zerolength = history.length;
			this.pointer = this.zerolength;
			this.stack[this.pointer] = this.getHash();
			this.save();
		}

		this.rid = this.getRID(this.stack[this.pointer]);
		this.rid++;
		if (this.functions.pageload) { this.functions.pageload(this.stack[this.pointer]); }

		if ( !jQuery.browser.msie || ( jQuery.browser.msie && jQuery.browser.version < 8 ) ) {
			this.poll();
		}
	},

	/*
	jssm.poll()
	This function polls the location bar to check for changes. When a change is detected it manually triggers a hashchange event.
	*/
	poll: function () {
		// Called out of context: "this" refers to window.
		if (jssm.getHash() != jssm.stack[jssm.pointer]) {
			jQuery(window).trigger('hashchange');
		}

		setTimeout(jssm.poll, jssm.interval);
	},

	/*
	jssm.fixOpera()
	Function does nothing. Opera clears timers except when an image has a callback function. This is that callback's target.
	*/
	fixOpera: function () {
		// Do nothing.
	},

	/*
	jssm.iframeEvent(String changedhash)
	Propagates a change made in the IE-only iframe to the main window.
	*/
	iframeEvent: function (changedhash) {
		if (changedhash || window.location.hash) {
			window.location.hash = changedhash;
		}
	},

	/*
	jssm.hashchange()
	This is the function that gets called on hashchange. It processes the changed hash and updates the current state of the JSSM object accordingly.
	It is also responsible for handling the load and unload functionality for all pages.
	*/
	hashchange: function () {
		// Called out of context: "this" refers to window

		// Store the previous and changed hash values in temporary variables for easy access.
		var changedhash = jssm.getHash();
		var calchash = changedhash;

		// The relative path from a page to itself is actually #pagename. Taking advantage of that, an empty string means we can only want to go back to the initial page.
		if (changedhash === '') { calchash = jssm.getCurrentPage(); }

		if ( jQuery.browser.msie && jQuery.browser.version < 8 && jssm.iframe.contentWindow.document.body.innerText != changedhash ) {
			jssm.setHash(changedhash);
		}

		if (jssm.functions.beforeunload) { jssm.functions.beforeunload(calchash); }
		if (jssm.functions.unload) { jssm.functions.unload(calchash); }
		if (jssm.functions.afterunload) { jssm.functions.afterunload(calchash); }

		if (jssm.functions.beforeload) { jssm.functions.beforeload(calchash); }
		if (jssm.functions.load) { jssm.functions.load(calchash); }
		if (jssm.functions.afterload) { jssm.functions.afterload(calchash); }

		// Figure out where to set the pointer.
		var exists = [];
		for ( var i = jssm.zerolength; i < jssm.stack.length; i++ ) {
			// Go through all items in the stack to see if this "new" hash exists. Store all indexes where there is a hash match.
			if ( changedhash === jssm.stack[i] ) { exists.push(i); }
		}

		switch ( exists.length ) {
			case 0:
				// The current hash is new. Add this state to the stack at pointer + 1 and then splice off all following elements.
				jssm.pointer++;
				jssm.stack[jssm.pointer] = changedhash;
				jssm.stack.length = jssm.pointer + 1;
			break;
			case 1:
				// The current hash has been used before, and there is exactly one match.
				jssm.pointer = exists[0];
			break;
		}

		jssm.save();
	},

	/* Helper functions used to manage the form-based history stack. */

	/*
	jssm.load()
	Pulls the information stored in the page forms into memory. Used to reconstitute a session.
	*/
	load: function () {
		this.stack = JSON.parse(this.stackelem.value);
		this.length = this.lengthelem.value;
		this.pointer = this.pointerelem.value;
	},

	/*
	jssm.save()
	Puts the information stored in memory into the form fields. Used to store a session in case the user leaves the site.
	*/
	save: function () {
		this.stackelem.value = JSON.stringify(this.stack);
		this.lengthelem.value = this.zerolength;
		this.pointerelem.value = this.pointer;
	},

	/* Helper functions for handling the location bar. */

	/*
	jssm.getHash(), returns String
	Gets the current hash for the page.
	*/
	getHash: function () {
		if (jQuery.browser.safari && parseInt(jQuery.browser.version, 10) < 522 && !/adobeair/i.test(jQuery.browser.userAgent)) {
			// Safari 2 needs help.
			this.getHash = function () {
				return jssm.stack[history.length - jssm.zerolength - 1];
			};
		} else {
			// Everybody else.
			this.getHash = function () {
				var r = window.location.href;
				var i = r.indexOf("#");
				return (i >= 0 ? r.substr(i+1) : '');
			};
		}
		return this.getHash();
	},

	/*
	jssm.setHash(String hash)
	Sets the hash in the location bar appropriately for each browser.
	*/
	setHash: function (hash) {
		if ( jQuery.browser.msie && jQuery.browser.version < 8 ) {
			this.setHash = function (hash) {
				var iframe = jssm.iframe.contentWindow.document;
				iframe.open("javascript:'<html></html>'");
				iframe.write('<html><head><scri' + 'pt type="text/javascript">window.parent.jssm.iframeEvent("' + hash + '");</scri' + 'pt></head><body>' + hash + '</body></html>');
				iframe.close();
			};
		} else {
			this.setHash = function (hash) {
				window.location.hash = hash;
			};
		}
		return this.setHash(hash);
	},

	/*
	jssm.getRID(String hash), returns RID
	Gets the unique RID (request ID) for the element.
	*/
	getRID: function (hash) {
		if (!hash) { return 0; }
		var str = hash.match(/rid=[\d]+/);
		return str ? str[0].substr(4) : 0;
	},

	/*
	jssm.getHref(), returns String
	Gets the current href from the location bar. Trims only the hash.
	*/
	getHref: function () {
		var i = window.location.href.indexOf("#");
		return (i >= 0 ? window.location.href.substr(0, i) : window.location.href);
	},

	/*
	jssm.getPathTokens(String href), returns PathTokens
	Gets string tokens out of a URL.
	*/
	getPathTokens: function (href) {
		// 1. Identify if there is a hash or querystring.
		var hashpos = href.indexOf('#');
		var querypos = href.indexOf('?');
		var querystring = '';
		var hashstring = '';

		if (hashpos != -1) {
			// There is a hashstring.
			if (querypos != -1 && querypos < hashpos) {
				// There is a querystring and a hashstring.
				querystring = href.substring(querypos, hashpos);
				hashstring = href.substring(hashpos);
			} else {
				// There is only a hashstring.
				hashstring = href.substring(hashpos);
			}
		} else {
			// There is no hashstring.
			if (querypos >= 0) {
				// There is a querystring.
				querystring = href.substring(querypos);
			} else {
				// There is no querystring.
			}
		}

		// 2. Strip off anything that is a hash or querystring.
		if (hashpos != -1 && querypos != -1) {
			href = href.substring(0, Math.min(hashpos, querypos));
		} else if (hashpos != -1) {
			href = href.substring(0, hashpos);
		} else if (querypos != -1) {
			href = href.substring(0, querypos);
		}

		// 3. Pass prepared url to regex.
		var regex = /^(https?:\/\/){0,1}([A-Za-z0-9\-\.]+){0,1}(\:\d+){0,1}(\/){0,1}((?:[^\/]*\/)*){0,1}(.*)$/;
		href = regex.exec(href);

		// 0: full string
		// 1: protocol (http:// || https://)
		// 2: hostname
		// 3: port
		// 4: leading slash?
		// 5: dirtree
		// 6: page

		if (href[5]) {
			href[5] = href[5].split('/');
			href[5].pop();
		} else {
			href[5] = [];
		}

		// 7: querystring
		// 8: hashstring
		href.push(querystring);
		href.push(hashstring);

		return href;
	},

	/*
	jssm.getCurrentPath(), returns String
	Gets the path relative to the current webroot.
	*/
	getCurrentPath: function () {
		var tokens = this.getPathTokens(this.getHref());
		return (tokens && tokens[5] ? tokens[5].join('/') + '/' : '');
	},

	/*
	jssm.getCurrentPage(), returns String
	Gets the currently active page, ignoring the path.
	*/
	getCurrentPage: function () {
		var tokens = this.getPathTokens(this.getHref());
		return (tokens && tokens[6] ? tokens[6] : '');
	},

	/*
	jssm.getRelativePath(String from, String two, Boolean strict), returns String
	Gets the relative path from the second argument to the first one.

	String from - Must always be fully qualified.
	String to - May be a relative path. In this scenario the protocol, hostname, and port must all be omitted.
	Boolean strict - Requires both arguments to be fully-qualified domains and does not infer default ports.
	Returns:
		- Boolean false if it is not possible to get a relative path.
		- Empty string if the paths are the same.
		- Relative path in all other scenarios.
	*/
	getRelativePath: function (from, to, strict) {
		// Check for the fastest scenario.
		if (from == to && to.indexOf('?') == -1) { return this.getCurrentPage(to); }

		from = this.getPathTokens(from);
		to = this.getPathTokens(to);
		strict = strict || false;

		// Protocol must match if strict checking is enabled, or the "to" protocol is defined.
		if ((strict || to[1]) && (from[1] !== to[1])) { return false; }

		// Hostname must match if strict checking is enabled, or the "to" hostname is defined.
		if ((strict || to[2]) && (from[2] !== to[2])) { return false; }

		// Port may match against defaults for the protocol except in strict mode.
		if (from[3] !== to[3]) {
			if (strict) { return false; }

			var port = false;

			// Match a protocol to its default port. Switch statement to leave room for more protocols.
			switch (from[1]) {
				case 'http': port = 80; break;
				case 'https': port = 443; break;
			}

			// Didn't match any of our supported protocols for matching.
			if (!port) { return false; }

			if (!(from[3] === '' && to[3] == port) && !(from[3] == port && to[3] === '')) { return false; }
		}

		// We are going to be able to determine a relative path.
		var buildstring = '';

		if (!from[5].length || !to[4]) {
			// If we're already at the root or we don't need to be comparing from the root dir.
			buildstring += to[5].join('/') + '/' + (to[6] ? to[6] : '');
		} else {
			// We have to begin the path comparison at the root.

			var i = 0;

			if (from[5].length && to[5].length) {
				// If both from and to have dirtrees.
				
				// Go through the directory tree and make comparisons.
				for ( i = 0; i < from[5].length; i++ ) {
					if (from[5][i] == to[5][i]) {
						// They match, shift them both of the front. Have to decrement "i" to stay where we want in the loop.
						from[5].shift(); to[5].shift();
						i--;
					} else {
						// First non-match, break out of the loop and calculate depth of difference.
						break;
					}
				}

				// Depth difference calculation.
				for ( i = 0; i < from[5].length; i++ ) {
					buildstring += '../';
				}

				buildstring += to[5].join('/') + (to[5].length ? '/' : '') + (to[6] ? to[6] : '');
			} else {
				// If only from has a dirtree we just need to do a depth difference calculation.
				for ( i = 0; i < from[5].length; i++) {
					buildstring += '../';
				}

				buildstring += (to[6] ? to[6] : '');
			}
		}

		// Querystrings and hashes in "from" are never part of the resulting relative path. Simply use what appears in "to" for the new path.
		if (to[7]) { buildstring += to[7]; }
		if (to[8]) { buildstring += to[8]; }

		return buildstring;
	},

	/*
	jssm.getRootRelativePath(String from), returns String
	Returns the root relative path from the supplied path. The parameter must be fully qualified.

	jssm.getRootRelativePath(String from, String to, Boolean strict), returns String
	Makes sure that the two paths are relative to eachother and then returns the root relative path of the second one.

	String from - Must always be fully qualified.
	String to - May be a relative path.
	Boolean strict - Requires both arguments to be fully-qualified domains and does not infer default ports.
	Returns:
		- Boolean false if it is not possible to get a relative path.
		- Root relative path.
	*/
	getRootRelativePath: function (from, to, strict) {
		if (arguments.length == 1) {
			// Looking for the root relative path of a single href.
			var tokens = jssm.getPathTokens(from);
			return '/' + tokens[5].join('/') + '/' + (tokens[6] ? tokens[6] : '');
		}

		from = this.getPathTokens(from);
		to = this.getPathTokens(to);
		strict = strict || false;

		// Protocol must match if strict checking is enabled, or the "to" protocol is defined.
		if ((strict || to[1]) && (from[1] !== to[1])) { return false; }

		// Hostname must match if strict checking is enabled, or the "to" hostname is defined.
		if ((strict || to[2]) && (from[2] !== to[2])) { return false; }

		// Port may match against defaults for the protocol except in strict mode.
		if (from[3] !== to[3]) {
			if (strict) { return false; }

			var port = false;

			// Match a protocol to its default port. Switch statement to leave room for more protocols.
			switch (from[1]) {
				case 'http': port = 80; break;
				case 'https': port = 443; break;
			}

			// Didn't match any of our supported protocols for matching.
			if (!port) { return false; }

			if (!(from[3] === '' && to[3] == port) && !(from[3] == port && to[3] === '')) { return false; }
		}

		// We are going to be able to determine a root relative path. Everything from 5 - 8 in the "to" path should be strung back together.
		var buildstring = '/';
		if (!to[4]) { buildstring += from[5].join('/') + '/'; }
		if (to[5].length) { buildstring += to[5].join('/') + '/'; }
		if (to[6]) { buildstring += to[6]; }

		// For querystrings and hashes in "from" are never part of the resulting relative path. Simply use what appears in "to" for the new path.
		if (to[7]) { buildstring += to[7]; }
		if (to[8]) { buildstring += to[8]; }

		return buildstring;
	},

	/* Helpers to deal with response information. */

	/*
	jssm.setTitle(String title)
	Sets the document title.
	*/
	setTitle: function(title) {
		document.title = title;
	},

	/*
	jssm.buildTitle(String title, String separator), returns String
	Builds the document title from the basetitle specified in the settings and the title specified by the page.
	*/
	buildTitle: function(title, separator) {
		separator = separator || this.settings.titleseparator;
		return this.settings.basetitle + (title ? separator + title : '');
	}
};

jQuery.fn.jssm = function (eventtype, params) {
	// Uncomment the following line to disable JSSM.
	// return;

	// Go through each element provided and attach events.
	return this.each(function (i) {
		// Add the supplied parameters to the element's expando with the JSSM namespace.
		// jQuery.data(this, 'jssm', params);

		// Attach event to the element that causes the hash to change. This triggers JSSM to provide all other functionality by noting the hash change.
		jQuery(this).bind(eventtype, function (event) {
			var params = jQuery.data(this, 'jssm');
			var href = '';
			var data = '';

			if (eventtype == 'submit' && this.action) {
				// We've been handed a form or something masquerading as one.

				// Form.action doesn't return a fully qualified domain.
				// Anchor.href returns fully qualified domains in all but IE.
				// Creating an Anchor using Element.innerHTML causes IE to use fully qualified domains.
				// jQuery's element creation uses innerHTML, this (ab)uses that side effect to get a fully qualified domain.
				href = jQuery('<a href="' + this.action + '"></a>').get(0).href;
				data = '&' + jQuery(this).serialize();
			} else if (this.href) {
				// We've been handed an anchor tag or something masquerading as one.
				href = this.href;
			}

			// Figure out what the new hash is supposed to be.
			var target = jssm.getRelativePath(jssm.getHref(), href);

			// Set the hash.
			if (target !== false) {
				target = target + (target.indexOf('?') >= 0 ? '&' : '?') + 'rid=' + (jssm.rid++) + data;
				jssm.setHash(target);
			}

			event.preventDefault();
			return false;
		});

	});
};

jQuery(document).ready(function () { jssm.init('ready'); });
jQuery(window).load(function () { jssm.init('load'); });