import FormUtilities from './FormUtilities.js';

const FETCH_REPORTE_URL = `${window.location.origin}/nexen_operaciones/request/finanzas/fetchReporteDocumentosFaltantes.php`;

const btnReporteDocsFaltantes = $('#btnReporteDocsFaltantes');
const btnDescargarReporteDocsFaltantes = $('#btnDescargarReporteDocsFaltantes');
const btnCerrarModalReporte = $('#btnCerrarModalReporte');
const modalReporteDocsFaltantes = $('#modalReporteDocsFaltantes');
const formDocsFaltantes = new FormUtilities('#formReporteDocsFaltantes');

/**
 * Reestablece el texto original al botón de descargar y activa los botones de
 * desactivar y cerrar.
 */
function resetModalUI() {
    btnDescargarReporteDocsFaltantes.text('Descargar');
    btnDescargarReporteDocsFaltantes.prop('disabled', false);
    btnCerrarModalReporte.prop('disabled', false);
};

/**
 * Detectar el evento `click` en el boton del menú para abrir el modal para
 * generar el reporte.
 */
btnReporteDocsFaltantes.on('click', function () {
    modalReporteDocsFaltantes.modal('show');
});

modalReporteDocsFaltantes.on('hidden.bs.modal', () => {
    resetModalUI();
    formDocsFaltantes.reset();
});

/**
 * Detecta cuando el usuario da clic en el boton de descargar reporte.
 */
btnDescargarReporteDocsFaltantes.on('click', function () {
    formDocsFaltantes.validarCampos();
    const formData = formDocsFaltantes.data;

    if (!formDocsFaltantes.validarUserInput()) {
        resetModalUI();
        return;
    }

    btnDescargarReporteDocsFaltantes.text('Descargando...');
    btnDescargarReporteDocsFaltantes.prop('disabled', true);
    btnCerrarModalReporte.prop('disabled', true);

    downloadReporteRequest(formData);
});

/**
 * Realiza la petición para consultar y descargar el reporte.
 * @param {Object} data Objeto con la fecha a enviar.
 */
function downloadReporteRequest(data) {
    fetch(`${FETCH_REPORTE_URL}?mes=${data.mes}&anio=${data.anio}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error('Ocurrió un error al generar el archivo Excel.');
            }

            // Verificar el tipo de contenido de la respuesta
            const contentType = response.headers.get('Content-Type');

            if (contentType.includes('application/json')) {
                // Si el tipo de contenido es JSON
                return response.json(); // Parsear la respuesta como JSON
            } else if (contentType.includes('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
                // Si el tipo de contenido es XLSX
                return response.blob(); // Obtener la respuesta como un Blob
            } else {
                // Si el tipo de contenido no es reconocido
                throw new Error('Tipo de contenido no compatible');
            }
        })
        .then((data) => {
            /**
             * Verificar si la respuesta es un objeto.
             */
            if (Object.keys(data).length > 0) {
                // Si es JSON
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                });

                formDocsFaltantes.reset();
                resetModalUI();
            } else if (data instanceof Blob) {
                // Crea un enlace para descargar el archivo Excel
                const url = window.URL.createObjectURL(new Blob([data]));
                const a = document.createElement('a');
                a.href = url;
                a.download = 'reporte-documentos-faltantes.xlsx';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);

                formDocsFaltantes.reset();
                modalReporteDocsFaltantes.modal('hide');
                resetModalUI();
            }
        })
}
