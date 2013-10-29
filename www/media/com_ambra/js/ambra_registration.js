    /**
     * Simple function to check email availability
     */
    function ambraCheckEmail( container, form , onCompleteFunction)
    {
        var url = 'index.php?option=com_ambra&controller=registration&task=checkEmail&format=json';
        
        // loop through form elements and prepare an array of objects for passing to server
        var str = new Array();
        for(i=0; i<form.elements.length; i++)
        {
            postvar = {
                name : form.elements[i].name,
                value : form.elements[i].value,
                checked : form.elements[i].checked,
                id : form.elements[i].id
            };
            str[i] = postvar;
        }
         var a = new Request({
        url: url,
        method:"post",
        data:{"elements":JSON.encode(str)},
        onSuccess: function(response){
            var resp = JSON.decode(response, false);
            if (resp.error != '1')
            {
                if (typeof onCompleteFunction == 'function') {
                    onCompleteFunction();
                }
               
            } 
             if ($(container)) { $(container).set( 'html', resp.msg);  }
        }
    }).send();

        
    }
    
    /**
     * Simple function to check username availability
     */
    function ambraCheckUsername( container, form, onCompleteFunction )
    {
        var url = 'index.php?option=com_ambra&controller=registration&task=checkUsername&format=json';
        
        // loop through form elements and prepare an array of objects for passing to server
        var str = new Array();
        for(i=0; i<form.elements.length; i++)
        {
            postvar = {
                name : form.elements[i].name,
                value : form.elements[i].value,
                checked : form.elements[i].checked,
                id : form.elements[i].id
            };
            str[i] = postvar;
        }
        var a = new Request({
        url: url,
        method:"post",
        data:{"elements":JSON.encode(str)},
        onSuccess: function(response){
            var resp = JSON.decode(response, false);
            if (resp.error != '1')
            {
                if (typeof onCompleteFunction == 'function') {
                    onCompleteFunction();
                }
               
            } 
             if ($(container)) { $(container).set( 'html', resp.msg);  }
        }
    }).send();
        
    }

    /**
     * Simple function to check a password strength
     */
    function ambraCheckPassword( container, form, onCompleteFunction )
    {
        var url = 'index.php?option=com_ambra&controller=registration&task=checkPassword&format=json';
        
        // loop through form elements and prepare an array of objects for passing to server
        var str = new Array();
        for(i=0; i<form.elements.length; i++)
        {
            postvar = {
                name : form.elements[i].name,
                value : form.elements[i].value,
                checked : form.elements[i].checked,
                id : form.elements[i].id
            };
            str[i] = postvar;
        }
        var a = new Request({
        url: url,
        method:"post",
        data:{"elements":JSON.encode(str)},
        onSuccess: function(response){
            var resp = JSON.decode(response, false);
            if (resp.error != '1')
            {
                if (typeof onCompleteFunction == 'function') {
                    onCompleteFunction();
                }
               
            } 
             if ($(container)) { $(container).set( 'html', resp.msg);  }
        }
    }).send();
    }
    
    /**
     * Simple function to compare passwords
     */
    function ambraCheckPassword2( container, form, onCompleteFunction )
    {
        var url = 'index.php?option=com_ambra&controller=registration&task=checkPassword2&format=json';
        
        // loop through form elements and prepare an array of objects for passing to server
        var str = new Array();
        for(i=0; i<form.elements.length; i++)
        {
            postvar = {
                name : form.elements[i].name,
                value : form.elements[i].value,
                checked : form.elements[i].checked,
                id : form.elements[i].id
            };
            str[i] = postvar;
        }
         var a = new Request({
        url: url,
        method:"post",
        data:{"elements":JSON.encode(str)},
        onSuccess: function(response){
            var resp = JSON.decode(response, false);
            if (resp.error != '1')
            {
                if (typeof onCompleteFunction == 'function') {
                    onCompleteFunction();
                }
               
            } 
             if ($(container)) { $(container).set( 'html', resp.msg);  }
        }
    }).send();
        
    }