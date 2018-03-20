function displayNotification()
{
  // build the message
  var message = "<div id=\"cookieslaw\">";
  // Message can be either on top of bottom of the screen.
  if (user_options.cookieTop == "on")
    var message = message + "<div id=\"top\">";
  else
    var message = message + "<div id=\"bottom\">";
  
  // This is the message displayed to the user.
  message = message + "<p id=\"textstrong\"><strong>" + nl2br(user_options.messageContent1);
  message = message + " <a id=\"cookies\" href=\"" + nl2br(user_options.cookieUrl) + "\">" + nl2br(user_options.messageContent2) + "</a> ";
  message = message + nl2br(user_options.messageContent3) + "</strong> <span id=\"text\">" + nl2br(user_options.messageContent4) + "</span></p>";

  // Displays the I agree/disagree buttons.
  // The 'no thanks' button redirect can be defined in the module options in the admin panel.
  message = message + "<div id=\"buttons\"><button id=\"cookieslawOK\" onClick='JavaScript:setCookie(\""+ user_options.cookieName +"\",365);'>"+user_options.okText+"</button> <button id=\"cookienotOK\" onClick='JavaScript:killCookies();'>"+user_options.notOkText+"</button></div>";
	
  // last bits for the complete message.
  message = message + "</div></div>";
  // alert(message);
  jQuery("body").prepend(message);
}

function getCookie(c_name)
{
  var i,x,y,ARRcookies=document.cookie.split(";");
  for (i=0;i<ARRcookies.length;i++)
  {
    x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
    y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
    x=x.replace(/^\s+|\s+$/g,"");
    if (x==c_name)
      return unescape(y);
  }
	return null;
}

function _setCookie( name, value, exp_y, exp_m, exp_d, path, domain, secure )
{
  var cookie_string = name + "=" + escape ( value );

  if ( exp_y )
  {
    var expires = new Date ( exp_y, exp_m, exp_d );
    cookie_string += "; expires=" + expires.toGMTString();
  }

  if ( path )
    cookie_string += "; path=" + escape ( path );

  if ( domain )
    cookie_string += "; domain=" + escape ( domain );
  
  if ( secure )
    cookie_string += "; secure";
  
  document.cookie = cookie_string;
}

function setCookie(name, exdays)
{
  var c_expires= new Date();

  c_expires.setDate(c_expires.getDate() + exdays);

  _setCookie(escape(name), escape("accepted"), c_expires.getFullYear(), c_expires.getMonth(), c_expires.getDay());

  // set cookieslaw to hidden.
  var cl = document.getElementById("cookieslaw");
  if(cl != null)
  	cl.innerHTML = "";
  
  return false;
}

function checkCookie()
{
  var cookieName= user_options.cookieName;
  var cookieChk=getCookie(cookieName);
  if (cookieChk!=null && cookieChk!="")
  {
    // the jsCookieCheck cookie exists so we can assume the person has read the notification
    // within the last year
    setCookie(cookieName, 365);	// set the cookie to expire in a year.
  }
  else 
  {
    // No cookie exists, so display the lightbox effect notification.
    displayNotification();
  } 
}

function nl2br (str, is_xhtml) {   
var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

function killCookies(){
  // Kill all non-essential cookies and leave
  jQuery('div#cookiewarning p#buttons').html('Clearing cookies and redirecting...');

	var cookies = document.cookie.split(";");
  for (var i = 0; i < cookies.length; i++)
    _setCookie(cookies[i].split("=")[0], -1,  1970, 1, 1, user_options.cookiePath, user_options.cookieDomain);
  
	// We can't remove cookies in javascript protected by the HttpOnly flag
	jQuery.getJSON( user_options.ajaxUrl , { path : user_options.cookiePath , domain : user_options.cookieDomain }, function( data ) {
		//alert(data.success);
  });

  window.location = user_options.redirectLink;
}

jQuery(window).load(function(){
	checkCookie();
});