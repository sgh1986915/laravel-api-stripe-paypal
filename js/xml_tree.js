var IDCounter = 1;
var NestingIndent = 15;

function LoadXMLDom(ParentElementID, xmlDoc) {
	if (xmlDoc) {
		var xmlHolderElement = GetParentElement(ParentElementID);
		if (xmlHolderElement == null) {
			return false;
		}
		while (xmlHolderElement.childNodes.length) {
			xmlHolderElement.removeChild(xmlHolderElement.childNodes.item(xmlHolderElement.childNodes.length - 1));
		}
		var Result = ShowXML(xmlHolderElement, xmlDoc.documentElement, 0);
		return Result;
	} else {
		return false;
	}
}

function toXml(s, doc) {
	if (window.ActiveXObject) {
		doc = new ActiveXObject('Microsoft.XMLDOM');
		doc.async = 'false';
		doc.loadXML(s);
	} else doc = (new DOMParser()).parseFromString(s, 'text/xml');
	return (doc && doc.documentElement && doc.documentElement.tagName != 'parsererror') ? doc : null;
}

function GetParentElement(ParentElementID) {
	if (typeof(ParentElementID) == 'string') {
		return document.getElementById(ParentElementID);
	} else if (typeof(ParentElementID) == 'object') {
		return ParentElementID;
	} else {
		return null;
	}
}

function ShowXML(xmlHolderElement, RootNode, indent) {
	if (RootNode == null || xmlHolderElement == null) {
		return false;
	}
	var Result = true;
	var TagEmptyElement = document.createElement('div');
	TagEmptyElement.className = 'Element';
	TagEmptyElement.style.position = 'relative';
	TagEmptyElement.style.left = NestingIndent + 'px';
	var ClickableElement = AddTextNode(TagEmptyElement, '+', 'Clickable');
	ClickableElement.onclick = function() {
		ToggleElementVisibility(this);
	}
	ClickableElement.id = 'div_empty_' + IDCounter;
	AddTextNode(TagEmptyElement, '<', 'Utility');
	AddTextNode(TagEmptyElement, RootNode.nodeName, 'NodeName')
	for (var i = 0; RootNode.attributes && i < RootNode.attributes.length; ++i) {
		CurrentAttribute = RootNode.attributes.item(i);
		AddTextNode(TagEmptyElement, ' ' + CurrentAttribute.nodeName, 'AttributeName');
		AddTextNode(TagEmptyElement, '=', 'Utility');
		AddTextNode(TagEmptyElement, '"' + CurrentAttribute.nodeValue + '"', 'AttributeValue');
	}
	AddTextNode(TagEmptyElement, '>  </', 'Utility');
	AddTextNode(TagEmptyElement, RootNode.nodeName, 'NodeName');
	AddTextNode(TagEmptyElement, '>', 'Utility');
	xmlHolderElement.appendChild(TagEmptyElement);
	SetVisibility(TagEmptyElement, false);
	var TagElement = document.createElement('div');
	TagElement.className = 'Element';
	TagElement.style.position = 'relative';
	TagElement.style.left = NestingIndent + 'px';
	ClickableElement = AddTextNode(TagElement, '-', 'Clickable');
	ClickableElement.onclick = function() {
		ToggleElementVisibility(this);
	}
	ClickableElement.id = 'div_content_' + IDCounter;
	++IDCounter;
	AddTextNode(TagElement, '<', 'Utility');
	AddTextNode(TagElement, RootNode.nodeName, 'NodeName');
	for (var i = 0; RootNode.attributes && i < RootNode.attributes.length; ++i) {
		CurrentAttribute = RootNode.attributes.item(i);
		AddTextNode(TagElement, ' ' + CurrentAttribute.nodeName, 'AttributeName');
		AddTextNode(TagElement, '=', 'Utility');
		AddTextNode(TagElement, '"' + CurrentAttribute.nodeValue + '"', 'AttributeValue');
	}
	AddTextNode(TagElement, '>', 'Utility');
	TagElement.appendChild(document.createElement('br'));
	var NodeContent = null;
	for (var i = 0; RootNode.childNodes && i < RootNode.childNodes.length; ++i) {
		if (RootNode.childNodes.item(i).nodeName != '#text') {
			Result &= ShowXML(TagElement, RootNode.childNodes.item(i), indent + 1);
		} else {
			NodeContent = RootNode.childNodes.item(i).nodeValue;
		}
	}
	if (RootNode.nodeValue) {
		NodeContent = RootNode.nodeValue;
	}
	if (NodeContent) {
		var ContentElement = document.createElement('div');
		ContentElement.style.position = 'relative';
		ContentElement.style.left = NestingIndent + 'px';
		AddTextNode(ContentElement, NodeContent, 'NodeValue');
		TagElement.appendChild(ContentElement);
	}
	AddTextNode(TagElement, '  </', 'Utility');
	AddTextNode(TagElement, RootNode.nodeName, 'NodeName');
	AddTextNode(TagElement, '>', 'Utility');
	xmlHolderElement.appendChild(TagElement);
	return Result;
}

function AddTextNode(ParentNode, Text, Class) {
	NewNode = document.createElement('span');
	if (Class) {
		NewNode.className = Class;
	}
	if (Text) {
		if(Text.indexOf('NEW_LINE') > -1) {
			var textElem = Text.split('NEW_LINE');
			for(var i = 0; i < textElem.length; i++) {
		        NewNode.appendChild(document.createTextNode(textElem[i]));
		        NewNode.appendChild(document.createElement("br"));
		    }
		} else {
			NewNode.appendChild(document.createTextNode(Text));
		}
	}
	if (ParentNode) {
		ParentNode.appendChild(NewNode);
	}
	return NewNode;
}

function CompatibleGetElementByID(id) {
	if (!id) {
		return null;
	}
	if (document.getElementById) {
		return document.getElementById(id);
	} else {
		if (document.layers) {
			return document.id;
		} else {
			return document.all.id;
		}
	}
}

function SetVisibility(HTMLElement, Visible) {
	if (!HTMLElement) {
		return;
	}
	var VisibilityStr = (Visible) ? 'block' : 'none';
	if (document.getElementById) {
		HTMLElement.style.display = VisibilityStr;
	} else {
		if (document.layers) {
			HTMLElement.display = VisibilityStr;
		} else {
			HTMLElement.id.style.display = VisibilityStr;
		}
	}
}

function ToggleElementVisibility(Element) {
	if (!Element || !Element.id) {
		return;
	}
	try {
		ElementType = Element.id.slice(0, Element.id.lastIndexOf('_') + 1);
		ElementID = parseInt(Element.id.slice(Element.id.lastIndexOf('_') + 1));
	} catch (e) {
		return;
	}
	var ElementToHide = null;
	var ElementToShow = null;
	if (ElementType == 'div_content_') {
		ElementToHide = 'div_content_' + ElementID;
		ElementToShow = 'div_empty_' + ElementID;
	} else if (ElementType == 'div_empty_') {
		ElementToShow = 'div_content_' + ElementID;
		ElementToHide = 'div_empty_' + ElementID;
	}
	ElementToHide = CompatibleGetElementByID(ElementToHide);
	ElementToShow = CompatibleGetElementByID(ElementToShow);
	if (ElementToHide) {
		ElementToHide = ElementToHide.parentNode;
	}
	if (ElementToShow) {
		ElementToShow = ElementToShow.parentNode;
	}
	SetVisibility(ElementToHide, false);
	SetVisibility(ElementToShow, true);
}

function hasText(el) {
	return el && isString(el.text()) && el.text().length > 0;
}

function isString(x) {
	return typeof(x) == 'string';
}