<?php
//$arreglo_permisos = explode(",", $_SESSION["perfil"]->PERMISOS);
$modulo_permisos = $_SESSION["permisos"];
?>
<div class="col-md-3 left_col">
        <div class="left_col">

          <div class="navbar nav_title" style="border: 0;">
            <a href="." class="site_title"><img src="./diff/<?php echo $global_diffname; ?>/logo.png" style="width: 155px; margin-left: 20px;"></a>
          </div>
          <div class="clearfix"></div>

          <!-- menu prile quick info -->
          <div class="profile">
            <div class="profile_pic">
              <img src="<?php echo $str_user_image; ?>" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span> <?php echo  $_SESSION["username"]; ?></span>
             <!-- <h2> <?php print_r($_SESSION["perfil"]->PERFIL); ?> </h2>-->
            </div>
          </div>
          <!-- /menu prile quick info -->

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            <div class="menu_section">
              <h3 style="position: relative; top: 5px;">Menú principal</h3>
              <ul class="nav side-menu">
                <li><a href="."><i class="fa fa-area-chart"></i> Estadísticas</a>
                </li>
          
                
    <?php
        echo '<li><a><i class="fa fa-book"></i> Catálogos </a>';
        echo '   <ul class="nav child_menu" style="display: none">';
        if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1) {
           
           echo '     <li><a href="./?pagina=servicios">Servicios</a>';
           echo '     </li>';
           echo '     <li><a href="./?pagina=normas">Normas</a>';
           echo '     </li>';
           echo '     <li><a href="./?pagina=sectores">Sectores</a>';
           echo '     </li>';
		   echo '     <li><a href="./?pagina=sector_nace">Sector NACE</a>';
           echo '     </li>';
           echo '     <li><a href="./?pagina=servicios_tipo">Tipos de servicio</a>';
           echo '     </li>';
		   echo '     <li><a href="./?pagina=normas_tiposervicio">Normas-Tipo de servicio</a>';
           echo '     </li>';
		   echo '     <li><a href="./?pagina=i_servicios_contratados_tipos_cambios">Tipos de cambio para servicios contratados</a>';
           echo '     </li>';
           echo '     <li><a href="./?pagina=tramites">Trámites</a>';
           echo '     </li>';
		   echo '     <li><a href="./?pagina=documentos">Documentos</a>';
           echo '     </li>';
           echo '     <li><a href="./?pagina=cursos">Cursos</a>';
           echo '     </li>'; 
        }
        if ($modulo_permisos["CLIENTES"]["catalogos"] == 1) {
           echo '     <li><a href="./?pagina=entidad_tipo">' . $str_tipo_entidad . '</a>';
           echo '     </li>';
           echo '     <li><a href="./?pagina=persona_tipo">' . $str_tipo_persona . '</a>';
           echo '     </li>';
        }
        if ($modulo_permisos["CRM"]["catalogos"] == 1) {
		   echo '     <li><a href="./?pagina=catalogos&catalogo=prospecto_origen">Prospecto origen</a>';
           echo '     </li>';
		   echo '     <li><a href="./?pagina=catalogos&catalogo=prospecto_propuesta_estado">Prospecto propuesta estado</a>';
           echo '     </li>';
		   echo '     <li><a href="./?pagina=catalogos&catalogo=prospecto_tipo_contrato">Prospecto tipo de contrato</a>';
           echo '     </li>';
		   echo '     <li><a href="./?pagina=catalogos&catalogo=prospecto_estatus_seguimiento">Prospecto estatus seguimiento</a>';
           echo '     </li>';
		   echo '     <li><a href="./?pagina=prospecto_porcentaje">Prospecto porcentaje</a>';
           echo '     </li>';
		   echo '     <li><a href="./?pagina=catalogos&catalogo=prospecto_competencia">Prospecto competencia</a>';
           echo '     </li>';
		   echo '     <li><a href="./?pagina=tarifa_cotizacion">Tarifas</a>';
           echo '     </li>';
		   echo '     <li><a href="./?pagina=tarifa_cotizacion_adicional">Tarifas Adicionales</a>';
           echo '     </li>';
        } 
        echo '   </ul>';
        echo ' </li>';  

        if ($modulo_permisos["AUDITORES"]["ver"] == 1) {
            echo '<li><a><i class="fa fa-users"></i> ' .  $str_personal_tecnico . ' </a>';
            echo '  <ul class="nav child_menu" style="display: none">';
			echo '      <li><a href="./?pagina=auditores_agenda_general">Agenda general</a>';
            echo '      </li>';
			echo '      <li><a href="./?pagina=certi_auditores">CERTI</a>';
            echo '      </li>';
            echo '      <li><a href="./?pagina=auditores">' .  $str_catalogo_personal_tecnico . '</a>';
            echo '      </li>';
            echo '      <li><a href="./?pagina=auditores-roles">Roles</a>';
            echo '      </li>';
            echo '  </ul>';
            echo ' </li>';
        } 
                
         if ($modulo_permisos["CLIENTES"]["ver"] == 1) {
            echo '<li><a><i class="fa fa-industry"></i> Clientes </a>';
            echo '  <ul class="nav child_menu" style="display: none">';
            echo '      <li><a href="./?pagina=clientes">Base</a></li>';
            echo '  </ul>';
            echo '</li>';
          }

          if ($modulo_permisos["CRM"]["ver"] == 1) {
            echo '<li><a><i class="fa fa-industry"></i> CRM </a>';
            echo '  <ul class="nav child_menu" style="display: none">';
            echo '      <li><a href="./?pagina=prospecto">Prospectos</a></li>';
            echo '      <li><a href="./?pagina=agenda_prospectos">Agenda de Prospectos</a></li>';
            echo '     <li><a href="./?pagina=reporte_prospecto">Reporte de Prospectos</a></li>';
            echo '     <li><a href="./?pagina=tipo_asunto">Tipo Asunto</a></li>';
            echo '  </ul>';
            echo '</li>';
          }
				
          if ($modulo_permisos["COTIZADOR"]["ver"] == 1) {
            echo ' <li>';
            echo '  <a href="./?pagina=cotizador">';
            echo '    <i class="fa fa-usd" aria-hidden="true"></i> Cotizador ';
            echo '  </a>';
            echo '</li>';
          }
          
          if ($modulo_permisos["SERVICIOS"]["ver"] == 1) {
            echo '<li><a><i class="fa fa-gear"></i> Programación </a>';
            echo '   <ul class="nav child_menu" style="display: none">';
            echo '      <li><a href="./?pagina=agenda_servicios">Agenda</a></li>';
            echo '      <li><a href="./?pagina=servicio_cliente_etapa">Clientes</a></li>';
            echo '      <li><a href="./?pagina=sg_tipos_auditoria">Tipos de ' . strtolower($str_auditoria) . '</a></li>';
            echo '      <li><a href="./?pagina=sg_status_auditoria">Status de ' .  strtolower($str_auditoria) . '</a></li>';
            echo '      <li><a href="./?pagina=cursos_programados">Cursos Programados</a></li>';
            echo '  </ul>';
            echo '</li>';
          }

          if ($modulo_permisos["FACTURACION"]["ver"] == 1) {
            echo '<li><a><i class="fa fa-file"></i> Facturación </a>';
            echo '   <ul class="nav child_menu" style="display: none">';
            echo '      <li><a href="./?pagina=solicitudes_facturacion">Solicitudes</a></li>';
            echo '  </ul>';
            echo '</li>';
          }
          
          if ($modulo_permisos["EXPEDIENTES"]["ver"] == 1) {
            echo '<li><a><i class="fa fa-folder-open"></i> Expediente </a>';
            echo '         <ul class="nav child_menu" style="display: none">';
            echo '            <li><a href="./?pagina=tipo_expediente">Tipo de Expediente</a></li>';
            echo '            <li><a href="./?pagina=tipo_documento">Tipo de Documento</a></li>';
            echo '            <li><a href="./?pagina=reporte_tareas_documentos">Reporte Tareas de Documentos</a></li>';
            echo '            <li><a href="./?pagina=reporte_documentos">Reporte Documentos</a></li>';
            //echo '  <li><a href="./?pagina=tabla_entidades">Tabla Entidades</a></li>';
            echo '        </ul>';
            echo '</li>';
          }

          if ($modulo_permisos["USUARIOS"]["ver"] == 1) {
            echo '<li>';
            echo '  <a><i class="fa fa-address-book-o"></i> Usuarios </a>';
            echo '  <ul class="nav child_menu" style="display: none">';
            echo '      <li><a href="./?pagina=usuariosc">Ver</a></li>';
            echo '     <li><a href="./?pagina=perfilesc">Perfiles</a></li>';
            echo '  </ul>';
            echo '</li>';
          }

        if ($modulo_permisos["REPORTES"]["ver"] == 1) {
            echo '<li>';
            echo '  <a><i class="fa fa-list-alt"></i> Reportes </a>';
            echo '  <ul class="nav child_menu" style="display: none">';
            echo '      <li><a href="./?pagina=reportes">Ver</a></li>';
            echo '  </ul>';
            echo '</li>';
        }
        if ($modulo_permisos["EMPLEADOS"]["ver"] == 1) {
            echo '<li>';
            echo '  <a><i class="fa fa-user-circle-o"></i> Personal Interno </a>';
            echo '  <ul class="nav child_menu" style="display: none">';
            echo '      <li><a href="./?pagina=personal_interno">Ver</a></li>';
            echo '  </ul>';
            echo '</li>';
        }
        echo ' <li>';
        echo '  <a href="./?pagina=lista_usuarios_graficas">';
        echo '    <i class="fa fa-area-chart" aria-hidden="true"></i> Gráficas por usuario ';
        echo '  </a>';
        echo '</li>';

        ?>

        
      </div>

    </div>

  </div>
</div>