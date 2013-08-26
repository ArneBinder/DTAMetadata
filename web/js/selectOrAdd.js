/* 
 * GUI logic for the selectOrAdd form type. (/src/DTA/MetadataBundle/Form/DerivedType/SelectOrAddType.php)
 * Allows either selection of an existing database entity or creation of a new one in a nested form (modal).
 * 
 * This file is responsible for adding the markup. Prototype DOM is expected to 
 * be available on the page already as #selectOrAddModalPrototype.
 * 
 */

jQuery(function(){
    // enable all searchable select boxes with typeahead functionality for new dom elements
    var searchableConfiguration = {};
    jQuery('.selectOrAdd.select.searchable').select2(searchableConfiguration);
    jQuery('body').on('DOMNodeInserted', function(e){
        $(e.target).find('.selectOrAdd.select.searchable').select2(searchableConfiguration)
    });
});

/**
 * Called when the add button of a select box. Creates a modal containing a 
 * form to create a new entity. The modal is created only once.
 */
function selectOrAdd_launchAddDialog(){
    
    var $addButton = $(this);
    
    // create modal only once
    if( ! $addButton.hasClass('modalCreated')){
        
        var $modal = createAjaxFormModal($addButton);
        
        var selectWidget = $addButton.parent().children('select');
        $modal.data('selectWidget', selectWidget);
        
        // avoid multiple requests by flagging the button and linking the created modal
        $addButton.addClass('modalCreated');
        $addButton.data('modal', $modal);
        
    } else {
        $addButton.data('modal').modal();
    }
    
}

/**
 * Posts the data of the new entity to the server.
 * Calls the update routine to add this dynamically 
 */
function selectOrAdd_submitFormData(modal){

    var $modal = $(modal);
    
    var form = $modal.find("form[method]");
    var formData = form.serialize();
    var targetUrl = form.attr("action");
    
    jQuery.post(targetUrl, formData, function(data){
        // toggle loading state (off)
        console.log(data);
        $('body').modalmanager('loading');
        selectOrAdd_updateSelectWidget($modal,data)
    } );
    
    $('body').modalmanager('loading');

}

/**
 * Adds the newly created option to the select widget
 */
function selectOrAdd_updateSelectWidget($modal, data){
    
    var $selectWidget = $modal.data("selectWidget");
    
    // deselect all options before selecting the new one
    $selectWidget.find("option").attr("selected", false);
    
    // add and select new option
    var $newOption = $(data);
    $selectWidget.append($newOption);

    // select new value
    $newOption.attr('selected', true);      // without searchable support
    $($selectWidget).select2("val", $newOption.attr('value'));
    
    $modal.modal('hide');
}

/**
 * loads the form wrapped in modal markup.
 * The responsible controller action is ORMController->ajaxModalFormAction.
 * @param addButton    dom element    button belonging to the select or add widget
 */
function createAjaxFormModal(addButton){
    
    // the href attribute is preset to '#modal_for_<select box id>'
    // @see dtaFormExtensions.html.twig under {% block selectOrAdd_widget %} 
    var modalId = $(addButton).attr('href');
    
    // the url to the form generating controller routine is rendered from the template engine into a hidden input
    // @see dtaFormExtensions.html.twig under {% block selectOrAdd_widget %}
    var modalRetrieveUrl = $(addButton).siblings('input[name=modalRetrieveUrl]').val();
    
    var $body = $('body');
    // create the backdrop and wait for next modal to be triggered
    $body.modalmanager('loading');

    var selectWidget = $(addButton).parent().children('select.selectOrAdd');
    
    // fill the modal with modal skeleton markup (header, body, footer) and form inputs
    $.get(modalRetrieveUrl, '', function(data){
        // the actual modal content is delivered with the modal markup by the controller 
        // this is useful because it allows for translation (model names) 
        // and generation of the submit href based on internal routes
        // @see ORMController generateAjaxModalForm
        
        $body.append(data);        
        $("#"+modalId+"-submitLink").on('click',function(e){selectOrAdd_submitFormData($("#"+modalId)); e.preventDefault();});
        
        var modal = $("#"+modalId);
        modal.data('selectWidget', selectWidget);
        modal.modal();
    });

    return $("#"+modalId);
}

    