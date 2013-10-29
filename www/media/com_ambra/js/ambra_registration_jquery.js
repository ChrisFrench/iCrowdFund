jQuery(document).ready(function() {
//instead of checking everyKeypress lets check when the user  stops typing for some type.
var typingTimer;
//timer identifier
var doneTypingInterval=1500;
//time in ms, .5 second for example
//on keyup, start the countdown
jQuery('input#username').keyup(function() {
typingTimer=setTimeout(checkUsername,doneTypingInterval);
});
//on keydown, clear the countdown
jQuery('input#username').keydown(function() {
clearTimeout(typingTimer);
});

//on keyup, start the countdown
jQuery('input#email').keyup(function() {
typingTimer=setTimeout(checkEmail,doneTypingInterval);
});
//on keydown, clear the countdown
jQuery('input#email').keydown(function() {
clearTimeout(typingTimer);
});

//on keyup, start the countdown
jQuery('input#password').keyup(function() {
typingTimer=setTimeout(checkPassword,doneTypingInterval);
});
//on keydown, clear the countdown
jQuery('input#password').keydown(function() {
clearTimeout(typingTimer);
});
jQuery('button#register').click(function() {

  jQuery('form#ambra_registration_form').submit();

});




function working(container,msg) {
var html='<img src="/media/dioscouri/images/ajax-loader.gif"> '+msg
jQuery(container).html(html);
}


function checkUsername() {
var url='index.php?option=com_ambra&controller=registration&task=checkUN&format=json';
var value=jQuery('input#username').val();
var container='#message-username';
var msg="Validating";
working(container,msg);
jQuery.post(url, {
username:value
}, function(data) {
jQuery(container).html(data.msg);
},"json");
}

function checkEmail() {
var url='index.php?option=com_ambra&controller=registration&task=emailCheck&format=json';
var value=jQuery('input#email').val();
var container='#message-email';
var msg="Validating";
working(container,msg);
jQuery.post(url, {
email:value
}, function(data) {
jQuery(container).html(data.msg);
},"json");
}

function checkPassword() {
var url='index.php?option=com_ambra&controller=registration&task=passwordCheck&format=json';
var value=jQuery('input#password').val();
var value2=jQuery('input#username').val();
var container='#message-password';
var msg="Validating";
working(container,msg);
jQuery.post(url, {
password:value , username: value2
}, function(data) {
jQuery(container).html(data.msg);
},"json");
}




});
