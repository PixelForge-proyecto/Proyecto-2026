
import 'bootstrap';

/*
 * SEGUIMIENTO DE FICHAS
 * Control de etapas, fechas y canal aduanero.
 */
document.addEventListener('DOMContentLoaded', () => {
    const etapa = document.getElementById('etapa');
    const fecha = document.getElementById('fecha');
    const canalAduanero =
        document.getElementById('canal_aduanero');
    const observacion =
        document.getElementById('observacion');
    const notificarCliente =
        document.getElementById('notificar_cliente');
    const datosEtapasElemento =
        document.getElementById(
            'datos-etapas-seguimiento'
        );

    if (!etapa || !canalAduanero) {
        return;
    }

    const etapasConCanal = [
        'aduana_internacional',
        'aduana_nacional',
    ];

    let datosEtapas = {};

    /*
     * En la pantalla de edición, este elemento
     * contiene los datos registrados por etapa.
     */
    if (datosEtapasElemento) {
        try {
            datosEtapas = JSON.parse(
                datosEtapasElemento.textContent
            );
        } catch {
            datosEtapas = {};
        }
    }

    const actualizarEstadoCampos = () => {
        const tieneEtapa = etapa.value !== '';

        const permiteCanal = etapasConCanal.includes(
            etapa.value
        );

        if (fecha) {
            fecha.required = tieneEtapa;
        }

        canalAduanero.disabled = !permiteCanal;

        if (!permiteCanal) {
            canalAduanero.value = '';
        }
    };

    const cargarDatosDeLaEtapa = () => {
        /*
         * En creación no existe el bloque con
         * los datos de las etapas.
         */
        if (!datosEtapasElemento) {
            actualizarEstadoCampos();
            return;
        }

        const datosEtapa =
            datosEtapas[etapa.value] ?? null;

        if (fecha) {
            fecha.value =
                datosEtapa?.fecha ?? '';
        }

        canalAduanero.value =
            datosEtapa?.canal_aduanero ?? '';

        if (observacion) {
            observacion.value =
                datosEtapa?.observacion ?? '';
        }

        if (notificarCliente) {
            notificarCliente.checked =
                datosEtapa?.notificar_cliente === true;
        }

        actualizarEstadoCampos();
    };

    etapa.addEventListener(
        'change',
        cargarDatosDeLaEtapa
    );

    /*
     * Conservamos los valores enviados por Laravel
     * si hubo un error de validación.
     */
    actualizarEstadoCampos();
});

/*
 * ANULACIÓN DE FICHAS
 */
document.addEventListener('DOMContentLoaded', () => {
    const formularios = document.querySelectorAll(
        '.formulario-anular-ficha'
    );

    formularios.forEach((formulario) => {
        formulario.addEventListener(
            'submit',
            (evento) => {
                const numeroFicha =
                    formulario.dataset.numeroFicha;

                const confirmacion = window.confirm(
                    `¿Confirmás que querés anular la ficha ${numeroFicha}?`
                );

                if (!confirmacion) {
                    evento.preventDefault();
                }
            }
        );
    });
});

/*
 * BAJA DE CLIENTES
 */
document.addEventListener('DOMContentLoaded', () => {
    const formulariosClientes = document.querySelectorAll(
        '.formulario-baja-cliente'
    );

    formulariosClientes.forEach((formulario) => {
        formulario.addEventListener(
            'submit',
            (evento) => {
                const nombreCliente =
                    formulario.dataset.nombreCliente;

                const confirmacion = window.confirm(
                    `¿Confirmás que querés dar de baja al cliente ${nombreCliente}?`
                );

                if (!confirmacion) {
                    evento.preventDefault();
                }
            }
        );
    });
});

/*
 * BAJA DE TRANSPORTISTAS
 */
document.addEventListener('DOMContentLoaded', () => {
    const formulariosTransportistas =
        document.querySelectorAll(
            '.formulario-baja-transportista'
        );

    formulariosTransportistas.forEach((formulario) => {
        formulario.addEventListener(
            'submit',
            (evento) => {
                const nombreTransportista =
                    formulario.dataset.nombreTransportista;

                const confirmacion = window.confirm(
                    `¿Confirmás que querés dar de baja al transportista ${nombreTransportista}?`
                );

                if (!confirmacion) {
                    evento.preventDefault();
                }
            }
        );
    });
});

/*
 * BAJA DE DESPACHANTES LOCALES
 */
document.addEventListener('DOMContentLoaded', () => {
    const formulariosDespachantes =
        document.querySelectorAll(
            '.formulario-baja-despachante'
        );

    formulariosDespachantes.forEach((formulario) => {
        formulario.addEventListener(
            'submit',
            (evento) => {
                const nombreDespachante =
                    formulario.dataset.nombreDespachante;

                const confirmacion = window.confirm(
                    `¿Confirmás que querés dar de baja al despachante ${nombreDespachante}?`
                );

                if (!confirmacion) {
                    evento.preventDefault();
                }
            }
        );
    });
});

/*
 * CÁLCULO AUTOMÁTICO DEL TOTAL COMERCIAL
 */
document.addEventListener('DOMContentLoaded', () => {
    const totalComercial =
        document.getElementById('total_comercial');

    if (!totalComercial) {
        return;
    }

    const camposDelTotal = [
        document.getElementById('puesta_fob'),
        document.getElementById('flete_internacional'),
        document.getElementById('flete_nacional'),
        document.getElementById('ad_valorem'),
        document.getElementById('despacho_brasil'),
    ];

    const calcularTotalComercial = () => {
        let total = 0;
        let tieneImportes = false;

        camposDelTotal.forEach((campo) => {
            if (!campo || campo.value.trim() === '') {
                return;
            }

            const valor = Number.parseFloat(
                campo.value
            );

            if (!Number.isNaN(valor)) {
                total += valor;
                tieneImportes = true;
            }
        });

        totalComercial.value = tieneImportes
            ? total.toFixed(2)
            : '';
    };

    camposDelTotal.forEach((campo) => {
        if (campo) {
            campo.addEventListener(
                'input',
                calcularTotalComercial
            );
        }
    });

    calcularTotalComercial();
});

