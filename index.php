<?php
if (isset($_POST['p']) && isset($_POST['l'])) {
    include_once './com.sine.controlador/ControladorSessionPost.php';
    $cs = new ControladorSessionPost();
    $u = $_POST['p'];
    $c = sha1($_POST['l']);
    $existe = $cs->loginPost($u,$c);
    

    if($existe){
        echo 'Si';
    }else{
        echo '0Usuario o contraseña no validos';
    }
} else {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <!--Cabecera comun de hojas de estilos y scripts-->
            <?php
            include 'com.sine.common/commonhead.php';
            ?>
        </head>
        <!--Cuerpo del index el id no debe modificarse, este permite darle estilos al retso de los elemntos dentro del cuerpo-->
        <body>
            <div class="row full-height">
                <div id="body-index" class="body-index"></div>
                <div id="body-right" class="body-right" >
                    <div class="sm-square"></div>
                    <div class="md-square"></div>
                    <div class="elipse-login"></div>
                </div>
            </div>
            <div class="row">

            </div>
            <script src="js/scriptlogin.js"></script>
        </body>
        <!--fin cuerpo de la pagina-->
        <script>
            window.addEventListener('resize', resetIndex);
            resetIndex();
        </script>
    </html>
<?php } ?>
