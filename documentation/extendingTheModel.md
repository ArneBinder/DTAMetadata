# Extending the Model

This covers how to extend the database and update the application to support the new data fields.

### Adding a publication type

Add a table definition to the dta_data_schema.xml for instance

	<table name="chapter">
        <!--<column name="id" type="INTEGER" primaryKey="true" autoIncrement="true" required="true"/>-->
        <column name="publication_id" primaryKey="true" type="INTEGER" required="true"/>
        
        <column name="pages" description="Seitenangabe" type="LONGVARCHAR"/>
        
        <behavior name="table_row_view"> <parameter name="embedcolumnspublication" value="publication"/> </behavior>
        
        <foreign-key foreignTable="publication"> <reference local="publication_id" foreign="id"/> </foreign-key>
    </table>

Then, add the name of the table (e.g. chapter) to the value set of the type enumeration of publication types.
It is defined in the publication table of dta_data_schema.xml:

Add new types at the end of the list and never change the order of the elements!
Elements are simply converted to integers in order of appearance so changing something would break a lot.
	
	<table name="publication">
        
        [...]
        
        <column name="type" description="Publikationstyp. Zur Auflösung des dynamischen Typs (ein Volume bettet ein Publication objekt ein, mit nichts als dem Publikationsobjekt in der Hand, lässt sich das zugehörige speziellere objekt aber nur durch ausprobieren aller objektarten herausfinden.)" 
                type="ENUM" valueSet="BOOK,VOLUME,MULTIVOLUME,CHAPTER,JOURNAL,ARTICLE" />

### XML Schema

The base of the model are the XML schemata which define the database.
There are four schemata covering numerous entities.
All of them are located in Resources/config.

- data. Entities that refer to things in history. ```dta_data_schema.xml```
    - 

- master. Administrative entities and cross references. ```dta_master_schema.xml```
    - ```DTAUser``` the user model, including password, etc.
    - ```CategoryWork``` and similar classes, cross reference classes for n-to-n relationships.