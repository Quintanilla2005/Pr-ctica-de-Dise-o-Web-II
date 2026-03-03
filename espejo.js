/**
 * Calcula la "hora real" espejo dada una hora en formato "HH:MM".
 *
 * Reglas:
 * - Si minutos === 0: hora real = 12 - hora, minutos = 0
 * - Si minutos !== 0: hora real = 11 - hora, minutos = 60 - minutos
 *
 * @param {string} tiempo - String en formato "HH:MM" (hora en 12h, minutos 00-59)
 * @returns {string} Hora espejo en formato "HH:MM" (siempre con dos dígitos)
 */
function RelojEspejo(tiempo){
    const partes = tiempo.split(":");
    const h = parseInt(partes[0], 10);
    const m = parseInt(partes[1], 10);

    // Normalizamos la hora dentro de 0-11 para evitar negativos con el módulo
    let HoraReal, MinutosReales;
    if (m === 0){
        HoraReal = (12 - h) % 12;
        MinutosReales = 0;
    } else {
        HoraReal = (11 - h) % 12;
        MinutosReales = 60 - m;
    }

    // Aseguramos dos dígitos
    return `${String(HoraReal).padStart(2, '0')}:${String(MinutosReales).padStart(2,'0')}`;
}


/**
 * Valida que el texto tenga el formato HH:MM y rangos válidos (hora 0-12, minutos 0-59).
 * @param {string} texto
 * @returns {boolean}
 */
function validateTime(texto){
    if (!/^\d{1,2}:\d{2}$/.test(texto)) return false;
    const [hh, mm] = texto.split(":").map(s => parseInt(s,10));
    if (Number.isNaN(hh) || Number.isNaN(mm)) return false;
    if (mm < 0 || mm > 59) return false;
    if (hh < 0 || hh > 12) return false; // asumimos 12h
    return true;
}

/**
 * Formatea una cadena de tiempo para mostrarla (pone dos dígitos)
 * @param {string} tiempo
 * @returns {string}
 */
function formatTimeDisplay(tiempo){
    if (!tiempo) return "--:--";
    const partes = tiempo.split(":");
    return `${String(partes[0]).padStart(2,'0')}:${String(partes[1]).padStart(2,'0')}`;
}

// --- Código de interacción DOM ---
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('horaInput');
    const calcBtn = document.getElementById('calcBtn');
    const resultEl = document.getElementById('clockResult');
    const inputDisplay = document.getElementById('clockInputDisplay');
    const copyBtn = document.getElementById('copyBtn');
    const exampleBtns = Array.from(document.querySelectorAll('.example'));

    // Actualiza la visualización del reloj de entrada
    function updateInputDisplay(){
        const val = input.value.trim();
        inputDisplay.textContent = validateTime(val) ? formatTimeDisplay(val) : '--:--';
    }

    // Evento calcular
    function onCalculate(){
        const val = input.value.trim();
        if (!validateTime(val)){
            alert('Entrada inválida. Usa formato HH:MM (hora 0-12, minutos 00-59).');
            return;
        }
        const res = RelojEspejo(val);
        resultEl.textContent = res;
    }

    // Copiar resultado al portapapeles
    function onCopy(){
        const txt = resultEl.textContent.trim();
        if (!txt || txt === '--:--'){
            alert('No hay resultado para copiar.');
            return;
        }
        navigator.clipboard?.writeText(txt).then(()=>{
            alert('Resultado copiado: ' + txt);
        }).catch(()=>{
            alert('No se pudo copiar al portapapeles.');
        });
    }

    // Asociar eventos
    input.addEventListener('input', updateInputDisplay);
    calcBtn.addEventListener('click', onCalculate);
    copyBtn.addEventListener('click', onCopy);
    input.addEventListener('keydown', (e) => { if (e.key === 'Enter') onCalculate(); });

    exampleBtns.forEach(b => {
        b.addEventListener('click', () => {
            input.value = b.textContent.trim();
            updateInputDisplay();
            onCalculate();
        });
    });

    // Inicializar displays
    updateInputDisplay();
});

// Exportar para uso en consola/node si es necesario
if (typeof module !== 'undefined' && module.exports){
    module.exports = { RelojEspejo };
}
