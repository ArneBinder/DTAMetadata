
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
 o modified dumpConversion: added recreation of databases (mysql 
 postgres)
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
 
2014-10-24
 o created win script to back up IDE (PhpStorm) data when they are changed. The environment variable IDEA_BACKUP_PATH has to be set for usage.
 o changed date_widget: displayed in one row
 o added text_widget: set width to 350px
	
2014-10-28
 o To dump only the available variable keys use: {{ dump(_context|keys) }} 
	source: https://www.drupal.org/node/1906780

2014-10-29	
 o tasks as collapsible panels
 
2014-10-30
 o setup of dev enviroment at new win pc:
	- install git
	- install php 
		+ add install path to win env var PATH
		+ modified php.ini (php.ini-production) according to http://php.net/manual/de/install.windows.manual.php
			> required extensions: php_bz2.dll, php_curl.dll, php_mbstring.dll, php_exif.dll, php_gd2.dll, php_gettext.dll, php_intl.dll, php_mysql.dll, php_openssl.dll, php_pdo_mysql.dll, php_pdo_pgsql.dll, php_pgsql.dll
			> date.timezone=Europe/Berlin
	- install postgres: 
		+ add install path to win env var PATH
		+ add windows user postgres
		+ add env var PGDATA (e.g. C:\Dokumente und Einstellungen\postgres\DATA) --> switch to this user to generate folders 
 restart
		+ runas /user:postgres "initdb --encoding=UTF8" to generate db files
		+ create database with name <db_name> via PgAdmin (replace <db_name>)
		+ runas /user:postgres "pgsql <db_name> < <dump_file>" (replace <db_name> and <dump_file>)	
	- install phpstorm
		+ install plugins: symfony2
		+ clone from github
		+ backup old .idea folder in IDEA_BACKUP_PATH and add windows env var IDEA_BACKUP_PATH (e.g. H:\IDEA_BACKUPS) 
 o trying to pass data to task_row
	+ Hint: http://stackoverflow.com/questions/12356025/how-to-pass-an-array-from-symfony-2-controller-to-a-twig-template
	+ Hint2: http://toni.uebernickel.info/2011/11/25/how-to-extend-form-fields-in-symfony2.html

