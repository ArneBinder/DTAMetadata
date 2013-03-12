/* 
 * Contains the UI logic for the sortableCollection form type (src/DTA/MetadataBundle/Form/DerivedType/SortableCollectionType.php).
 * The form type is an input to specify a dynamic number of relations between a database entity and another.
 * This script handles the DOM manipulation to dynamically generate the necessary form inputs.
 * 
 * Additionally, some relations are sortable. Buttons to control the order of
 * form elements are generated by this javascript as well.
 * 
 * Collections are by (my) convention maintained within unordered lists that have the css-class collection-editor.
 */

jQuery(document).ready(createGui);

function createGui(){
    
    jQuery(document).on('click', '.sortableCollectionWidget.add-entity', addFormElement);
    jQuery(document).on('DOMNodeRemoved', '.collection-sortable li', updateSortableRanks);
    
    // add up and down control elements for sortable collections
    var sortableElements = jQuery('.collection-sortable li');
    jQuery.each(sortableElements, function(idx,element){
        createSortableControls(element);
    });

    jQuery('ol.collection-sortable').sortable({
        cursor: "move",
        update: updateSortableRanks,
        placeholder: "ui-state-highlight"
    });

}

/* Propagates the new positions (in the ordered list) to the hidden sortable rank input fields.
 * Applied each time the user changes the position of a sortable element. */
function updateSortableRanks( event, ui ){
       
    var $sortableCollectionHolder = $('#' + event.target.id);
    
    // traverse in order of dom tree, push this 'real' index to the hidden inputs
    $sortableCollectionHolder.children('li').each(function(index, listElement){
        $(listElement).find('input[name*=sortableRank]').val(index);
    });
}

/**
 * Extends the sortable collection by one collection element.
 */
function addFormElement(){
    var $addLink = $(this);
    
    var $collectionHolder = $addLink.parent();
    var $collection = $collectionHolder.children('ol.collection-sortable');
//    console.log('collection holder', $collectionHolder, 'collection ol', $collection);
    
    // PREPARE PROTOTYPE 
    
    if($collection.attr("data-prototype") === undefined){
        console.log('No protoype element for the collection editor available!');
        return false;
    }            
    
    // using the children method (instead of find) is important here, because sortable collections might be nested,
    // in which case the collection element would be to descending sortable collections as well.
    var elementId = $collection.children("li").length; 
    var modelClassName = $collectionHolder
        .children('input[name=modelClassName]')
        .val();
    var translatedModelClassName = $collectionHolder
        .children('input[name=translatedModelClassName]')
        .val();
    
    
    var prototype = $.trim($collection.attr("data-prototype"))
        .replace(/\n/g,'')
        .replace(/<label class="required">__name__label__<\/label>/g, '');  // remove per fragment label
    prototype = prototype.replace(new RegExp('__'+ modelClassName +'ID__', 'g'), elementId);

    // CREATE NEW DOM ELEMENT 
    var $newForm = $(prototype);
    
    var $newFormLi = $('<li></li>')
        .text(translatedModelClassName)
        .append($newForm);
    
    var sortable = $collection.hasClass('collection-sortable');
    if(sortable){
        // add up and down buttons
        createSortableControls($newFormLi, translatedModelClassName);

        // initialize the rank hidden input field
        var rank = elementId + 1; // the rank is 1-based
        var $rankInput = $newFormLi.find('input[name*=sortableRank]');
        $rankInput.attr('value', rank);
    }
    
    $collection.append($newFormLi);
    
    return false;
}

function createSortableControls(element, translatedModelClassName){
                     
    var $collectionHolder = $(element).parent().parent(); // element: li, parent: ol, parent: collection holder
    
    if(undefined === translatedModelClassName)
        translatedModelClassName = $collectionHolder
            .children('input[name=translatedModelClassName]')
            .val();
        
//    var iconUpStr = '<i class="icon-arrow-up"></i>';
//    var iconDownStr = '<i class="icon-arrow-down"></i>';
//    var upStr = ' nach oben';
//    var downStr = ' nach unten';
//    var up   = $('<a href="#" class="sortable-up">'+ iconUpStr + /*translatedModelClassName + upStr + */'</a> ');
//    var down = $('<a href="#" class="sortable-down">'+ iconDownStr + /*translatedModelClassName + downStr + */'</a>');
//    $(element).append(up);
//    $(element).append(down);
}

