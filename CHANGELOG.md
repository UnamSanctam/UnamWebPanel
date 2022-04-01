### v1.4.2 (01/01/2022)
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