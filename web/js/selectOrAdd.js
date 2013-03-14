/* 
 * GUI logic for the selectOrAdd form type. (/src/DTA/MetadataBundle/Form/DerivedType/SelectOrAddType.php)
 * Allows either selection of an existing database entity or creation of a new one in a nested form (modal).
 */

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
        $modal.modal('hide');
        selectOrAdd_updateSelectWidget($modal,data)
    } );
    $modal.modal('loading');

//    clickEvent.preventDefault();
//    return false;
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
    $newOption.attr('selected', true);
    
    $modal.modal('hide');
}

/**
 * loads the form wrapped in modal markup (header, body, footer).
 * The responsible controller action is DTABaseController->generateAjaxForm.
 */
function createAjaxFormModal(addButton){
    
    // the href attribute is preset to '#modal_for_<select box id>'
    // @see dtaFormExtensions.html.twig under {% block selectOrAdd_widget %} 
    var modalId = $(addButton).attr('href');
    var rawId = modalId.substr(1); // id without #
    
    // the url to the form generating controller routine is rendered from the template engine into a hidden input
    // @see dtaFormExtensions.html.twig under {% block selectOrAdd_widget %}
    var modalRetrieveUrl = $(addButton).siblings('input[name=modalRetrieveUrl]').val();
    
    // the actual modal content is delivered wrapped around the form by the controller 
    // this is useful because it allows for translation (model names into german) 
    // and generation of the submit href based on internal routes
    // @see DTABaseController generateAjaxModalForm
    var $modal = $('<div id="'+modalId+'" class="modal hide fade" tabindex="-1">');
    
    // put modal in the containing div, although the position probably doesn't matter
    $(addButton).parent().append($modal);
    
    // create the backdrop and wait for next modal to be triggered
    $('body').modalmanager('loading');

    // fill the modal with modal skeleton markup (header, body, footer) and form inputs
    $modal.load(modalRetrieveUrl, '', function(data){
        $modal.modal();
    });
        
    return $modal;
}

    