<!DOCTYPE html><html  >
<head>
    <link rel="stylesheet" href="./css/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
    <body>
        <div>
            

            <h1>
            <form action="" method="Post">
                <label for="stock">Stock:</label><br>
                <input type="text" id="stockin" name="stock" value="" ><br>
                <input type="submit" id="stocksub" name="submit" value="Submit">
            </form>
            </h1>
            <h2>
            <?php
                $valor = $_POST['stock'];
                $price = exec("python ./python/get_price.py $valor 2<&1");
                $valor_py = file_get_contents( "./python/tmp/stock.txt" );
                $price_py = file_get_contents( "./python/tmp/price.txt" );
                echo "Price for: " .$valor_py ."is: ";
                echo $price_py ;
            ?>
            </h2>
        </div>
    </body>
</html>