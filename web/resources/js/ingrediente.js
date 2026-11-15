import { postJson } from './ia';

// Una fila de ingrediente del detalle de receta
export default (url) => ({
    tengo: false,
    estado: 'inactivo',
    alternativas: [],

    async pedir() {
        this.estado = 'cargando';

        try {
            const datos = await postJson(url);

            this.alternativas = datos.alternativas.map((texto) => {
                const [desde, ...resto] = texto.split('→');

                return resto.length
                    ? { desde: desde.trim(), hacia: resto.join('→').trim() }
                    : { desde: null, hacia: texto };
            });
            this.estado = 'listo';
        } catch {
            this.estado = 'error';
        }
    },

    cerrar() {
        this.estado = 'inactivo';
    },
});
