/**
 * FormUtilities - Utilidades para formularios
 *
 * Esta clase proporciona utilidades para formularios con las que podremos realizar
 * validaciones, añadiendo clases de Bootstrap a los campos del formulario para indicar
 * visualmente validación correcta o incorrecta.
 *
 * Además, tenemos la propiedad `data`, que nos ayudará a plasmar información en
 * el formulario a partir de un objeto dado. Esto resulta útil para cuando necesitamos
 * plasmar información en el formulario proveniente de alguna fuente, como por ejemplo de
 * una base de datos cuando deseamos editar un registro.
 *
 * Asimismo, la propiedad `setData` nos permite obtener todos los valores ingresados en el
 * formulario por el usuario en un objeto que podemos manipular.
 */
export default class FormUtilities {
    /**
     * Para comenzar, indica el selector del formulario.
     *
     * @param {string} formSelector Selector del formulario.
     *
     * @example
     * HTML:
     * ```
     * <form id="myForm" class="myForm">
     *  ...
     * </form>
     * ```
     * JavaScript:
     * ```
     * const myForm = new FormUtilities('#myForm');
     * const myForm = new FormUtilities('.myForm');
     * ```
     */
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
        this.formElements = Array.from(this.form.elements);
        this._ignoreInputs = [];

        this.liveInputValidation();
        this.trimInputValue();

        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            // this.validarCampos();
        });
    }

    /**
     * Obtiene los valores de un formulario y los devuelve en un objeto.
     *
     * Es importante que en cada `<input>` del formulario  se le asigne el atributo
     * `name` con el nombre del dato a obtener. Este atributo será la `key` del objeto y su `value`
     * será lo introducido por el usuario. En caso de que el usuario no introduzca nada, este
     * `value` será un string vacío.
     *
     * @example
     * HTML:
     * ```
     * <form id="myForm">
     *  <input type="text" name="myInput">
     *  <input type="text" name="emptyInput">
     * </form>
     * ```
     * JavaScript:
     * ```
     * const myForm = new FormUtilities('#myForm');
     * const data = myForm.formData // { myInput: 'myInputValue', emptyInput: '' }
     * ```
     */
    get data() {
        let datosForm = {};

        this.formElements.forEach((element) => {
            if (element.tagName !== 'BUTTON' && element.value !== undefined && element.name !== '') {
                if (element.type === 'radio') {
                    if (element.checked) datosForm[element.name] = element.value.trim();
                } else {
                    datosForm[element.name] = element.value.trim();
                }
            }
        });

        return datosForm;
    }

    /**
     * Llena los campos de un formulario de acuerdo al objeto que le pasemos como parametro.
     *
     * Las `keys` del objeto deben coincidir con el aributo `name` de cada elemento del
     * formulario.
     *
     * @param {Object} obj Objeto con la información que llenará el formulario.
     */
    set setData(obj = {}) {
        this.formElements.forEach((element) => {
            if (element.tagName !== 'BUTTON' && element.tagName !== 'FIELDSET') {
                const value = obj[element.name];
                element.value = value !== undefined && value !== null ? value.trim() : '';
            }
        });
    }

    /**
     * Indica los campos que deben ser ignorados para su validación.
     * @param {string[]} inputs Atributo name.
     */
    set ignoreInputs(inputs) {
        this._ignoreInputs = inputs;
    }

    /**
     * Evita que el usuario introduzca espacios antes de cualquier otro caracter.
     */
    trimInputValue() {
        this.formElements.forEach((element) => {
            if (element.tagName !== 'FIELDSET' && element.tagName !== 'BUTTON') {
                element.addEventListener('keyup', () => {
                    element.value = element.value.trimStart();
                });
            }
        });
    }

    /**
     * Valida que el objeto local `data` que contiene la información introducida por el usuario
     * no tenga valores vacíos.
     *
     * @returns
     */
    validarUserInput() {
        for (let key in this.data) {
            if (this.data.hasOwnProperty(key) && !this._ignoreInputs.includes(key)) {
                if (this.data[key] === null || this.data[key] === undefined || this.data[key].trim() === '') {
                    return false; // Si encuentra un valor vacío, retorna false
                }
            }
        }

        return true;
    }

    /**
     * Añade clases de validación a los elementos del formulario.
     * Usa el evento `input` para escuchar los cambios.
     */
    liveInputValidation() {
        this.formElements.forEach((element) => {
            if (element.tagName !== 'BUTTON') {
                element.addEventListener('input', (e) => {
                    this.addValidationClasses(e.target);
                });
            }
        });
    }

    /**
     * Añade las clases de validación a todos los elementos del formulario.
     */
    validarCampos() {
        this.formElements.forEach((element) => {
            if (!this._ignoreInputs.includes(element.name)) {
                this.addValidationClasses(element);
            }
        });
    }

    /**
     * Añade clases de valicación de Bootstrap a un elemento HTML dado
     * del formulario.
     *
     * Si el `value` del elemento está vacío añade la clase `is-invalid`, de lo contrario
     * añade la clase `is-valid`.
     *
     * @param {HTMLElement} elemento Elemento HTML del formulario.
     */
    addValidationClasses(elemento) {
        if (elemento.tagName !== 'FIELDSET' && elemento.tagName !== 'BUTTON') {
            if (elemento.value.trim() === '') {
                elemento.classList.remove('is-valid');
                elemento.classList.add('is-invalid');
            } else {
                elemento.classList.remove('is-invalid');
                elemento.classList.add('is-valid');
            }
        }
    }

    /**
     * Elimina todas las clases CSS de validación de cada uno de los elementos
     * del formulario.
     */
    removeAllValidationClasses() {
        this.formElements.forEach((elemento) => {
            elemento.classList.remove('is-valid');
            elemento.classList.remove('is-invalid');
        });
    }

    /**
     * Limpia los campos de todo el formulario: los valores introducidos por
     * el usuario y las clases de validación.
     */
    reset() {
        this.form.reset();
        this.removeAllValidationClasses();
    }
}
