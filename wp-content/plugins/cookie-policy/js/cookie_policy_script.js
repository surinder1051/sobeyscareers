//Cookie Functions
function createCookie(name,value,days) {
    var expires = "";
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days*24*60*60*1000));
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

//readcookie
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
      var c = ca[i];
      while (c.charAt(0)==' ') c = c.substring(1,c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

//deletecookie
function delete_cookie( name ) {
    //document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
}

jQuery(document).ready(function(){
  
  var policy_cookie = readCookie("policy-cookie");
  var policy_cookie_ran = readCookie("policy-cookie-value");
  var cookie_policy_random_no = parseInt(jQuery('#cookie_policy_random_no').val());
  if(policy_cookie_ran != cookie_policy_random_no){
    jQuery('.cookie_policy').show();
  }
  else if(policy_cookie != null){
     jQuery('.cookie_policy').html('').hide();
  }
  
   jQuery('body').keyup(function(e) {
        if (e.keyCode == 9) {     
      if (!jQuery(".tabpress_1").hasClass("tabFocused")) {
        jQuery(".tabpress_1").focus().addClass('tabFocused');             
      }           
      else if(!jQuery(".tabpress_2").hasClass("tabFocused")) {                
        jQuery(".tabpress_2").focus().addClass('tabFocused');           
      } else {    
        return false;
      }
    }
    });
  
});

jQuery('.cookie_policy').on('click','.cookie_policy_btn',function(e){
  e.preventDefault();
  var policy_cookie = readCookie("policy-cookie");
  var policy_cookie_ran = readCookie("policy-cookie-value");
  var cookie_policy_random_no = jQuery('#cookie_policy_random_no').val();
  var cookie_policy_time = jQuery('#cookie_policy_cookie_time').val();
  if(policy_cookie == null || policy_cookie == ''){
    createCookie('policy-cookie','1',parseInt(cookie_policy_time));
    createCookie('policy-cookie-value',parseInt(cookie_policy_random_no),parseInt(cookie_policy_time));
  }
  if(policy_cookie_ran != null && policy_cookie != null && policy_cookie_ran != cookie_policy_random_no){
    createCookie('policy-cookie','1',parseInt(cookie_policy_time));
    createCookie('policy-cookie-value',parseInt(cookie_policy_random_no),parseInt(cookie_policy_time));    
  }
  jQuery('.cookie_policy').hide();
});

jQuery(window).on('load', function() {
    jQuery('.cookie_policy').css('opacity','1');
});

var poppy = sessionStorage.getItem("popup");
if(!poppy){
       jQuery('.cookie_ad_chocie').show();
}else{
  jQuery(".cookie_ad_chocie").hide();
}
jQuery('.cookie_ad_close').on("click",function(e){
  e.preventDefault();
  jQuery(".cookie_ad_chocie").hide();
  sessionStorage.setItem("popup", "true");
});