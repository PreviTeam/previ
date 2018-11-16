
// Script générant les connades à créer au cgargement de la page.
// Les évenements sont ensuite mis en place  lors d'évènements.
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

  var loading = '<div id="floatingCirclesG">'+
                      '<div class="f_circleG" id="frotateG_01"></div>'+
                      '<div class="f_circleG" id="frotateG_02"></div>'+
                      '<div class="f_circleG" id="frotateG_03"></div>'+
                      '<div class="f_circleG" id="frotateG_04"></div>'+
                      '<div class="f_circleG" id="frotateG_05"></div>'+
                     ' <div class="f_circleG" id="frotateG_06"></div>'+
                      '<div class="f_circleG" id="frotateG_07"></div>'+
                      '<div class="f_circleG" id="frotateG_08"></div>'+
                    '</div>';


  for(i = 0; i < links.length; i++){
  	links[i].addEventListener('click', function(e) {
    e.preventDefault();

   result.innerHTML=loading;

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


  $('.dropdown-toggle').click(function () { $(".collapse").collapse("hide") });

});

