<?php
$carpetaNombre = isset($_GET['??']) ? $_GET['??'] : '';
$carpetaRuta = "./descarga/" . $carpetaNombre;

try {
    if (!file_exists($carpetaRuta) && !empty($carpetaNombre)) {
        mkdir($carpetaRuta, 0755, true);
        $mensaje = "Carpeta '$carpetaNombre' creada con éxito.";
    } elseif (empty($carpetaNombre)) {
        $mensaje = "No se proporcionó un nombre de carpeta válido.";
    } else {
        $mensaje = "La carpeta '$carpetaNombre' ya existe.";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['archivo'])) {
            $archivos = $_FILES['archivo'];

            if (is_array($archivos['name'])) {
                for ($i = 0; $i < count($archivos['name']); $i++) {
                    $archivoTmp = $archivos['tmp_name'][$i];
                    $archivoNombreOriginal = $archivos['name'][$i];

                    // Reemplaza los espacios en blanco con guiones bajos
                    $archivoNombre = str_replace(' ', '_', $archivoNombreOriginal);

                    if (move_uploaded_file($archivoTmp, $carpetaRuta . '/' . $archivoNombre)) {
                        $mensaje = "Archivo '$archivoNombre' subido con éxito.";
                    } else {
                        $mensaje = "Error al subir el archivo '$archivoNombre'.";
                    }
                }
            } else {
                $mensaje = "No se enviaron archivos.";
            }
        }
    }

    if (isset($_POST['eliminarArchivo'])) {
        $archivoAEliminar = $_POST['eliminarArchivo'];
        $archivoRutaAEliminar = $carpetaRuta . '/' . $archivoAEliminar;

        if (file_exists($archivoRutaAEliminar)) {
            if (unlink($archivoRutaAEliminar)) {
                $mensaje = "Archivo '$archivoAEliminar' eliminado con éxito.";
            } else {
                throw new Exception("Error al eliminar el archivo.");
            }
        } else {
            throw new Exception("El archivo '$archivoAEliminar' no existe.");
        }
    }
} catch (Exception $e) {
    $mensaje = "Error: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Compartir archivos</title>
    <script src="parametro.js"></script>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <h1>Compartir archivos <sup class="beta">BETA</sup></h1>
    <div class="content">
        <h3>Envia tus archivos a travez de este sitio, y comparte <br>este enlace temporal: <span>http://localhost/instafile/???=<?php echo $carpetaNombre;?></span></h3>
        <div class="drop-area" id="drop-area">
            <form action="" id="form" method="POST" enctype="multipart/form-data">
            <svg xmlns="http://www.w3.org/2000/svg" fill="#00a2ff" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M246.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 109.3 192 320c0 17.7 14.3 32 32 32s32-14.3 32-32l0-210.7 73.4 73.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-128-128zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-64z"/></svg><br>
                <!-- <input type="file" class="file-input" name="archivo" id="archivo" onchange="document.getElementById('form').submit()"> -->
                <input type="file" class="file-input" name="archivo[]" id="archivo" multiple onchange="uploadFiles(this.files)">
                <label> Suelta aqui tu archivo para empezar a subirlo<br>o</label>
                <p><b>Abre el explorador</b></p> 
            </form>
        </div>    
        <div id="file-list" class="pila">
            <?php
            $targetDir = $carpetaRuta;

            $files = scandir($targetDir);
            $files = array_diff($files, array('.', '..'));

            if (count($files) > 0) {
                echo " <h3 style='margin-bottom:10px;'>Archivos Subidos:</h3>";

                foreach ($files as $file) {
                    echo "<div class='archivos_subidos'>
                    <div><a href='$carpetaRuta/$file' download class='boton-descargar'>$file</a></div>
                    <div>
                    <form action='' method='POST' style='display:inline;'>
                        <input type='hidden' name='eliminarArchivo' value='$file'>
                        <button type='submit' class='btn_delete'>
                            <svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-trash' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'>
                                <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                                <path d='M4 7l16 0' />
                                <path d='M10 11l0 6' />
                                <path d='M14 11l0 6' />
                                <path d='M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12' />
                                <path d='M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3' />
                            </svg>
                        </button>
                    </form>
                </div>
                </div>";
                }
            } else {
                echo "No se han subido archivos.";
            }
            ?>
                </div>
    </div>
    <script src="uploadFiles.js"></script>

</body>

</html>
