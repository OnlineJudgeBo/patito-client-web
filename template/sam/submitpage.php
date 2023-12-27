<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo $view_title ?></title>
  <link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE ?>/<?php echo isset($OJ_CSS) ? $OJ_CSS : "hoj.css" ?>' type='text/css'>
  <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
  <link rel="stylesheet" href="/monaco-editor/min/vs/editor/editor.main.css" />

</head>

<body>
  <div id="wrapper">
    <?php
    if (isset($_GET['id']))
      require_once("oj-header.php");
    else
      require_once("contest-header.php");

    ?>
    <div id="main">
        <?php
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') || isset($_GET['textarea'])) {
          $OJ_EDITE_AREA = false;
        }
        ?>
        <script src="/monaco-editor/min/vs/loader.js"></script>
        <script>
          var editor;
          require.config({
            paths: {
              'vs': '/monaco-editor/min/vs'
            }
          });
          require(['vs/editor/editor.main'], function() {
            let code_escaped = "<?php echo str_replace(array("\r\n", "\r", "\n"), '\\n', addslashes($view_src)); ?>"
            if (code_escaped.length == 0) {
              code_escaped = "Pegue aqui el codigo\n\n\n\n\n\n";
            }
            editor = monaco.editor.create(document.getElementById('sourceView'), {
              value: code_escaped.split('\\n').join('\n'),
              theme: 'vs-dark',
              minimap: {
                enabled: false 
              }
            });
          });
        </script>

        <script src="include/checksource.js"></script>
        <script src="include/jquery-latest.js"></script>
        <center>
        <form id="frmSolution" action="submit.php" method="post" <?php if ($OJ_LANG == "cn") { ?> onsubmit="return checksource(window.editor.getValue());" <?php } ?>>
          <?php if (isset($id)) { ?>
            Problem <span class="blue"><b><?php echo $id ?></b></span>
            <input id="problem_id" type='hidden' value='<?php echo $id ?>' name="id"><br>
          <?php } else {
            $PID = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ", "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM", "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ");
            //if ($pid>25) $pid=25;
          ?>

            Problem
            <span class="blue"><b>
                <?php echo $PID[$pid] ?></b>
            </span> of Contest
            <span class=blue><b>
                <?php echo $cid ?></b>
            </span>
            <br>

            <input id="cid" type='hidden' value='<?php echo $cid ?>' name="cid">
            <input id="pid" type='hidden' value='<?php echo $pid ?>' name="pid">
          <?php } ?>
          <input type=hidden name="source" id="source">
          Lenguaje:
          <select id="language" name="language" onchange="setModelLanguage(this)">
            <?php
            $lang_count = count($language_ext);
            if (isset($_GET['langmask']))
              $langmask = $_GET['langmask'];
            else
              $langmask = $OJ_LANGMASK;

            $lang = (~((int)$langmask)) & ((1 << ($lang_count)) - 1);

            if (isset($_COOKIE['lastlang'])) $lastlang = $_COOKIE['lastlang'];
            else $lastlang = 0;
            for ($i = 0; $i < $lang_count; $i++) {
              if ($lang & (1 << $i) && $language_visible[$i] == 1) {
                echo "<option value=$i " . ($lastlang == $i ? "selected" : "") . ">" . $language_name[$i] . "</option>";
              }
            }
            ?>
          </select>

          <br>
      </center>
      <div id="sourceView" style="height:600px;"></div>

      <br>
      <input id="Submit" class="btn btn-info" type="button" value="<?php echo $MSG_SUBMIT ?>" onclick=do_submit();>
      </form>
      <script>
        var sid = 0;
        var i = 0;
        var judge_result = [<?php
                            foreach ($judge_result as $result) {
                              echo "'$result',";
                            }
                            ?> ''];

        function print_result(solution_id) {
          sid = solution_id;
          $("#out").load("status-ajax.php?tr=1&solution_id=" + solution_id);

        }

        function fresh_result(solution_id) {
          sid = solution_id;
          var xmlhttp;
          if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
          } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
          }
          xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
              var tb = window.document.getElementById('result');
              var r = xmlhttp.responseText;
              var ra = r.split(",");
              //     alert(r);
              //     alert(judge_result[r]);
              var loader = "<img width=18 src=image/loader.gif>";
              var tag = "span";
              if (ra[0] < 4) tag = "span disabled=true";
              else tag = "a";
              tb.innerHTML = "<" + tag + " href='reinfo.php?sid=" + solution_id + "' class='badge badge-info' target=_blank>" + judge_result[ra[0]] + "</" + tag + ">";
              if (ra[0] < 4) tb.innerHTML += loader;
              tb.innerHTML += "Memory:" + ra[1] + "kb&nbsp;&nbsp;";
              tb.innerHTML += "Time:" + ra[2] + "ms";
              if (ra[0] < 4)
                window.setTimeout("fresh_result(" + solution_id + ")", 2000);
              else
                window.setTimeout("print_result(" + solution_id + ")", 2000);
            }
          }
          xmlhttp.open("GET", "status-ajax.php?solution_id=" + solution_id, true);
          xmlhttp.send();
        }


        function getSID() {
          var ofrm1 = document.getElementById("testRun").document;
          var ret = "0";
          if (ofrm1 == undefined) {
            ofrm1 = document.getElementById("testRun").contentWindow.document;
            var ff = ofrm1;
            ret = ff.innerHTML;
          } else {
            var ie = document.frames["frame1"].document;
            ret = ie.innerText;
          }
          return ret + "";
        }

        var count = 0;

        function do_submit() {

          document.getElementById("source").value = window.editor.getValue();
          console.log(document.getElementById("source").value)

          if (typeof(eAL) != "undefined") {
            eAL.toggle("source");
            eAL.toggle("source");
          }

          var mark = "<?php echo isset($id) ? 'problem_id' : 'cid'; ?>";
          var problem_id = document.getElementById(mark);

          if (mark == 'problem_id')
            problem_id.value = '<?php echo $id ?>';
          else
            problem_id.value = '<?php echo $cid ?>';

          document.getElementById("frmSolution").target = "_self";
          document.getElementById("frmSolution").submit();
        }

        var handler_interval;

        function do_test_run() {
          if (handler_interval) window.clearInterval(handler_interval);
          var loader = "<img width=18 src=image/loader.gif>";
          var tb = window.document.getElementById('result');
          tb.innerHTML = loader;
          if (typeof(eAL) != "undefined") {
            eAL.toggle("source");
            eAL.toggle("source");
          }


          var mark = "<?php echo isset($id) ? 'problem_id' : 'cid'; ?>";
          var problem_id = document.getElementById(mark);
          problem_id.value = 0;
          document.getElementById("frmSolution").target = "testRun";
          document.getElementById("frmSolution").submit();
          document.getElementById("TestRun").disabled = true;
          document.getElementById("Submit").disabled = true;
          count = 20;
          handler_interval = window.setTimeout("resume();", 1000);

        }

        function resume() {
          count--;
          var s = document.getElementById('Submit');
          var t = document.getElementById('TestRun');
          if (count < 0) {
            s.disabled = false;
            t.disabled = false;
            s.value = "<?php echo $MSG_SUBMIT ?>";
            t.value = "<?php echo $MSG_TR ?>";
            if (handler_interval) window.clearInterval(handler_interval);
          } else {
            s.value = "<?php echo $MSG_SUBMIT ?>(" + count + ")";
            t.value = "<?php echo $MSG_TR ?>(" + count + ")";
            window.setTimeout("resume();", 1000);

          }
        }

        function setModelLanguage(element) {
          var currentValue = element.value;
          const model = editor.getModel();
          if (currentValue == 0 || currentValue == 1 || currentValue == 16) {
            monaco.editor.setModelLanguage(model, 'c++');
          } else if (currentValue == 3 ) {
            monaco.editor.setModelLanguage(model, 'java');
          } else if (currentValue == 17 || currentValue == 19 ) {
            monaco.editor.setModelLanguage(model, 'python');
          }

          monaco.languages.typescript.javascriptDefaults.setCompilerOptions({
            allowNonTsExtensions: true
          });

          monaco.languages.registerCompletionItemProvider('java', {
                provideCompletionItems: function(model, position) {
                    var suggestions = [
                        {
                            label: 'System.out.println',
                            kind: monaco.languages.CompletionItemKind.Function,
                            insertText: 'System.out.println(${1:message});',
                            insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                            detail: 'Print to console'
                        },
                        {
                            label: 'for loop',
                            kind: monaco.languages.CompletionItemKind.Keyword,
                            insertText: 'for (${1:int i = 0; i < length; i++}) {\n\t${2: // Your code here }\n}',
                            insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                            detail: 'for loop'
                        }
                    ];

                    return {
                        suggestions: suggestions
                    };
                }
            });
            
        }
        $(window).load(function(){
          var option1 = document.createElement('option');
          option1.value = "<?php echo $language?>";
          setModelLanguage(option1);
        })
      </script>
      <div id="foot">
        <?php require_once("oj-footer.php"); ?>

      </div><!--end foot-->
    </div><!--end main-->
  </div><!--end wrapper-->
</body>

</html>