<!--
Esta web funciona con un formulario que permite ingresar pares de frases en inglés y español. Almacena la información en una base de datos y muestra dinámicamente los registros ordenados alfabéticamente, además permite borrar los ya existentes.

@Author Isabel Rodenas Marin

Para que funcione correctamente hay que crear la base de datos

CREATE DATABASE IF NOT EXISTS traducciones;
CREATE TABLE english_spanish (
    id INT AUTO_INCREMENT PRIMARY KEY,
    english_text VARCHAR(255) NOT NULL,
    spanish_text VARCHAR(255) NOT NULL,
    pronunciation VARCHAR(255)
);
-->

<!DOCTYPE html>
<html>

<head>
    <title>Diccionario personal</title>
</head>

<style>
    label {
        font-size: large;
        font-weight: 800;
        margin-left: 20px;
    }

    input[type="text"] {
        width: 20%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        margin-bottom: 10px;
        text-align: center;
        /* Centrar el texto dentro del input */
        font-size: 16px;
    }
    
    input#pronunciation {
        width: 10%;
    }

    input[type="submit"], #play {
        background-color: darkred;
        color: white;
        /* Aumenta el padding para hacer el botón más grande */
        border: none;
        border-radius: 4px;
        cursor: pointer;
        padding: 3px 5px 3px 5px;
    }

    input[name="submit"], #refresh {
        background-color: #04AA6D;
        color: white;
        /* Aumenta el padding para hacer el botón más grande */
        border: none;
        border-radius: 4px;
        cursor: pointer;
        padding: 15px;
    }

    table {
        margin-top: 2%;
        border-collapse: collapse;
        width: 70%;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        width: 2%;
    }

    td:nth-child(2), td:nth-child(3) {
    width: 20%;
    }

    td:nth-child(4) {
    width: 5%;
    }

    th {
        background-color: #f2f2f2;
    }

    #scrollToTop {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 99;
    border: none;
    outline: none;
    background-color: red;
    color: white;
    cursor: pointer;
    padding: 15px;
    border-radius: 10px;
    }

    #play {
        padding-left: 15px;
        padding-right: 15px;
        background-color: #04AA6D;
    }

</style>

<script>
    // JavaScript para recargar la página al hacer clic en el botón
    let refresh = document.getElementById('refresh');
    refresh.addEventListener('click', () => {
        location.reload();
    });

    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("scrollToTop").style.display = "block";
        } else {
            document.getElementById("scrollToTop").style.display = "none";
        }
    }

    function topFunction() {
        document.body.scrollTop = 0; // Para Safari
        document.documentElement.scrollTop = 0; // Para Chrome, Firefox, IE y Opera
    }

    function playAudio(texto) {
        var synth = window.speechSynthesis;
        var utterance = new SpeechSynthesisUtterance(texto);
        utterance.lang = 'en-GB'; // Para inglés británico
        synth.speak(utterance);
    }

</script>


<body>
    <h2>Diccionario personal</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="english">Inglés:</label>
        <input type="text" id="english" name="english">

        <label for="spanish">Español:</label>
        <input type="text" id="spanish" name="spanish">

        <label for="pronunciation">Pronunciación:</label>
        <input type="text" id="pronunciation" name="pronunciation">

        <input type="submit" name="submit" value="Guardar">
        <button id="refresh">Recargar</button>
    </form>

    <?php
    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "traducciones");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error en la conexión: " . $conexion->connect_error);
    }

    // Obtener las traducciones de la base de datos
    $resultado = $conexion->query("SELECT id, english_text, spanish_text, pronunciation FROM english_spanish ORDER BY english_text");

    // Mostrar las traducciones en el formulario
    if ($resultado->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Nº</th><th>Inglés</th><th>Español</th><th>Pronunciación</th><th>Reproducir</th><th>Acción</th></tr>";
        $contador = 1;  // Contador para numerar las filas de la tabla
        while ($row = $resultado->fetch_assoc()) {
            echo "<tr><td>$contador</td><td>" . $row["english_text"] . "</td><td>" . $row["spanish_text"] . "</td><td>" . $row["pronunciation"] . "</td><td><button id='play' onclick=\"playAudio('" . $row["english_text"] . "')\">Play</button></td>";
            echo "<td><form method='post' action='" . $_SERVER['PHP_SELF'] . "'><input type='hidden' name='id' value='" . $row["id"] . "'><input type='submit' name='delete' value='Borrar'></form></tr>";
            $contador++;
        }
        echo "</table>";
    } else {
        echo "0 resultados";

    // Recargar la página
    echo "<meta http-equiv='refresh' content='0'>";
    }

    // Procesar la eliminación cuando se envíe
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
        // Conectar a la base de datos
        $conexion = new mysqli("localhost", "root", "", "traducciones");

        // Verificar la conexión
        if ($conexion->connect_error) {
            die("Error en la conexión: " . $conexion->connect_error);
        }

        // Obtener el ID a eliminar de forma segura
        $id = $conexion->real_escape_string($_POST['id']);

        // Eliminar la fila de la base de datos
        $sql = "DELETE FROM english_spanish WHERE id = $id";
        if ($conexion->query($sql) === TRUE) {
            echo "Registro eliminado correctamente";
        } else {
            echo "Error al eliminar el registro: " . $conexion->error;
        }

        // Cerrar la conexión a la base de datos
        $conexion->close();

        // Recargar la página
        echo "<meta http-equiv='refresh' content='0'>";
    }
    ?>

    <?php
    // Procesar el formulario cuando se envíe
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar que los campos tengan texto
        if (!empty($_POST['spanish']) && !empty($_POST['english'])) {
            // Conectar a la base de datos
            $conexion = new mysqli("localhost", "root", "", "traducciones");

            // Verificar la conexión
            if ($conexion->connect_error) {
                die("Error en la conexión: " . $conexion->connect_error);
            }

            // Obtener los valores del formulario y eliminar espacios al inicio y final
            $spanish = $conexion->real_escape_string(trim($_POST['spanish']));
            $english = $conexion->real_escape_string(trim($_POST['english']));
            $pronunciation = $conexion->real_escape_string(trim($_POST['pronunciation']));

            // Consulta para verificar si el término en inglés ya existe
            $consulta = "SELECT id FROM english_spanish WHERE english_text = '$english'";
            $resultado = $conexion->query($consulta);

            // Verificar si se encontraron resultados
            if ($resultado->num_rows > 0) {
                echo "<script>alert('El término en inglés ya existe en la base de datos');</script>";
            } else {
                // Insertar los valores en la base de datos
                $sql = "INSERT INTO english_spanish (english_text, spanish_text, pronunciation) VALUES ('$english', '$spanish', '$pronunciation')";
                if ($conexion->query($sql) === TRUE) {
                    echo "Traducción guardada correctamente";
                } else {
                    echo "Error al guardar la traducción: " . $conexion->error;
                }

                // Cerrar la conexión a la base de datos
                $conexion->close();
            }

        } else {

        }
        
}
    ?>

    <button onclick="topFunction()" id="scrollToTop" title="Go to top">▲</button>

</body>

</html>