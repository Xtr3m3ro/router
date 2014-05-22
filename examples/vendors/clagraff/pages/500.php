<html>
    <head>
        <title></title>
        <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
        <style>
            body {
                background-color: #ededed;
                font-family: 'Oswald', sans-serif;
            }
            
            .container {
                width: 70%;
                min-height: 300px;
                margin: 30px auto;
                border: 2px solid #CCCCCC;
                background-color: #F1F1F1;
                padding: 15px;
            }
            
            .container h1 {
                color: #DE6A6A;
            }
            
            .container h3 {
                margin-left: 30px;
            }
            
            .container .params tr, th {
                padding-left: 30px;
            }
            
            .container .stacktrace {
                    margin-top: 20px;
                    background-color: #FAF0B1;
            }
            
            .container .stacktrace code {
                white-space: pre-line;
            }
        </style>
    </head>
    <body>
        <?php $error = $request->meta->error; ?>
        <div class="container">
            <h1><?php print(get_class($error)); ?></h1>
            <h3><?php print($error->getMessage()); ?></h2>
            <hr>
            <div class="params">
                <table>
                    <tr>
                        <th>
                            File
                        </th>
                        <td>
                            <?php print($error->getFile()); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Line
                        </th>
                        <td>
                            <?php print($error->getLine()); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            URL
                        </th>
                        <td>
                            <?php print($request->url); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            URI
                        </th>
                        <td>
                            <?php print($request->uri); ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="stacktrace">
                <h3>Stack Trace</h3>
                <code>
                    <?php
                        $stacktrace = $request->meta->error->getTraceAsString();
                        $lines = explode("\n", $stacktrace);
                        $stacktrace = implode("\n", array_reverse($lines));
                        $stacktrace = preg_replace(
                            "/(#[0-9]+) (.*)/",
                            '<b>${1}</b> <i>${2}</i>',
                            $stacktrace
                        );
                        print($stacktrace);
                    ?>
                </code>
            </div>
        </div>
    </body>
</html>
