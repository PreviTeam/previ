
// Script générant les connades à créer au cgargement de la page.
// Les évenements sont ensuite mis en place  lors d'évènements.
$(document).ready(function () {  

  loadpage('#content-data', 'dashboard_content.php');

  //Capture des liens affichant une page et du bloc contenu qui affichera le contenu des pages chargées
  var links= document.getElementsByClassName('phplink');

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
  * Fonction de chargement d'une page de manière assynchrone avec Fetch.
  * Les pages sont appelées depuis le sider statique
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
      external_links();
}

/**
* Fonction d'application du chargement des fenêtres modales
*
*/
function f() {  
  
  var btn = document.getElementsByClassName('btn-modal');
  var add = document.getElementById('add');
  var select = document.getElementsByClassName('selecteur');

  add != null && add.addEventListener('click', function(e) {
    e.preventDefault();
    post_load_modal_add(this.getAttribute('href'));
    });

  
  for(j = 0; j < select.length; j++){
    select[j].addEventListener('click', function(e) {
      e.preventDefault();
      post_load_modal_select(this.getAttribute('href'), this.getAttribute('id'));
    });
   }

  for(i = 0; i < btn.length; i++){
     btn[i].addEventListener('click', function(e) {
        e.preventDefault();
        post_load_modal(this.getAttribute('href'), this.getAttribute('id'), '#Modifymodal-body');
      });
  }
}


/**
*Fonction de gestion du chargement Fetch du contenu des pages appelées depuis un autre contenu de page
*
*/
function external_links(){
  var links= document.getElementsByClassName('ajaxphplink');

  // Application, sur l'évènement Click, de la fonction précédente, sur tous les items modifiant le contenu de la page
  // Récupération de la page cible via l'attribut href
  for(i = 0; i < links.length; i++){
      links[i].addEventListener('click', function(e) {
      e.preventDefault();
      post_load_modal(this.getAttribute('href'), this.getAttribute('id'), "#content-data");
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
  f();
}

/**
* Chargement de la fenêtre modale de modification
*
*/
async function post_load_modal(fname, id, $content){

  var i='id='+id;
  var str = await fetch(fname, {  
    method: "POST",  
    body: i,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });
  
  $($content).html(await str.text());
  f();
  external_links();
}



/**
* Chargement de la fenêtre modale de recherche
*
*/
async function post_load_modal_select(fname, $caller){
  console.log($caller);
  var str = await fetch(fname);
  $('#Selectmodal-body').html(await str.text());

  $('#AddModal').modal('hide');
  $('#ModifyModal').modal('hide');

   var ret = document.getElementById('return');
    ret.addEventListener('click', function(e) {
      e.preventDefault();
      
      if($caller === 'addcall'){
        $('#addEPI').prepend("<tr><td>test</td></tr>");
        $('#AddModal').modal('toggle');
      }
      else if($caller === 'modifycall'){
        $('#modifyEPI').prepend("<tr><td>test</td></tr>");
        $('#ModifyModal').modal('toggle');
      }
    });

}
