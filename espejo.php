<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"> <!-- Define la codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Hace la página responsiva -->
    <title>Reloj Espejo - Ejemplo</title> <!-- Título de la página -->
</head>
<body>
    <!-- Formulario simple que envía el tiempo al mismo script usando GET (puede ser POST también) -->
    <form method="get" action=""> <!-- El atributo action vacío envía el formulario al mismo archivo -->
        <label for="tiempo">Introduce un tiempo (HH:MM):</label> <!-- Etiqueta para el campo de entrada -->
        <input type="text" id="tiempo" name="tiempo" placeholder="10:00" /> <!-- Campo donde el usuario ingresa el tiempo -->
        <button type="submit">Calcular espejo</button> <!-- Botón para enviar el formulario -->
    </form>

    <?php
    // A partir de aquí empieza el bloque PHP que contiene la lógica y comentarios por línea.

    // Define la función RelojEspejo que recibe una cadena de tiempo en formato "HH:MM".
    function RelojEspejo($tiempo){
        // Separa la cadena por ":" en un arreglo con dos elementos: [hora, minuto]
        $partes = explode(":", $tiempo);
        // Convierte la parte de la hora a entero (por si viene con ceros a la izquierda)
        $h = intval($partes[0]);
        // Convierte la parte de los minutos a entero
        $m = intval($partes[1]);

        // Si los minutos son exactamente 0, la fórmula del espejo usa 12 - hora
        if ($m == 0){
            // Calcula la hora reflejada cuando los minutos son 0.
            // El módulo asegura que el resultado quede en el rango 0..11.
            $Hora_real = (12 - $h) % 12;
            // Cuando los minutos originales son 0, los minutos reflejados también son 0
            $Minutos_Reales = 0;
        }else{
            // Si hay minutos distintos de 0, la hora reflejada se calcula como 11 - hora
            $Hora_real = (11 - $h) % 12;
            // Los minutos reflejados son 60 menos los minutos originales
            $Minutos_Reales = 60 - $m;
        }
        // Devuelve la hora en formato "HH:MM" con dos dígitos para hora y minutos
        return sprintf("%02d:%02d", $Hora_real, $Minutos_Reales);
    }

    // --- Uso de la variable de request ---
    // Recupera el valor enviado desde el formulario usando la superglobal $_REQUEST
    // (contiene los datos de GET, POST y COOKIES). Aquí usamos GET por el formulario.
    $tiempo_enviado = isset($_REQUEST['tiempo']) ? trim($_REQUEST['tiempo']) : '';

    // Comprueba si el usuario ha enviado algún valor
    if ($tiempo_enviado !== ''){
        // Validación básica: que tenga formato H:M o HH:MM
        // preg_match devuelve 1 si coincide, 0 si no.
        if (preg_match('/^\d{1,2}:\d{2}$/', $tiempo_enviado)){
            // Extrae hora y minutos para validar rangos válidos
            list($hora_val, $min_val) = explode(":", $tiempo_enviado);
            $hora_val = intval($hora_val); // convierte la hora a entero
            $min_val = intval($min_val);   // convierte los minutos a entero

            // Verifica que la hora esté entre 0 y 23 y los minutos entre 0 y 59
            if ($hora_val >= 0 && $hora_val <= 23 && $min_val >= 0 && $min_val <= 59){
                // Calcula el espejo usando la función definida arriba
                $resultado = RelojEspejo($tiempo_enviado);
                // Muestra el resultado al usuario en el navegador
                echo "<p>Tiempo original: <strong>" . htmlspecialchars($tiempo_enviado) . "</strong></p>"; // Protege la entrada con htmlspecialchars
                echo "<p>Tiempo espejo: <strong>" . htmlspecialchars($resultado) . "</strong></p>";
            } else {
                // Mensaje si los valores numéricos están fuera de rango
                echo "<p>Formato de hora inválido: hora o minutos fuera de rango.</p>";
            }
        } else {
            // Mensaje si el formato no coincide con HH:MM
            echo "<p>Formato inválido. Usa HH:MM (por ejemplo: 09:30).</p>";
        }
    } else {
        // Si no se envió valor, muestra unos ejemplos fijos usando la función
        echo "<p>Ejemplos calculados automáticamente:</p>";
        echo "<pre>"; // Abre un bloque preformateado para mostrar ejemplos alineados
        // Calcula y muestra varios ejemplos de reloj espejo
        echo "10:00 -> " . RelojEspejo("10:00") . "\n";
        echo "03:40 -> " . RelojEspejo("03:40") . "\n";
        echo "00:00 -> " . RelojEspejo("00:00") . "\n";
        echo "01:45 -> " . RelojEspejo("01:45") . "\n";
        echo "</pre>"; // Cierra el bloque pre
    }

    ?>
</body>
</html>

variables, tipos de datos, estructuras de control, indicadores, 