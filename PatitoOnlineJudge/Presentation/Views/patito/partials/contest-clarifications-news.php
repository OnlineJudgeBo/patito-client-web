<?php
// Banner shown when a new announcement or answer arrives while the page is open (polled every 30 s).
?>
<div id="clarifications-news" class="hidden mb-4 rounded-lg border border-yellow-300 bg-yellow-100 p-3 text-center">
    Hay nuevas aclaraciones o anuncios.
    <a href="#aclaraciones" onclick="location.reload()" class="text-blue-500 hover:underline">Ver</a>
</div>
<script>
    (function () {
        var version = <?php echo json_encode($clarificationsVersion) ?>;
        var url = "contest.php?cid=<?php echo intval($cid) ?>&clarifications=1";
        setInterval(function () {
            fetch(url, { credentials: "same-origin" })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (data.version !== version) {
                        document.getElementById("clarifications-news").classList.remove("hidden");
                    }
                })
                .catch(function () { });
        }, 30000);
    })();
</script>
