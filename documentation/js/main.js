$(document).ready(function(){
    function output(inp, id) {
        document.getElementById(id).innerHTML = inp;
    }

    var requestData = {'first_name':'String','last_name':'String','email':'String','password':'String','country':'String','province':'String','postal_code':'String','profession':'String','specialty':'String','language':'String','matricule':0,'sta_agreement':true};
    var request_str = JSON.stringify(requestData, undefined, 4);

    output(request_str, "json-request");

    var toa = {'error':'terms of agreement not checked'};
    var toa_str = JSON.stringify(toa, undefined, 4);    

    output(toa_str, "json-toa");

    var exists = {'notification':'user already exists in Database'};
    var exists_str = JSON.stringify(exists, undefined, 4);    

    output(exists_str, "user-exists");

    var created = {'notification':'registration was successful'};
    var created_str = JSON.stringify(created, undefined, 4);    

    output(created_str, "created");

});	