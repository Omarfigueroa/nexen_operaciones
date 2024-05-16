export class Cliente {
    static requestURL;

    constructor(props = {}) {
        this.Id_cliente = props.Id_cliente ?? '';
        this.RAZON_SOCIAL = props.RAZON_SOCIAL ?? '';
        this.RFC = props.RFC ?? '';
        this.TELEFONO = props.TELEFONO ?? '';
        this.MOVIL = props.MOVIL ?? '';
        this.CONTACTO = props.CONTACTO ?? '';
        this.EMAIL_1 = props.EMAIL_1 ?? '';
        this.EMAIL_2 = props.EMAIL_2 ?? '';
        this.Domicilio_Fisico = props.Domicilio_Fisico ?? '';
        this.Pais = props.Pais ?? '';
        this.Codigo_Postal = props.Codigo_Postal ?? '';
        this.Estado = props.Estado ?? '';
        this.Delegacion_Municipio = props.Delegacion_Municipio ?? '';
        this.Referencia = props.Referencia ?? '';
        this.tipo_cliente = props.tipo_cliente ?? '';
        this.usuarioSupervisor = props.usuarioSupervisor ?? '';
        this.contrasenaSupervisor = props.contrasenaSupervisor ?? '';

        this.REFERENCIA_NEXEN = props.REFERENCIA_NEXEN ?? '';

        this.action = props.Id_cliente ? 'actualizar' : 'registrar';
    }

    async guardar() {
        try {
            const response = await fetch(Cliente.requestURL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this),
            });

            return await response.json();
        } catch (error) {
            return error;
        }
    }

    static async getCliente(idCliente) {
        const data = {
            action: 'getCliente',
            Id_cliente: idCliente,
        };

        try {
            const response = await fetch(Cliente.requestURL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
            });

            return await response.json();
        } catch (error) {
            return error;
        }
    }
}
