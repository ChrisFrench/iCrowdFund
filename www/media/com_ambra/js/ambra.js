    /**
     * Simple function to refresh a page.
     */
    function ambraUpdate()
    {
        Dsc.update();
    }
    
    /**
     * Resets the filters in a form.
     * This should be renamed to ambraResetFormFilters
     * 
     * @param form
     * @return
     */
    function ambraFormReset(form)
    {
       Dsc.formReset(form);
    }
    
    /**
     * 
     * @param {Object} order
     * @param {Object} dir
     * @param {Object} task
     */
    function ambraGridOrdering( order, dir ) 
    {
        Dsc.gridOrdering(order, dir);
    }
    
    /**
     * 
     * @param id
     * @param change
     * @return
     */
    function ambraGridOrder(id, change) 
    {
        Dsc.gridOrder(id, change);
    }
    
    /**
     * Sends form values to server for validation and outputs message returned.
     * Submits form if error flag is not set in response
     * 
     * @param {String} url for performing validation
     * @param {String} form element name
     * @param {String} task being performed
     */
    function ambraFormValidation( url, container, task, form ) 
    {
        Dsc.formValidation(url, container, task, form );
    }
    
    /**
     * Submits form using onsubmit if present
     * @param task
     * @return
     */
    function ambraSubmitForm(task, form)
    {   
         Dsc.submitForm(task, form);
       
    }
    
    /**
     * Overriding core submitbutton task to perform our onsubmit function
     * without submitting form afterwards
     * 
     * @param task
     * @return
     */
    function submitbutton(task) 
    {
        if (task) 
        {
            document.adminForm.task.value = task;
        }

        if (typeof document.adminForm.onsubmit == "function") 
        {
            document.adminForm.onsubmit();
        }
            else
        {
            submitform(task);
        }
    }
    
    /**
     * 
     * @param {Object} divname
     * @param {Object} spanname
     * @param {Object} showtext
     * @param {Object} hidetext
     */
    function ambraDisplayDiv (divname, spanname, showtext, hidetext) { 
      Dsc.displayDiv(divname, spanname, showtext, hidetext);
    }
    
    /**
     * 
     * @param {Object} prefix
     * @param {Object} newSuffix
     */
    function ambraSwitchDisplayDiv( prefix, newSuffix )
    {
        Dsc.switchDisplayDiv( prefix, newSuffix );
    }
    
    function ambraShowHideDiv(divname)
    {
       Dsc.showHideDiv(divname);
    }

    /**
     * 
     * @param {String} url to query
     * @param {String} document element to update after execution
     * @param {String} form name (optional)
     * @param {String} msg message for the modal div (optional)
     */
    function ambraDoTask( url, container, form, msg ) 
    {
        Dsc.doTask( url, container, form, msg );
    }

    /**
     * 
     * @param {String} msg message for the modal div (optional)
     */
    function ambraNewModal (msg)
    {
       Dsc.newModal (msg);
    }

    
    /**
     * Gets the value of a selected radiolist item
     * 
     * @param radioObj
     * @return string
     */
    function ambraGetCheckedValue(radioObj) 
    {
        if (!radioObj) { return ""; }
        
        var radioLength = radioObj.length;
        if (radioLength == undefined)
        {
            if(radioObj.checked)
                return radioObj.value;
            else
                return "";
        }
        
        for (var i = 0; i < radioLength; i++) 
        {
            if(radioObj[i].checked) {
                return radioObj[i].value;
            }
        }
        return "";
    }
    
    /**
     * Strips slashes from a string
     * @param str
     * @return
     */
    function ambraStripslashes (str) 
    {
        return (str+'').replace(/\\(.?)/g, function (s, n1) 
        {
            switch (n1) 
            {
                case '\\':
                    return '\\';
                case '0':                
                    return '\u0000';
                case '':
                    return '';
                default:
                    return n1;        
            }
        });
    }