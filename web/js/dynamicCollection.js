/* 
 * Contains the UI logic for the dynamicCollection form type (src/DTA/MetadataBundle/Form/DerivedType/DynamicCollectionType.php).
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
    
    jQuery(document).on('click', '.dynamic-collection.add-entity', addFormElement);
    jQuery(document).on('DOMNodeRemoved', '.dynamic-collection.list li', updateSortableRanks);
    
    // add up and down control elements for dynamic collections
    var dynamicElements = jQuery('.dynamic-collection.list li');
    
    
    jQuery.each(dynamicElements, function(idx,element){
        createElementControls(element);
    });

    jQuery('.dynamic-collection.list.sortable').sortable({
        cursor: "move",
        update: updateSortableRanks,
        placeholder: "sortableDragPlaceholder",
        distance: 20
    });

    // formInfo is set within the formWrapper template
    if(typeof formInfo != 'undefined' && formInfo.action == 'new'){
        // create default fragment for empty collections
        var holders = $('.dynamic-collection.list').parent();
        $('a.add-entity', holders).click();
    }
}

/* Propagates the new positions (in the ordered list) to the hidden sortable rank input fields.
 * Applied each time the user changes the position of a sortable element. */
function updateSortableRanks( event, ui ){
       
    var $dynamicCollectionList = $('#' + event.target.id);
    
    // traverse in order of dom tree, push this 'visible' index to the hidden inputs
    $dynamicCollectionList.children('li').each(function(index, listElement){
//        console.log($(listElement), $(listElement).children().children('input[name*=sortableRank]').val());
        $(listElement).children().children('input[name*=sortable_rank]').val(index);
    });
}

/**
 * Extends the dynamic collection by one collection element.
 */
function addFormElement(){
    var $addLink = $(this);
    
    var $collectionHolder = $addLink.parent();
    var $collectionList = $collectionHolder.children('.dynamic-collection.list');
//    console.log('collection holder', $collectionHolder, 'collection ol', $collectionList);
    
    // PREPARE PROTOTYPE 
    
    if($collectionList.attr("data-prototype") === undefined){
        console.log('No protoype element for the collection editor available!');
        return false;
    }            
    
    // using the children method (instead of find) is important here, because dynamic collections might be nested,
    // in which the li selector might illegally descend into other dynamic collections.
    var elementId = $collectionList.children("li").length; 
    var modelClassName = $collectionHolder
        .children('input[name=modelClassName]')
        .val();
    var translatedModelClassName = $collectionHolder
        .children('input[name=translatedModelClassName]')
        .val();
    var asPanel = $collectionHolder
        .children('input[name=asPanel]')
        .val();
    
    // remove leading and trailing whitespace, because jQuery has problems to recognize the string otherwise
    var prototype = $.trim($collectionList.attr("data-prototype"));
//        .replace(/\n/g,'')
//        .replace(/<label class="required">__name__label__<\/label>/g, '');  // remove per fragment label

    // generate proper name and id attributes for form input by replacing the ID placeholder with the index of the element
    prototype = prototype.replace(new RegExp('__'+ modelClassName +'ID__', 'g'), elementId);

//    console.log(prototype);

    // CREATE NEW DOM ELEMENT 
    var $newForm = $(prototype);
    
    var $newFormLi = $('<li></li>');

    if (asPanel){
        $newFormLi.addClass('panel').addClass('panel-default');
    }
    $newFormLi.append($newForm);
    
    // add e.g. remove button
    createElementControls($newFormLi, translatedModelClassName);
        
    var sortable = $collectionList.hasClass('sortable');
    if(sortable){
        
        // add a caption to feed the enumeration (with just a plain div, list elements can't be numbered)
        // without the list-style-type set to decimal, more compact list elements are possible and preferred
//        $newFormLi.prepend($('<span/>').text(translatedModelClassName));

        // initialize the rank hidden input field
        var rank = elementId + 1; // the rank is 1-based
        var $rankInput = $newFormLi.find('input[name*=sortableRank]');
        $rankInput.attr('value', rank);
    }
    
    $collectionList.append($newFormLi);
    
    return false;
}

/* Adds remove button to each fragment */
function createElementControls(element, translatedModelClassName){
             
    var $collectionHolder = $(element).parent().parent(); // element: li, parent: ol, parent: collection holder
    var elementId = $(element).attr('class');
    
//    console.log(element, elementId);
    
    if(undefined === translatedModelClassName)
        translatedModelClassName = $collectionHolder
            .children('input[name=translatedModelClassName]')
            .val();
    
    var removeButtonLink = $('<a></a>')
        .on('click', function(){ $(element).remove() })
        .html('<span class="glyphicon glyphicon-remove dynamic-collection-remove-item-link"></span>');
        
//    var iconUpStr = '<i class="icon-arrow-up"></i>';
//    var iconDownStr = '<i class="icon-arrow-down"></i>';
//    var upStr = ' nach oben';
//    var downStr = ' nach unten';
//    var up   = $('<a href="#" class="sortable-up">'+ iconUpStr + /*translatedModelClassName + upStr + */'</a> ');
//    var down = $('<a href="#" class="sortable-down">'+ iconDownStr + /*translatedModelClassName + downStr + */'</a>');

    $(element).children('div').prepend(removeButtonLink);
}

