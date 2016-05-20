I owe a debt of gratitude to a number of people for contributions that they have (unwittingly) made:
Brian Dillard and Brad Neuberg's Really Simple History provided an excellent existing solution on which I could base my approach. (http://code.google.com/p/reallysimplehistory/)
Bertrand Le Roy provided succinct test cases on his blog that were invaluable. (http://weblogs.asp.net/bleroy/archive/2007/09/07/how-to-build-a-cross-browser-history-management-system.aspx)
Julien Lecomte is I think responsible for the window.location.href.indexOf('#') trick to get the hash with URL encoding (http://developer.yahoo.com/yui/history/).
Douglas Crockford wrote a so-called reference implementation of a JSON stringifier and parser of which I've included the latest version. (http://www.json.org/js.html)
Peter Michaux introduced me to the lazy function definition pattern. (http://peter.michaux.ca/article/3556)

JSSM Rules:
	1. All pages that use JSSM should be tested without JavaScript (or at least JSSM) enabled. Ensure that all items JSSM animates on page load appear as necessary without JavaScript. Look into using the <noscript> tag.
	2. AJAX POST requests should be handled outside of JSSM. Post requests should not be a bookmarkable event. These are the type of requests that generally require special handling because of the way that they will change session state. If needed have the response from a manual POST request trigger a JSSM redirect.

General:
	1. The latest released version of jQuery (1.2.6) is packaged with the plugin. The plugin should work with jQuery 1.2 and all subsequent releases.
	2. Douglas Crockford's JSON implementation from JSON.org is included. ***NOTE: It has been modified! Lines 186 and 187 have been changed to resolve the issue discussed here: http://dev.opera.com/forums/topic/215757

JSSM Instructions:
	1. Include blank.html in a location on the same host as the page being supported by JSSM.
	2. Modify jquery.jssm.config.js to meet the needs of the site/application by defining the default functions for JSSM handling: beforeunload, unload, afterunload, beforeload, load, afterload. It is recommended to write at least the load and unload default functions.
	3. Define initial page load handler function. This is assuming that the way the page is loaded initially does not match the standard loading methods. If it does, have the pageload method alias the load method.
	4. Include all scripts in their proper order:
		<head>
		<script type="text/javascript" src="json2.js"></script>
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="jquery.jssm.js"></script>
		<script type="text/javascript" src="jquery.jssm.config.js"></script>
		</head>
		<body>
			<script type="text/javascript">jssm.inline();</script>	
			...
		</body>
	5. Attach JSSM to all elements to be handled with it inside a $(document).ready() block.

JSSM Initialization:
	1. User requests the initial page.
		- This could possibly not be the standard point of entry, a subpage: /mypictures/florida.html
		- This request could possibly have a hash appended to it: /mypictures/florida.html#../myvideos/keywest.html
	2. Initial page is sent from the server to the user's computer. The page as provided by the server will not reflect the hash state.

	*** JavaScript is enabled. ***
	1. jQuery is compiled and sets itself up.
	2. The JSSM extension is compiled and sets itself up. This adds the function .jssm() to jQuery to indicate that an element should be handled by JSSM.
	3. The JSSM object is compiled and sets itself up. This sets defaults for handling the browsers different history mechanisms.
	4. The JSSM method "inline", placed inside the body tag, is run to create the storage form.
	5. User supplied scripts are run.
	6. Page reaches ready/load state.
	7. The JSSM object's init function is called, careful to respect needs of different browsers as to timing.
		a. Initializes the history stack to mirror the browser state.
		b. Saves the history state to form elements to maintain themselves for the entire browser session.
		c. Binds the hashchange event handler. (See JSSM Event Processing)
		d. Determines if there is a hash and loads the initial page.
			- If there is a hash, from this point forward it uses the handler functions defined for that hash.
			- If there is not a hash, it continues using the initial page handler functions.
		e. Begins polling for hashchange events (IE8: won't need to poll).
	11. Page is now finished loading.

	*** JavaScript is not enabled. ***
	1. Items in <noscript> tags are rendered, including CSS that counteracts all initial page loading functionality.
	2. Page is now finished loading.

JSSM Event Processing:
	1. A JSSM'd page element has the specified event happen to it (click, submit).
	2. That event changes location.hash (IE: iframe.location.src) according to the element's href or action. It is set to be the relative URL between the initial page and the target of the link. Additionally, forms are serialized before being placed on the hash string.
	3. The location bar polling function that notices that the hash is changed and triggers the body's hashchange event (and associated callback).
	4. The JSSM object's hashchange method is invoked on document.body to process the changed hash.
		a. This processes the changed hash and updates JSSM's understanding of history state.
		b. Once the history state is set, it calls the transition functions first for unloading, and then for loading.

Server Side Page Design:
	1. Plan the design of the code carefully as the response handling functionality will need to know what to do with the response text.
	2. It is recommended to have two ways to call the same page: one to serve the page statically, one to serve the response to JSSM in a format the defined response handlers know how to deal with. This branching will allow for better optimization of the JSSM response handling code.