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
### v1.4.2 (01/04/2022)
* Added French translation (Kolhax)
* Added German translation (leisefuxX)
### v1.4.1 (11/01/2022)
* Fixed null hashrate datatable formatting error
* Changed project versioning to x.x.x formatting
### v1.4.0 (09/01/2022)
* Added functionality to remove miners from the list
* Added JSON validation functionality to warn when saving incorrect configurations
* Added username display into the miner list
* Added "Auto refresh" toggle button for automatic miner list refreshing
* Added robots.txt file to stop search engines from indexing the web panel
* Added directory listing block in .htaccess for better privacy
* Added previously ignored "Logs" folder back
* Changed "Default" configuraiton into "Default ethminer" and "Default xmrig" configurations to allow different default configurations for the two different miners
* Fixed possible database "corruption" when null hashrates were submitted
* Fixed broken miner searching and sorting
### v1.3.0 (09/11/2021)
* Added Unique ID generation on the panel side instead of the miner side
* Changed all file calls to be relative to allow easier deployment of the panel in subfolders
* Removed unnecessary configuration options due to everything being relative now
### v1.2.0 (09/11/2021)
* Added GPU and CPU to the miners datatable
* Added GPU and CPU to the database
### v1.1.0 (09/11/2021)
* Added unamwebpanel.db into the .htaccess and web.config files as a forbidden path to secure the SQLite database on Apache and IIS servers without having to place the database in a non-public folder
* Removed recommendation to move the database file to a non-public folder due to the added protection files for Apache and IIS
* Downgraded web panels required PHP version to 7.0
* Added miner type to the miners datatable to make it easier to differentiate what base miner it is using
* Fixed broken miner status condition
### v1.0.0 (08/11/2021)
* Initial release