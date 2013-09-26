# Extending the Model

This covers how to extend the database and update the application to support the new data fields.

### XML Schema

The base of the model are the XML schemata which define the database.
There are four schemata covering numerous entities.
All of them are located in Resources/config.

- data. Entities that refer to things in history. ```dta_data_schema.xml```
    - 

- master. Administrative entities and cross references. ```dta_master_schema.xml```
    - ```DTAUser``` the user model, including password, etc.
    - ```CategoryWork``` and similar classes, cross reference classes for n-to-n relationships.