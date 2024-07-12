<?php
foreach ($view_contest as $row) {
	$css1 = "";
	$css2 = "";
	if ($row["wait"] === true) {
		$css1 = "blue-text";
		$css2 = "green-text";
	} else {
		$css1 = "red-text";
		$css2 = "green-text";
	}

	echo '
	<a href="contest.php?cid='.$row["contest_id"].'">
		<div class="max-w-sm rounded overflow-hidden shadow-lg bg-white mb-2 flex flex-col justify-center items-center">
			<div class="pt-2 text-center mb-2">
				<div class="font-bold text-xl py-2">' . $row["title"] . '</div>
			</div>
	
			<div class="px-2 pb-1 flex flex-col justify-center items-center">
				<span class="inline-block rounded-full text-base font-semibold ' . $css1 . '">' . $row["start_time_run"] . '</span>
				<span class="inline-block rounded-full text-base font-semibold ' . $css2 . '">' . $row["start_time"] . '</span>
			</div>
		</div>
	</a>
	';
}
$css1 = "";
$css2 = "";
?>
<script>
	<?php date_default_timezone_set("America/La_Paz"); ?>
	var diff = new Date("<?php echo date("Y/m/d H:i:s") ?>").getTime() - new Date().getTime();

	function addZeroDigit(digit) {
		return digit >= 10 ? digit: "0" + digit;
	}

	function clock() {
		var x, h, m, s, n, xingqi, y, mon, d;
		var x = new Date(new Date().getTime() + diff);
		y = x.getYear() + 1900;
		if (y > 3000) y -= 1900;
		mon = x.getMonth() + 1;
		d = x.getDate();
		xingqi = x.getDay();
		h = x.getHours();
		m = x.getMinutes();
		s = x.getSeconds();

		n = y + "-" + addZeroDigit(mon) + "-" + addZeroDigit(d) + " " + addZeroDigit(h) + ":" + addZeroDigit(m) + ":" + addZeroDigit(s);
		var elements = document.getElementsByTagName("nowdate");
		var j = elements.length;
		for (var i = 0; i < j; i++) {
			elements[i].innerHTML = n;
		}
		setTimeout("clock()", 1000);
	}
	clock();
</script>