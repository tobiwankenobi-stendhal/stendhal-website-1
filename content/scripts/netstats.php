<?php
class NetstatsPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Netstats'.STENDHAL_TITLE.'</title>';
		$this->includeJs();
	}

	function writeContent() {
		startBox('Trace route');
		echo '<div id="traceresult1">Tracing route, please wait...';
		echo '<table class="progressTable"><tr><td id="progress1" class="progress">&nbsp;</td><td class="pendingprogress">&nbsp;</td></tr></table>';
		echo' </div>';
		endBox();

		echo '<div id="tracebox2" style="display:none">';
		startBox('Details');
		echo '<div id="traceresult2">Gathering details about every hop on the route, please wait...';
		echo '<table class="progressTable"><tr><td id="progress2" class="progress">&nbsp;</td><td class="pendingprogress">&nbsp;</td></tr></table>';
		echo' </div>';
		endBox();
		echo' </div>';

		$ip = '';
		if (isset($_REQUEST['ip'])) {
			$ip = '&ip='.urlencode($_REQUEST['ip']);
		}
		?>
<script type="text/javascript">
$().ready(function() {
	var progressIdx = 1;
	var progress = 1;
	var progressInterval = 0.2;

	$.ajax({url: "/index.php?id=content/scripts/api&method=traceroute&fast=1<?php echo $ip ?>",
		dataType: 'html',
		success: function(data) {
		$('#traceresult1').html(data);
		$('#tracebox2').css('display', 'block');
		progressIdx = 2;
		progress = 1;
		progressInterval = 0.2;

		$.ajax({url: "/index.php?id=content/scripts/api&method=traceroute&fast=0&i="+ new Date().getTime()+"<?php echo $ip ?>",
			dataType: 'html',
			success: function(data) {
			$('#traceresult2').html(data);
		}});
	}});

	setInterval(function() {
		if (progress == 50 || progress == 75) {
			progressInterval = progressInterval / 2;
		}
		if (progress < 95) {
			$("#progress" + progressIdx).css("width", progress + "%");
			progress = progress + progressInterval;
		}
	}, 100);
});
</script>
		<?php
	}
}
$page = new NetstatsPage();