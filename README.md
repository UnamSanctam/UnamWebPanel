<img src="https://github.com/UnamSanctam/UnamWebPanel/blob/master/UnamWebPanel.png?raw=true">

# UnamWebPanel v1.8.0

A web panel currently used to optionally monitor and manage the [SilentCryptoMiner](https://github.com/UnamSanctam/SilentCryptoMiner). Might support any other projects I release in the future as well.

## Setup

The panel is quite easy to set up, the only real requirement is a  web server with PHP support. You can either host it yourself using Apache or something similar, or you can use any free or paid online webhost.

Here are some simple steps to get started:
1. Download the panel files and open the UnamWebPanel/config.php file with a text editor.
2. Change the password  at`$config['password'] = 'UnamSanctam';` to whatever password you wish to use (only change the `UnamSanctam` text to your own password), this is the password used to access the web panel.
3. Upload the contents of the UnamWebPanel folder to your webhosts "public_html" folder or the respective folder for your specific webhost.
4. Your web panel should now be up and running, you can browse to the URL or IP of your website and you should see the login screen if everything went correctly.

If you wish to add the web panel to the SilentCryptoMiner then enter the following website URL: `http://yourwebsite.com/api/endpoint.php` (replace yourwebsite.com with your URL or IP, also make sure to use the correct `http` or `https` protocol depending on if your site has SSL "support" or not) into the `API Endpoint URL` field inside the miner.

If you use something other than Apache or IIS to host the web panel then you should check if your database file is exposed to the internet, you can check it by visting the URL `http://yourwebsite.com/db/unamwebpanel.db` (replace yourwebsite.com with your URL or IP), if it says forbidden or doesn't display anything then your database is secured.

### For local testing

If you simply want to set up a local web panel for testing then here are some simple steps to do so.
1. Download XAMPP and install it
2. Extract the UnamWebPanel files into `C:\xampp\htdocs` (or wherever you installed it)
3. Open the XAMPP Control Panel and press the "Start" button next to "Apache"
4. Browse to http://localhost/ and you should be able to login (default password `UnamSanctam`) and view the web panel

Then if you want any local miners on your computer to connect to it then enter http://localhost/api/endpoint.php into the "API Endpoint URL" of the miners in the miner builder.

## Supported Projects

* [SilentCryptoMiner](https://github.com/UnamSanctam/SilentCryptoMiner)

## Changelog

### 1.8.0 (06/02/2024)
* Rewrote almost all the code of the web panel to make it easier for others to edit
* Added new functionality called IP Blocking with its own page that allows blocking IP addresses from connecting to the web panel
* Added constant verification of the hashed password during login sessions, ensuring that any change to the password results in all users being logged out
* Added an error log option to the config for enabling or disabling error logging
* Changed the error logging function to only log vital error information
* Rewrote datatables server side class to be shorter, more optimized and safer
* Added further XSS mitigation to both the endpoint, the pages, the APIs and the datatable server side output
* Added many strict headers that improve browser security
* Added meta tags and headers alongside the current robots.txt to further discourage search engine indexing
* Added line graph showing the total amount of online miners over time based on hashrate history reporting
* Added pie graph showing the amount of GPU Miners and CPU Miners
* Added pie graph showing the statuses of the miners
* Remade some of the statistics to have better clarity
* Added automatic SQLite database and database folder permissions checks that will display an error if they do not have the required permissions
* Merged and removed many unused or unnecessary assets
* Replaced SweetAlert2 with another plugin due to its malicious behaviour on .ru, .su, .by and .рф domains
* Changed miner types to the more clear CPU Miner and GPU Miner types
* Added new miner datatable field called Extra Data that will receive data such as resource reporting in future miner versions
* Added logout button to the top navigation menu
* Added all missing translations for all supported languages
* Added language selection to the login page
* Changed the terminology from Active to Mining
* Improved the miner endpoint performance
### 1.7.1 (06/01/2023)
* Moved miner statistics to a new "Statistics" page
* Added more statistics such as GPU, CPU, Version and Algorithm graphs
* Fixed "Hide Offline Miners" bug
* Reworked endpoint again for better performance
* Added inactive journal size limit and higher cache limit
* Reduced WAL file growth and added cleaning
* Changed SQLite synchronous mode to OFF for higher performance
* Added Spanish translation (Xeneht)
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

[You can view the full Changelog here](CHANGELOG.md)

## Author

* **Unam Sanctam**

## Contributors

* **[Kolhax](https://github.com/Kolhax)** - French Translation
* **[leisefuxX](https://github.com/leisefuxX)** - German Translation
* **[Werlrlivx](https://github.com/Werlrlivx)** - Polish Translation
* **[marat2509](https://github.com/marat2509)** - Russian Translation
* **[Zem0rt](https://github.com/Zem0rt)** - Ukrainian Translation
* **[Xeneht](https://github.com/Xeneht)** - Spanish Translation

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
