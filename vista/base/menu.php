<!-- ------------------------------------------------------------------------------------------
MENU PRINCIPAL
------------------------------------------------------------------------------------------- -->

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: Purple;">
<div class="container-fluid">
    
    <!-- se inicializa el icono de menú -->
    <?php $iconoMenu = 'iconoVioletRelatos.png'; ?>

    <!-- se inicializa el array de items activos -->
    <?php $arrItemsActivos = array(
        'inicio' => '',
        'relatos' => '',
        'guiones' => '',
        'personajes' => '',
        'caracteristicas' => '',
        'relaciones' => '',
        'palabrasclave' => ''
    ); ?>

    <!-- Se obtiene el item activo del menú -->
    <?php switch ($vistaActiva) {

        case 'VistaCaracteristicas': 
            $itemActivo = $arrItemsActivos['caracteristicas'] = 'active'; 
            break;

        case 'VistaGuiones':
        case 'VistaParrafo':
            $itemActivo = $arrItemsActivos['guiones'] = 'active';
            break;

        case 'VistaPalabrasClave':
        case 'VistaValoresPalabraClave':
            $itemActivo = $arrItemsActivos['palabrasclave'] = 'active';
            break;

        case 'VistaPersonajes':
        case 'VistaPersonajeImagenes':
        case 'VistaPersonajeRelaciones':
            $itemActivo = $arrItemsActivos['personajes'] = 'active';
            break;

        case 'VistaRelaciones':
            $itemActivo = $arrItemsActivos['relaciones'] = 'active';
            break;

        case 'VistaRelatos':
        case 'VistaVisualizador':
            $itemActivo = $arrItemsActivos['relatos'] = 'active';
            break;

        case 'VistaLogin':
        default:
            $itemActivo = $arrItemsActivos['inicio'] = 'active';
            $iconoMenu = 'iconoBlancoRelatos.png';
            break;

    } ?>

    <!-- Icono de la aplicación para regresar al índice-->
    <!-- El efecto de cambio de color del icono se realiza en jquery en pie.php -->
    <a class="navbar-brand" href="/relatosapp/usuario">
        <img 
            class="iconoMenu" 
            src="/relatosapp/res/iconos/<?php echo $iconoMenu; ?>"
            alt=""         
            width="30" 
            height="24">
    </a>

    <!-- se añade el responsive del Navbar-->
    <button 
        class="navbar-toggler" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#navbarNavDropdown" 
        -controls="navbarNavDropdown" 
        aria-expanded="false" 
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- área para el resto de accesos del aplicación-->
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
        
        <li class="nav-item">
            <a 
                class="nav-link itemMenu <?php echo $arrItemsActivos['relatos']; ?>" 
                aria-current="page" 
                href="/relatosapp/relatos/">Relatos</a></li>
        
        <li class="nav-item">
            <a 
                class="nav-link itemMenu <?php echo $arrItemsActivos['guiones']; ?>" 
                href="/relatosapp/guiones/">Guiones</a></li>
        
        <li class="nav-item">
            <a 
                class="nav-link itemMenu <?php echo $arrItemsActivos['personajes']; ?>" 
                href="/relatosapp/personajes/">Personajes</a></li>
        
        <li class="nav-item">
            <a 
                class="nav-link itemMenu <?php echo $arrItemsActivos['caracteristicas']; ?>" 
                href="/relatosapp/caracteristicas/">Características</a></li>
        
        <li class="nav-item">
            <a 
                class="nav-link itemMenu <?php echo $arrItemsActivos['relaciones']; ?>" 
                href="/relatosapp/relaciones/">Relaciones</a></li>
        
        <li class="nav-item">
            <a 
                class="nav-link itemMenu <?php echo $arrItemsActivos['palabrasclave']; ?>" 
                href="/relatosapp/palabrasclave/">Palabras clave</a></li>

</ul>                

    <!-- Si el usuario está logeado se muestra el botón para cerrar sesión -->
    <?php if (isset($_SESSION['usuario'])) { ?>
  
        <!-- se muestra el botón  para cerrar sesión -->
        <form class="d-flex ms-auto mb-2 mb-lg-0" action="/relatosapp/controlador/logout.php" method="POST">
            <button class="btn btn-outline outlineViolet" type="submit">
                <span>
                    <i 
                        class="bi bi-power Violet" 
                        data-placement="top"
                        data-bs-toggle="tooltip"
                        data-bs-html="true"  
                        title="<em>Cerrar sesión</em>"
                        style="font-size:1rem; color:Violet;"></i>
                </span>
            </button>
        </form>

    <?php  } ?>

    </div>

</div>
</nav>
