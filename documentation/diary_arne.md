
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
 o usage of xdebug:
	+ configure xdebug: http://www.jetbrains.com/phpstorm/webhelp/configuring-xdebug.html
	+ enable debugging:http://confluence.jetbrains.com/display/PhpStorm/Zero-configuration+Web+Application+Debugging+with+Xdebug+and+PhpStorm
 
2014-09-22
 o deployed current version of DTAMetadata on khan (without updating the database content)
	+ git clone git@github.com:ArneBinder/DTAMetadata.git DTAProjectDB
	+ cd DTAProjectDB/
	+ php composer.phar install
		(using carlwitt's parameter.yml)
	+ php app/console propel:model:build
	+ chmod app/cache -R 077
	+ chmod app/logs -R 077
 o tried to fix display error: edit or create publication
	+ seems to be a problem concerning xdebug: disabled local, rebuild (->Installationsanweisung) and connected to empty database (dtab3) and it works again (just local)
 o WARNING: IF DUMP CONVERSION ABORTS AND IS RESTARTED WITHOUT MANUAL MOVING THE PRODUCTION SCHEMA FILES BACK IN PLACE, THE DUMPCONVERSION SCHEMA FILES ARE CONSIDERED AS PRODUCTION FILES!!!
    --> don't copy current schema files to folder schemas_final anymore, but take schemas from schemas_final in the end
	
2014-10-21
 o added aliases at khan: cleardev (clears symfony dev cache) and reapache (restarts the apache server)
 o still trying to fix disply form error
	+ tried to rebuild everything at the server (except complete dumpconversion)
	+ imported several (different encodings: e.g. utf8; different methods: via pgAdmin, pg_dump) data dumps from good working local version to the server
	--> disabling xdebug did it! (but just hacky style via renaming the file /etc/php5/conf.d/20-xdebug.ini to ...20-xdebug.bk)
 o still encoding errors
    + local: WIN1252
    --> have to start new dumpConversion (encoding for rebuilding the postgres database is already set to UTF8) --> no changes --> using again Carl's converted dump (database dtametadata)
	
2014-10-22
 o getting into layout:
   + changed Publication layout: PublicationType.php sets the elements presented, so switched type of some elements to change there appearance ('text' --> null) 
   + form_div_layout defines the appearance of singel elmentes: started to modifie the date_widget to display the choice boxes in one row (not yet ready)
   + could form_div_layut be used to define the behavior of textareas and so on?
   + HINT: modals. useable for checks before deleting/canceling?

2014-10-23
 o changeoradd publication is different from specialpublication (Book, ...) --> different layout!
 o added autoresize for textarea (but adds a new line in every textarea)
	
	