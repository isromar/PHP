<!--
Este archivo surge porque al leer textos en inglés y aparecer algunas palabras que desconozco, pienso que estaría bien poder disponer de un diccionario propio donde poder almacenarlas y de ese modo facilitar la memorización.
Funciona con un formulario que permite ingresar pares de frases en inglés y español. Almacena la información en una base de datos y muestra dinámicamente los registros ordenados alfabéticamente, además permite borrar los ya existentes.

@Author Isabel Rodenas Marin

Para que funcione correctamente hay que crear la base de datos
CREATE DATABASE IF NOT EXISTS traducciones;
CREATE TABLE english_spanish (
    id INT AUTO_INCREMENT PRIMARY KEY,
    english_text VARCHAR(255) NOT NULL,
    spanish_text VARCHAR(255) NOT NULL
);
-->

<!DOCTYPE html>
<html>

<head>
    <title>Traductor</title>
</head>

<style>
    label {
        font-size: large;
        font-weight: 800;
        margin-left: 20px;
    }

    input[type="text"] {
        width: 30%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        margin-bottom: 10px;
        text-align: center;
        /* Centrar el texto dentro del input */
        font-size: 16px;
    }

    input[type="submit"] {
        background-color: darkred;
        color: white;
        /* Aumenta el padding para hacer el botón más grande */
        border: none;
        border-radius: 4px;
        cursor: pointer;
        padding: 3px 5px 3px 5px;
    }

    input[name="submit"] {
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
        width: 50%;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }
</style>


<body>

    <h2>Traductor</h2>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="setTimeout(function(){location.reload();},10);">
        <label for="english">Inglés:</label>
        <input type="text" id="english" name="english">

        <label for="spanish">Español:</label>
        <input type="text" id="spanish" name="spanish">

        <input type="submit" name="submit" value="Guardar">
    </form>

    <?php
    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "traducciones");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error en la conexión: " . $conexion->connect_error);
    }

    // Obtener las traducciones de la base de datos
    $resultado = $conexion->query("SELECT id, english_text, spanish_text FROM english_spanish ORDER BY english_text");

    // Mostrar las traducciones en el formulario
    if ($resultado->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Inglés</th><th>Español</th><th>Acción</th></tr>";
        while ($row = $resultado->fetch_assoc()) {
            echo "<tr><td>" . $row["english_text"] . "</td><td>" . $row["spanish_text"] . "</td>";
            echo "<td><form method='post' action='" . $_SERVER['PHP_SELF'] . "'><input type='hidden' name='id' value='" . $row["id"] . "'><input type='submit' name='delete' value='Borrar'></form></td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 resultados";
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
        // Verificar que ambos campos tengan texto
        if (!empty($_POST['spanish']) && !empty($_POST['english'])) {
            // Conectar a la base de datos
            $conexion = new mysqli("localhost", "root", "", "traducciones");

            // Verificar la conexión
            if ($conexion->connect_error) {
                die("Error en la conexión: " . $conexion->connect_error);
            }

            // Obtener los valores del formulario
            $spanish = $conexion->real_escape_string($_POST['spanish']);
            $english = $conexion->real_escape_string($_POST['english']);

            // Insertar los valores en la base de datos
            $sql = "INSERT INTO english_spanish (english_text, spanish_text) VALUES ('$english', '$spanish')";
            if ($conexion->query($sql) === TRUE) {
                echo "Traducción guardada correctamente";
            } else {
                echo "Error al guardar la traducción: " . $conexion->error;
            }

            // Cerrar la conexión a la base de datos
            $conexion->close();
        } else {
            echo "Ambos campos deben tener texto";
        }
    }
    ?>

</body>

</html>