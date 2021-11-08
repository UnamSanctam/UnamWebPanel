<img src="https://github.com/UnamSanctam/UnamWebPanel/blob/master/UnamWebPanel.png?raw=true">

# UnamWebPanel v1.0

A web panel currently used to optionally monitor and manage the [SilentCryptoMiner](https://github.com/UnamSanctam/SilentCryptoMiner). Might support any other projects I release in the future as well.

## Setup

The panel is quite easy to set up, the only real requirement is a  web server with PHP support. You can either host it yourself using Apache or something similar, or you can use any free or paid online webhost. Nearly all webhosts has PHP support so it should not be difficult to find one you can use.

Here are some simple steps to get started:
1. Download the panel files and open the UnamWebPane\config.php file with a text editor.
2. Change the `$config['password']` to whatever password you wish to use, this is the password used to access the web panel.
3. Change the `$config['db_file']` to where you will place the database file (unamwebpanel.db), this file should be placed in some location that is not a part of the exposed web server so that it cannot be downloaded by everyone. Some online webhosts have a folder called "public_html" that is exposed to the public, you can probably place the database file outside the "public_html" folder and then enter `dirname(__FILE__, 1)."/unamwebpanel.db"` into `$config['db_file']` for it to use the database file one folder back from the location of config.php.
4. Upload the contents of the UnamWebPanel folder to your webhosts "public_html" folder or the respective folder for your specific webhost, then upload the unamwebpanel.db to the location that you entered in the config.php, usually just outside the "public_html" folder.
5. Your web panel should now be up and running, you can browse to the URL or IP of your website and you should see the login screen if everything went correctly.

If you wish to add the web panel to the SilentCryptoMiner then enter the following website URL: `WEBSITEURL/api/endpoint.php` (replace WEBSITEURL with your URL or IP) into the `API Endpoint URL` field inside the miner.

## Wiki

You can find the wiki [here](https://github.com/UnamSanctam/SilentCryptoMiner/wiki) or at the top of the page. (In progress)

## Supported Projects

* [SilentCryptoMiner](https://github.com/UnamSanctam/SilentCryptoMiner)

## Changelog

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