import Alpine from 'alpinejs';
import ingrediente from './ingrediente';

// Alpine responsable de lo interactivo en las vistas

Alpine.data('ingrediente', ingrediente);

window.Alpine = Alpine;

Alpine.start();
