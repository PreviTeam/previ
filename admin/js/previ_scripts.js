// A la premiere execution de la page dashboard
$(document).ready(function () {  

  loadpage('#content-data', 'dashboard_content.php');

  //Capture des liens affichant une page et du bloc contenu qui affichera le contenu des pages chargées
  var links= document.getElementsByClassName('phplink');

  // Application, sur l'évènement Click, de la fonction précédente, sur tous les items modifiant le contenu de la page
  // Récupération de la page cible via l'attribut href
    $('.phplink').click( function(e) {
      e.preventDefault();
      loadpage('#content-data', this.getAttribute('href'), null);
    });

  // Chargement de la page de recherche
  $('#searchBtn').click(function(e) {
      e.preventDefault();
      loadpage('#content-data', this.getAttribute('href'), $("#searchBar").val());
    });

  // Fermeture des menu déroulant au click sur un nouvel item
  $('.dropdown-toggle').click(function () { $(".collapse").collapse("hide") });

});


 /**
  * Fonction de chargement d'une page de manière assynchrone avec Fetch.
  * Les pages sont appelées depuis le sider statique
  * @param fname : url de la page à charger
  */
  async function loadpage(name, fname, search){

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
      if(search != null){
        var temp = "id="+search;
        var str = await fetch(fname, {  
            method: "POST",  
            body: temp,
            headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
          });
        $(name).html(await str.text());
      }
      else{
        var str = await fetch(fname);
        $(name).html(await str.text());
      }
      name == '#content-data' && f();
      external_links();
}

/**
* Fonction d'application du chargement des fenêtres modales dans les pages chargées
*
*/
function f() {  
  
  $('#add').click(function(e) {
    e.preventDefault();
    post_load_modal_add(this.getAttribute('href'));
    });

  $('.selecteur').click(function(e) {
    e.preventDefault();
    post_load_modal_select(this.getAttribute('href'), this.getAttribute('id'));
  });

  $('.selecteurUnique').click(function(e) {
    e.preventDefault();
    post_load_modal_select_unique(this.getAttribute('href'), this.getAttribute('id'));
  });

  $('.btn-modal').click(function(e) {
    e.preventDefault();
    post_load_modal(this.getAttribute('href'), this.getAttribute('id'), '#Modifymodal-body');
  });

  // A déplacer ! 
  $('.bdd_request').unbind();
  $('.bdd_request').click(function(e){
    e.preventDefault();
    bdd_modifier(this.getAttribute('href'), this.getAttribute('data-bddAction'));
  });

}


