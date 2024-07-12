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
        let elements = document.getElementsByClassName("nowdate");
        for (let i = 0; i < elements.length; i++) {
            if (elements[i].getAttribute("data-start_time") != null) {
                let start_time = elements[i].getAttribute("data-start_time");
                let end_time = (elements[i].getAttribute("data-end_time"));
                elements[i].innerHTML = formatTimeLength(parseInt(new Date().getTime() / 1000), start_time, end_time);
            } else {
                elements[i].innerHTML = "&nbsp;" + n;
            }
        }
        setTimeout("clock()", 1000);
    }
    clock();

    function formatTimeLength(current_time, start_time, end_time) {
        let timestamp1 = start_time;
        let timestamp2 = current_time;

        let diff_seconds = timestamp1 - timestamp2;

        let days = Math.floor(diff_seconds / (24 * 3600));
        diff_seconds %= 24 * 3600;
        let hours = Math.floor(diff_seconds / 3600);
        diff_seconds %= 3600;
        let minutes = Math.floor(diff_seconds / 60);
        let seconds = diff_seconds % 60;

        return `${days} días, ${hours} horas, ${minutes} minutos, ${seconds} segundos`;
    }
</script>
