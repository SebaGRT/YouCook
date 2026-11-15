<?php

// Solo las reglas que usa YouCook. Si agregas una regla nueva, agrega aquí
// su mensaje; si falta, Laravel muestra el mensaje en inglés.
return [
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'current_password' => 'La contraseña es incorrecta.',
    'email' => 'Escribe un correo válido.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'lowercase' => 'El campo :attribute debe estar en minúsculas.',
    'max' => [
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
    ],
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser texto.',
    'unique' => 'Ya existe una cuenta con ese :attribute.',

    // Nombre de cada campo dentro de los mensajes.
    'attributes' => [
        'name' => 'nombre',
        'email' => 'correo',
        'password' => 'contraseña',
        'current_password' => 'contraseña actual',
        'mensaje' => 'mensaje',
        'q' => 'búsqueda',
        'tiempo' => 'tiempo',
    ],
];