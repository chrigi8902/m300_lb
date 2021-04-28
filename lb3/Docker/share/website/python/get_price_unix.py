import sys
import time
import logging
from watchdog.observers import Observer
from watchdog.events import LoggingEventHandler

class Event(LoggingEventHandler):
    def dispatch(self, event):
        import sys
        from yahoo_fin import stock_info as si

        #stock = str(sys.argv[1])
        file_price = "/usr/src/app/tmp/price.txt"
        file_stock = "/usr/src/app/tmp/stock.txt"
        
        #price_str = str(price)
        if (event.src_path != "./tmp/price.txt") and (event.src_path != "./tmp/stock.txt") and (event.src_path != "./tmp"):
                stock_path = event.src_path
                stock = stock_path.replace("./tmp/", "")
                price = si.get_live_price(stock)
                price_str = str(price)

                print (price_str)
                print (stock)
                with open(file_price, 'w') as fileowrite:
                        fileowrite.write(price_str)
                with open(file_stock, 'w') as fileowrite:
                        fileowrite.write(stock)
        else:
                print("\\nothing")

if __name__ == "__main__":
    logging.basicConfig(level=logging.INFO,
                        format='%(asctime)s - %(message)s',
                        datefmt='%Y-%m-%d %H:%M:%S')
    path = sys.argv[1] if len(sys.argv) > 1 else '.'
    event_handler = Event()
    observer = Observer()
    observer.schedule(event_handler, path, recursive=True)
    observer.start()
    try:
        while True:
            time.sleep(1)
    except KeyboardInterrupt:
        observer.stop()
    observer.join()