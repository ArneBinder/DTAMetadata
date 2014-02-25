# Writing Templates

This part of the documentation is mainly about two things.
The basics of twig, the template language used and the custom template mechanisms developed to ease the process of creating views.
Some additional details are given where specific bootstrap classes are used.

# Forms

## General Architecture

### Determining how a database entity is rendered

## Forms Cookbook

### Setting HTML attributes of form elements

To achieve e.g. a textarea input tag to be rendered with a specific id, style, class etc.

One possibility is to do this in the view using the form_row command.
Simply pass the attributes as associative array under the key 'attr'. 

```php
{{ form_widget(form.publication.comment, {'attr':{'style':'width:100%'} }) }}
```

Another way to do this is to manipulate the form type php file.
    
```php
// PublicationType.php
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder->add('comment', 'textarea', array(
            'attr' => array('style'=> 'width:100%', 'placeholder'=>'Vorlage beschädigt...'),
    ));
}
```
### Using pre-defined Macros

The file ```buildingBlocks.html.twig``` contains macros to ease recurring tasks.
It can be included using the following directive.
```php
{# template Publication.html.twig #}
{% import  'DTAMetadataBundle:Form:buildingBlocks.html.twig' as dta %}
```
Sometimes it might be necessary to include within a block to use it: 
```php
{% block imagesource_row %}
    {% import  'DTAMetadataBundle:Form:buildingBlocks.html.twig' as dta %}
    ...
```

It provides the following macros, available through the variable ```dta```.

- labelled content

    The command generates an extra column for the label and the enclosed content is displayed indented.
    
    ```php
    {{ dta.beginLabelledContent({'controlId':'none', 'labelCaption':'Datierung'}) }}
        {{ form_row(form.publication.DatespecificationRelatedByPublicationdateId) }}
        {{ form_row(form.publication.DatespecificationRelatedByFirstpublicationdateId) }}
        {{ form_row(form.publication.work.datespecification) }}
    {{ dta.endLabelledContent() }}
    ```

    renders

    ![labelled control example][labelledContent]

- field sets 

- modals 

[labelledContent]: img/labelledContent.png "The left column is created by the labelled control macro."