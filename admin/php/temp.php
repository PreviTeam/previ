echo
          '<div id="menu" class="dropdown">',
            '<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Mennu',
               '<span class="caret"></span></button>',
                '<ul class="dropdown-menu">',
                   '<li><a tabindex="-1" href="#">Dashboard</a></li>';

              if($status === 'ADMIN'){
              echo
                  '<li class="dropdown-submenu">',
                    '<a class="test" tabindex="-1" href="#">Administration <span class="caret"></span></a>',
                    '<ul class="dropdown-menu">',
                      '<li><a tabindex="-1" href="#">Users</a></li>',
                      '<li><a tabindex="-1" href="#">Visites</a></li>',
                      '<li><a tabindex="-1" href="#">Fiches</a></li>',
                      '<li><a tabindex="-1" href="#">Operations</a></li>',
                    '</ul>',
                  '</li>';
              }

              echo
                  '<li class="dropdown-submenu">',
                    '<a class="test" tabindex="-1" href="#">Passations<span class="caret"></span></a>',
                    '<ul class="dropdown-menu">',
                      '<li><a tabindex="-1" href="#">En Cours</a></li>',
                      '<li><a tabindex="-1" href="#">Historisées</a></li>',
                    '</ul>',
                  '</li>';


              if($status != 'TECH'){
              echo 
                  '<li class="dropdown-submenu">',
                    '<a class="test" tabindex="-1" href="#">Equipements<span class="caret"></span></a>',
                    '<ul class="dropdown-menu">',
                      '<li><a tabindex="-1" href="#">Organisations</a></li>',
                      '<li><a tabindex="-1" href="#">Modèles</a></li>',
                      '<li><a tabindex="-1" href="#">Outils</a></li>',
                    '</ul>',
                  '</li>';
              }
echo
                '</ul>',
          '</div>';