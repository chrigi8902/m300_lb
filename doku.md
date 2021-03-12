# Table of contents

- [Table of contents](#table-of-contents)
- [Einleitung](#einleitung)
- [Webseite](#webseite)
  - [Konzept](#konzept)
  - [Funktionsweise der Webseite](#funktionsweise-der-webseite)


# Einleitung

In der LB2 werden wir eine Webseite erstellen, welche anhand eines Aktien kürzels den Preis der aktie rausfinden kann.
Ziel ist die Website selber zu erstellen, Sie automatisch auf der Vagrant vm laufen zu lassen und die Seite Passwort zu schützen. 

# Webseite

## Konzept
Die Website soll eine PHP Webseite sein, welche eine Python Datei zum rausfinden von Aktien preisen benutzt. 

Hier Visualisiert:
<img src="./doku/aufbau.png" alt="Aufbau"><br><br>

## Funktionsweise der Webseite
Auf der Webseite haben wir ein kleines Fenster zum eingeben des Valors erstellt welches mit einem Submit button abgeschickt werden kann.

    <form action="" method="Post">
        <label for="stock">Stock:</label><br>
        <input type="text" name="stock" value="" ><br><br>
        <input type="submit" name="submit" value="Submit">
    </form>

Um die Daten auszulesen wird anschliessend PHP verwendet, was in der Praxis etwa so aussieht:

    <?php
        $valor = $_POST['stock'];
        $price = exec("python3.6 ./python/get_price.py $valor 2<&1");
        $valor_py = file_get_contents( "./python/tmp/stock" );
        $price_py = file_get_contents( "./python/tmp/price" );
        echo "Price for: " .$valor_py ."is: " .$price_py ;
    ?>

Dabei ist $valor der eingegebene Wert welcher weiter unten verwendet wird um mithilfe von Pyhton den Preis rauszufinden. An unterster Stelle werden sowohl der Preis als auch der Valor ausgegeben welche aus den von Python erstellten Files ausgelesen werden kann. 

