<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="./css/style.css">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
  <header>
    <h1>Stonks Website</h1>
    <hr>
  </header>
  <h1>
    <form action="" method="Post">
      <input type="text" id="stockin" name="stock" value="">
      <input type="submit" id="stocksub" name="submit" value="Submit">
    </form>
  </h1>    
    <?php
    $valor = $_POST['stock'];

    $myfile = fopen("./python/tmp/$valor", "w") or die("Unable to open file!");
    fclose($myfile);
    sleep(1);
    $price = exec("python ./python/get_price.py $valor 2<&1");
    $valor_py = file_get_contents( "./python/tmp/stock.txt" );
    $price_py = file_get_contents( "./python/tmp/price.txt" );
    ?>
 
  <table>
    <tr>
       
      <th>Beschrieb</th>
      <th>Wert</th>
      
    </tr>

    <tr>
      <td>Aktie:</td>
      <td><?php echo $valor_py ?></td>
    </tr>
    <tr>
      <td>Preis:</td>
      <td><?php echo $price_py?></td>
    </tr>
  </table>
  <p>

  </p>
</body>

</html>