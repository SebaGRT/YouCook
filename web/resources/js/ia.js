// Envía POST con JSON a ruta de Laravel y devuelve la respuesta como objeto
export async function postJson(url, datos = {}) {
    const respuesta = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(datos),
    });

    if (!respuesta.ok) {
        throw new Error(`Error ${respuesta.status}`);
    }

    return respuesta.json();
}

