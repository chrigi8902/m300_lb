<!DOCTYPE html><html  >
<head>

</head>
    <body>
        <?php
            $valor = $_POST['stock'];
            $price = exec("python ./python/get_price.py $valor 2<&1");
            $valor_py = file_get_contents( "./python/tmp/stock.txt" );
            $price_py = file_get_contents( "./python/tmp/price.txt" );
            echo "Price for: " .$valor_py ."is: " .$price_py ;
        ?>
        <form action="" method="Post">
            <label for="stock">Stock:</label><br>
            <input type="text" name="stock" value="" ><br><br>
            <input type="submit" name="submit" value="Submit">
        </form>

    </body>
</html>