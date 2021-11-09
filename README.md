<img src="https://github.com/UnamSanctam/UnamWebPanel/blob/master/UnamWebPanel.png?raw=true">

# UnamWebPanel v1.2

A web panel currently used to optionally monitor and manage the [SilentCryptoMiner](https://github.com/UnamSanctam/SilentCryptoMiner). Might support any other projects I release in the future as well.

## Setup

The panel is quite easy to set up, the only real requirement is a  web server with PHP support. You can either host it yourself using Apache or something similar, or you can use any free or paid online webhost. Nearly all webhosts has PHP support so it should not be difficult to find one you can use.

Here are some simple steps to get started:
1. Download the panel files and open the UnamWebPane\config.php file with a text editor.
2. Change the `$config['password']` to whatever password you wish to use, this is the password used to access the web panel.
3. Upload the contents of the UnamWebPanel folder to your webhosts "public_html" folder or the respective folder for your specific webhost.
4. Your web panel should now be up and running, you can browse to the URL or IP of your website and you should see the login screen if everything went correctly.

If you wish to add the web panel to the SilentCryptoMiner then enter the following website URL: `http://yourwebsite.com/api/endpoint.php` (replace yourwebsite.com with your URL or IP, also make sure to use the correct `http` or `https` protocol depending on if your site has SSL "support" or not) into the `API Endpoint URL` field inside the miner.

If you use something other than Apache or IIS to host the web panel then you should check if your database file is exposed to the internet, you can check it by visting the URL `http://yourwebsite.com/unamwebpanel.db` (replace yourwebsite.com with your URL or IP), if it says forbidden or doesn't display anything then your database is secured.

## Wiki

You can find the wiki [here](https://github.com/UnamSanctam/SilentCryptoMiner/wiki) or at the top of the page. (In progress)

## Supported Projects

* [SilentCryptoMiner](https://github.com/UnamSanctam/SilentCryptoMiner)

## Changelog

### v1.2 (09/11/2021)
* Added GPU and CPU to the miners datatable
* Added GPU and CPU to the database
### v1.1 (09/11/2021)
* Added unamwebpanel.db into the .htaccess and web.config files as a forbidden path to secure the SQLite database on Apache and IIS servers without having to place the database in a non-public folder
* Removed recommendation to move the database file to a non-public folder due to the added protection files for Apache and IIS
* Downgraded web panels required PHP version to 7.0
* Added miner type to the miners datatable to make it easier to differentiate what base miner it is using
* Fixed broken miner status condition
### v1.0 (08/11/2021)
* Initial release

[You can view the full Changelog here](CHANGELOG.md)

## Author

* **Unam Sanctam**

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
