

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
  var select_unique = document.getElementsByClassName('selecteurUnique');

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

   for(k = 0; k < select_unique.length; k++){
    select_unique[k].addEventListener('click', function(e) {
      e.preventDefault();
      post_load_modal_select_unique(this.getAttribute('href'), this.getAttribute('id'));
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
  supressor();
  moveRow("#addTable");
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
  supressor("#modifyTable");
  moveRow("#modifyTable");
  external_links();
}



/**
* Chargement de la fenêtre modale de recherche générique
* Le contenu chargé est le contenu du lien Href du bouton appelant 
*/
async function post_load_modal_select(fname, $caller){
  var str = await fetch(fname);
  $('#Selectmodal-body').html(await str.text());

  // ---- Récupération de la modal appelante (Modify ou Add)
  var table_name = 'none'
  if($caller == 'addcall')
    table_name = '#addTable'; 
  else
    table_name = '#modifyTable';

  var nbLines = 0;

  // --- Récupération de toutes les lignes déjà présentes dans le tableau 
  var identifiants = $(table_name+' .line-table');
    for(i = 0 ; i < identifiants.length; ++i){
      nbLines++;
      var cont = identifiants[i].firstChild.firstChild.nodeValue;
     $('.return:contains("'+cont+'")').css('display','none');
    }


  // --- L'ouverture de l'écran de sélection masque les modales précédentes
  $('#AddModal').modal('hide');
  $('#ModifyModal').modal('hide');


  // --- Au clique sur un équipement, celui ci est ajouté au tableau du modal appelant
  var ret = document.getElementsByClassName('return');
  for(i = 0; i < ret.length; i++){
    ret[i].addEventListener('click', function(e) {
    e.preventDefault();

    var lastordre = '';
    if($("#modifycall").attr("href") === "select_operation.php"){
      lastordre = '<td><span class="ordre">'+(nbLines+1)+'</span></td>' +
                  '<td><button class="btn btn-link upper">up</button></td>' +  
                  '<td><button class="btn btn-link downer">down</button></td>';
    }
      
      
      var content_line = '<tr class="line-table" ><td class="cell">'+
                        this.firstChild.nodeValue+'</td>'+ 
                        lastordre + 
                        '<td><button class="supress btn btn-link" >Supprimer</button></td></tr>';

      // --- Réaffichage De la modale appelante  ----                            
      if($caller === 'addcall'){
        $('#addTable').prepend(content_line);
        $('#AddModal').modal('toggle');
      }
      else if($caller === 'modifycall'){
        $('#modifyTable').append(content_line);
        $('#ModifyModal').modal('toggle');
      }

      // Application des liens de supression
      supressor(table_name);
      moveRow(table_name);
    });
  }
}


function supressor($table_name){
   var supress= document.getElementsByClassName('supress');

  // Application, sur l'évènement Click, de la fonction précédente, sur tous les items modifiant le contenu de la page
  // Récupération de la page cible via l'attribut href
  for(i = 0; i < supress.length; i++){
      supress[i].addEventListener('click', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        reOrder($table_name);
    });
  }
}

function reOrder($table_name){
  var nbLignes = 0;

  // --- Récupération de toutes les lignes déjà présentes dans le tableau 
  var identifiants = $($table_name+' .line-table .ordre');
    for(i = 0 ; i < identifiants.length; ++i){
      nbLignes++;
      identifiants[i].firstChild.nodeValue = nbLignes;
    }
}

function moveRow($table_name){

  var upper = $('.upper');
  for(i = 0 ; i < upper.length; ++i){
    upper[i].addEventListener('click', function(e){
      var temp = $(this).parent().parent().prev('tr');
      if(temp){
        $(this).parent().parent().after(temp);
      }
      reOrder($table_name);
    });
  }

  var downer = $('.downer');
  for(i = 0 ; i < downer.length; ++i){
    downer[i].addEventListener('click', function(e){
      var temp = $(this).parent().parent().next('tr');
      if(temp){
        $(this).parent().parent().before(temp);
      }
      reOrder($table_name);
    });
  }
}


async function post_load_modal_select_unique(fname, $caller){

  var str = await fetch(fname);
  $('#Selectmodal-body').html(await str.text());

  // --- L'ouverture de l'écran de sélection masque les modales précédentes
  $('#AddModal').modal('hide');
  $('#ModifyModal').modal('hide');


  // --- Au clique sur un équipement, celui ci est ajouté au tableau du modal appelant
  var ret = document.getElementsByClassName('return');
  for(i = 0; i < ret.length; i++){
    ret[i].addEventListener('click', function(e) {
      e.preventDefault();
         
      var value = this.firstChild.nodeValue;

      // --- Réaffichage De la modale appelante  ----                            
      if($caller === 'addcall'){
        $('#addUniqueSelector').attr("value", value);
        $('#AddModal').modal('toggle');
      }
      else if($caller === 'modifycall'){
        $('#modifyUniqueSelector').attr("value", value);
        $('#ModifyModal').modal('toggle');
      }
    });
  }
}