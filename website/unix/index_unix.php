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
    $price = exec("python3.6 ./python/get_price.py $valor 2<&1");
    $valor_py = file_get_contents( "./python/tmp/stock" );
    $price_py = file_get_contents( "./python/tmp/price" );
    ?>
 
  <table>
    <tr>
       
      <th>Beschrieb</th>
      <th>Wert</th>
      
    </tr>

    <tr>
      <td>Aktie:</td>
      <td style="text-transform:uppercase;"><?php echo $valor_py ?></td>
    </tr>
    <tr>
      <td>Preis:</td>
      <td><?php echo $price_py?></td>
    </tr>
  </table>
  
</body>

</html>