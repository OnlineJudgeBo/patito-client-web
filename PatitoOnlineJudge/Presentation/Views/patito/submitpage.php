<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <?php echo file_get_contents(__DIR__."/partials/utils-header.php"); ?>
</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php" ?>
    <?php require __DIR__ . "/../Modules/StatusTime.php"; ?>
    <main class="container mx-auto p-4 grid grid-cols-0">
        <div class="col-span-2">

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col items-center p-6">
                    Lenguaje:
                    <select id="language" name="language" onchange="setModelLanguage(this)" required>
                        <?php
                        //include(__DIR__ . "/../../../Legacy/Include/const.inc.php");
                        $language_name    = array("C", "C++11", "Pascal", "Java", "Ruby", "Bash", "Python2", "PHP", "Perl", "C#", "Obj-C", "FreeBasic", "Other Language", "", "", "Python3", "C++11", "Python3.12", "Go", "Python3.12", "Pseint");
                        foreach ($languagesAvailable as $key => $value) {
                            echo "<option value=".$value['language_id'] .">" . $language_name[$value['language_id']] . "</option>";
                        }
                        ?>
                    </select>

                    <?php if (isset($id)) { ?>
                        Problem 
                        <span class="blue"><b>
                            <?php
                                if (isset($cid)) {
                                    echo $problemName;
                                }
                            ?>
                        </b></span>
                        <input id="problem_id" type='hidden' value='<?php echo $id ?>' name="id"><br>
                    <?php } else {
                        $PID = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ", "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM", "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ");
                    }
                    ?>

                    <div id="sourceView" style="height:400px; width: 100%;"></div>

                    <script src="./assets/monaco-editor/min/vs/loader.js"></script>
                    <script>
                        var editor;
                        require.config({
                            paths: {
                                'vs': './assets/monaco-editor/min/vs'
                            }
                        });
                        require(['vs/editor/editor.main'], function() {
                            let code_escaped = "";

                            editor = monaco.editor.create(document.getElementById('sourceView'), {
                                value: code_escaped.split('\\n').join('\n'),
                                theme: 'vs-dark',
                                minimap: {
                                    enabled: false
                                }
                            });
                        });
                    </script>
                    <form id="frmSolution" action="submitpage.php" method="post" onclick=do_submit()>
                        <input type=hidden name="source" id="source">
                        <input id="cid" type='hidden' value='<?php echo $cid ?>' name="cid">
                        <input id="pid" type='hidden' value='<?php echo $id ?>' name="pid">
                        <input id="language_id" type='hidden' value='<?php echo $id ?>' name="language_id">
                        <input id="Submit" type="button" class="mt-5 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" value="Enviar codigo">
                    </form>

                </div>
            </div>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>

    <script>
        function setModelLanguage(element) {
            var currentValue = element.value;
            const model = editor.getModel();
            if (currentValue == 0 || currentValue == 1 || currentValue == 16) {
                monaco.editor.setModelLanguage(model, 'cpp');
            } else if (currentValue == 3) {
                monaco.editor.setModelLanguage(model, 'java');
            } else if (currentValue == 17 || currentValue == 19) {
                monaco.editor.setModelLanguage(model, 'python');
            }

            monaco.languages.typescript.javascriptDefaults.setCompilerOptions({
                allowNonTsExtensions: true
            });

            monaco.languages.registerCompletionItemProvider('java', {
                provideCompletionItems: function(model, position) {
                    var suggestions = [{
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

        function do_submit() {
            document.getElementById("source").value = window.editor.getValue();
            document.getElementById("language_id").value = document.getElementById("language").value;
            document.getElementById("frmSolution").target = "_self";
            document.getElementById("frmSolution").submit();
        }
    </script>
</body>

</html>