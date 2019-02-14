$(document).ready(function () {  

  // Attribution / réatribution des écouteurs du dashboard
  init_page();

});


/**
 * Modification en BDD d'une fiche vide pour l'attibuer à l'utilisateur en cours
 *
 * @param  fname  : page à charger dans la fenêtre modale
 * @param  id     : id de l'élément à modifier, qui sera transmit via la méthode post
 */
async function loadAttribution(fname, id){

  var i='id='+id;
  var str = await fetch(fname, {  
    method: "POST",  
    body: i,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });

     $('#Page').html(await str.text());
     init_page();
}


// -------------- Chargement et traitement des operations à passer -------------- //

async function loadAsyncPage(fname, id){

  var i='id='+id;
  var str = await fetch(fname, {  
    method: "POST",  
    body: i,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });

  // Récupération du tableau endocé JSON depuis passation.php
  // Le tableau récupéré contient des tableaux de toutes les opérations à réaliser
  // Le premier tableau contient le nombre d'opération déja réalisées
  var temporaire = JSON.parse(await str.text());
  var arr = [];

  for(var x in temporaire){
    arr.push(temporaire[x]);
  }

  // Affichage du contenu de la page avec la premiere opération non rélisée
  var current = parseInt(arr[0][0]) + 1;
  printOperation(arr, current);
  
  $('#adder').html("");

}


function printOperation(arr, current){

  if(arr[current] == null){
    var str = '<div class="content-passation"><h3>Fiche terminée !<h3>'+
              '<button type="button" class="btn btn-success btn-menu">Sauvegarder</button></div>';
      $('#Page').html(str);
      $('.btn-menu').click(function(e){ 
        close_fiche(arr[0][1]);
        
      });

  }else{
    str = '<div class="content-passation">' +
             '<h3 class="title-fiche">'+arr[current][4]+'</h3>'+
             '<p class="phone-text">'+arr[current][2]+'</p>';

    // Affichage des inputs relatype au type de l'opération (Oui/non ou texte)
    if(parseInt(arr[current][3]) === 1){
      str += '<div class="btn-line">' +   
                '<button type="button" class="btn btn-success btn-operation" data-type="btn" data-res="oui">Oui</button>'+
                '<button type="button" class="btn btn-warning btn-operation" data-type="btn" data-res="non">Non</button>'+
              '</div></div>';
    }else{
      str += '<div class="btn-line">' +   
                '<input type="text" id="input-text-res" required>'+
                '<button type="button" class="btn btn-primary btn-operation" data-type="input-text" data-res="non">Valider</button>'+
              '</div></div>';
    }

    str+='<div class="epi-line">';
    for(var i = 0; i < arr[current][5].length; ++i){
      str += print_epi_logo(arr[current][5][i]);  
    }
    str += '</div>';

    $('#Page').html(str);

    $('.btn-operation').click(function(e){

      var res = "null";
      if($(this).attr('data-type') == "btn"){
        res = $(this).attr('data-res');
      }else if($(this).attr('data-type') == "input-text"){
        res = $('#input-text-res').val();
        if(res === "")
          $('#input-text-res').css("border", "solid 2px #d84a3a");
      }

      if(res != null && res != "")
        save_operation(arr[current][0], arr[0][1], res) && printOperation(arr, current+1);

    });
  }

  
}



async function save_operation(operation_id, realisation_fiche_id, res){
  var i='op_id='+operation_id+'&rf_id='+realisation_fiche_id+'&res='+res;

  var str = await fetch('save_operation.php', {  
    method: "POST",  
    body: i,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });
}

async function close_fiche(realisation_fiche_id){
  var i='rf_id='+realisation_fiche_id;
  var str = await fetch('close_fiche.php', {  
    method: "POST",  
    body: i,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });

  $('#Page').html(await str.text());
  $('#adder').html('<button id="adder-btn" type="button" class="btn btn-success btn-adder btn-modal" data-toggle="modal" href="select_modele.php" data-target="#SelectModal">Nouvelle Visite</button>');
  init_page();

  var str = await fetch('historiser.php', {  
    method: "POST",  
    body: i,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });
  
}


/**
 * Selection des Modele pour démarrer une nouvelle visite
 *
 * @param  fname  : page à charger dans la fenêtre modale
 */
