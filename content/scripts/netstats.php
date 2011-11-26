<?php
class NetstatsPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Netstats'.STENDHAL_TITLE.'</title>';
		$this->includeJs();
	}

	function writeContent() {
		startBox('Trace route');
		echo '<div id="traceresult">Tracing route, please wait...</div>';
		endBox();
		?>
<script type="text/javascript">
$().ready(function() {
	$.ajax({url: "/index.php?id=content/scripts/api&method=traceroute&fast=1",
		dataType: 'html',
		success: function(data) {
		$('#traceresult').html(data);

		$.ajax({url: "/index.php?id=content/scripts/api&method=traceroute&fast=0",
			dataType: 'html',
			success: function(data) {
			$('#traceresult').html(data);
		}});
	}});
});
</script>
		<?php
	}
}
$page = new NetstatsPage();