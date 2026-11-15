import { postJson } from './ia';

// La pantalla del asistente.
// Cada mensaje: { id, de: 'usuario' | 'ia', texto, estado: null | 'cargando' | 'error' }
export default ({ url, mensajes }) => ({
    mensajes: mensajes.map((m, i) => ({
        id: i + 1,
        de: m.role === 'user' ? 'usuario' : 'ia',
        texto: m.content,
        estado: null,
    })),
    texto: '',
    caida: false,

    init() {
        this.bajar();
    },

    get ocupado() {
        return this.mensajes.some((m) => m.estado === 'cargando');
    },

    // --- Enviar una pregunta ---
    enviar(pregunta = this.texto) {
        pregunta = pregunta.trim();

        if (!pregunta || this.ocupado) {
            return;
        }

        const id = Date.now();

        // Dos burbujas: la pregunta y la respuesta, que parte «pensando».
        this.mensajes.push({ id: id - 1, de: 'usuario', texto: pregunta, estado: null });
        this.mensajes.push({ id, de: 'ia', texto: '', pregunta, estado: 'cargando' });
        this.texto = '';

        this.responder(id);
    },
    // --- Pedir la respuesta (también lo usa «Reintentar») ---
    async responder(id) {
        const burbuja = this.mensajes.find((m) => m.id === id);

        burbuja.estado = 'cargando';
        this.bajar();

        try {
            const datos = await postJson(url, { mensaje: burbuja.pregunta });

            burbuja.texto = datos.respuesta;
            burbuja.estado = null;
            this.caida = false;
        } catch {
            burbuja.estado = 'error';
            this.caida = true;
        }

        this.bajar();
    },

    // Baja el chat hasta el último mensaje.
    bajar() {
        this.$nextTick(() => this.$refs.fin.scrollIntoView({ behavior: 'smooth', block: 'nearest' }));
    },
});

