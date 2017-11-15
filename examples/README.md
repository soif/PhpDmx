# HowTo fastly use the examples

While phpDmx can also send Art-Net messages, using the OLA transport is the preferred way to test/use it.
Using OLA allows to have maintain a DMX state at anytime, thus allowing to send some "Merge" commands (ie just change a fixture or channel, without resending the whole DMX state). 
OLA, also provide a nice DMX Monitor that is really useful for debugging.


## 1) Install OLA
 On recent a Debian (tested on Jessie) / Ubuntu (tested on 17.10)  distribution, [OLA](https://www.openlighting.org/ola/) can be easely installed with just :
 `sudo apt-get install ola`.

*BTW if your distribution is not recent, the Web Server on port 9090 is NOT launched. The fix is to upgrade your distribution!*


## 2) Create a Universe in OLA
- In a browser navigate to `http://IP_OF_THE_OLA_SERVER:9090`
- Click the "Add Universe" button.
- Set *Universe ID* to **1** (to match the example config).
- Set *Universe Name* to anything you like.
- Choose one Port (with direction "output'). Ie : 
	- '**Dummy**' to just visualize DMX channels
	- '**Anyma USB Device**' when connecting a cheap USB/DMX adapter like [this one](https://www.aliexpress.com/item/x/32725727772.html).
	- '**ArtNet [*IP_OF_THE_OLA_SERVER*]**' if you want to send Art-Net messages to an Art-Net gateway.
	- Any other Port if you're already mastering OLA.
- Click the "Save" button.

*BTW: if you've added a USB device AFTER installing OLA, you have to restart OLA, in order to let him see the new USB device : `sudo service olad restart`.*


## 3) Make sure that OLA is working
- In you browser, click on "Universes" in the left column.
- Your newly created Universe should appear, click on it.
- Now select the "DMX Console" Tab, an see if your connected fixtures are receiving your fader orders.
- If all goes well, go to the "DMX Monitor" Tab, to watch the DMX value.

 
## 4) Playing with the Examples
- You might change the **config.php** file according to your own settings:
    - Change **$cfg['server_ip']** to your *IP_OF_THE_OLA_SERVER*.
    - Change **$cfg['universe']** to your *Universe ID* as set in OLA. 
    - Change **$cfg['dmx_address']** according to the fixtures connected to the DMX port. 

You should start with the **api.php** example, and test some queries (commented in the code)
