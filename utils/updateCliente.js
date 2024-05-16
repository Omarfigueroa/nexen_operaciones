import FormUtilities from '../resources/js/FormUtilities.js';
import { Cliente } from './helpers.js';

const API_URL = '../include/registrar_cliente.php';

const modalUpdateCliente = $('#modalUpdateCliente');
const btnEditarCliente = document.querySelector('#btnEditarCliente');
const btnUpdateCliente = document.querySelector('#btnUpdateCliente');
const registro = { ID_CLIENTE: '', REF_NEXEN: '' };

const formClienteUpdate = new FormUtilities('#formClienteUpdate');
formClienteUpdate.reset();

btnEditarCliente.addEventListener('click', () => {
    modalUpdateCliente.modal('show');

    getReferencia().then((referenciaRes) => {
        const cliente = referenciaRes.data.Cliente;
        registro.REF_NEXEN = referenciaRes.data.REFERENCIA_NEXEN;

        getClienteByRazonSocial(cliente.trim()).then((clienteRes) => {
            formClienteUpdate.removeAllValidationClasses();
            formClienteUpdate.setData = normalizarClavesObj(clienteRes.data);

            registro.ID_CLIENTE = clienteRes.data.Id_cliente;
        });
    });
});

btnUpdateCliente.addEventListener('click', async () => {
    formClienteUpdate.validarCampos();

    if (!formClienteUpdate.validarUserInput()) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Todos los campos son obligatorios',
        });
        return;
    }

    const newClienteData = {
        Id_cliente: registro.ID_CLIENTE,
        ...formClienteUpdate.data,
        REFERENCIA_NEXEN: registro.REF_NEXEN,
    };

    Cliente.requestURL = API_URL;
    const clienteActualizar = new Cliente(newClienteData);

    const response = await clienteActualizar.guardar();

    if (response.success) {
        formClienteUpdate.reset();
        modalUpdateCliente.modal('hide');

        Swal.fire({
            title: response.message,
            icon: 'success',
        }).then(function () {
            location.reload();
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: response.message,
        });
    }
});

modalUpdateCliente.on('hidden.bs.modal', () => formClienteUpdate.reset());

async function getClienteByRazonSocial(razonSocial) {
    const data = {
        action: 'getClienteByRS',
        razonSocial,
    };

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });

        return await response.json();
    } catch (error) {
        return error;
    }
}

async function getReferencia() {
    const referencia = new URL(location.href).searchParams.get('referencia');

    const data = {
        action: 'operacion',
        referencia,
    };

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });

        return await response.json();
    } catch (error) {
        return error;
    }
}

/**
 * Normaliza las keys de un objeto dado.
 *
 * Debido a que hay en una tabla puede haber keys de objeto con el mismo nombre
 * pero diferente estructura (por ej.: `[RAZON_SOCIAL]` y `[RAZON SOCIAL ]`), este método se encarga
 * de que todas las keys de esta estructura se llamen de la forma correcta.
 *
 * @param {Object} objeto Objeto con las claves no normalizadas.
 * @returns
 */
function normalizarClavesObj(objeto) {
    const nuevoObjeto = {};

    for (let clave in objeto) {
        if (objeto.hasOwnProperty(clave) && clave !== '') {
            const nuevaClave = clave.trim(); // Eliminar espacios y convertir a minúsculas
            nuevoObjeto[nuevaClave] = objeto[clave];
        }
    }
    return nuevoObjeto;
}
