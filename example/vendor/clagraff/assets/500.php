<html>
    <head>
        <title>500 - Internal exception has occurred</title>
        <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
        <style>
            body {
                background-color: #ededed;
                font-family: 'Oswald', sans-serif;
            }
            
            .container {
                width: 70%;
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
            
            .container .fileLines {
                background-color: #ABEBE4;
                margin: 0;
                padding: 0;
                line-height: 1em;
                
            }
            
            .container .fileLines code {
                white-space: pre;
            }
            
            .container .fileLines .line-0 {
                background-color: #B8FFEE;
            }
            
            .container .fileLines .line-1 {
                background-color: #9EFFE8;
            }
            
            .container .fileLines .line-h {
                background-color: #FCFFE6;
            }
            
            .container .fileLines .num {
                background-color: #ABEBE4;
                padding-right: 10px;
                padding-left: 3px;
                font-style: italic;
            }
            
            .container .fileLines .num-h {
                background-color: #FCFFE6;
                padding-right: 10px;
                padding-left: 3px;
                font-style: italic;
            }
        </style>
    </head>
    <body>
        <?php $error = $request->meta->error->getTrace()[0]; ?>
        <div class="container">
            <h1>500 - Internal exception has occurred</h1>
            <?php if ($debug == true) { ?>
                <h3><?php print($request->meta->error->getMessage()); ?></h2>
            <?php } else { ?>
                <h3>An internal error has occurred. Please contact a site administrator</h3>
            <?php } ?>
            <hr>
            <?php if ($debug == true) { ?>
                <div class="params">
                    <table>
                        <?php if (array_key_exists("file", $error)) { ?>
                        <tr>
                            <th>
                                File
                            </th>
                            <td>
                                <?php print($error["file"]); ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if (array_key_exists("line", $error)) { ?>
                            <tr>
                                <th>
                                    Line
                                </th>
                                <td>
                                    <?php print($error["line"]); ?>
                                </td>
                            </tr>
                        <?php } ?>
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
                    <hr>
                    <h3>Execution Stack Trace</h3>
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
                    <hr>
                </div>
                <?php if (array_key_exists("line", $error)
                    && array_key_exists("file", $error)) {
                ?>
                    <div class="fileLines">
                        <hr>
                        <h3><?php print($error["file"]); ?></h3>
                        <code>
                            <?php
                            
                                
                                $fileLines = array_map("htmlspecialchars", file($error["file"]));
                                
                                $start = 0;
                                $end = 0;
                                $diff = 8;
                                
                                $line = $error["line"];
                                
                                if ($line - $diff > 0) {
                                    $start = $line - $diff;
                                }
                                if ($line + $diff < count($fileLines)) {
                                    $end = $line + $diff;
                                }
                                
                                
                                for ($i = $start; $i <= $end; $i++) {
                                        $html = "<div class=\"line-";
                                    if (($i + 1) != $line) {
                                        $html .= $i % 2 . "\">";
                                    } else {
                                        $html .= "h\">";
                                    }
                                    
                                    $html .= "<span class=\"num";
                                    if (($i + 1) == $line) {
                                        $html .= "-h\">" . ($i + 1) . "</span>";
                                    } else {
                                        $html .= "\">" . ($i + 1) . "</span>";
                                    }
                                    $html .= str_replace(array("\r", "\n"), "", $fileLines[$i]) . "</div>";
                                    print($html);
                                }
                            ?>
                        </code>
                        <hr>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </body>
</html>
