		</div><!--/content-->
	</div><!--/bd-->
	<div id="ft" class="container">
		<p><%= config['php']['footer_text'] %></p>
		<?php print_text_navigation(); ?>
		<p class="small"><strong><a href="http://www.sesamewebdesign.com">Orthodontic Web Site by Sesame Design&trade;</a></strong></p>
	</div><!--/ft-->
	<div id="util"></div><!--/util-->
	<?php print_navigation($exclusions = array('Contact Us','Site Map'), $include_sub=true); ?><!--/nav-->
</div><!--/container-->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("<%= config['php']['ga_code_id'] %>");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>