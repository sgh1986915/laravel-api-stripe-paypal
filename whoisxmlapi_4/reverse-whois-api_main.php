

<p class="rightTop"></p>
<h1>Reverse Whois API Guide</h1> 
<div class="rightTxt2">
<h2>How to make a webservice call to Reverse Whois API?</h2>
		<b>http://www.whoisxmlapi.com/reverse-whois-api/search.php?term1=topcoder&search_type=current&mode=purchase&username=xxxxx&password=xxxxx</b>
<br/>
<br/>


<table style="width:95%" cellspacing="1" cellpadding="7" border="0" align="center">
              <tr>
			  	
                <td align="left" style="background-color:#C0C0C0">Input Parameters</td>
           		<td align="left" style="background-color:#C0C0C0">Values</td>
               
              </tr>
              <tr>
                <td align="left">term<b>x</b></td>
           		<td align="left">term to search for, x ranges from 1 to 4, term1 is the first term to search for, 
           		term2 is the second term to search for, the relationship between the terms is AND.  
           		At least one term is required.
           		</td>
              </tr>
              <tr>
                <td align="left">exclude_term<b>x</b></td>
           		<td align="left">term to exclude in the search, x ranges from 1 to 4, exclude_term1 is the first term to exclude, 
           		exclude_term2 is the second term to exclude.   optional.  
           		
           		</td>
              </tr>              
              
              <tr>
                <td align="left">search_type</td>
           		<td align="left">current or historic, defaults to current.  optional.  </td>
              </tr>
              <tr>
                <td align="left">mode</td>
           		<td align="left">purchase, preview, or sample_purchase. defaults to preview.  optional.  <br>
           		preview: only lists the size and retail price of the query. It does not cost any reverse whois credit.
				<br/>purchase: includes the complete list of domain names that match the query.   It costs you one reverse whois credit
				<br/>sample_purchase: a sample result is returned regardless of the input search terms.  It does not cost any reverse whois credit.
           		</td>
              </tr>
                <tr>
                <td align="left">since_date</td> 
           		<td align="left">if specified, only domains with whois record discovered/created after (including domains that are registered after) this date will be returned </td>
              </tr>
             <tr>
                <td align="left">username</td>
           		<td align="left">required, your account username</td>
              </tr>
                <tr>
                <td align="left">password</td>
           		<td align="left">required, your account password</td>
              </tr>
              
              <tr>
			  	
                <td align="left" colspan=2 style="background-color:#C0C0C0">Output Schema</td>
               
              </tr>
              
              <tr>
              
              <td colspan=2>
              <div id="reversewhois_schema" class="jsonview" style="width:100%;">
<pre>{
/* an array of resulting domains */	
domains: Array, 
/* total number of domains returned for this query */
records: Integer,
stats: {
	/* total number of possible domains for this query */
	total_count: Integer,
	/* regular report price */
	report_price: Float
},
search_terms: {
	/* input term to search for */
	include: Array,
	/* input terms to exclude */
	exclude: Array,
	/* maximum number of search terms*/
	max_search_terms: Integer
},
/* search type: current or historic */
search_type: String,
/* duration of the search in seconds */
time: Float
}

</pre>


              </div>
              </td>
              </tr>                                                              
</table>              
	<br/>
You must <a href="bulk-reverse-whois-order.php" class="ignore_jssm"> purchase reverse whois credits</a> to use Reverse Whois API.




</div>

