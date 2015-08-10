function addFieldToList(field, list, file, checkboxName){
    var title = field.val();
    
    field.attr('disabled', true);
    
    jQuery.ajax({
            url: file,
            type: 'POST',
            data: {fieldValue: title}
        })
        .done(function(insertId){
            if(parseInt(insertId) == insertId) {
            	if (insertId == -1) {
            		alert('Entry already exists');
            	} else {
            		field.val('');
                	list.append('<li><input type="checkbox" class="deallab-checkbox" name="' + checkboxName + '[' + insertId + ']" value="1" checked="checked" />' + title + '</li>');
                	field.val('');
            	}
            } else {
                alert('Saving failed with error: ' + insertId);
            }
        })
        .fail(function(){
            alert("AJAX request failed.");
        })
        .always(function(){
            field.attr('disabled', false);
        });
    
}