async function load_select_modele(fname){

  var str = await fetch(fname);
  $('#Selectmodal-body').html(await str.text());

  $('.reload-select').click(function(e){
    e.preventDefault();
    load_select_outil($(this).attr('href'), $(this).attr('data-id'));
  });

}


/**
 * Selection des Outils pour démarrer une nouvelle visite
 *
 * @param  fname  : page à charger dans la fenêtre modale
 */
async function load_select_outil(fname, id_modele){

  var i='id_modele='+id_modele;

  var str = await fetch(fname, {  
    method: "POST",  
    body: i,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });

  $('#Selectmodal-body').html(await str.text());

  $('.reload-select').click(function(e){
    e.preventDefault();
    load_select_visite($(this).attr('href'), $(this).attr('data-id'), id_modele);
  });

}


/**
 * Selection des Outils pour démarrer une nouvelle visite
 *
 * @param  fname  : page à charger dans la fenêtre modale
 */
async function load_select_visite(fname, id_outil, id_modele){

  var i='id_modele='+id_modele;

  var str = await fetch(fname, {  
    method: "POST",  
    body: i,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });

  $('#Selectmodal-body').html(await str.text());

  $('.reload-select').click(function(e){
    e.preventDefault();
    createBDD($(this).attr('href'), $(this).attr('data-id'), id_modele, id_outil);
  });

}


/**
 * Création d'une nouvelle visite en BDD et des fiches associées
 *
 * @param  fname  : page à charger dans la fenêtre modale
 */
async function createBDD(fname, id_visite, id_modele, id_outil){

  var i='id_modele='+id_modele+'&id_visite='+id_visite+'&id_outil='+id_outil;

  var visiteId = await fetch(fname, {  
    method: "POST",  
    body: i,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });

  var i2 = 'rv_id='+ await visiteId.text();
  console.log(i2);
  var str = await fetch('historiser.php', {  
    method: "POST",  
    body: i2,
    headers: { 'Content-type': 'application/x-www-form-urlencoded' } 
  });

  $("#SelectModal").modal('hide');
  $('#Page').html(await str.text());
  init_page();
}


function init_page(){
  // Application de l'écouteur pour le chargement de la page de recherche
  $('.attribute').click(function(e) {
      e.preventDefault();
      loadAttribution( $(this).attr('href'), $(this).attr('data-id'));
    });

  // Ecouteur du bouton d'ajout d'une nouvelle visite
  $('#adder-btn').click(function(e){
      e.preventDefault();
      load_select_modele( $(this).attr('href'));
  });

  // Fermeture de la fenêtre modale
  $('#closeSelectModal').click(function(e) {
    $("#SelectModal").modal('hide');
  });


  // Ecouteur du bouton de lancement d'une fiche
  $('.btn-link').click(function(e) {
    loadAsyncPage( $(this).attr('href'), $(this).attr('data-id'));
  });
}

function print_epi_logo(epi_designation){

  switch(epi_designation){ 

    case 'Casque' :
      return '<img src="../img/icones/PNG/epi/helmet.png" alt="casque" height="30">';
      break;

    case 'Lunettes' :
      return '<img src="../img/icones/PNG/epi/safety-glasses.png" alt="lunettes" height="30">';
      break;

    case 'Anti-bruit' :
      return '<img src="../img/icones/PNG/epi/ear-protection1.png" alt="anti-bruit" height="30">';
      break;

    case 'Gants' :
      return '<img src="../img/icones/PNG/epi/gants.png" alt="gants" height="30">';
      break;

    case 'isolant electriques' :
      return '<img src="../img/icones/PNG/epi/combinaison.png" alt="combinaison" height="30">';
      break;

    case 'Gilet de signalisation' :
       return '<img src="../img/icones/PNG/epi/rescue.png" alt="gilet" height="30">';
      break;

    case 'Masque' :
      return '<img src="../img/icones/PNG/epi/gasmask.png" alt="masque" height="30">';
      break;

    case 'Combinaison' :
      return '<img src="../img/icones/PNG/epi/combinaison.png" alt="combinaison" height="30">';
      break;

    case 'Chaussures de sécurité' :
      return '<img src="../img/icones/PNG/epi/boots.png" alt="Chaussure de sécurité" height="30">';
      break;

  }
}


