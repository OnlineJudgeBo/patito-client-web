<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juez Virtual Patito</title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">

    <script src="/assets/highlight/highlight.min.js"></script>

    <link rel="stylesheet" href="/assets/highlight/styles/default.css">
</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php" ?>
    <?php require __DIR__ . "/Modules/StatusTime.php"; ?>
    <main class="container mx-auto p-4 grid grid-cols-0">
        <div class="col-span-2">

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col items-center p-6">
                    <pre><code class="language-javascript">
function helloWorld() {
  console.log('Hello, world!');
}
</code></pre>
                </div>
            </div>
        </div>

    </main>

    <?php require_once "oj-footer.php" ?>
    <script type="text/javascript">
        document.querySelectorAll('pre code').forEach(el => {
            hljs.highlightElement(el);
        });
    </script>
</body>

</html>