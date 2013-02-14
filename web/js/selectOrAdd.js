/* 
 * GUI logic for the selectOrAdd form type. (/src/DTA/MetadataBundle/Form/DerivedType/SelectOrAddType.php)
 * Allows either selection of an existing database entity or creation of a new one in a nested form (modal).
 */

jQuery(document).ready(function(){
    $(".selectOrAdd.btn.add").on("click",launchAddDialog);
});

function launchAddDialog(){
    
    var $addButton = $(this);

    var modal; // the dialog
    
    // create modal only once
    if( ! $addButton.hasClass("modalCreated")){
        
        var selectWidget = $addButton.parent().find("select");
        modal = createModal($addButton.parent());
        modal.data("selectWidget", selectWidget);
        
        $addButton.data("modal", modal);
        $addButton.addClass("modalCreated");
    } else {
        modal = $addButton.data("modal");
    }
    
    // the url to the form generating controller routine is rendered from the template engine into a hidden input
    var formRetrieveUrl = $(this).parent().find('input[name=formRetrieveUrl]').val();
    modal.load(formRetrieveUrl, '', function(){ 
        
        // ajax behavior for the nested submit button
        var submitButton = $(modal).find("input[type=submit]");
        submitButton.on("click", function(clickEvent){submitFormData.apply(modal,[clickEvent])}); // set "this" to the modal, not the submit button
        
        modal.modal(); 
    });
}

function submitFormData(clickEvent){

    var $modal = $(this);
    
    var form = $(this).find("form");
    var formData = form.serialize();
    var targetUrl = form.attr("action");
    
    jQuery.post(targetUrl, formData, function(data){updateSelectWidget.apply($modal,[data])} );

    clickEvent.preventDefault();
    return false;
}

function updateSelectWidget(data){
    
    var $modal = $(this);
    var $selectWidget = $modal.data("selectWidget");
    
    // deselect all options before selecting the new one
    $selectWidget.find("option").attr("selected", false);
    
    // add and select new option
    var $newOption = $(data);
    $selectWidget.append($newOption);
    $newOption.attr('selected', true);
    
    $modal.modal('hide');
}

// formElement is the dom element with which the modal interacts
function createModal(formElement){
    
    var modal = $('<div id="testModal" class="modal hide fade" tabindex="-1"/>');
    $(formElement).append(modal);
    
    return modal;
}

