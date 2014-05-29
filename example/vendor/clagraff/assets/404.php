<?php
    $definedVars = get_defined_vars();
?>
<html>
    <head>
        <title>404 - Page not found</title>
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
            
            .container .routes {
                background-color: #FAF0B1;
            }
            
            .container .routes h2 {
                padding-left: 15px;
            }
            
            .container .routes tr, th {
                padding-left: 10px;
                padding-right: 15px;
                text-align: right;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>404 - Page not found</h1>
            <h3>Sorry, but we were unable to locate the desired page.</h2>
            <hr>
            <table>
                <tr>
                    <th>URL</th>
                    <td><?php print($request->url); ?></td>
                </tr>
                <tr>
                    <th>URI</th>
                    <td><?php print($request->uri); ?></td>
                </tr>
                <tr>
                    <th>Method</th>
                    <td><?php print($request->method); ?></td>
                </tr>
            </table>
            <div class="routes">
                <hr>
                <h2>Routes</h2>
                <table>
                    <?php
                        foreach ($this->routes as $value) {
                            print("<tr><th>");
                            print($this->basePath . $value->regex);
                            print("</th><td>");
                            print_r($value->handler);
                            print("</td></tr>\n");
                        }
                    ?>
                </table>
                <hr>
            </div>
        </div>
    </body>
</html>
