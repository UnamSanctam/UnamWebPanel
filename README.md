<img src="https://github.com/UnamSanctam/UnamWebPanel/blob/master/UnamWebPanel.png?raw=true">

# UnamWebPanel v1.7.0

A web panel currently used to optionally monitor and manage the [SilentCryptoMiner](https://github.com/UnamSanctam/SilentCryptoMiner). Might support any other projects I release in the future as well.

## Setup

The panel is quite easy to set up, the only real requirement is a  web server with PHP support. You can either host it yourself using Apache or something similar, or you can use any free or paid online webhost. Nearly all webhosts has PHP support so it should not be difficult to find one you can use.

Here are some simple steps to get started:
1. Download the panel files and open the UnamWebPane\config.php file with a text editor.
2. Change the `$config['password'] = 'UnamSanctam';` (change `UnamSanctam` to your own password) to whatever password you wish to use, this is the password used to access the web panel.
3. Upload the contents of the UnamWebPanel folder to your webhosts "public_html" folder or the respective folder for your specific webhost.
4. Your web panel should now be up and running, you can browse to the URL or IP of your website and you should see the login screen if everything went correctly.

If you wish to add the web panel to the SilentCryptoMiner then enter the following website URL: `http://yourwebsite.com/api/endpoint.php` (replace yourwebsite.com with your URL or IP, also make sure to use the correct `http` or `https` protocol depending on if your site has SSL "support" or not) into the `API Endpoint URL` field inside the miner.

If you use something other than Apache or IIS to host the web panel then you should check if your database file is exposed to the internet, you can check it by visting the URL `http://yourwebsite.com/unamwebpanel.db` (replace yourwebsite.com with your URL or IP), if it says forbidden or doesn't display anything then your database is secured.

### For local testing

If you simply want to set up a local web panel for testing then here are some simple steps to do so.
1. Download XAMPP and install it
2. Extract the UnamWebPanel files into `C:\xampp\htdocs` (or wherever you installed it)
3. Open the XAMPP Control Panel and press the "Start" button next to "Apache"
4. Browse to http://localhost/ and you should be able to login (default password `UnamSanctam`) and view the web panel

Then if you want any local miners on your computer to connect to it then enter http://localhost/api/endpoint.php into the "API Endpoint URL" of the miners in the miner builder.

## Wiki

You can find the wiki [here](https://github.com/UnamSanctam/SilentCryptoMiner/wiki) or at the top of the page. (In progress)

## Supported Projects

* [SilentCryptoMiner](https://github.com/UnamSanctam/SilentCryptoMiner)

## Changelog

### 1.7.0 (25/12/2022)
* Greatly improved database performance
* Greatly improved endpoint performance
* Added configurable hashrate history feature
* Added "Total Hashrate" graphs for each algorithm
* Added individual "Hashrate History" to each miner
* Added miner status statistics
* Fixed datatable width scaling
* Added "Hide Offline Miners" option
* Fixed status priority for offline and error statuses
* Added Russian translation (marat2509)
* Added Ukrainian translation (Zem0rt)
### 1.6.0 (01/06/2022)
* Added support for reporting the executable name of the program that triggered "Stealth" and displaying it in the status text
* Added offline miner removal tool which removes miners who have been offline for longer than the chosen number of days
* Added support for new miner ID per build to allow for running multiple miners of the same type at the same time
* Added Polish translation (Werlrlivx)
* Changed database settings to allow for better performance during large amounts of activity
* Changed offline status time threshold from five minutes to three minutes
* Changed endpoint text when the request isn't from the miner to reduce confusion
* Changed string sanitation away from FILTER_SANITIZE_STRING due to PHP 8.1 deprication
* Moved database to its own folder to allow for broader database file blocks
### 1.5.0 (01/05/2022)
* Added new field "Version" that shows the miner version
* Added new field "Active Window" that shows the currently active foreground windows title
* Added new field "Run Time" that shows how long the current session of the miner has been running for
* Added "First Connection" field that shows the date and time when the miner first connected
* Added new miner statuses "Starting" and "Error"
* Added text next to the "Offline" status that shows how long the miner has been offline
* Added error text when an XMR miner cannot connect to its pool
* Added German and French datatable translation files
* Fixed miner table ordering

[You can view the full Changelog here](CHANGELOG.md)

## Author

* **Unam Sanctam**

## Contributors

* **[Kolhax](https://github.com/Kolhax)** - French Translation
* **[leisefuxX](https://github.com/leisefuxX)** - German Translation
* **[Werlrlivx](https://github.com/Werlrlivx)** - Polish Translation
* **[marat2509](https://github.com/marat2509)** - Russian Translation
* **[Zem0rt](https://github.com/Zem0rt)** - Ukrainian Translation

## Disclaimer

I, the creator, am not responsible for any actions, and or damages, caused by this software.

You bear the full responsibility of your actions and acknowledge that this software was created for educational purposes only.

This software's main purpose is NOT to be used maliciously, or on any system that you do not own, or have the right to use.

By using this software, you automatically agree to the above.

## License

This project is licensed under the MIT License - see the [LICENSE](/LICENSE) file for details

## Donate

XMR: 8BbApiMBHsPVKkLEP4rVbST6CnSb3LW2gXygngCi5MGiBuwAFh6bFEzT3UTufiCehFK7fNvAjs5Tv6BKYa6w8hwaSjnsg2N

BTC: bc1q26uwkzv6rgsxqnlapkj908l68vl0j753r46wvq

ETH: 0x40E5bB6C61871776f062d296707Ab7B7aEfFe1Cd

ETC: 0xd513e80ECc106A1BA7Fa15F1C590Ef3c4cd16CF3

RVN: RFsUdiQJ31Zr1pKZmJ3fXqH6Gomtjd2cQe

LINK: 0x40E5bB6C61871776f062d296707Ab7B7aEfFe1Cd

DOGE: DNgFYHnZBVLw9FMdRYTQ7vD4X9w3AsWFRv

LTC: Lbr8RLB7wSaDSQtg8VEgfdqKoxqPq5Lkn3
