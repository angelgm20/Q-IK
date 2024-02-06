<?php

if (isset($_FILES["certificado-fiel"])) {
    $rfc = strtoupper($_POST['rfc-fiel']);
    $tempfile = ($_FILES['certificado-fiel']['tmp_name']);
    $file = $_FILES["certificado-fiel"];
    $nombre = $file["name"];
    $tipo = $file["type"];
    $ruta_provisional = $file["tmp_name"];
    $size = $file["size"];
    $filename = pathinfo($_FILES['certificado-fiel']['name'], PATHINFO_FILENAME);
    $ext = pathinfo($_FILES['certificado-fiel']['name'], PATHINFO_EXTENSION);
    if ($ext == "cer") {
        $carpeta = "../temporal/Fiel";
        $src = $carpeta . '/' . $rfc . '.' . $ext;
        move_uploaded_file($ruta_provisional, $src);
        /* $cert_content = file_get_contents( $src );

          $res = openssl_x509_read( $cert_content );

          $data = openssl_x509_parse( $res );
          var_dump( $data );
          $shellserial = "openssl x509 -in $src -noout -subject -text -inform der";

          $exc = shell_exec($shellserial); */
        echo '1Carga Existosa';
    } else {
        echo '0El tipo de archivo no es valido';
    }
}

