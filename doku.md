# Table of contents

- [Table of contents](#table-of-contents)
- [Einleitung](#einleitung)
- [Webseite](#webseite)
  - [Konzept](#konzept)
  - [Funktionsweise der Webseite](#funktionsweise-der-webseite)
  - [Funktionsweise "get_price.py"](#funktionsweise-get_pricepy)


# Einleitung

In der LB2 werden wir eine Webseite erstellen, welche anhand eines Aktien kürzels den Preis der aktie rausfinden kann.
Ziel ist die Website selber zu erstellen, Sie automatisch auf der Vagrant vm laufen zu lassen und die Seite Passwort zu schützen. 

# Webseite

</br>

___
## Konzept
___

</br>

Die Website soll eine PHP Webseite sein, welche eine Python Datei zum rausfinden von Aktien preisen benutzt. 

Hier Visualisiert:
<img src="./doku/aufbau.png" alt="Aufbau"><br>
*Der Zugriff übers Internet funktioniert leider nicht*
</br>
</br>

___
## Funktionsweise der Webseite
___

</br>

Auf der Webseite haben wir ein kleines Fenster zum eingeben des Valors erstellt welches mit einem Submit button abgeschickt werden kann.

    <form action="" method="Post">
        <label for="stock">Stock:</label><br>
        <input type="text" id="stockin" name="stock" value="" ><br>
        <input type="submit" id="stocksub" name="submit" value="Submit">
    </form>

Um die Daten auszulesen wird anschliessend PHP verwendet, was in der Praxis etwa so aussieht:

    <?php
        $valor = $_POST['stock'];
        $price = exec("python3.6 ./python/get_price.py $valor 2<&1");
        $valor_py = file_get_contents( "./python/tmp/stock" );
        $price_py = file_get_contents( "./python/tmp/price" );
        echo "Price for: " .$valor_py ."is: ";
        echo $price_py ;
    ?>

Dabei ist $valor der eingegebene Wert welcher weiter unten verwendet wird um mithilfe von Pyhton den Preis rauszufinden. An unterster Stelle werden sowohl der Preis als auch der Valor ausgegeben welche aus den von Python erstellten Files ausgelesen werden kann. 

</br>

___ 
## Funktionsweise "get_price.py" 
___

</br>

Die "get_price.py" datei ist eine Python Datei welche den Preis des Valors mithilfe einer API rausfindet. Anschliessend wird sowohl der Valor als auch der Preis in zwei seperate Files geschrieben. Diese werden dann wie oben vom PHP auf der Webseite angezeigt. 

Hier eine kleine übersicht zu den Dateien:

| Filename | Description        | Path                      | Content  |
|----------|--------------------|---------------------------|----------|
| stock    | Name of the stock  | /website/python/tmp/stock | ``tsla``     |
| price    | Price of the stock | /website/python/tmp/price | ``658.(..)`` |

</br>
Die Python Datei sieht folgendermassen aus:

</br>

    #1 import sys
    #2 from yahoo_fin import stock_info as si
    #3
    #4 stock = str(sys.argv[1])
    #5
    #6 price = si.get_live_price(stock)
    #7 price_str = str(price)
    #8 file_price = "/var/www/html/python/tmp/price"
    #9 file_stock = "/var/www/html/python/tmp/stock"
    #10
    #11 with open(file_price, 'w') as fileowrite:
    #12        fileowrite.write(price_str+"<br>")
    #13
    #14 with open(file_stock, 'w') as fileowrite:
    #15        fileowrite.write(stock+"<br>")

-   Im oberen Teil werden alle benötigten Module installiert welche  benötigt werden. "sys" benutze ich im script um Argumente vom PHP entgegen zu nehmen (#4). 
-   Das zweite Modul in der Zeile #2 wird dazu benötigt die aktuellen Preise der Aktien zu bekommen. 
-   Anschliessend wird in der Zeile #6 der preis rausgefunden in dem Man die API mit dem Stock Namen "füttert"
-   

