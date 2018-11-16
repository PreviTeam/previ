
// Script générant les connades à créer au cgargement de la page.
// Les évenements sont ensuite mis en place  lors d'évènements.
$(document).ready(function () {  

  //Capture des liens affichant une page et du bloc contenu qui affichera le contenu des pages chargées
  var links = document.getElementsByClassName('phplink');
  var result = document.getElementById('content-data');


  /**
  * Fonction de chargement d'une page de manière assynchrone avec Fetch
  * @param fname : url de la page à charger
  */
  async function loadpage(fname){

      //Animation de chargement pendant le loading de la page
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
      $('#content-data').html(loading);

      // Récuperation et affichage du contenu
      var str = await fetch(fname);
      $('#content-data').html(await str.text());
  }



  // Application, sur l'évènement Click, de la fonction précédente, sur tous les items modifiant le contenu de la page
  // Récupération de la page cible via l'attribut href
  for(i = 0; i < links.length; i++){
    links[i].addEventListener('click', function(e) {
      e.preventDefault();
      loadpage(this.getAttribute('href'));
    });
  }



  // Fermeture des menu déroulant au click sur un nouvel item
  $('.dropdown-toggle').click(function () { $(".collapse").collapse("hide") });

});




