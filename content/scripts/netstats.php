<?php
class NetstatsPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Netstats'.STENDHAL_TITLE.'</title>';
		$this->includeJs();
	}

	function writeContent() {
		startBox('Trace route');
		echo '<div id="traceresult1">Tracing route, please wait...</div>';
		endBox();

		echo '<div id="tracebox2" style="display:none">';
		startBox('Details');
		echo '<div id="traceresult2">Tracing route, please wait...</div>';
		endBox();
		echo' </div>';
		?>
<script type="text/javascript">
$().ready(function() {
	$.ajax({url: "/index.php?id=content/scripts/api&method=traceroute&fast=1",
		dataType: 'html',
		success: function(data) {
		$('#traceresult1').html(data);
		$('#tracebox2').css('display', 'block');

		$.ajax({url: "/index.php?id=content/scripts/api&method=traceroute&fast=0",
			dataType: 'html',
			success: function(data) {
			$('#traceresult2').html(data);
		}});
	}});
});
</script>
		<?php
	}
}
$page = new NetstatsPage();