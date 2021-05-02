# Anleitung für die LB 3

Folgendermassen sollte der Folder welcher das docker-compose.yml enthält aufgebaut sein:

* [python/](.\docker\python)
  * [Dockerfile](.\docker\python\Dockerfile)
  * [requirements.txt](.\docker\python\requirements.txt)
* [share/](.\docker\share)
  * [website/](.\docker\share\website)
    * [css/](.\docker\share\website\css)
      * [style.css](.\docker\share\website\css\style.css)
    * [pictures/](.\docker\share\website\pictures)
      * [background.jpg](.\docker\share\website\pictures\background.jpg)
    * [python/](.\docker\share\website\python)
      * [tmp/](.\docker\share\website\python\tmp)
        * [price.txt](.\docker\share\website\python\tmp\price.txt)
        * [stock.txt](.\docker\share\website\python\tmp\stock.txt)
      * [get_price_unix.py](.\docker\share\website\python\get_price_unix.py)
    * [.htaccess](.\docker\share\website\.htaccess)
    * [.htpasswd](.\docker\share\website\.htpasswd)
    * [index.php](.\docker\share\website\index.php)
* [website/](.\docker\website)
  * [apache2.conf](.\docker\website\apache2.conf)
  * [Dockerfile](.\docker\website\Dockerfile)
* [docker-compose.yml](.\docker\docker-compose.yml)
</br>

Anschliessend kann man das docker-compose starten

        docker-compose up -d

