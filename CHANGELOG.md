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