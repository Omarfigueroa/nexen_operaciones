//Validación de que cualquier select tenga que ser seleccionado
function validateSelect(selectElement) {
    if (selectElement.selectedIndex !== 0) { // Comprueba si se ha seleccionado una opción diferente de la predeterminada (primer índice)
        return true;
    } else {
        return false;
    }
}
function setupSelectValidation() {
    let selectElements = document.querySelectorAll(".validSelect");
    selectElements.forEach(function (selectElement) {
        selectElement.addEventListener("change", function () {
            if (!validateSelect(this)) {
                this.classList.add("is-invalid");
            } else {
                this.classList.add("is-valid");
                this.classList.remove("is-invalid");
            }
        });
    });
}

function testText(txtString) {
    var stringText = new RegExp(/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü\s_]+$/);
    if (stringText.test(txtString)) {
        return true;
    } else {
        return false;
    }
}
function fntValidText() {
    let validText = document.querySelectorAll(".validText");
    validText.forEach(function (validText) {
        validText.addEventListener("keyup", function () {
            let inputValue = this.value;
            if (!testText(inputValue)) {
                this.classList.add("is-invalid");
            } else {
                this.classList.add("is-valid");
                this.classList.remove("is-invalid");
            }
        });
    });
}

// Validamos inputs que acepeten Números 
function fntNumberValidate(number) {
    var stringNumber = new RegExp(/^([0-9])/);
    if (stringNumber.test(number) == false) {
        return false;
    } else {
        return true;
    }
}

function fntValidNumber() {
    let validNumber = document.querySelectorAll(".validNumber");
    validNumber.forEach(function (validNumber) {
        validNumber.addEventListener("keyup", function () {
            let inputValue = this.value;
            if (!fntNumberValidate(inputValue)) {
                this.classList.add("is-invalid");
            } else {
                this.classList.add("is-valid");
                this.classList.remove("is-invalid");
            }
        });
    });
}

// Validamos inputs de la decripcion los cuales pueden tener: Mayúsculas, Minúsculas, Números 
function testDescription(Description) {
    var stringDomi = new RegExp(/^[a-zA-Z0-9.°, -_áéíóúÁÉÍÓÚüÜñÑ]+$/);
    if (stringDomi.test(Description)) {
        return true;
    } else {
        return false;
    }
}
function fntValidDescription() {
    let validDomi = document.querySelectorAll(".validDescription");
    validDomi.forEach(function (validDomi) {
        validDomi.addEventListener("keyup", function () {
            let inputValue = this.value;
            if (!testDescription(inputValue)) {
                this.classList.add("is-invalid");
            } else {
                this.classList.add("is-valid");
                this.classList.remove("is-invalid");
            }
        });
    });
}

// Validar input Mark los cuales pueden tener: Mayúsculas, Minúsculas, Números, espacios y diagonal
function testMark(Mark) {
    var stringDomi = new RegExp(/^[a-zA-Z0-9\s\/]+$/);
    if (stringDomi.test(Mark)) {
        return true;
    } else {
        return false;
    }
}
function fntValidMark() {
    let validDomi = document.querySelectorAll(".validMark");
    validDomi.forEach(function (validDomi) {
        validDomi.addEventListener("keyup", function () {
            let inputValue = this.value;
            if (!testMark(inputValue)) {
                this.classList.add("is-invalid");
            } else {
                this.classList.add("is-valid");
                this.classList.remove("is-invalid");
            }
        });
    });
}

//Permitir todo tipo de caracteres, números y símbolos, pero no permitir espacios vacíos
function testFactura(Factura) {
    var stringDomi = new RegExp(/^\S+$/);
    if (stringDomi.test(Factura)) {
        return true;
    } else {
        return false;
    }
}
function fntValidFactura() {
    let validDomi = document.querySelectorAll(".validFactura");
    validDomi.forEach(function (validDomi) {
        validDomi.addEventListener("keyup", function () {
            let inputValue = this.value;
            if (!testFactura(inputValue)) {
                this.classList.add("is-invalid");
            } else {
                this.classList.add("is-valid");
                this.classList.remove("is-invalid");
            }
        });
    });
}

