
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
		+ add env var PGDATA (e.g. C:\Dokumente und Einstellungen\postgres\DATA) --> switch to this user to generate folders & restart
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
	+ second approach of second answer seems to work... but doesnt: data' => (isset($options['data']) && $options['data']->getStartDate() !== null) ? $options['data']->getStartDate() : new \DateTime('today')
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
 
 TODO:
 o Tab-Constraint DONE
 o merge multiple spaces
 
 o Fix delete task buttons DONE
 o Fix display of validation errors
 o Fix sortable tables
 
 o add confirm form when deleting entries
 o add genre and tag import from old database
 
 o add filter to search for entries
 
 o use inherit_data (http://symfony.com/doc/current/cookbook/form/inherit_data_option.html)