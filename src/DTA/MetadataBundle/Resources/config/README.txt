
The main schema where everything is defined, is combined_schema.xml.

It is found by propel using the convention to take it from this (Resources/config) directory.
It also has to have the "schema" suffix before its extension.

Since schema modularization is NOT yet supported by propel, I combined the subschemata (classification, etc.)
into combined_schema and put the parts into the deprecated directory.