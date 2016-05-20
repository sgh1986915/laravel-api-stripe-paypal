<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/**
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 **/

return array(
    	'whoisxmlapi_js' => array(
    		
    	 //'//whoisxmlapi/js/dev/ui.core.js',
    		//'//whoisxmlapi/js/dev/ui.tabs.js',
    		'//whoisxmlapi/js/dev/jquery.form.js',
    		'//whoisxmlapi/js/dev/jquery.validate.js',
    		'//whoisxmlapi/js/dev/XMLDisplay.js',
    		'//whoisxmlapi/js/dev/json.js',
    		'//whoisxmlapi/js/dev/util.js',
			'//whoisxmlapi/js/dev/urlEncode.js',
    		'//whoisxmlapi/js/dev/json2.js',
    		'//whoisxmlapi/js/dev/jquery.jssm.js',
    		'//whoisxmlapi/js/dev/jquery.jssm.config.js',
    		'//whoisxmlapi/js/dev/price_util.js'
    	),
    	'whoisxmlapi_css' => array(
    	   '//whoisxmlapi/themes/base/ui.all.css', 
    	   //'//whoisxmlapi/themes/base/ui.tabs.css',
    	   '//whoisxmlapi/style.css',
    	   '//whoisxmlapi/XMLDisplay.css',
    	   '//whoisxmlapi/view.css',
    	   '//whoisxmlapi/css/colorbox.css',
    	   '//whoisxmlapi/css/socialite.css'
    	   ),
		'mortgagecalc_js'=> array('//mortgageCalc/js/view.js',
			'//mortgageCalc/js/jquery-1.3.2.js',
			'//mortgageCalc/js/ui/ui.core.js',
			'//mortgageCalc/js/ui/ui.tabs.js',
			'//mortgageCalc/js/ui/ui.datepicker.js',
			'//mortgageCalc/js/jquery.validate.js',
			'//mortgageCalc/js/jquery.schedule.js',
			'//mortgageCalc/js/number-functions.js',
			'//mortgageCalc/js/util.js',
			'//mortgageCalc/js/jquery.flot.js',
			'//mortgageCalc/js/swindow.js',
			'//mortgageCalc/js/mcalc_core.js'
		),
		'mortgagecalc_css' => array(
			'//mortgageCalc/view.css',
			'//mortgageCalc/main.css',

		),
		'domainsearchguru_css'=>array(
			'//domainsearchguru/css/reset.css',
			'//domainsearchguru/css/typography.css',
			'//domainsearchguru/css/main.css',
			'//domainsearchguru/css/form.css',
			'//domainsearchguru/css/style.css'
		),
		'domainsearchguru_js'=>array(
			'//domainsearchguru/js/jzutil.js',
			'//domainsearchguru/js/DomainChecker.js',
			'//domainsearchguru/js/init.js',
			'//domainsearchguru/js/ffsearch.js'
		),
		'ichart_css'=>array(
			"//ichart/javascripts/ext/resources/css/ext-all.css",
			"//ichart/javascripts/iChart/css/moduleToolbars.css",
			"//ichart/javascripts/ext/resources/css/newgentheme.css",
			"//ichart/javascripts/iChart/css/general.css",
			"//ichart/javascripts/ux/fileuploadfield/css/fileuploadfield.css",
			"//ichart/javascripts/iChart/css/main.css",
			"//ichart/javascripts/iChart/calendar/css/calendar.css",
			"//ichart/javascripts/iChart/calendar/css/mycalendar.css",
			"//ichart/javascripts/ux/treegrid_310/treegrid.css",
			"//ichart/javascripts/iChart/css/Portal.css"

		),
		'ichart_js'=>array(
		      
        "//ichart/javascripts/ext/adapter/ext/ext-base.js",        
        "//ichart/javascripts/ext/ext-all-debug.js",
        "//ichart/javascripts/iChart/util/Loader.js",    
        "//ichart/javascripts/iChart/init.js",
        
        "//ichart/javascripts/ux/uxMedia213/uxmedia.js",
        
        "//ichart/javascripts/ux/Ext.ux.Image.js",
        
        "//ichart/javascripts/iChart/extension/popupWindow.js",
        
        "//ichart/javascripts/ux/Ext.ux.Expandian.js",
        
        "//ichart/javascripts/iChart/extension/IChartWindow.js",
        
        "//ichart/javascripts/iChart/util/util.js",
        
        "//ichart/javascripts/iChart/main/recentCharts.js",
        
        "//ichart/javascripts/iChart/main/patientSearchPanel.js",
        
        "//ichart/javascripts/iChart/main/main.js",
        
        "//ichart/javascripts/iChart/main/eSticky.js",
        
        "//ichart/javascripts/iChart/model/dataStore.js",
        
        "//ichart/javascripts/iChart/patient/NewPatientForm.js",
        
        "//ichart/javascripts/iChart/patient/encounter/states.js",
        
        "//ichart/javascripts/iChart/patient/encounter/EncounterForm.js",
        
        "//ichart/javascripts/iChart/main/API.js",
        
        "//ichart/javascripts/iChart/patient/patientChart/meds/meds_ref_renew.js",
        
        "//ichart/javascripts/iChart/patient/patientChart/probProc/browseDxPnl.js",
        
        "//ichart/javascripts/iChart/patient/ptListPanel.js",
        
        "//ichart/javascripts/iChart/patient/patientChart/chartsPanel.js",
        
        "//ichart/javascripts/iChart/messages/alertForm.js",
        
        "//ichart/javascripts/iChart/messages/emailForm.js",
        
        "//ichart/javascripts/iChart/messages/emailGrid.js",
        
        "//ichart/javascripts/iChart/dashboard/dashboardCard.js",
        
        "//ichart/javascripts/iChart/dashboard/dayViewPanel.js",
        
    
        "//ichart/javascripts/ux/treegrid_310/TreeGridSorter.js",
        
        "//ichart/javascripts/ux/treegrid_310/TreeGridColumnResizer.js",
        
        "//ichart/javascripts/ux/treegrid_310/TreeGridNodeUI.js",
        
        "//ichart/javascripts/ux/treegrid_310/TreeGridLoader.js",
        
        "//ichart/javascripts/ux/treegrid_310/TreeGridColumns.js",
        
        "//ichart/javascripts/ux/treegrid_310/TreeGrid.js",
        
        "//ichart/javascripts/iChart/extension/resizeTreeGrid.js",
        
        "//ichart/javascripts/iChart/model/chart_alg/dataStore.js"
        
		)


    // custom source example
    /*'js2' => array(
        dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
        // do NOT process this file
        new Minify_Source(array(
            'filepath' => dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
            'minifier' => create_function('$a', 'return $a;')
        ))
    ),//*/

    /*'js3' => array(
        dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
        // do NOT process this file
        new Minify_Source(array(
            'filepath' => dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
            'minifier' => array('Minify_Packer', 'minify')
        ))
    ),//*/
);