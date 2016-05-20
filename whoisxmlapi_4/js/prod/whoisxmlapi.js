;(function($){$.fn.ajaxSubmit=function(options){if(!this.length){log('ajaxSubmit: skipping submit process - no element selected');return this;}
if(typeof options=='function')
options={success:options};var url=this.attr('action')||window.location.href;url=(url.match(/^([^#]+)/)||[])[1];url=url||'';options=$.extend({url:url,type:this.attr('method')||'GET'},options||{});var veto={};this.trigger('form-pre-serialize',[this,options,veto]);if(veto.veto){log('ajaxSubmit: submit vetoed via form-pre-serialize trigger');return this;}
if(options.beforeSerialize&&options.beforeSerialize(this,options)===false){log('ajaxSubmit: submit aborted via beforeSerialize callback');return this;}
var a=this.formToArray(options.semantic);if(options.data){options.extraData=options.data;for(var n in options.data){if(options.data[n]instanceof Array){for(var k in options.data[n])
a.push({name:n,value:options.data[n][k]});}
else
a.push({name:n,value:options.data[n]});}}
if(options.beforeSubmit&&options.beforeSubmit(a,this,options)===false){log('ajaxSubmit: submit aborted via beforeSubmit callback');return this;}
this.trigger('form-submit-validate',[a,this,options,veto]);if(veto.veto){log('ajaxSubmit: submit vetoed via form-submit-validate trigger');return this;}
var q=$.param(a);if(options.type.toUpperCase()=='GET'){options.url+=(options.url.indexOf('?')>=0?'&':'?')+q;options.data=null;}
else
options.data=q;var $form=this,callbacks=[];if(options.resetForm)callbacks.push(function(){$form.resetForm();});if(options.clearForm)callbacks.push(function(){$form.clearForm();});if(!options.dataType&&options.target){var oldSuccess=options.success||function(){};callbacks.push(function(data){$(options.target).html(data).each(oldSuccess,arguments);});}
else if(options.success)
callbacks.push(options.success);options.success=function(data,status){for(var i=0,max=callbacks.length;i<max;i++)
callbacks[i].apply(options,[data,status,$form]);};var files=$('input:file',this).fieldValue();var found=false;for(var j=0;j<files.length;j++)
if(files[j])
found=true;if(options.iframe||found){if(options.closeKeepAlive)
$.get(options.closeKeepAlive,fileUpload);else
fileUpload();}
else
$.ajax(options);this.trigger('form-submit-notify',[this,options]);return this;function fileUpload(){var form=$form[0];if($(':input[name=submit]',form).length){alert('Error: Form elements must not be named "submit".');return;}
var opts=$.extend({},$.ajaxSettings,options);var s=jQuery.extend(true,{},$.extend(true,{},$.ajaxSettings),opts);var id='jqFormIO'+(new Date().getTime());var $io=$('<iframe id="'+id+'" name="'+id+'" src="about:blank" />');var io=$io[0];$io.css({position:'absolute',top:'-1000px',left:'-1000px'});var xhr={aborted:0,responseText:null,responseXML:null,status:0,statusText:'n/a',getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(){this.aborted=1;$io.attr('src','about:blank');}};var g=opts.global;if(g&&!$.active++)$.event.trigger("ajaxStart");if(g)$.event.trigger("ajaxSend",[xhr,opts]);if(s.beforeSend&&s.beforeSend(xhr,s)===false){s.global&&jQuery.active--;return;}
if(xhr.aborted)
return;var cbInvoked=0;var timedOut=0;var sub=form.clk;if(sub){var n=sub.name;if(n&&!sub.disabled){options.extraData=options.extraData||{};options.extraData[n]=sub.value;if(sub.type=="image"){options.extraData[name+'.x']=form.clk_x;options.extraData[name+'.y']=form.clk_y;}}}
setTimeout(function(){var t=$form.attr('target'),a=$form.attr('action');form.setAttribute('target',id);if(form.getAttribute('method')!='POST')
form.setAttribute('method','POST');if(form.getAttribute('action')!=opts.url)
form.setAttribute('action',opts.url);if(!options.skipEncodingOverride){$form.attr({encoding:'multipart/form-data',enctype:'multipart/form-data'});}
if(opts.timeout)
setTimeout(function(){timedOut=true;cb();},opts.timeout);var extraInputs=[];try{if(options.extraData)
for(var n in options.extraData)
extraInputs.push($('<input type="hidden" name="'+n+'" value="'+options.extraData[n]+'" />').appendTo(form)[0]);$io.appendTo('body');io.attachEvent?io.attachEvent('onload',cb):io.addEventListener('load',cb,false);form.submit();}
finally{form.setAttribute('action',a);t?form.setAttribute('target',t):$form.removeAttr('target');$(extraInputs).remove();}},10);var nullCheckFlag=0;function cb(){if(cbInvoked++)return;io.detachEvent?io.detachEvent('onload',cb):io.removeEventListener('load',cb,false);var ok=true;try{if(timedOut)throw'timeout';var data,doc;doc=io.contentWindow?io.contentWindow.document:io.contentDocument?io.contentDocument:io.document;if((doc.body==null||doc.body.innerHTML=='')&&!nullCheckFlag){nullCheckFlag=1;cbInvoked--;setTimeout(cb,100);return;}
xhr.responseText=doc.body?doc.body.innerHTML:null;xhr.responseXML=doc.XMLDocument?doc.XMLDocument:doc;xhr.getResponseHeader=function(header){var headers={'content-type':opts.dataType};return headers[header];};if(opts.dataType=='json'||opts.dataType=='script'){var ta=doc.getElementsByTagName('textarea')[0];xhr.responseText=ta?ta.value:xhr.responseText;}
else if(opts.dataType=='xml'&&!xhr.responseXML&&xhr.responseText!=null){xhr.responseXML=toXml(xhr.responseText);}
data=$.httpData(xhr,opts.dataType);}
catch(e){ok=false;$.handleError(opts,xhr,'error',e);}
if(ok){opts.success(data,'success');if(g)$.event.trigger("ajaxSuccess",[xhr,opts]);}
if(g)$.event.trigger("ajaxComplete",[xhr,opts]);if(g&&!--$.active)$.event.trigger("ajaxStop");if(opts.complete)opts.complete(xhr,ok?'success':'error');setTimeout(function(){$io.remove();xhr.responseXML=null;},100);};function toXml(s,doc){if(window.ActiveXObject){doc=new ActiveXObject('Microsoft.XMLDOM');doc.async='false';doc.loadXML(s);}
else
doc=(new DOMParser()).parseFromString(s,'text/xml');return(doc&&doc.documentElement&&doc.documentElement.tagName!='parsererror')?doc:null;};};};$.fn.ajaxForm=function(options){return this.ajaxFormUnbind().bind('submit.form-plugin',function(){$(this).ajaxSubmit(options);return false;}).each(function(){$(":submit,input:image",this).bind('click.form-plugin',function(e){var form=this.form;form.clk=this;if(this.type=='image'){if(e.offsetX!=undefined){form.clk_x=e.offsetX;form.clk_y=e.offsetY;}else if(typeof $.fn.offset=='function'){var offset=$(this).offset();form.clk_x=e.pageX-offset.left;form.clk_y=e.pageY-offset.top;}else{form.clk_x=e.pageX-this.offsetLeft;form.clk_y=e.pageY-this.offsetTop;}}
setTimeout(function(){form.clk=form.clk_x=form.clk_y=null;},10);});});};$.fn.ajaxFormUnbind=function(){this.unbind('submit.form-plugin');return this.each(function(){$(":submit,input:image",this).unbind('click.form-plugin');});};$.fn.formToArray=function(semantic){var a=[];if(this.length==0)return a;var form=this[0];var els=semantic?form.getElementsByTagName('*'):form.elements;if(!els)return a;for(var i=0,max=els.length;i<max;i++){var el=els[i];var n=el.name;if(!n)continue;if(semantic&&form.clk&&el.type=="image"){if(!el.disabled&&form.clk==el)
a.push({name:n+'.x',value:form.clk_x},{name:n+'.y',value:form.clk_y});continue;}
var v=$.fieldValue(el,true);if(v&&v.constructor==Array){for(var j=0,jmax=v.length;j<jmax;j++)
a.push({name:n,value:v[j]});}
else if(v!==null&&typeof v!='undefined')
a.push({name:n,value:v});}
if(!semantic&&form.clk){var inputs=form.getElementsByTagName("input");for(var i=0,max=inputs.length;i<max;i++){var input=inputs[i];var n=input.name;if(n&&!input.disabled&&input.type=="image"&&form.clk==input)
a.push({name:n+'.x',value:form.clk_x},{name:n+'.y',value:form.clk_y});}}
return a;};$.fn.formSerialize=function(semantic){return $.param(this.formToArray(semantic));};$.fn.fieldSerialize=function(successful){var a=[];this.each(function(){var n=this.name;if(!n)return;var v=$.fieldValue(this,successful);if(v&&v.constructor==Array){for(var i=0,max=v.length;i<max;i++)
a.push({name:n,value:v[i]});}
else if(v!==null&&typeof v!='undefined')
a.push({name:this.name,value:v});});return $.param(a);};$.fn.fieldValue=function(successful){for(var val=[],i=0,max=this.length;i<max;i++){var el=this[i];var v=$.fieldValue(el,successful);if(v===null||typeof v=='undefined'||(v.constructor==Array&&!v.length))
continue;v.constructor==Array?$.merge(val,v):val.push(v);}
return val;};$.fieldValue=function(el,successful){var n=el.name,t=el.type,tag=el.tagName.toLowerCase();if(typeof successful=='undefined')successful=true;if(successful&&(!n||el.disabled||t=='reset'||t=='button'||(t=='checkbox'||t=='radio')&&!el.checked||(t=='submit'||t=='image')&&el.form&&el.form.clk!=el||tag=='select'&&el.selectedIndex==-1))
return null;if(tag=='select'){var index=el.selectedIndex;if(index<0)return null;var a=[],ops=el.options;var one=(t=='select-one');var max=(one?index+1:ops.length);for(var i=(one?index:0);i<max;i++){var op=ops[i];if(op.selected){var v=op.value;if(!v)
v=(op.attributes&&op.attributes['value']&&!(op.attributes['value'].specified))?op.text:op.value;if(one)return v;a.push(v);}}
return a;}
return el.value;};$.fn.clearForm=function(){return this.each(function(){$('input,select,textarea',this).clearFields();});};$.fn.clearFields=$.fn.clearInputs=function(){return this.each(function(){var t=this.type,tag=this.tagName.toLowerCase();if(t=='text'||t=='password'||tag=='textarea')
this.value='';else if(t=='checkbox'||t=='radio')
this.checked=false;else if(tag=='select')
this.selectedIndex=-1;});};$.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=='function'||(typeof this.reset=='object'&&!this.reset.nodeType))
this.reset();});};$.fn.enable=function(b){if(b==undefined)b=true;return this.each(function(){this.disabled=!b;});};$.fn.selected=function(select){if(select==undefined)select=true;return this.each(function(){var t=this.type;if(t=='checkbox'||t=='radio')
this.checked=select;else if(this.tagName.toLowerCase()=='option'){var $sel=$(this).parent('select');if(select&&$sel[0]&&$sel[0].type=='select-one'){$sel.find('option').selected(false);}
this.selected=select;}});};function log(){if($.fn.ajaxSubmit.debug&&window.console&&window.console.log)
window.console.log('[jquery.form] '+Array.prototype.join.call(arguments,''));};})(jQuery);;(function($){$.extend($.fn,{validate:function(options){if(!this.length){options&&options.debug&&window.console&&console.warn("nothing selected, can't validate, returning nothing");return;}
var validator=$.data(this[0],'validator');if(validator){return validator;}
validator=new $.validator(options,this[0]);$.data(this[0],'validator',validator);if(validator.settings.onsubmit){this.find("input, button").filter(".cancel").click(function(){validator.cancelSubmit=true;});this.submit(function(event){if(validator.settings.debug)
event.preventDefault();function handle(){if(validator.settings.submitHandler){validator.settings.submitHandler.call(validator,validator.currentForm);return false;}
return true;}
if(validator.cancelSubmit){validator.cancelSubmit=false;return handle();}
if(validator.form()){if(validator.pendingRequest){validator.formSubmitted=true;return false;}
return handle();}else{validator.focusInvalid();return false;}});}
return validator;},valid:function(){if($(this[0]).is('form')){return this.validate().form();}else{var valid=false;var validator=$(this[0].form).validate();this.each(function(){valid|=validator.element(this);});return valid;}},removeAttrs:function(attributes){var result={},$element=this;$.each(attributes.split(/\s/),function(index,value){result[value]=$element.attr(value);$element.removeAttr(value);});return result;},rules:function(command,argument){var element=this[0];if(command){var settings=$.data(element.form,'validator').settings;var staticRules=settings.rules;var existingRules=$.validator.staticRules(element);switch(command){case"add":$.extend(existingRules,$.validator.normalizeRule(argument));staticRules[element.name]=existingRules;if(argument.messages)
settings.messages[element.name]=$.extend(settings.messages[element.name],argument.messages);break;case"remove":if(!argument){delete staticRules[element.name];return existingRules;}
var filtered={};$.each(argument.split(/\s/),function(index,method){filtered[method]=existingRules[method];delete existingRules[method];});return filtered;}}
var data=$.validator.normalizeRules($.extend({},$.validator.metadataRules(element),$.validator.classRules(element),$.validator.attributeRules(element),$.validator.staticRules(element)),element);if(data.required){var param=data.required;delete data.required;data=$.extend({required:param},data);}
return data;}});$.extend($.expr[":"],{blank:function(a){return!$.trim(a.value);},filled:function(a){return!!$.trim(a.value);},unchecked:function(a){return!a.checked;}});$.format=function(source,params){if(arguments.length==1)
return function(){var args=$.makeArray(arguments);args.unshift(source);return $.format.apply(this,args);};if(arguments.length>2&&params.constructor!=Array){params=$.makeArray(arguments).slice(1);}
if(params.constructor!=Array){params=[params];}
$.each(params,function(i,n){source=source.replace(new RegExp("\\{"+i+"\\}","g"),n);});return source;};$.validator=function(options,form){this.settings=$.extend({},$.validator.defaults,options);this.currentForm=form;this.init();};$.extend($.validator,{defaults:{messages:{},groups:{},rules:{},errorClass:"error",errorElement:"label",focusInvalid:true,errorContainer:$([]),errorLabelContainer:$([]),onsubmit:true,ignore:[],ignoreTitle:false,onfocusin:function(element){this.lastActive=element;if(this.settings.focusCleanup&&!this.blockFocusCleanup){this.settings.unhighlight&&this.settings.unhighlight.call(this,element,this.settings.errorClass);this.errorsFor(element).hide();}},onfocusout:function(element){if(!this.checkable(element)&&(element.name in this.submitted||!this.optional(element))){this.element(element);}},onkeyup:function(element){if(element.name in this.submitted||element==this.lastElement){this.element(element);}},onclick:function(element){if(element.name in this.submitted)
this.element(element);},highlight:function(element,errorClass){$(element).addClass(errorClass);},unhighlight:function(element,errorClass){$(element).removeClass(errorClass);}},setDefaults:function(settings){$.extend($.validator.defaults,settings);},messages:{required:"This field is required.",remote:"Please fix this field.",email:"Please enter a valid email address.",url:"Please enter a valid URL.",date:"Please enter a valid date.",dateISO:"Please enter a valid date (ISO).",dateDE:"Bitte geben Sie ein gültiges Datum ein.",number:"Please enter a valid number.",numberDE:"Bitte geben Sie eine Nummer ein.",digits:"Please enter only digits",creditcard:"Please enter a valid credit card number.",equalTo:"Please enter the same value again.",accept:"Please enter a value with a valid extension.",maxlength:$.format("Please enter no more than {0} characters."),minlength:$.format("Please enter at least {0} characters."),rangelength:$.format("Please enter a value between {0} and {1} characters long."),range:$.format("Please enter a value between {0} and {1}."),max:$.format("Please enter a value less than or equal to {0}."),min:$.format("Please enter a value greater than or equal to {0}.")},autoCreateRanges:false,prototype:{init:function(){this.labelContainer=$(this.settings.errorLabelContainer);this.errorContext=this.labelContainer.length&&this.labelContainer||$(this.currentForm);this.containers=$(this.settings.errorContainer).add(this.settings.errorLabelContainer);this.submitted={};this.valueCache={};this.pendingRequest=0;this.pending={};this.invalid={};this.reset();var groups=(this.groups={});$.each(this.settings.groups,function(key,value){$.each(value.split(/\s/),function(index,name){groups[name]=key;});});var rules=this.settings.rules;$.each(rules,function(key,value){rules[key]=$.validator.normalizeRule(value);});function delegate(event){var validator=$.data(this[0].form,"validator");validator.settings["on"+event.type]&&validator.settings["on"+event.type].call(validator,this[0]);}
$(this.currentForm).delegate("focusin focusout keyup",":text, :password, :file, select, textarea",delegate).delegate("click",":radio, :checkbox",delegate);if(this.settings.invalidHandler)
$(this.currentForm).bind("invalid-form.validate",this.settings.invalidHandler);},form:function(){this.checkForm();$.extend(this.submitted,this.errorMap);this.invalid=$.extend({},this.errorMap);if(!this.valid())
$(this.currentForm).triggerHandler("invalid-form",[this]);this.showErrors();return this.valid();},checkForm:function(){this.prepareForm();for(var i=0,elements=(this.currentElements=this.elements());elements[i];i++){this.check(elements[i]);}
return this.valid();},element:function(element){element=this.clean(element);this.lastElement=element;this.prepareElement(element);this.currentElements=$(element);var result=this.check(element);if(result){delete this.invalid[element.name];}else{this.invalid[element.name]=true;}
if(!this.numberOfInvalids()){this.toHide=this.toHide.add(this.containers);}
this.showErrors();return result;},showErrors:function(errors){if(errors){$.extend(this.errorMap,errors);this.errorList=[];for(var name in errors){this.errorList.push({message:errors[name],element:this.findByName(name)[0]});}
this.successList=$.grep(this.successList,function(element){return!(element.name in errors);});}
this.settings.showErrors?this.settings.showErrors.call(this,this.errorMap,this.errorList):this.defaultShowErrors();},resetForm:function(){if($.fn.resetForm)
$(this.currentForm).resetForm();this.submitted={};this.prepareForm();this.hideErrors();this.elements().removeClass(this.settings.errorClass);},numberOfInvalids:function(){return this.objectLength(this.invalid);},objectLength:function(obj){var count=0;for(var i in obj)
count++;return count;},hideErrors:function(){this.addWrapper(this.toHide).hide();},valid:function(){return this.size()==0;},size:function(){return this.errorList.length;},focusInvalid:function(){if(this.settings.focusInvalid){try{$(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus();}catch(e){}}},findLastActive:function(){var lastActive=this.lastActive;return lastActive&&$.grep(this.errorList,function(n){return n.element.name==lastActive.name;}).length==1&&lastActive;},elements:function(){var validator=this,rulesCache={};return $([]).add(this.currentForm.elements).filter(":input").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function(){!this.name&&validator.settings.debug&&window.console&&console.error("%o has no name assigned",this);if(this.name in rulesCache||!validator.objectLength($(this).rules()))
return false;rulesCache[this.name]=true;return true;});},clean:function(selector){return $(selector)[0];},errors:function(){return $(this.settings.errorElement+"."+this.settings.errorClass,this.errorContext);},reset:function(){this.successList=[];this.errorList=[];this.errorMap={};this.toShow=$([]);this.toHide=$([]);this.formSubmitted=false;this.currentElements=$([]);},prepareForm:function(){this.reset();this.toHide=this.errors().add(this.containers);},prepareElement:function(element){this.reset();this.toHide=this.errorsFor(element);},check:function(element){element=this.clean(element);if(this.checkable(element)){element=this.findByName(element.name)[0];}
var rules=$(element).rules();var dependencyMismatch=false;for(method in rules){var rule={method:method,parameters:rules[method]};try{var result=$.validator.methods[method].call(this,element.value.replace(/\r/g,""),element,rule.parameters);if(result=="dependency-mismatch"){dependencyMismatch=true;continue;}
dependencyMismatch=false;if(result=="pending"){this.toHide=this.toHide.not(this.errorsFor(element));return;}
if(!result){this.formatAndAdd(element,rule);return false;}}catch(e){this.settings.debug&&window.console&&console.log("exception occured when checking element "+element.id
+", check the '"+rule.method+"' method");throw e;}}
if(dependencyMismatch)
return;if(this.objectLength(rules))
this.successList.push(element);return true;},customMetaMessage:function(element,method){if(!$.metadata)
return;var meta=this.settings.meta?$(element).metadata()[this.settings.meta]:$(element).metadata();return meta&&meta.messages&&meta.messages[method];},customMessage:function(name,method){var m=this.settings.messages[name];return m&&(m.constructor==String?m:m[method]);},findDefined:function(){for(var i=0;i<arguments.length;i++){if(arguments[i]!==undefined)
return arguments[i];}
return undefined;},defaultMessage:function(element,method){return this.findDefined(this.customMessage(element.name,method),this.customMetaMessage(element,method),!this.settings.ignoreTitle&&element.title||undefined,$.validator.messages[method],"<strong>Warning: No message defined for "+element.name+"</strong>");},formatAndAdd:function(element,rule){var message=this.defaultMessage(element,rule.method);if(typeof message=="function")
message=message.call(this,rule.parameters,element);this.errorList.push({message:message,element:element});this.errorMap[element.name]=message;this.submitted[element.name]=message;},addWrapper:function(toToggle){if(this.settings.wrapper)
toToggle=toToggle.add(toToggle.parents(this.settings.wrapper));return toToggle;},defaultShowErrors:function(){for(var i=0;this.errorList[i];i++){var error=this.errorList[i];this.settings.highlight&&this.settings.highlight.call(this,error.element,this.settings.errorClass);this.showLabel(error.element,error.message);}
if(this.errorList.length){this.toShow=this.toShow.add(this.containers);}
if(this.settings.success){for(var i=0;this.successList[i];i++){this.showLabel(this.successList[i]);}}
if(this.settings.unhighlight){for(var i=0,elements=this.validElements();elements[i];i++){this.settings.unhighlight.call(this,elements[i],this.settings.errorClass);}}
this.toHide=this.toHide.not(this.toShow);this.hideErrors();this.addWrapper(this.toShow).show();},validElements:function(){return this.currentElements.not(this.invalidElements());},invalidElements:function(){return $(this.errorList).map(function(){return this.element;});},showLabel:function(element,message){var label=this.errorsFor(element);if(label.length){label.removeClass().addClass(this.settings.errorClass);label.attr("generated")&&label.html(message);}else{label=$("<"+this.settings.errorElement+"/>").attr({"for":this.idOrName(element),generated:true}).addClass(this.settings.errorClass).html(message||"");if(this.settings.wrapper){label=label.hide().show().wrap("<"+this.settings.wrapper+"/>").parent();}
if(!this.labelContainer.append(label).length)
this.settings.errorPlacement?this.settings.errorPlacement(label,$(element)):label.insertAfter(element);}
if(!message&&this.settings.success){label.text("");typeof this.settings.success=="string"?label.addClass(this.settings.success):this.settings.success(label);}
this.toShow=this.toShow.add(label);},errorsFor:function(element){return this.errors().filter("[for='"+this.idOrName(element)+"']");},idOrName:function(element){return this.groups[element.name]||(this.checkable(element)?element.name:element.id||element.name);},checkable:function(element){return /radio|checkbox/i.test(element.type);},findByName:function(name){var form=this.currentForm;return $(document.getElementsByName(name)).map(function(index,element){return element.form==form&&element.name==name&&element||null;});},getLength:function(value,element){switch(element.nodeName.toLowerCase()){case'select':return $("option:selected",element).length;case'input':if(this.checkable(element))
return this.findByName(element.name).filter(':checked').length;}
return value.length;},depend:function(param,element){return this.dependTypes[typeof param]?this.dependTypes[typeof param](param,element):true;},dependTypes:{"boolean":function(param,element){return param;},"string":function(param,element){return!!$(param,element.form).length;},"function":function(param,element){return param(element);}},optional:function(element){return!$.validator.methods.required.call(this,$.trim(element.value),element)&&"dependency-mismatch";},startRequest:function(element){if(!this.pending[element.name]){this.pendingRequest++;this.pending[element.name]=true;}},stopRequest:function(element,valid){this.pendingRequest--;if(this.pendingRequest<0)
this.pendingRequest=0;delete this.pending[element.name];if(valid&&this.pendingRequest==0&&this.formSubmitted&&this.form()){$(this.currentForm).submit();}else if(!valid&&this.pendingRequest==0&&this.formSubmitted){$(this.currentForm).triggerHandler("invalid-form",[this]);}},previousValue:function(element){return $.data(element,"previousValue")||$.data(element,"previousValue",previous={old:null,valid:true,message:this.defaultMessage(element,"remote")});}},classRuleSettings:{required:{required:true},email:{email:true},url:{url:true},date:{date:true},dateISO:{dateISO:true},dateDE:{dateDE:true},number:{number:true},numberDE:{numberDE:true},digits:{digits:true},creditcard:{creditcard:true}},addClassRules:function(className,rules){className.constructor==String?this.classRuleSettings[className]=rules:$.extend(this.classRuleSettings,className);},classRules:function(element){var rules={};var classes=$(element).attr('class');classes&&$.each(classes.split(' '),function(){if(this in $.validator.classRuleSettings){$.extend(rules,$.validator.classRuleSettings[this]);}});return rules;},attributeRules:function(element){var rules={};var $element=$(element);for(method in $.validator.methods){var value=$element.attr(method);if(value){rules[method]=value;}}
if(rules.maxlength&&/-1|2147483647|524288/.test(rules.maxlength)){delete rules.maxlength;}
return rules;},metadataRules:function(element){if(!$.metadata)return{};var meta=$.data(element.form,'validator').settings.meta;return meta?$(element).metadata()[meta]:$(element).metadata();},staticRules:function(element){var rules={};var validator=$.data(element.form,'validator');if(validator.settings.rules){rules=$.validator.normalizeRule(validator.settings.rules[element.name])||{};}
return rules;},normalizeRules:function(rules,element){$.each(rules,function(prop,val){if(val===false){delete rules[prop];return;}
if(val.param||val.depends){var keepRule=true;switch(typeof val.depends){case"string":keepRule=!!$(val.depends,element.form).length;break;case"function":keepRule=val.depends.call(element,element);break;}
if(keepRule){rules[prop]=val.param!==undefined?val.param:true;}else{delete rules[prop];}}});$.each(rules,function(rule,parameter){rules[rule]=$.isFunction(parameter)?parameter(element):parameter;});$.each(['minlength','maxlength','min','max'],function(){if(rules[this]){rules[this]=Number(rules[this]);}});$.each(['rangelength','range'],function(){if(rules[this]){rules[this]=[Number(rules[this][0]),Number(rules[this][1])];}});if($.validator.autoCreateRanges){if(rules.min&&rules.max){rules.range=[rules.min,rules.max];delete rules.min;delete rules.max;}
if(rules.minlength&&rules.maxlength){rules.rangelength=[rules.minlength,rules.maxlength];delete rules.minlength;delete rules.maxlength;}}
if(rules.messages){delete rules.messages}
return rules;},normalizeRule:function(data){if(typeof data=="string"){var transformed={};$.each(data.split(/\s/),function(){transformed[this]=true;});data=transformed;}
return data;},addMethod:function(name,method,message){$.validator.methods[name]=method;$.validator.messages[name]=message;if(method.length<3){$.validator.addClassRules(name,$.validator.normalizeRule(name));}},methods:{required:function(value,element,param){if(!this.depend(param,element))
return"dependency-mismatch";switch(element.nodeName.toLowerCase()){case'select':var options=$("option:selected",element);return options.length>0&&(element.type=="select-multiple"||($.browser.msie&&!(options[0].attributes['value'].specified)?options[0].text:options[0].value).length>0);case'input':if(this.checkable(element))
return this.getLength(value,element)>0;default:return $.trim(value).length>0;}},remote:function(value,element,param){if(this.optional(element))
return"dependency-mismatch";var previous=this.previousValue(element);if(!this.settings.messages[element.name])
this.settings.messages[element.name]={};this.settings.messages[element.name].remote=typeof previous.message=="function"?previous.message(value):previous.message;param=typeof param=="string"&&{url:param}||param;if(previous.old!==value){previous.old=value;var validator=this;this.startRequest(element);var data={};data[element.name]=value;$.ajax($.extend(true,{url:param,mode:"abort",port:"validate"+element.name,dataType:"json",data:data,success:function(response){if(response){var submitted=validator.formSubmitted;validator.prepareElement(element);validator.formSubmitted=submitted;validator.successList.push(element);validator.showErrors();}else{var errors={};errors[element.name]=response||validator.defaultMessage(element,"remote");validator.showErrors(errors);}
previous.valid=response;validator.stopRequest(element,response);}},param));return"pending";}else if(this.pending[element.name]){return"pending";}
return previous.valid;},minlength:function(value,element,param){return this.optional(element)||this.getLength($.trim(value),element)>=param;},maxlength:function(value,element,param){return this.optional(element)||this.getLength($.trim(value),element)<=param;},rangelength:function(value,element,param){var length=this.getLength($.trim(value),element);return this.optional(element)||(length>=param[0]&&length<=param[1]);},min:function(value,element,param){return this.optional(element)||value>=param;},max:function(value,element,param){return this.optional(element)||value<=param;},range:function(value,element,param){return this.optional(element)||(value>=param[0]&&value<=param[1]);},email:function(value,element){return this.optional(element)||/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);},url:function(value,element){return this.optional(element)||/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);},date:function(value,element){return this.optional(element)||!/Invalid|NaN/.test(new Date(value));},dateISO:function(value,element){return this.optional(element)||/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(value);},dateDE:function(value,element){return this.optional(element)||/^\d\d?\.\d\d?\.\d\d\d?\d?$/.test(value);},number:function(value,element){return this.optional(element)||/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);},numberDE:function(value,element){return this.optional(element)||/^-?(?:\d+|\d{1,3}(?:\.\d{3})+)(?:,\d+)?$/.test(value);},digits:function(value,element){return this.optional(element)||/^\d+$/.test(value);},creditcard:function(value,element){if(this.optional(element))
return"dependency-mismatch";if(/[^0-9-]+/.test(value))
return false;var nCheck=0,nDigit=0,bEven=false;value=value.replace(/\D/g,"");for(n=value.length-1;n>=0;n--){var cDigit=value.charAt(n);var nDigit=parseInt(cDigit,10);if(bEven){if((nDigit*=2)>9)
nDigit-=9;}
nCheck+=nDigit;bEven=!bEven;}
return(nCheck%10)==0;},accept:function(value,element,param){param=typeof param=="string"?param:"png|jpe?g|gif";return this.optional(element)||value.match(new RegExp(".("+param+")$","i"));},equalTo:function(value,element,param){return value==$(param).val();}}});})(jQuery);;(function($){var ajax=$.ajax;var pendingRequests={};$.ajax=function(settings){settings=$.extend(settings,$.extend({},$.ajaxSettings,settings));var port=settings.port;if(settings.mode=="abort"){if(pendingRequests[port]){pendingRequests[port].abort();}
return(pendingRequests[port]=ajax.apply(this,arguments));}
return ajax.apply(this,arguments);};})(jQuery);;(function($){$.each({focus:'focusin',blur:'focusout'},function(original,fix){$.event.special[fix]={setup:function(){if($.browser.msie)return false;this.addEventListener(original,$.event.special[fix].handler,true);},teardown:function(){if($.browser.msie)return false;this.removeEventListener(original,$.event.special[fix].handler,true);},handler:function(e){arguments[0]=$.event.fix(e);arguments[0].type=fix;return $.event.handle.apply(this,arguments);}};});$.extend($.fn,{delegate:function(type,delegate,handler){return this.bind(type,function(event){var target=$(event.target);if(target.is(delegate)){return handler.apply(target,arguments);}});},triggerEvent:function(type,target){return this.triggerHandler(type,[$.event.fix({type:type,target:target})]);}})})(jQuery);;function LoadXML(ParentElementID,URL)
{var xmlHolderElement=GetParentElement(ParentElementID);if(xmlHolderElement==null){return false;}
return RequestURL(URL,URLReceiveCallback,ParentElementID);}
function LoadXMLDom(ParentElementID,xmlDoc)
{if(xmlDoc){var xmlHolderElement=GetParentElement(ParentElementID);if(xmlHolderElement==null){return false;}
while(xmlHolderElement.childNodes.length){xmlHolderElement.removeChild(xmlHolderElement.childNodes.item(xmlHolderElement.childNodes.length-1));}
var Result=ShowXML(xmlHolderElement,xmlDoc.documentElement,0);return Result;}
else{return false;}}
function LoadXMLString(ParentElementID,XMLString)
{xmlDoc=CreateXMLDOM(XMLString);return LoadXMLDom(ParentElementID,xmlDoc);}
function GetParentElement(ParentElementID)
{if(typeof(ParentElementID)=='string'){return document.getElementById(ParentElementID);}
else if(typeof(ParentElementID)=='object'){return ParentElementID;}
else{return null;}}
function URLReceiveCallback(httpRequest,xmlHolderElement)
{try{if(httpRequest.readyState==4){if(httpRequest.status==200){var xmlDoc=httpRequest.responseXML;if(xmlHolderElement&&xmlHolderElement!=null){xmlHolderElement.innerHTML='';return LoadXMLDom(xmlHolderElement,xmlDoc);}}else{return false;}}}
catch(e){return false;}}
function RequestURL(url,callback,ExtraData){var httpRequest;if(window.XMLHttpRequest){httpRequest=new XMLHttpRequest();if(httpRequest.overrideMimeType){httpRequest.overrideMimeType('text/xml');}}
else if(window.ActiveXObject){try{httpRequest=new ActiveXObject("Msxml2.XMLHTTP");}
catch(e){try{httpRequest=new ActiveXObject("Microsoft.XMLHTTP");}
catch(e){}}}
if(!httpRequest){return false;}
httpRequest.onreadystatechange=function(){callback(httpRequest,ExtraData);};httpRequest.open('GET',url,true);httpRequest.send('');return true;}
function CreateXMLDOM(XMLStr)
{if(window.ActiveXObject)
{xmlDoc=new ActiveXObject("Microsoft.XMLDOM");xmlDoc.loadXML(XMLStr);return xmlDoc;}
else if(document.implementation&&document.implementation.createDocument){var parser=new DOMParser();return parser.parseFromString(XMLStr,"text/xml");}
else{return null;}}
var IDCounter=1;var NestingIndent=15;function ShowXML(xmlHolderElement,RootNode,indent)
{if(RootNode==null||xmlHolderElement==null){return false;}
var Result=true;var TagEmptyElement=document.createElement('div');TagEmptyElement.className='Element';TagEmptyElement.style.position='relative';TagEmptyElement.style.left=NestingIndent+'px';var ClickableElement=AddTextNode(TagEmptyElement,'+','Clickable');ClickableElement.onclick=function(){ToggleElementVisibility(this);}
ClickableElement.id='div_empty_'+IDCounter;AddTextNode(TagEmptyElement,'<','Utility');AddTextNode(TagEmptyElement,RootNode.nodeName,'NodeName')
for(var i=0;RootNode.attributes&&i<RootNode.attributes.length;++i){CurrentAttribute=RootNode.attributes.item(i);AddTextNode(TagEmptyElement,' '+CurrentAttribute.nodeName,'AttributeName');AddTextNode(TagEmptyElement,'=','Utility');AddTextNode(TagEmptyElement,'"'+CurrentAttribute.nodeValue+'"','AttributeValue');}
AddTextNode(TagEmptyElement,'>  </','Utility');AddTextNode(TagEmptyElement,RootNode.nodeName,'NodeName');AddTextNode(TagEmptyElement,'>','Utility');xmlHolderElement.appendChild(TagEmptyElement);SetVisibility(TagEmptyElement,false);var TagElement=document.createElement('div');TagElement.className='Element';TagElement.style.position='relative';TagElement.style.left=NestingIndent+'px';ClickableElement=AddTextNode(TagElement,'-','Clickable');ClickableElement.onclick=function(){ToggleElementVisibility(this);}
ClickableElement.id='div_content_'+IDCounter;++IDCounter;AddTextNode(TagElement,'<','Utility');AddTextNode(TagElement,RootNode.nodeName,'NodeName');for(var i=0;RootNode.attributes&&i<RootNode.attributes.length;++i){CurrentAttribute=RootNode.attributes.item(i);AddTextNode(TagElement,' '+CurrentAttribute.nodeName,'AttributeName');AddTextNode(TagElement,'=','Utility');AddTextNode(TagElement,'"'+CurrentAttribute.nodeValue+'"','AttributeValue');}
AddTextNode(TagElement,'>','Utility');TagElement.appendChild(document.createElement('br'));var NodeContent=null;for(var i=0;RootNode.childNodes&&i<RootNode.childNodes.length;++i){if(RootNode.childNodes.item(i).nodeName!='#text'){Result&=ShowXML(TagElement,RootNode.childNodes.item(i),indent+1);}
else{NodeContent=RootNode.childNodes.item(i).nodeValue;}}
if(RootNode.nodeValue){NodeContent=RootNode.nodeValue;}
if(NodeContent){var ContentElement=document.createElement('div');ContentElement.style.position='relative';ContentElement.style.left=NestingIndent+'px';AddTextNode(ContentElement,NodeContent,'NodeValue');TagElement.appendChild(ContentElement);}
AddTextNode(TagElement,'  </','Utility');AddTextNode(TagElement,RootNode.nodeName,'NodeName');AddTextNode(TagElement,'>','Utility');xmlHolderElement.appendChild(TagElement);return Result;}
function AddTextNode(ParentNode,Text,Class)
{NewNode=document.createElement('span');if(Class){NewNode.className=Class;}
if(Text){NewNode.appendChild(document.createTextNode(Text));}
if(ParentNode){ParentNode.appendChild(NewNode);}
return NewNode;}
function CompatibleGetElementByID(id)
{if(!id){return null;}
if(document.getElementById){return document.getElementById(id);}
else{if(document.layers){return document.id;}
else{return document.all.id;}}}
function SetVisibility(HTMLElement,Visible)
{if(!HTMLElement){return;}
var VisibilityStr=(Visible)?'block':'none';if(document.getElementById){HTMLElement.style.display=VisibilityStr;}
else{if(document.layers){HTMLElement.display=VisibilityStr;}
else{HTMLElement.id.style.display=VisibilityStr;}}}
function ToggleElementVisibility(Element)
{if(!Element||!Element.id){return;}
try{ElementType=Element.id.slice(0,Element.id.lastIndexOf('_')+1);ElementID=parseInt(Element.id.slice(Element.id.lastIndexOf('_')+1));}
catch(e){return;}
var ElementToHide=null;var ElementToShow=null;if(ElementType=='div_content_'){ElementToHide='div_content_'+ElementID;ElementToShow='div_empty_'+ElementID;}
else if(ElementType=='div_empty_'){ElementToShow='div_content_'+ElementID;ElementToHide='div_empty_'+ElementID;}
ElementToHide=CompatibleGetElementByID(ElementToHide);ElementToShow=CompatibleGetElementByID(ElementToShow);if(ElementToHide){ElementToHide=ElementToHide.parentNode;}
if(ElementToShow){ElementToShow=ElementToShow.parentNode;}
SetVisibility(ElementToHide,false);SetVisibility(ElementToShow,true);};if(!this.JSON){JSON=function(){function f(n){return n<10?'0'+n:n;}
Date.prototype.toJSON=function(){return this.getUTCFullYear()+'-'+
f(this.getUTCMonth()+1)+'-'+
f(this.getUTCDate())+'T'+
f(this.getUTCHours())+':'+
f(this.getUTCMinutes())+':'+
f(this.getUTCSeconds())+'Z';};var meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'},escapeable=/["\\\x00-\x1f\x7f-\x9f]/g;function quote(string){return escapeable.test(string)?'"'+string.replace(escapeable,function(a){var c=meta[a];if(typeof c==='string'){return c;}
c=a.charCodeAt();return'\\u00'+Math.floor(c/16).toString(16)+
(c%16).toString(16);})+'"':'"'+string+'"';}
function stringify(key,holder,replacer){var i,k,v,length,partial,value=holder[key];if(value&&typeof value==='object'&&typeof value.toJSON==='function'){value=value.toJSON(key);}
if(typeof replacer==='function'){value=replacer.call(holder,key,value);}
switch(typeof value){case'string':return quote(value);case'function':return value;case'number':return isFinite(value)?String(value):'null';case'boolean':case'null':return String(value);case'object':if(!value){return'null';}
partial=[];if(typeof value.length==='number'&&!(value.propertyIsEnumerable('length'))){length=value.length;for(i=0;i<length;i+=1){partial[i]=stringify(i,value,replacer)||'null';}
return'['+partial.join(',')+']';}
if(typeof replacer==='object'){length=replacer.length;for(i=0;i<length;i+=1){k=replacer[i];if(typeof k==='string'){v=stringify(k,value,replacer);if(v){partial.push(quote(k)+':'+v);}}}}else{for(k in value){v=stringify(k,value,replacer);if(v){partial.push(quote(k)+':'+v);}}}
return'{'+partial.join(',')+'}';}}
return{stringify:function(value,replacer){if(typeof replacer!=='function'){if(!replacer){replacer=function(key,value){if(!Object.hasOwnProperty.call(this,key)){return undefined;}
return value;};}else if(typeof replacer!=='object'||typeof replacer.length!=='number'){throw new Error('JSON.stringify');}}
return stringify('',{'':value},replacer);},parse:function(text,reviver){var j;function walk(holder,key){var k,v,value=holder[key];if(value&&typeof value==='object'){for(k in value){if(Object.hasOwnProperty.call(value,k)){v=walk(value,k);if(v!==undefined){value[k]=v;}else{delete value[k];}}}}
return reviver.call(holder,key,value);}
if(/^[\],:{}\s]*$/.test(text.replace(/\\["\\\/bfnrtu]/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))){j=eval('('+text+')');return typeof reviver==='function'?walk({'':j},''):j;}
throw new SyntaxError('JSON.parse');},info:{"version":"","www":"http://www.json.org/","date":"2008-03-22","description":"Open source code of a JSON parser and JSON stringifier. [Douglas Crockford]"},quote:quote};}();};function get_file_name(url){return url.replace(/^.*\//,'');}
function build_url(url,params){for(var key in params){url+="&"+key+"="+params[key];}
return url;}
function getParameterByName(url,name)
{name=name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");var regexS="[\\?&]"+name+"=([^&#]*)";var regex=new RegExp(regexS);var results=regex.exec(url);if(results==null)
return"";else
return decodeURIComponent(results[1].replace(/\+/g," "));}
function hasText(el){return el&&isString(el.text())&&el.text().length>0;}
function get(element){if(isString(element))element=$('#'+element);return element;}
function isString(x){return typeof(x)=='string';}
function mask(ct){if(isString(ct))ct=$('#'+ct);ct.addClass('loading_mask');var m=ct.data('mask');if(!m){m=$('<div></div>');ct.data('mask',m);}
if(ct.index(m)<0)m.prependTo(ct);m.css({position:'absolute',top:ct.css('top'),left:ct.css('left'),width:ct.css('width'),height:ct.css('height')}).addClass('ajax_buzy');m.show();}
function unmask(ct){if(isString(ct))ct=$('#'+ct);var mask=ct.data('mask');if(mask){mask.hide();}
ct.removeClass('loading_mask');}
function mod_url(url,mod){var index=url.lastIndexOf('.');if(index<=0){return url;}
url=url.substring(0,index)+mod+url.substring(index);return url;}
function sect_open(url,target,options){if(options&&options.url_mod){url=mod_url(url,options.url_mod);}
$.ajax({url:url,beforeSend:function(request){if(options&&options.mask){mask(get(target));}
return true;},success:function(data){get(target).html(data);},error:function(request,textStatus,errorThrown){alert(errorThrown);},complete:function(){if(options&&options.mask)unmask(get(target));}});};$.extend({URLEncode:function(c){var o='';var x=0;c=c.toString();var r=/(^[a-zA-Z0-9_.]*)/;while(x<c.length){var m=r.exec(c.substr(x));if(m!=null&&m.length>1&&m[1]!=''){o+=m[1];x+=m[1].length;}else{if(c[x]==' ')o+='+';else{var d=c.charCodeAt(x);var h=d.toString(16);o+='%'+(h.length<2?'0':'')+h.toUpperCase();}x++;}}return o;},URLDecode:function(s){var o=s;var binVal,t;var r=/(%[^%]{2})/;while((m=r.exec(o))!=null&&m.length>1&&m[1]!=''){b=parseInt(m[1].substr(1),16);t=String.fromCharCode(b);o=o.replace(m[1],t);}return o;}});;if(!this.JSON){JSON=function(){function f(n){return n<10?'0'+n:n;}
Date.prototype.toJSON=function(key){return this.getUTCFullYear()+'-'+
f(this.getUTCMonth()+1)+'-'+
f(this.getUTCDate())+'T'+
f(this.getUTCHours())+':'+
f(this.getUTCMinutes())+':'+
f(this.getUTCSeconds())+'Z';};String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(key){return this.valueOf();};var cx=new RegExp('/[\\u0000\\u00ad\\u0600-\\u0604\\u070f\\u17b4\\u17b5\\u200c-\\u200f\\u2028-\\u202f\\u2060-\\u206f\\ufeff\\ufff0-\\uffff]/g'),escapeable=new RegExp('/[\\\\\\"\\x00-\\x1f\\x7f-\\x9f\\u00ad\\u0600-\\u0604\\u070f\\u17b4\\u17b5\\u200c-\\u200f\\u2028-\\u202f\\u2060-\\u206f\\ufeff\\ufff0-\\uffff]/g'),gap,indent,meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'},rep;function quote(string){escapeable.lastIndex=0;return escapeable.test(string)?'"'+string.replace(escapeable,function(a){var c=meta[a];if(typeof c==='string'){return c;}
return'\\u'+('0000'+
(+(a.charCodeAt(0))).toString(16)).slice(-4);})+'"':'"'+string+'"';}
function str(key,holder){var i,k,v,length,mind=gap,partial,value=holder[key];if(value&&typeof value==='object'&&typeof value.toJSON==='function'){value=value.toJSON(key);}
if(typeof rep==='function'){value=rep.call(holder,key,value);}
switch(typeof value){case'string':return quote(value);case'number':return isFinite(value)?String(value):'null';case'boolean':case'null':return String(value);case'object':if(!value){return'null';}
gap+=indent;partial=[];if(typeof value.length==='number'&&!(value.propertyIsEnumerable('length'))){length=value.length;for(i=0;i<length;i+=1){partial[i]=str(i,value)||'null';}
v=partial.length===0?'[]':gap?'[\n'+gap+
partial.join(',\n'+gap)+'\n'+
mind+']':'['+partial.join(',')+']';gap=mind;return v;}
if(rep&&typeof rep==='object'){length=rep.length;for(i=0;i<length;i+=1){k=rep[i];if(typeof k==='string'){v=str(k,value);if(v){partial.push(quote(k)+(gap?': ':':')+v);}}}}else{for(k in value){if(Object.hasOwnProperty.call(value,k)){v=str(k,value);if(v){partial.push(quote(k)+(gap?': ':':')+v);}}}}
v=partial.length===0?'{}':gap?'{\n'+gap+partial.join(',\n'+gap)+'\n'+
mind+'}':'{'+partial.join(',')+'}';gap=mind;return v;}}
return{stringify:function(value,replacer,space){var i;gap='';indent='';if(typeof space==='number'){for(i=0;i<space;i+=1){indent+=' ';}}else if(typeof space==='string'){indent=space;}
rep=replacer;if(replacer&&typeof replacer!=='function'&&(typeof replacer!=='object'||typeof replacer.length!=='number')){throw new Error('JSON.stringify');}
return str('',{'':value});},parse:function(text,reviver){var j;function walk(holder,key){var k,v,value=holder[key];if(value&&typeof value==='object'){for(k in value){if(Object.hasOwnProperty.call(value,k)){v=walk(value,k);if(v!==undefined){value[k]=v;}else{delete value[k];}}}}
return reviver.call(holder,key,value);}
cx.lastIndex=0;if(cx.test(text)){text=text.replace(cx,function(a){return'\\u'+('0000'+
(+(a.charCodeAt(0))).toString(16)).slice(-4);});}
if(/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))){j=eval('('+text+')');return typeof reviver==='function'?walk({'':j},''):j;}
throw new SyntaxError('JSON.parse');}};}();};var supports_onhashchange=false;var jssm={created:false,rid:0,zerolength:false,pointer:false,interval:100,stackelem:false,lengthelem:false,pointerelem:false,iframe:false,stack:[],settings:{formid:'jssmform',stackid:'jssmstack',lengthid:'jssmlength',pointerid:'jssmpointer',iframeid:'jssmiframe',blankurl:'/blank.html',basetitle:'',titleseparator:' : '},functions:{pageload:false,beforeload:false,load:false,afterload:false,beforeunload:false,unload:false,afterunload:false},inline:function(params){if(this.created){return;}
this.created=true;this.settings=jQuery.fn.extend(this.settings,params);document.write('<div style="display: none;">');document.write('<form id="'+this.settings.formid+'" action="" method="post"><div><textarea name="'+this.settings.stackid+'" id="'+this.settings.stackid+'"></textarea><input type="text" id="'+this.settings.lengthid+'" name="'+this.settings.lengthid+'" /><input type="text" id="'+this.settings.pointerid+'" name="'+this.settings.pointerid+'" /></div></form>');if(jQuery.browser.msie&&jQuery.browser.version<8){document.write('<iframe id="'+this.settings.iframeid+'" src="'+this.settings.blankurl+'?'+this.getHash()+'"></iframe>');}
document.write('</div>');if(jQuery.browser.opera){document.write('<img style="position: absolute; left: -999em; top: -999em;" width="1" height="1"  src="javascript:location.href=\'javascript:jssm.fixOpera();\';" />');}},init:function(type){var str_hashchange='hashchange';var doc_mode=document.documentMode;supports_onhashchange='on'+str_hashchange in window&&(doc_mode===undefined||doc_mode>7);if(jQuery.browser.msie&&type=='ready'){return;}
if(!jQuery.browser.msie&&type=='load'){return;}
jQuery(window).bind('hashchange',jssm.hashchange);this.stackelem=document.getElementById(this.settings.stackid);this.lengthelem=document.getElementById(this.settings.lengthid);this.pointerelem=document.getElementById(this.settings.pointerid);this.iframe=document.getElementById(this.settings.iframeid);if(jQuery.browser.opera){this.stackelem.focus();this.lengthelem.focus();this.pointerelem.focus();window.focus();}
if(this.lengthelem.value){this.zerolength=this.lengthelem.value;this.load();}else{this.zerolength=history.length;this.pointer=this.zerolength;this.stack[this.pointer]=this.getHash();this.save();}
this.rid=this.getRID(this.stack[this.pointer]);this.rid++;if(this.functions.pageload){this.functions.pageload(this.stack[this.pointer]);}
if(!jQuery.browser.msie||(jQuery.browser.msie&&jQuery.browser.version<8)){this.poll();}},poll:function(){if(jssm.getHash()!=jssm.stack[jssm.pointer]){if(supports_onhashchange==false)jQuery(window).trigger('hashchange');}
setTimeout(jssm.poll,jssm.interval);},fixOpera:function(){},iframeEvent:function(changedhash){if(changedhash||window.location.hash){window.location.hash=changedhash;}},hashchange:function(){var changedhash=jssm.getHash();var calchash=changedhash;if(changedhash===''){calchash=jssm.getCurrentPage();}
if(jQuery.browser.msie&&jQuery.browser.version<8&&jssm.iframe.contentWindow.document.body.innerText!=changedhash){jssm.setHash(changedhash);}
if(jssm.functions.beforeunload){jssm.functions.beforeunload(calchash);}
if(jssm.functions.unload){jssm.functions.unload(calchash);}
if(jssm.functions.afterunload){jssm.functions.afterunload(calchash);}
if(jssm.functions.beforeload){jssm.functions.beforeload(calchash);}
if(jssm.functions.load){jssm.functions.load(calchash);}
if(jssm.functions.afterload){jssm.functions.afterload(calchash);}
var exists=[];for(var i=jssm.zerolength;i<jssm.stack.length;i++){if(changedhash===jssm.stack[i]){exists.push(i);}}
switch(exists.length){case 0:jssm.pointer++;jssm.stack[jssm.pointer]=changedhash;jssm.stack.length=jssm.pointer+1;break;case 1:jssm.pointer=exists[0];break;}
jssm.save();},load:function(){this.stack=JSON.parse(this.stackelem.value);this.length=this.lengthelem.value;this.pointer=this.pointerelem.value;},save:function(){this.stackelem.value=JSON.stringify(this.stack);this.lengthelem.value=this.zerolength;this.pointerelem.value=this.pointer;},getHash:function(){if(jQuery.browser.safari&&parseInt(jQuery.browser.version,10)<522&&!/adobeair/i.test(jQuery.browser.userAgent)){this.getHash=function(){return jssm.stack[history.length-jssm.zerolength-1];};}else{this.getHash=function(){var r=window.location.href;var i=r.indexOf("#");return(i>=0?r.substr(i+1):'');};}
return this.getHash();},setHash:function(hash){if(jQuery.browser.msie&&jQuery.browser.version<8){this.setHash=function(hash){var iframe=jssm.iframe.contentWindow.document;iframe.open("javascript:'<html></html>'");iframe.write('<html><head><scri'+'pt type="text/javascript">window.parent.jssm.iframeEvent("'+hash+'");</scri'+'pt></head><body>'+hash+'</body></html>');iframe.close();};}else{this.setHash=function(hash){window.location.hash=hash;};}
return this.setHash(hash);},getRID:function(hash){if(!hash){return 0;}
var str=hash.match(/rid=[\d]+/);return str?str[0].substr(4):0;},getHref:function(){var i=window.location.href.indexOf("#");return(i>=0?window.location.href.substr(0,i):window.location.href);},getPathTokens:function(href){var hashpos=href.indexOf('#');var querypos=href.indexOf('?');var querystring='';var hashstring='';if(hashpos!=-1){if(querypos!=-1&&querypos<hashpos){querystring=href.substring(querypos,hashpos);hashstring=href.substring(hashpos);}else{hashstring=href.substring(hashpos);}}else{if(querypos>=0){querystring=href.substring(querypos);}else{}}
if(hashpos!=-1&&querypos!=-1){href=href.substring(0,Math.min(hashpos,querypos));}else if(hashpos!=-1){href=href.substring(0,hashpos);}else if(querypos!=-1){href=href.substring(0,querypos);}
var regex=/^(https?:\/\/){0,1}([A-Za-z0-9\-\.]+){0,1}(\:\d+){0,1}(\/){0,1}((?:[^\/]*\/)*){0,1}(.*)$/;href=regex.exec(href);if(href[5]){href[5]=href[5].split('/');href[5].pop();}else{href[5]=[];}
href.push(querystring);href.push(hashstring);return href;},getCurrentPath:function(){var tokens=this.getPathTokens(this.getHref());return(tokens&&tokens[5]?tokens[5].join('/')+'/':'');},getCurrentPage:function(){var tokens=this.getPathTokens(this.getHref());return(tokens&&tokens[6]?tokens[6]:'');},getRelativePath:function(from,to,strict){if(from==to&&to.indexOf('?')==-1){return this.getCurrentPage(to);}
from=this.getPathTokens(from);to=this.getPathTokens(to);strict=strict||false;if((strict||to[1])&&(from[1]!==to[1])){return false;}
if((strict||to[2])&&(from[2]!==to[2])){return false;}
if(from[3]!==to[3]){if(strict){return false;}
var port=false;switch(from[1]){case'http':port=80;break;case'https':port=443;break;}
if(!port){return false;}
if(!(from[3]===''&&to[3]==port)&&!(from[3]==port&&to[3]==='')){return false;}}
var buildstring='';if(!from[5].length||!to[4]){buildstring+=to[5].join('/')+'/'+(to[6]?to[6]:'');}else{var i=0;if(from[5].length&&to[5].length){for(i=0;i<from[5].length;i++){if(from[5][i]==to[5][i]){from[5].shift();to[5].shift();i--;}else{break;}}
for(i=0;i<from[5].length;i++){buildstring+='../';}
buildstring+=to[5].join('/')+(to[5].length?'/':'')+(to[6]?to[6]:'');}else{for(i=0;i<from[5].length;i++){buildstring+='../';}
buildstring+=(to[6]?to[6]:'');}}
if(to[7]){buildstring+=to[7];}
if(to[8]){buildstring+=to[8];}
return buildstring;},getRootRelativePath:function(from,to,strict){if(arguments.length==1){var tokens=jssm.getPathTokens(from);return'/'+tokens[5].join('/')+'/'+(tokens[6]?tokens[6]:'');}
from=this.getPathTokens(from);to=this.getPathTokens(to);strict=strict||false;if((strict||to[1])&&(from[1]!==to[1])){return false;}
if((strict||to[2])&&(from[2]!==to[2])){return false;}
if(from[3]!==to[3]){if(strict){return false;}
var port=false;switch(from[1]){case'http':port=80;break;case'https':port=443;break;}
if(!port){return false;}
if(!(from[3]===''&&to[3]==port)&&!(from[3]==port&&to[3]==='')){return false;}}
var buildstring='/';if(!to[4]){buildstring+=from[5].join('/')+'/';}
if(to[5].length){buildstring+=to[5].join('/')+'/';}
if(to[6]){buildstring+=to[6];}
if(to[7]){buildstring+=to[7];}
if(to[8]){buildstring+=to[8];}
return buildstring;},setTitle:function(title){document.title=title;},buildTitle:function(title,separator){separator=separator||this.settings.titleseparator;return this.settings.basetitle+(title?separator+title:'');}};jQuery.fn.jssm=function(eventtype,params){return this.each(function(i){jQuery(this).bind(eventtype,function(event){var params=jQuery.data(this,'jssm');var href='';var data='';if(eventtype=='submit'&&this.action){href=jQuery('<a href="'+this.action+'"></a>').get(0).href;data='&'+jQuery(this).serialize();}else if(this.href){href=this.href;}
var target=jssm.getRelativePath(jssm.getHref(),href);if(target!==false){target=target+(target.indexOf('?')>=0?'&':'?')+'rid='+(jssm.rid++)+data;jssm.setHash(target);}
event.preventDefault();return false;});});};jQuery(document).ready(function(){jssm.init('ready');});jQuery(window).load(function(){jssm.init('load');});;function beforeWhoisSubmit(xmlHttpReq){var target=$('#right');mask(target);$('#whoisform :submit').attr('disabled',true);}
function apply_history(){$("a:not(.ignore_jssm)",$(document)).jssm('click');$("form:not(.ignore_jssm)",$(document)).jssm('submit');}
function whois_complete(){$('#whoisform :submit').attr('disabled',false);}
function showWhoisResponse(response,ct,options){options=options||{};var mainTab=options.main_tab||"Whois Record";var secTab=options.sec_tab||"Raw Text";if(isString(ct))ct=$('#'+ct);unmask(ct);if(!$('#whois_res').length){ct.empty();var whois_res="<div id=\"whois_res\">\
   <ul>\
      <li><a href=\"#whois-struct-tab\">"+mainTab+"</a></li>\
      <li><a href=\"#whois-raw-tab\">"+secTab+"</a></li>\
   </ul>\
   <div id=\"whois-struct-tab\">\
      <p></p>\
   </div>\
   <div id=\"whois-raw-tab\">\
      <p></p>\
   </div>\
</div>";$(whois_res).appendTo(ct);$('#whois_res').tabs();}
var whois_struct_tab=$('#whois-struct-tab');var whois_raw_tab=$('#whois-raw-tab');if(jQuery.isXMLDoc(response)){LoadXMLDom('whois-struct-tab',response);var rawText='';if(mainTab=='Domain Info'){var domainName=$(response).find("DomainInfo > domainName").text();var av=$(response).find("DomainInfo > domainAvailability").text();rawText=domainName+" is "+av;}
else{rawText=$(response).find("WhoisRecord > rawText");if(hasText(rawText)){rawText=rawText.text();}
else{rawText=$(response).find("WhoisRecord > registryData > rawText");if(hasText(rawText)){rawText=rawText.text();}}}
whois_raw_tab.html('<pre>'+rawText+'</pre>');}
else{whois_struct_tab.html("<pre>"+response+"</pre>");var json_res=undefined;try{json_res=JSON.parse(response);}catch(err){}
var rawText='';if(json_res!==undefined){if(json_res.WhoisRecord){if(json_res.WhoisRecord.rawText)rawText=json_res.WhoisRecord.rawText;else if(json_res.WhoisRecord.registryData){rawText=json_res.WhoisRecord.registryData.rawText;}}}
whois_raw_tab.html('<pre>'+rawText+'</pre>');}
if(options.target=='raw'){hili(whois_raw_tab);}
else if(options.target=='structured'){hili(whois_struct_tab);}}
function hili(tab){$('#whois_res').tabs('select',tab.attr('id'));}
jssm.settings.blankurl='blank.html';jssm.settings.basetitle='JSSM Test';jssm.settings.titleseparator=' : ';jssm.settings.target_holder={'newaccount.php':'right','forgotpassword.php':'right','hosted_pricing.php':'right','order.php':'right','index.php':"body",'whois-api-contact.php':"right",'myaccount.php':"right",'ceo-message.php':"right",'whois-api-doc.php':"right",'whois-api-support.php':"right",'whois-api.php':'right','whois-api-software.php':'right','whois-api-client.php':'right','bulk-whois-lookup.php':'right','whois-database-download.php':'right','registrar-whois-services.php':'right','domain-availability-api.php':'right','reverse-whois-intro.php':'right','reverse-whois.php':'body','reverse-whois-search.php':'right','reverse-whois-lookup.php':'right','reverse-whois-api.php':'right','reverse-whois-lookup-v2.php':'right','reverse-whois-v2.php':'body','reverse-whois-api-v2.php':'right','reverse-whois-lookup-v1.php':'right','reverse-whois-v1.php':'body','reverse-whois-api-v1.php':'right'};jssm.settings.returnto={'newaccount.php':'right','forgotpassword.php':'right','hosted_pricing.php':'right','order.php':'right','index.php':"body",'whois-api-contact.php':"right",'ceo-message.php':"right",'whois-api-doc.php':"right",'whois-api-support.php':"right",'whois-api.php':'right','whois-api-software.php':'right','whois-api-client.php':'right','whois-database-download.php':'right','registrar-whois-services.php':'right','domain-availability-api.php':'right','reverse-whois-intro.php':'right','reverse-whois.php':'body','reverse-whois-search.php':'right','reverse-whois-lookup.php':'right'};jssm.settings.load_action={'whoisserver/WhoisService':function(hash){var res=split_hash(hash,';');var path=res?res['path']:hash;var targetOptions={};if(res.paramdata){if(res.paramdata.domainName)$('#whoisform :text[name=domainName]').attr('value',res.paramdata.domainName);if(res.paramdata.outputFormat=='xml')$('#f_xml').attr('checked',true);if(res.paramdata.outputFormat=='json')$('#f_json').attr('checked',true);targetOptions={target:res.paramdata.target};if(res.paramdata.cmd=='GET_DN_AVAILABILITY'){targetOptions['main_tab']="Domain Info";}}
var full_path='/'+path;var options={url:full_path,beforeSubmit:beforeWhoisSubmit,success:function(response,status){whois_complete();showWhoisResponse(response,'right',targetOptions);sect_open('user_stats.php','user_stats');},error:function(req,textStatus,errorThrown){whois_complete();var err=(req.responseText?req.responseText:req.responseXML);if(!err)err=(errorThrown?errorThrown:textStatus);showWhoisResponse(err,'right');}};$('#whoisform').ajaxSubmit(options);}};function split_hash(hash,separator){var index=hash.indexOf("?");var paramdata={};if(index>=0){var param=hash.substring(index+1);var end=hash.indexOf(';');if(end>index){param=hash.substring(index,end);}
param=param.split("&");for(var i=0;i<param.length;i++){var keyval=param[i].split("=");if(!keyval)continue;if(keyval.length==2){paramdata[keyval[0]]=$.URLDecode(keyval[1]);}}}
var ar={path:index>=0?hash.substring(0,index):hash,paramdata:paramdata};return ar;}
jssm.functions.pageload=function(hash){if(hash){jssm.functions.load(hash);}else{var DEFAULT_HASH='index.php;body';jssm.functions.load(DEFAULT_HASH);}}
jssm.functions.beforeload=function(hash){var res=split_hash(hash,';');var target=res['path']?jssm.settings.target_holder[res['path']]:false;if(target){mask(get(target));}}
function ltrim(s){while(s.indexOf('/')==0){s=s.substring(1);}
return s;}
function combine_path(paths){var s='';if(paths.length>0)s=paths[0];for(var i=0;i<paths.length-1;i++){if(/\/$/.test(paths[i])||paths[i+1].indexOf('/')===0)s+=paths[i+1];else s+='/'+paths[i+1];}
return s;}
jssm.functions.load=function(hash){var res=split_hash(hash,';');var path=res?res['path']:hash;path=ltrim(path,'/');if(jssm.settings.load_action[path]){jssm.settings.load_action[path].apply(this,[hash]);return;}
var holder=jssm.settings.target_holder[path]?jssm.settings.target_holder[path]:false;if(!holder||holder==undefined)return;var full_path=mod_url(path,'_main');var original_path=jssm.getCurrentPath();if(ltrim(original_path,'/')!=''){full_path=combine_path(new Array('/',jssm.getCurrentPath(),full_path),'/');}
$.get(full_path,res['paramdata'],function(response){var regextitle=new RegExp('<title>([^<]*)<\/title>');var matches=regextitle.exec(response);if(matches)jssm.setTitle(jssm.buildTitle(matches[1]));var inside=response;get(holder).queue(function(){$(this).html(inside);$("a:not(.ignore_jssm)",$(this)).jssm('click');$("form:not(.ignore_jssm)",$(this)).jssm('submit');$(this).dequeue();if(jssm.settings.returnto[path]){var encoded_hash=hash;$('#login_returnto').val(encoded_hash);$('#logout_link').attr('href','logout.php?returnto='+encoded_hash);}});});}
jssm.functions.afterload=function(hash){var res=split_hash(hash,';');var target=res['path']?jssm.settings.target_holder[res['path']]:false;if(target)unmask(get(target));}
jssm.functions.beforeunload=function(hash){}
jssm.functions.unload=function(hash){$('.wrapper').queue(function(){$(this).fadeOut(500);$(this).dequeue();});}
jssm.functions.afterunload=function(hash){};var PriceUtil=function(){};PriceUtil.compute_items_total_price=function(items,field_name){if(!field_name)field_name='price';var total=0;for(var i=0;i<items.length;i++){total+=parseFloat($(items[i]).attr(field_name));}
return total;};PriceUtil.compute_custom_wdb_items_total_price=function(items){var total=PriceUtil.compute_items_total_price(items,false);var discount=(items.length>1?0.2:0);return total*(1-discount);};PriceUtil.compute_cctld_wdb_items_total_price=function(items,type){var total=PriceUtil.compute_items_total_price(items,type+"_price");var discount=(items.length>1?Math.min(0.5,0.1*items.length):0);return total*(1-discount);};