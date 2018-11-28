
// Script générant les connades à créer au cgargement de la page.
// Les évenements sont ensuite mis en place  lors d'évènements.
$(document).ready(function () {  

  //Capture des liens affichant une page et du bloc contenu qui affichera le contenu des pages chargées
  var links= document.getElementsByClassName('phplink');
  var result = document.getElementById('content-data');

  loadpage('#content-data', 'dashboard_content.php');


  // Application, sur l'évènement Click, de la fonction précédente, sur tous les items modifiant le contenu de la page
  // Récupération de la page cible via l'attribut href
  for(i = 0; i < links.length; i++){
      links[i].addEventListener('click', function(e) {
      e.preventDefault();
      loadpage('#content-data', this.getAttribute('href'));
    });
  }

  // Fermeture des menu déroulant au click sur un nouvel item
  $('.dropdown-toggle').click(function () { $(".collapse").collapse("hide") });
});



 /**
  * Fonction de chargement d'une page de manière assynchrone avec Fetch
  * @param fname : url de la page à charger
  */
  async function loadpage(name, fname){

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
      $(name).html(loading);

      // Récuperation et affichage du contenu
      var str = await fetch(fname);
      $(name).html(await str.text());
      name == '#content-data' && f();
}

/**
* Fonction d'application du chargement des fenêtres modales
*
*/
function f() {  
  var btn = document.getElementsByClassName('btn-link');
  var add = document.getElementById('add');

  add != null && add.addEventListener('click', function(e) {
    e.preventDefault();
    post_load_modal_add(this.getAttribute('href'));
    });

  for(i = 0; i < btn.length; i++){
     btn[i].addEventListener('click', function(e) {
      e.preventDefault();
      post_load_modal(this.getAttribute('href'), this.getAttribute('id'));
      });
  }
}

/**
* Chargement de la fenêtre modale d'ajout
*
*/
async function post_load_modal_add(fname){
  var str = await fetch(fname);
  $('#Addmodal-body').html(await str.text());
}


/**
* Chargement de la fenêtre modale de modification
*
*/
async function post_load_modal(fname, id){
  var str = await fetch(fname, {  
    method: 'POST',  
    body: 'id='+id,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });
  
  $('#Modifymodal-body').html(await str.text());
}





