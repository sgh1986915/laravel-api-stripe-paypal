<div class="row wa-content-bg wa-content-bg-whoislookup">
	<div class="col-xs-12 wa-col-xs-no-padding wa-box-width-xs wa-box-margin-whoisapi wa-auto-margin">
		<div class="wa-box-whoislookup">
			<nav class="navbar navbar-default wa-tab-navbar wa-tab-navbar-whoislookup">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header wa-navbar-header-lookup wa-cursor">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#wa-tab-list-whoislookup">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand visible-xs wa-tab-top-menu-xs" id="wa-xs-tab-active-menu">Whois Record</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse wa-navbar-collapse-lookup wa-cursor" id="wa-tab-list-whoislookup">
					<ul class="nav navbar-nav wa-navbar-nav wa-navbar-nav-whoislookup">
						<li class="active wa-tab-li-menu wa-tab-li-menu-whoislookup wa-border-right-tab" rel="wa-tab-whois-record"><a href="#whois-record">Whois Record</a></li>
						<li class="wa-tab-li-menu wa-tab-li-menu-whoislookup" rel="wa-tab-raw-text"><a href="#raw-text">Raw Text</a></li>
					</ul>
				</div>
			</nav>
			<div id="wa-tab-content-whoislookup" class="wa-tab-background">
				<div class="wa-content-lookup" id="wa-tab-whois-record"></div>
				<div class="wa-content-lookup" id="wa-tab-raw-text" style="display:none;"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$.ajax({
			url: "{{ $data['url'] }}",
			success: function(res) {
				formatWhoisResponse(res, "{{ $data['outputFormat'] }}");
			}
		});
	});
</script>
