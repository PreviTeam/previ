$(document).ready(function () {  
$('.nav-item').click(function () { $(".collapse").collapse("hide") });
});


$(document).ready(function () {  
var getHttpRequest = function() {
var httpRequest = false;

if (window.XMLHttpRequest) { // Mozilla, Safari,...
  httpRequest = new XMLHttpRequest();
  if (httpRequest.overrideMimeType) {
    httpRequest.overrideMimeType('text/xml');
  }
}
else if (window.ActiveXObject) { // IE
  try {
    httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
  }
  catch (e) {
    try {
      httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
    }
    catch (e) {}
  }
}

if (!httpRequest) {
  alert('Abandon :( Impossible de créer une instance XMLHTTP');
  return false;
}

return httpRequest
}


var links = document.getElementsByClassName('phplink');
var result = document.getElementById('content-data');



for(i = 0; i < links.length; i++){
	links[i].addEventListener('click', function(e) {
  
  e.preventDefault();
  result.innerHLTM="Chargement...";
  var httpRequest = getHttpRequest();

  httpRequest.onreadystatechange = function (){
    if(httpRequest.readyState === 4){
        result.innerHTML = httpRequest.responseText;
    }        
  }
  
  httpRequest.open('GET', this.getAttribute('href') , true);
  httpRequest.setRequestHeader('X-Requested-With', 'xmlhttprequest');
  httpRequest.send();
});
}

});

