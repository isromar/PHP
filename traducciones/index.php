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
    <title>Traductor</title>
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

    input[type="submit"] {
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

<script>
    // JavaScript para recargar la página al hacer clic en el botón
    let refresh = document.getElementById('refresh');
    refresh.addEventListener('click', () => {
        location.reload();
    });
</script>


<body>
    <h2>Traductor</h2>
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
        echo "<tr><th>Nº</th><th>Inglés</th><th>Español</th><th>Pronunciación</th><th>Acción</th></tr>";
        $contador = 1;  // Contador para numerar las filas de la tabla
        while ($row = $resultado->fetch_assoc()) {
            echo "<tr><td>$contador</td><td>" . $row["english_text"] . "</td><td>" . $row["spanish_text"] . "</td><td>" . $row["pronunciation"] . "</td>";
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
        // Verificar que todos los campos tengan texto
        if (!empty($_POST['spanish']) && !empty($_POST['english']) && !empty($_POST['pronunciation'])) {
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

            // Insertar los valores en la base de datos
            $sql = "INSERT INTO english_spanish (english_text, spanish_text, pronunciation) VALUES ('$english', '$spanish', '$pronunciation')";
            if ($conexion->query($sql) === TRUE) {
                echo "Traducción guardada correctamente";
            } else {
                echo "Error al guardar la traducción: " . $conexion->error;
            }

            // Cerrar la conexión a la base de datos
            $conexion->close();
        } else {
            echo "Todos los campos deben tener texto";
        }
}
    ?>

</body>

</html>