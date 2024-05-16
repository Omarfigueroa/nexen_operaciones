const FETCH_REPORTE_CARPETAS = `${window.location.origin}/nexen_operaciones/request/finanzas/fetchReporteCarpetas.php`;

const btnReporteCarpetas = document.querySelector("#btnReporteCarpetas");

if(document.querySelector("#btnReporteCarpetas")){
    btnReporteCarpetas.addEventListener("click", () => {
    Swal.fire({
        title: "Espera un momento",
        text: "El reporte está siendo generado. En un momento será descargado.",
        icon: "info",
        showConfirmButton: false,
    });

        downloadReporteCarpetas();
    });
}


/**
 * Realiza la petición para consultar y descargar el reporte.
 */
function downloadReporteCarpetas() {
    fetch(FETCH_REPORTE_CARPETAS)
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "Ocurrió un error al generar el archivo Excel."
                );
            }

            const contentType = response.headers.get("Content-Type");

            if (contentType.includes("application/json")) {
                return response.json();
            } else if (
                contentType.includes(
                    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                )
            ) {
                return response.blob(); 
            } else {
                throw new Error("Tipo de contenido no compatible");
            }
        })
        .then((data) => {
            if (Object.keys(data).length > 0) {
                Swal.close();
                
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: data.message,
                });
            } else if (data instanceof Blob) {
                const url = window.URL.createObjectURL(new Blob([data]));
                const a = document.createElement("a");
                a.href = url;
                a.download = "reporte-carpetas-digitales.xlsx";
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);

                Swal.close();
            }
        });
}