/*
 * CREACIÓN DE FICHAS
 * Autocompletado de clientes nacionales
 * e internacionales y rol en la operación.
 */
document.addEventListener('DOMContentLoaded', () => {
    const clienteNacional =
        document.getElementById('cliente_id');

    const clienteInternacional =
        document.getElementById(
            'cliente_internacional_id'
        );

    const tipoOperacion =
        document.getElementById('tipo_operacion');

    /*
     * Solo se ejecuta en formularios que
     * contienen estos tres selectores.
     */
    if (
        !clienteNacional ||
        !clienteInternacional ||
        !tipoOperacion
    ) {
        return;
    }

    const obtenerCampo = (id) =>
        document.getElementById(id);

    const establecerValor = (id, valor) => {
        const campo = obtenerCampo(id);

        if (campo) {
            campo.value = valor ?? '';
        }
    };

    const obtenerOpcion = (selector) => {
        if (!selector.value) {
            return null;
        }

        return selector.selectedOptions[0] ?? null;
    };

    /*
     * CLIENTE NACIONAL
     * Completa los datos de Uruguay.
     */
    const completarClienteNacional = () => {
        const opcion = obtenerOpcion(clienteNacional);

        establecerValor(
            'cliente_nombre',
            opcion?.dataset.nombre ?? ''
        );

        establecerValor(
            'cliente_contacto',
            opcion?.dataset.contacto ?? ''
        );

        establecerValor(
            'cliente_telefono',
            opcion?.dataset.telefono ?? ''
        );

        establecerValor(
            'cliente_ruc',
            opcion?.dataset.rucRut ?? ''
        );
    };

    /*
     * CLIENTE INTERNACIONAL
     * Completa los datos de Brasil o Argentina.
     */
    const completarClienteInternacional = () => {
        const opcion = obtenerOpcion(
            clienteInternacional
        );

        establecerValor(
            'exportador_nombre',
            opcion?.dataset.nombre ?? ''
        );

        establecerValor(
            'exportador_contacto',
            opcion?.dataset.contacto ?? ''
        );

        establecerValor(
            'exportador_telefono',
            opcion?.dataset.telefono ?? ''
        );

        establecerValor(
            'exportador_email',
            opcion?.dataset.email ?? ''
        );

        establecerValor(
            'despachante_exportador',
            opcion?.dataset.despachante ?? ''
        );
    };

    /*
     * ROL DEL CLIENTE INTERNACIONAL
     *
     * Importación: Exportador
     * Exportación: Importador
     * Sin operación: Sin definir
     */
    const actualizarRolInternacional = () => {
        let rol = 'Sin definir';

        if (tipoOperacion.value === 'importacion') {
            rol = 'Exportador';
        } else if (
            tipoOperacion.value === 'exportacion'
        ) {
            rol = 'Importador';
        }

        /*
         * Campo de solo lectura que muestra
         * el rol dentro del formulario.
         */
        const campoRol = document.getElementById(
            'rol_cliente_internacional'
        );

        if (campoRol) {
            campoRol.value = rol;
        }

        /*
         * Actualiza cualquier texto dinámico
         * identificado en el formulario.
         */
        document.querySelectorAll(
            '[data-rol-internacional]'
        ).forEach((elemento) => {
            elemento.textContent = rol;
        });
    };

    /*
     * EVENTOS DE LOS SELECTORES
     */
    clienteNacional.addEventListener(
        'change',
        completarClienteNacional
    );

    clienteInternacional.addEventListener(
        'change',
        completarClienteInternacional
    );

    tipoOperacion.addEventListener(
        'change',
        actualizarRolInternacional
    );

    /*
     * Al abrir el formulario se muestra el rol
     * correcto, incluso si Laravel recuperó
     * una operación mediante old().
     *
     * No se sobrescriben los demás campos
     * al cargar la página.
     */
    actualizarRolInternacional();
});

/*
 * DESPACHANTE LOCAL
 * Autocompletado de los siete campos de la ficha.
 *
 * Los campos siguen siendo editables.
 * Los valores existentes no se modifican al
 * abrir la página, solo al cambiar el selector.
 */
document.addEventListener('DOMContentLoaded', () => {
    const selectorDespachante =
        document.getElementById(
            'despachante_local_id'
        );

    if (!selectorDespachante) {
        return;
    }

    const completarDespachanteLocal = () => {
        const opcion = selectorDespachante.value
            ? selectorDespachante.selectedOptions[0]
            : null;

        const campos = {
            despachante_uy:
                opcion?.dataset.nombre ?? '',
            email_despachante:
                opcion?.dataset.email ?? '',
            telefono_despachante:
                opcion?.dataset.telefono ?? '',
            direccion_despachante:
                opcion?.dataset.direccion ?? '',
            gestor:
                opcion?.dataset.gestor ?? '',
            telefono_gestor:
                opcion?.dataset.gestorTelefono ?? '',
            contacto_despachante:
                opcion?.dataset.contacto ?? '',
        };

        Object.entries(campos).forEach(
            ([id, valor]) => {
                const campo = document.getElementById(id);

                if (campo) {
                    campo.value = valor;
                }
            }
        );
    };

    selectorDespachante.addEventListener(
        'change',
        completarDespachanteLocal
    );
});
