# Table of contents


- [Table of contents](#table-of-contents)
- [1. Einleitung](#1-einleitung)
- [2. Webseite](#2-webseite)
  - [2.1 Konzept](#21-konzept)
  - [2.2 Funktionsweise der Webseite](#22-funktionsweise-der-webseite)
- [3. Python Script](#3-python-script)
  - [2.1 Konzept](#21-konzept-1)
  - [2.2 Aufbau](#22-aufbau)
- [3. Docker](#3-docker)
  - [3.1 Dockerfiles](#31-dockerfiles)
    - [3.1.1 Python](#311-python)
    - [3.1.2 Website](#312-website)
  - [3.2 Docker-compose](#32-docker-compose)
- [4. Schwierigkeiten](#4-schwierigkeiten)


</br></br></br></br>
# 1. Einleitung

In der LB3 werden wir eine Webseite der LB2 zwar wiederverwenden, jedoch Erweiterungen hinzufügen und die Abfrage des Preises über einen Seperaten Container laufen lassen. 

Dies möchten wir über einen share ermöglichen, in dem die Webseite Daten mit dem Python Script austauschen kann.
</br></br></br></br>

# 2. Webseite

</br>

___
## 2.1 Konzept


</br>

Die Website soll eine PHP-Webseite sein, welche eine Python Datei zum rausfinden von Aktien preisen benutzt. 

Hier visualisiert:
<img src="./doku/Aufbau.PNG" alt="Aufbau"><br>

</br>
</br>
Auf der Browser Seite sieht es für den User ungefähr folgendermassen aus:
</br>
<img src="./doku/Design.PNG" alt="Aufbau"><br>

___
## 2.2 Funktionsweise der Webseite


</br>

Auf der Webseite haben wir ein kleines Fenster zum Eingeben des Valors erstellt welches mit einem Submit button abgeschickt werden kann.

    <?php
    if (isset($_POST['stock']))
    {
      $valor = $_POST['stock'];
    } 
    else{
      $valor = "Nothing";
    }
    if (preg_match("/[A-Z]/i",$valor)) {
      $myfile = fopen("./python/tmp/$valor", "w") or die("Unable to open file!"); #File erstellen
      fclose($myfile);
      sleep(1);
      $valor_py = file_get_contents( "./python/tmp/stock.txt" ); #Aktien Namen auslesen
      $price_py = file_get_contents( "./python/tmp/price.txt" ); #Preis auslesen
    } else {
      $valor_py = "Not Set"; #Falls nichts eingegeben Not Set
      $price_py = "Not Set";
    }
    ?>

Hier mussten wir gleich mehrere Sachen abänder, weil sich der "Python Container" nicht über einen einfachen Befehl aufrufen lies. 
Um in durchgehend laufen zu lassen hat er also in einem Ordner nach änderungen gesucht.

Mit unserer Webseite mussten wir so nur noch die richtigen Berrechtigungen haben um ein file im Share zu generieren und dieses anschliessend mit dem Namen der Aktie umbenennen. 

</br>

# 3. Python Script

</br>

___
## 2.1 Konzept

<img src="./doku/Aufbau_python.PNG" alt="Aufbau"><br>

</br>

1) Nach dem input auf der Webseite erstellt die Webseite ein File mit dem Namen vom Input (z.B. TSLA)
2) Der Python Container, welcher durchgehend nach Änderungen auf dem Share sucht, merkt das und findet den Namen der Aktie durch den Filenamen raus. 
3) Mit dem Namen versucht er mithilfe von yahoo_fin den Preis für diese Aktie rauszufinden.
4) Falls dies geglückt ist und die Aktie existiert, schreibt er Preis und Namen seperat in zwei Files.
5) Die Webseite zeigt die Inhalte der Files genau eine Sekunde nach dem erstellen vom File an.
6) Das File wird vom Script gelöscht damit der Ordner nicht überfüllt wird. 


</br>
___

## 2.2 Aufbau

Um auf dem Share nach Änderungen zu schauen habe ich den Watchdog Observer benutzt, welches als Modul importiert werden kann und eine Art Beispiel auf der Webseite vorzufinden ist. </br>
https://pypi.org/project/watchdog/ </br>

Da dieser Script allerdings nur die Outputs mit einem Zeitformat ausgibt, habe ich ihn ein wenig umgeschrieben sodass er bei einer Änderung den Namen der Datei ausliesst und damit, wie in der LB2, weiter arbeitet. 

class Event(LoggingEventHandler):
    def dispatch(self, event):
        import sys
        from yahoo_fin import stock_info as si

        file_price = "/usr/src/app/tmp/price.txt"
        file_stock = "/usr/src/app/tmp/stock.txt"
        stock_path = "Place Hold"
        
        if (event.src_path != "./tmp/price.txt") and (event.src_path != "./tmp/stock.txt") and (event.src_path != "./tmp") and ("tmp" in event.src_path):
                try:
                    stock_path = event.src_path
                    stock = stock_path.replace("./tmp/", "")

                    price = si.get_live_price(stock)
                    price_str = str(price)
                    print(stock_path)
                    try:
                        os.remove(stock_path)
                    except:
                        pass

                    with open(file_price, 'w') as fileowrite:
                            fileowrite.write(price_str)
                    with open(file_stock, 'w') as fileowrite:
                            fileowrite.write(stock)
                except:
                    print("not working")
                    price_str = "Not Found"
                    stock = "Not Found"
                    try:
                        os.remove(stock_path)
                    except:
                        pass
                    with open(file_price, 'w') as fileowrite:
                            fileowrite.write(price_str)
                    with open(file_stock, 'w') as fileowrite:
                            fileowrite.write(stock)
        else:
                pass

Der "event.src_path" ist dabei der Pfad für die Datei, die angepasst wurde. Auch habe ich Fallunterscheidungen eingebaut, damit es nicht zu Errors kommen kann und der Container am Laufen Bleibt. 


</br>
___

# 3. Docker

Da ich mit dem Python Teil viel machen konnte, habe ich bei den Dockerfiles lediglich zwei Dockerfiles zum generieren von dem Python- und dem Website-Container gebraucht. Der wichtigste Teil war dabei, dass beide mit dem gleichen Share verbunden werden und die richtigen Berrechtigungen haben womit ich sehr stark zu kämpfen hatte. 

Da ich die Permissions nicht mit den Dockerfiles setzen konnte, habe ich sie als Workaround direkt mit dem Python Script gesetzt. 

    os.system("chmod 777 -R ./*")

So hat der Share ab einem bestimmten Punkt volle Berrechtigungen und kann somit das erstellen von Files erlauben. 

</br>
___

## 3.1 Dockerfiles

### 3.1.1 Python

    FROM python:3-onbuild
    COPY . /usr/src/app
    CMD [ "python", "get_price_unix.py" ]

Beim Python Dockerfile war es wichtig, dasss die Requirements runtergeladen werden, welches ich mit der zweiten Zeile erledigen konnte. 

requirements.txt:

    yahoo_fin==0.8.8
    watchdog==2.0.3

Wenn man die nicht per Requirements file installiert, müsste man jeweils pip install 'name_vom_modul' eingeben was besonders bei grösseren Scripts sehr aufwendig werden kann. 
Am Schluss vom Dockerfile wird dann noch mein Script ausgeführt welches den Container am Laufen lässt. 

### 3.1.2 Website

    FROM php:apache
    ADD apache2.conf /etc/apache2/apache2.conf
    EXPOSE 80

Bei der Webseite musste ich lediglich die konfig rüber kopieren, welche die Authentification per Password ermöglicht.

## 3.2 Docker-compose

    version: '3'

    services:
      get-price:
        build: ./python
        volumes:
          - ./share/website/python:/usr/src/app
      
      website:
        build: ./website
        volumes:
          - ./share/website:/var/www/html
        ports:
          - 80:80
        depends_on:
          - get-price


Das Dockercompose File setzt alles zusammen und erstellt die benötigten Shares. Wie oben bereits erwähnt werden die Berrechtigungen mit dem Python container gegeben da ich es anders nicht hinbekommen habe. 

# 4. Schwierigkeiten