2014-11-03	
 o PublicationType: 
	+ legacy_dwds_category2 and ...subcategory2 aren't required anymore
	+ wwwready (Veröffentlichungsstatus) set to min:0 and max:3
 o tried to use dateTimePicker (collot_datetime)
 o tried to pass data to form builder (http://stackoverflow.com/questions/7807388/passing-data-from-controller-to-type-symfony2?lq=1)
	+ furthermore: setDefaultOptions?
 o validate startDate and endDate: via Callback? or Expression (http://symfony.com/doc/current/reference/constraints/Expression.html)?
	+ tried to prepare using expressions
	+ have a look to https://github.com/symfony/symfony/issues/7726
	
2014-11-04
 o added option to DynamicCollection: asPanel (default:false)
 o collot_datetime:
   + javascript doesnt work because it's embedded? https://github.com/genemu/GenemuFormBundle/issues/285
		https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/template.md
   + http://symfony.com/doc/current/cookbook/form/form_collections.html
   + or take this http://eonasdan.github.io/bootstrap-datetimepicker/#example9

2014-11-05
 o access data in twig template via form.VALUE (form.year) or form.parent.VALUE to get data of embedding form. access twig data of embedding form (e.g. id) via form.parent.vars.VALUE (e.g. form.parent.vars.id)
 o form type extensions! (http://symfony.com/doc/current/cookbook/form/create_form_type_extension.html)
 o modified start_date/end_date: it's not possible to select a start_date after an end_date and vice versa :-)
 o get closed from task_row: dump(form.parent.vars.value[0].closed). no id doesn't work right...
 o validation:
	+ http://stackoverflow.com/questions/21486538/symfony2-how-to-use-constraints-on-custom-compound-form-type
	+ http://symfony2-document.readthedocs.org/en/latest/cookbook/validation/custom_constraint.html
	+ http://stackoverflow.com/questions/16936437/validate-a-collection-field-type-in-symfony-2-with-allowextrafields-true
 o read http://symfony.com/blog/form-goodness-in-symfony-2-1
 
2014-11-06
 o added regex constraint to disallow tabs in input fields
 o set default value (for start_date): http://stackoverflow.com/questions/17986481/set-default-values-using-form-classes-in-symfony-2
	+ second approach of second answer seems to work... but doesnt: data' => (isset($options['data']) 

 $options['data']->getStartDate() !== null) ? $options['data']->getStartDate() : new \DateTime('today')
	+ try this: http://symfony.com/doc/current/cookbook/form/dynamic_form_modification.html#cookbook-form-events-underlying-data
		--> worked! (via event listener)
		
2014-11-10
 o twig template globals are defined in config.yml/twig/globals
 o changed layout to two columns
 o try to merge duplicated spaces:
	+ http://stackoverflow.com/questions/11348370/symfony2-how-to-modify-a-form-value-before-validation
	+ via dataTransformer http://symfony.com/doc/master/cookbook/form/data_transformers.html
	+ or FormEvents http://symfony.com/doc/master/cookbook/form/dynamic_form_modification.html 
		--> works. but no feedback for the user is implemented yet!
 o prepared constraint for Language: has to begin with a capital letter
 
2014-11-12
 o Home redirects to showAll/Publication
 o added Button "<Publicationstype> neu anlegen" to listViewWithoptions (button is displayed ahead the publication data overview)
 o modified panel head of task: splitted clickable (to expand) area --> disabled data-toggle at "closed" etc.
	+ size of placeholder span not correct yet!
	
2014-11-12
 o fixed delete button for tasks (wrong position): position of containing element has to be absolute, fixed or relative to use 'position: absolute' in child element correctly (finally added position:relative to .panel in forms.css)
 o Button "<Publikationstyp> neu anlegen" next to listView: if className='Publication' (all publications are displayed), it will be "BUCH neu anlegen" 
 
2014-11-17
 o dataTable options are set in base.html.twig http://www.datatables.net/
 
2014-11-18
 o prepared to move 'neu anlegen' button into the dropdown menu. 
 
2014-12-09
 o select2 dropdown: moved 'neu anlegen' button into the dropdown menu. 
	But dropdown content doesn't refresh! 

2014-12-10
 o fixed dropdown refresh issue
 o <publication_type> 
 dropdown neu anlegen button: adapted style from genericViewAll.html.twig (used by 'Verlag', etc.)
 o ORMController.php/findPaginatedSortedFiltered: add filtering logic here
 o reference for datatables 1.9: http://legacy.datatables.net/usage/server-side, upgrading names: http://datatables.net/upgrade/1.10-convert
 o switched to datatables 1.10.4 (shallow): breaks layout of bootstrap style buttons, but better positioning of column heads
 o switched to datatables 1.10.4 (deep): pagination works
 
2014-12-11
 o fixed dropdown add button: returned from <a/> to <button/>, otherwise an option is selected which will reload the page and close the modal
 o DataTablesBundle for Symfony2/Doctrine: http://code.rbsolutions.us/datatables/
 o DynamicCollection: changed asPanel(boolean) to displayAs(string) with possible values: 'list'(default) and 'panel', to add value 'tag' or similar later
 
2014-12-16
 o symfony select2: https://github.com/kbond/ZenstruckFormBundle
 o sets a field as rendered: {% do form.field_name.setRendered %}
 
2014-12-16
 o trying to get column names (DataDomainController.php): http://propelorm.org/documentation/cookbook/runtime-introspection.html
 
2014-12-23
 o calling getColumns() from PublicationTableMap directly... but context error (static vs not static)
 o http://stackoverflow.com/questions/20688246/how-do-i-check-if-columns-are-valid-in-propel --> gives all columns in the model
 
2014-12-29
 o deleted doctrine bundle (composer.json)
 o updated monolog-symfony bridge to ver 2.4 (tried to enable log console output... doesn't work yet)
 o getting accessors for displayed columns (ORMController). How to do sorting considering an accessor?

2014-12-30
 o modify row_table_view behavior http://propelorm.org/documentation/06-behaviors.html 
 o modified TableRowView: moved query and embedColumns to a new parameter "attribute*" (the name is arbitrary and can be changed). New syntax for the value: "query:<queryString>" or "embedColumns:<tableName>". Furthermore multiple parameters can be added to one caption by concatenating them: e.g name="Ort" value="accessor:getPlace orderBy:<>".
 o ordering for columns without an acessor works
 o check out virtual columns http://propelorm.org/Propel/reference/model-criteria.html#adding-columns
 o handle resolveEmbeddedColumns and resolveRepresentativeColumn for ordering!
 
2015-01-06
 o index of ordering column is shifted by inserted column via reconstructed_flaggable WRONG

2015-01-06 
 o index is shifted because ID column is invisible via listViewWithOptions.html.twig
 o why is the ID column accessible? It's added DataDomainController/genericDataTablesDataSourceAction. Why not to add to $tableRowViewCaptions?
 
2015-01-12
 o Added parameter $hiddenIdColumn to findPaginatedSortedFiltered in order to handle the index shift. 
 o sorting for _not_ embedded/representive columns works
 
2015-01-13
 o sorting of embedded columns is working.
 o prepared to generate orderBy functions. added templates tableRowOrderColumnFunction and tableRowViewQueryMethods to the table_row_view behavior. 
 
2015-01-14
 o reverted generation of sort methods via table_row_view behavior. could not declare required classes in the Query classes.
 o modified table_row_view: changed tableRowViewAccessors to static and added filterColumns array which is generated by the schema. column captions in the table_row_view block have to be tagged with "filterColumn:" e.g. <parameter name="erster Autor" value="accessor:getFirstAuthorName filterColumn:"/>
 o implemented filtering for the different publication types 
 o implemented filtering for persons

2015-01-15
 o pagination bar updates after filtering
 o simplified genericDataTablesDataSourceAction of ORMController and DataController, added the boolean parameter $addIdColumn, which is passed to the underlying methods (getSortedFilteredQuery and formatAsArray)
 
2015-01-19
 o Filter input will be expanded via '*'. Or via '0' and '9', if it is a year (SQL 'IN' statement).
 o Added translation strings (lables and captions) to messages.de.php. All strings of 'Publication' are translated.
 o The filter box and pagination length are displayed above the table. But still on incorrect sides!
 o Added column adjustment after resizing.
 
2015-01-19
 o imported database dump from server (khan) into local database dtadb_khan_utf8
 o all child volumes are linked in it's multi volume. still to do: enable editing! 
 o Hint: to get object data in twig template, e.g: form.vars.value.getPublication().getTitleString()
 o added links to all entries which are objects in table view
 
2015-01-22
 o implemented conversion of Book <-> Volume
 o got new database dumps (mysql and postgres) from Frank. Not yet imported!
 o current TODO: 
	- import new data
	   modify script to use psql-dump: 
	   > remove "1" from IMCREMENT 1 (?)
	   > adapt CONCAT
	   > adapt show columns / tables (http://www.linuxscrew.com/2009/07/03/postgresql-show-tables-show-databases-show-columns/)
	   > adapt SUBSTRING_INDEX
	   > adapt FIND_IN_SET
	   > adapt NULLIF(... '0000-00-00 00:00:00')
	   > adapt _utf8
	- add padding (above search box) DONE
	- add missing "Herausgeber" (editing form) DONE
	- modify "Buchband hinzufügen": show List of volumes, don't create a new one! 
	- implement MultiVolume/removeVolume (DONE not tested yet!)
	
2015-01-27
 o adapted schema files
 o started to modify convertAction()

2015-01-28
 o adapted cleanUpOldDatabase() and checkOldDatabase() for PostgreSQL
 
2015-01-29
 o adapted convertUsers()
 o adapted convertPublications() TODO: check legacy_publication_types J, N and NULL!
 o adapted remaining converting functions
 o current TODO:
    - fix convertMultiVolume
    - fix doublets in table_row_view of Publications 
  
2015-02-09
 o to change owner of all tables in a database (fixes access error on server):
        for tbl in `psql -qAt -c "select tablename from pg_tables where schemaname = 'public';" dtametadata_new` ; do  psql -c "alter table $tbl owner to dtametadata" dtametadata_new ; done
 o new dbcreate comand
		psql -d postgres -c "CREATE DATABASE dtametadata_new2 OWNER = dtametadata TEMPLATE = template0 ENCODING = 'UTF8'"
 o preconditions to use the dumpConversionScript on khan:
		CREATE ROLE "www-data" with CREATEDB LOGIN IN ROLE dtametadata;
		CREATE DATABASE "www-data";
		ALTER ROLE dtametadata WITH SUPERUSER;
 o to get all sequences in current db (http://www.alberton.info/postgresql_meta_info.html):
		SELECT relname
		FROM pg_class
		WHERE relkind = 'S'
		AND relnamespace IN (
			SELECT oid
			FROM pg_namespace
			WHERE nspname NOT LIKE 'pg_%'
			AND nspname != 'information_schema'
		) order by relname;
 o conversion: CHECK FOR "FAILED" IN MESSAGES! --> displayed as "error"
 o current ToDo: 
		- fix convertMultiVolumes
		- fix duplicated entries in table_row_view (all publications)
 
2015-02-12
 o Invalid PDO query does not return an error: 
     http://stackoverflow.com/questions/6203503/invalid-pdo-query-does-not-return-an-error 
     and 
     https://bugs.php.net/bug.php?id=61613
 
 
 TODO:
 o Tab-Constraint DONE
 o merge multiple spaces DONE
 
 o Fix delete task buttons DONE
 o Fix display of validation errors
 o Fix sortable tables DONE
 
 o add confirm form when deleting entries
 o add genre and tag import from old database DEPRICATED?
 o change tag list to tag field
 
 o add filter to search for entries FONE
 
 o use inherit_data (http://symfony.com/doc/current/cookbook/form/inherit_data_option.html)