/**
*Fonction de gestion du chargement via Fetch du contenu des pages appelées depuis un autre contenu de page
*
*/
function external_links(){

  // Application, sur l'évènement Click, sur tous les items modifiant le contenu de la page
  // Récupération de la page cible via l'attribut href
      $('.ajaxphplink').click(function(e) {
      e.preventDefault();
      post_load_modal(this.getAttribute('href'), this.getAttribute('id'), "#content-data");
    });
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
async function post_load_modal(fname, id, content){

  var i='id='+id;
  var str = await fetch(fname, {  
    method: "POST",  
    body: i,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });
  
  $(content).html(await str.text());
  f();
  supressor("#modifyTable");
  moveRow("#modifyTable");
  external_links();
}



/**
* Chargement de la fenêtre modale de recherche générique
* Le contenu chargé est le contenu du lien Href du bouton appelant 
*/
async function post_load_modal_select(fname, caller){

  $('#closeSelectModal').unbind();
  $('#closeSelectModal').click(function() { 
  $("#SelectModal").modal('hide');

    // --- Réaffichage De la modale appelante  ----                            
      if(caller === 'addcall'){
        $('#AddModal').modal('toggle');
      }
      else if(caller === 'modifycall'){
        $('#ModifyModal').modal('toggle');
      }

      // Application des liens de supression
      supressor(table_name);
      moveRow(table_name);
  });


  var str = await fetch(fname);
  $('#Selectmodal-body').html(await str.text());


  // ---- Récupération de la modal appelante (Modify ou Add)
  var table_name = 'none'
  if(caller == 'addcall')
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
    $('.return').click( function(e) {
    e.preventDefault();

    var lastordre = '';
    if($("#modifycall").attr("href") === "select_operation.php"){
      lastordre = '<td><span class="ordre">'+(nbLines+1)+'</span></td>' +
                  '<td><button class="btn btn-link upper">up</button></td>' +  
                  '<td><button class="btn btn-link downer">down</button></td>';
    }
      
      
      var content_line = '<tr class="line-table" >'+
                        '<td class="cell">' + this.firstChild.nodeValue+'</td>'+ 
                        lastordre + 
                        '<td><button class="supress btn btn-link" >Supprimer</button></td></tr>';

      // --- Réaffichage De la modale appelante  ----                            
      if(caller === 'addcall'){
        $('#addTable .tableBody').prepend(content_line);
        $('#AddModal').modal('toggle');
      }
      else if(caller === 'modifycall'){
        $('#modifyTable .tableBody').append(content_line);
        $('#ModifyModal').modal('toggle');
      }

      // Application des liens de supression
      supressor(table_name);
      moveRow(table_name);
    });
}



function supressor($table_name){

  // Application, sur l'évènement Click, de la fonction précédente, sur tous les items modifiant le contenu de la page
  // Récupération de la page cible via l'attribut href
      $('.supress').click(function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        reOrder($table_name);
    });
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

function moveRow(table_name){

  $(table_name + ' .upper').unbind();
  $(table_name + ' .upper').bind('click', function(e){
    var temp = $(this).parent().parent().prev('tr');
    if(temp){
      $(this).parent().parent().after(temp);
    }
    reOrder(table_name);
  });


  $(table_name +  ' .downer').unbind("click");
  $(table_name +  ' .downer').bind('click', function(e){
  var temp = $(this).parent().parent().next('tr');
  if(temp){
    $(this).parent().parent().before(temp);
  }
  reOrder(table_name);
});

}


async function post_load_modal_select_unique(fname, caller){

  $('#closeSelectModal').unbind();
  $('#closeSelectModal').click(function() { 
  $("#SelectModal").modal('hide');

    // --- Réaffichage De la modale appelante  ----                            
      if(caller === 'addcall'){
        $('#AddModal').modal('toggle');
      }
      else if(caller === 'modifycall'){
        $('#ModifyModal').modal('toggle');
      }
  });

  var str = await fetch(fname);
  $('#Selectmodal-body').html(await str.text());

  // --- L'ouverture de l'écran de sélection masque les modales précédentes
  $('#AddModal').modal('hide');
  $('#ModifyModal').modal('hide');


  // --- Au clique sur un équipement, celui ci est ajouté au tableau du modal appelant
    $('.return').click(function(e) {
      e.preventDefault();      
      var value = this.firstChild.nodeValue;

      // --- Réaffichage De la modale appelante  ----                            
      if(caller === 'addcall'){
        $('#addUniqueSelector').attr("value", value);
        $('#AddModal').modal('toggle');
      }
      else if(caller === 'modifycall'){
        $('#modifyUniqueSelector').attr("value", value);
        $('#ModifyModal').modal('toggle');
      }
    });
}


async function bdd_modifier(fname, action){

  var post_params = '';


  if(action === 'delete'){ // ------------------------------------  Préparation pour la suppression de l'élément
    post_params = 'id_delete='+ $('#ModifyModal .id').val() +'';
  }
  else if(action === 'modify'){
    var inputs = $('#ModifyModal input');

    //Envoie des traitements des inputs
    for(i = 0; i < inputs.length ; ++i){
      if($(inputs[i]).attr('type') === 'checkbox')
        post_params += $(inputs[i]).attr('data-input') +'='+ $(inputs[i]).is(':checked');
      else
        post_params += $(inputs[i]).attr('data-input') +'='+ $(inputs[i]).val();
      
      if(i < inputs.length)
        post_params += '&';
    }

    //Envoie des données des tables
    var tableLines = $('#ModifyModal tr');  // --------------------  Préparation pour la modification de l'élément
    for(i = 1; i < tableLines.length ; ++i){
      post_params += 't'+i +'='+ $(tableLines[i].firstChild).text();
      if(i < tableLines.length)
        post_params += '&';
    }
  }
  else if(action === 'create'){
    var inputs = $('#AddModal input');
    console.log(inputs);

    //Envoie des traitements des inputs
    var inputs = $('#AddModal input');
    for(i = 0; i < inputs.length ; ++i){
      if($(inputs[i]).attr('type') === 'checkbox')
        post_params += $(inputs[i]).attr('data-input') +'='+ $(inputs[i]).is(':checked');
      else
        post_params += $(inputs[i]).attr('data-input') +'='+ $(inputs[i]).val();
      
      if(i < inputs.length)
        post_params += '&';
    }

    //Envoie des données des tables
    var tableLines = $('#AddModal tr');
    for(i = 1; i < tableLines.length ; ++i){
      post_params += 't'+i +'='+ $(tableLines[i].firstChild).text();
      if(i < tableLines.length)
        post_params += '&';
    }
  }
  else if(action === 'updatePrefs'){ // ------------------------  Préparation pour la modification des préférences

    //Envoie des traitements des inputs
    var inputs = $('#preferenceModal input');
    for(i = 0; i < inputs.length ; ++i){
      post_params += $(inputs[i]).attr('data-input') +'='+ $(inputs[i]).val();
      if(i < inputs.length)
        post_params += '&';
    }

  }

  var str = await fetch(fname, {  
    method: "POST",  
    body: post_params,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });
  console.log(await str.text());

  if(action === 'updatePrefs')
    window.location.reload(true);
  
}