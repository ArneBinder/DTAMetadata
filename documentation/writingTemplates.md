
# form templates

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

- labelled controls

    The command generates an extra column for the label and the enclosed content is displayed indented.
    
    ```php
    {{ dta.beginLabelledControl({'controlId':'none', 'labelCaption':'Datierung'}) }}
        {{ form_row(form.publication.DatespecificationRelatedByPublicationdateId) }}
        {{ form_row(form.publication.DatespecificationRelatedByFirstpublicationdateId) }}
        {{ form_row(form.publication.work.datespecification) }}
    {{ dta.endLabelledControl() }}
    ```

    renders

    ![labelled control example][labelledControl]

- field sets 

- modals 

[labelledControl]: img/labelledControl.png "The left column is created by the labelled control macro."