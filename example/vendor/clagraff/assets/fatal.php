<html>
    <head>
        <title>500 - Fatal error has occurred</title>
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
        <div class="container">
            <h1>500 - Fatal error has occurred</h1>
            <h3><?php print($error["message"]); ?></h3>
            <hr>
            <table>
                <tr>
                    <th>
                        Error Type
                    </th>
                    <td>
                        <?php print($error["type"]); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        Error Name
                    </th>
                    <td>
                        <?php print($error["name"]); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        File
                    </th>
                    <td>
                        <?php print($error["file"]); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        Line
                    </th>
                    <td>
                        <?php print($error["line"]); ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        Message
                    </th>
                    <td>
                        <?php print($error["message"]); ?>
                    </td>
                </tr>
            </table>
            <hr>
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
        </div>
    </body>
</html>
