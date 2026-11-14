import Alpine from 'alpinejs';
import ingrediente from './ingrediente';

// Componentes de Alpine que usan las vistas con x-data="ingrediente(...)".
Alpine.data('ingrediente', ingrediente);

window.Alpine = Alpine;

Alpine.start();
