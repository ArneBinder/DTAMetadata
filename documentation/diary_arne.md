
2014-09-19
 - extract-meta.pl: 
	o tabs will be replaced by a single space (print warning, which doesnt prevent the generation of the files)
	o leading and trailing spaces will be deleted also in personData
### 
 - DTAMetadata
	> php: if intl isnt availible, use "$ php composer.phar require symfony/icu ~1.0" instead of "php composer.phar install"
	o modified massages.de.php 
	> Form/Data/PublicationType.php defines the fields presented in the book edit form, e.g

2014-09-21
 o modified dumpConversion: added recreation of databases (mysql & postgres)
 > usage of xdebug
 
2014-09-22
 o deployed current version of DTAMetadata on khan (without updating the database content)
	+ git clone git@github.com:ArneBinder/DTAMetadata.git DTAProjectDB
	+ cd DTAProjectDB/
	+ php composer.phar install
		(using carlwitt's parameter.yml)
	+ php app/console propel:model:build
	+ chmod app/cache -R 077
	+ chmod app/logs -R 077
	