//Valida fechas con el formato de un input de tipo date en HTML y aqui hacemos uso del evento "change"
function testDate(Fecha) {
    var stringDomi = new RegExp(/^\d{4}-\d{2}-\d{2}$/);
    if (stringDomi.test(Fecha)) {
        return true;
    } else {
        return false;
    }
}
function fntValidDate() {
    let validDomi = document.querySelectorAll(".validDate");
    validDomi.forEach(function (validDomi) {
        validDomi.addEventListener("change", function () {//<--- OJO: evento change aqui es usado
            let inputValue = this.value;
            if (!testDate(inputValue)) {
                this.classList.add("is-invalid");
            } else {
                this.classList.add("is-valid");
                this.classList.remove("is-invalid");
            }
        });
    });
}

function quitarAcentosYComillas(elemento) {
    var valor = elemento.value;
    valor = valor.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    valor = valor.replace(/["']/g, '');
    elemento.value = valor;
}


function SweetView(mensaje, callback){
    Swal.fire({
        title: '¡Tenemos un problema!',
        html: '<h5><span class="spangru">Nota: </span><span class="spandel">Por favor a segurate de rellenar todos los datos</span></h5> <br>'+mensaje,
        icon: 'error',
        confirmButtonText: 'Aceptar',
        customClass: {
            confirmButton: 'colortrue'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            callback(); 
        }
    });
}


function SweetViewTrue(mensaje, callback) {
    Swal.fire({
        title: '¡Todo Bien!',
        html: mensaje,
        icon: 'success', 
        confirmButtonText: 'Aceptar',
        customClass: {
            confirmButton: 'colortrue'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            callback(); 
        }
    });
}



function dondeMeEncuentro() {
    var rutaAbsoluta = self.location.href;
    var posicionUltimaBarra = rutaAbsoluta.lastIndexOf("/");
    var posicionPrimerInterrogacion = rutaAbsoluta.indexOf("?", posicionUltimaBarra);
    if (posicionPrimerInterrogacion !== -1) {
        var rutaRelativa = rutaAbsoluta.substring(posicionUltimaBarra + 1, posicionPrimerInterrogacion);
        return rutaRelativa;
    } else {
        var rutaRelativa = rutaAbsoluta.substring(posicionUltimaBarra + 1);
        return rutaRelativa;
    }
}

/*Se cierra modal de subir comprobante pagos*/
function openCarpetas(referencia_nexen, tipo_trafico) {

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl=''; var capas=0;

    if(dondeMeEncuentro()==='index.php' || dondeMeEncuentro()==='index.php#'){
        ajaxUrl = '../../include/datos.php';
        capas=2;
      
    }else{
        ajaxUrl = '../include/datos.php';
        capas=1;
    }
   
    // Ajustar la cadena de datos para enviar las variables por separado
    if((dondeMeEncuentro()==='index.php' || dondeMeEncuentro()==='operaciones.php') || dondeMeEncuentro()==='index.php#' || dondeMeEncuentro()==='operaciones.php#'){var cargo='Finanzas';}else{ var cargo='';}
    let strData = "referencia_nexen=" + referencia_nexen + "&tipo_trafico=" + tipo_trafico + "&cargo=" + cargo + "&capas=" + capas;
    request.open("POST", ajaxUrl, true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(strData);
    var botonCarpeta = document.getElementById('verDocumentos');
    // botonCarpeta.disabled= true;
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);

            if (objData) {
                let res = document.querySelector('#tableDocumentos');

                // Construir la tabla HTML
                let tableHTML = '<table>';
                for (let index = 0; index < objData.length; index++) {
                    let element = objData[index];
                    tableHTML += '<tr>';
                    if (element.hasOwnProperty('array2')) {
                        let array2 = element.array2;
                        tableHTML += '<td>' + array2.Nombre_Documento + '</td>';
                        tableHTML += '<td>' + array2.OPTIONS + '</td>';
                        tableHTML += '<td>' + array2.Estatus + '</td>';
                        // ... y así sucesivamente para los demás campos que deseas mostrar
                    } else {
                        // Mostrar todos los valores en una fila si no hay 'array2'
                        for (const key in element) {
                            if (element.hasOwnProperty(key)) {
                                if (key === 'DOCUMENTO' || key === 'Estatus' || key === 'OPTIONS') {
                                    tableHTML += '<td>' + element[key] + '</td>';
                                }
                            }
                        }
                    }
                    tableHTML += '</tr>';
                }
                tableHTML += '</table>';

                // Insertar la tabla en el elemento HTML
                res.innerHTML = tableHTML;
                $('#openCarpetas .carpetaDigitalP').text(referencia_nexen);
                $('#openCarpetas').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: objData.msg
                });
            }
        }
        //   botonCarpeta.disabled= false;
    }

}