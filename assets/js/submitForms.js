function submitForms(form1id, form2id, action, method) {
    //create new form and assign attributes
    var newForm = document.createElement('form');
    newForm.setAttribute('action', action);
    newForm.setAttribute('method', method);
    newForm.style.display = 'none';
    var formIds = [form1id, form2id];
    //this will hold the innerhtml
    var html = '';
    for(var i = 0; i < formIds.length; i++) {
        //get the form
        var form = document.getElementById(formIds[i]);
        //this is just to make it easier to work with
        var childNodes = form.getElementsByTagName("*")
        //loop through the childnodes looking for data to add to the new form
        for(var e = 0; e < childNodes.length; e++) {
            //make sure its an input, so we only send what we have to
            if(childNodes[e].tagName == 'INPUT') {
                var type='hidden';
                var style = '';
                var checked = '';
                if(childNodes[e].type == 'checkbox') {
                    type = 'checkbox'
                    style = ' style="display:none" '
                    if(childNodes[e].checked == true) {
                        checked = ' checked';
                    }
                } else if(childNodes[e].type == 'radio') {
                    type = 'radio';
                    style = ' style="display:none" '
                    if(childNodes[e].checked == true) {
                        checked = ' checked';
                    }
                }
                //dont need double quotes for type because we know it wont have any spaces
                html += '<input type="' + type + '" name="' + childNodes[e].name + 
                        '" value="' + childNodes[e].value + '"' + style + checked + '>';
            }
        }
    }
    newForm.innerHTML = html;
    //because of this your page must have a body
    document.getElementsByTagName('body')[0].appendChild(newForm);
    //you might have to get the new form again, im pretty sure that you dont have to though.
    newForm.submit();
}