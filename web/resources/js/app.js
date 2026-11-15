import Alpine from 'alpinejs';
import chat from './chat';
import ingrediente from './ingrediente';

// Componentes de Alpine que usan las vistas con x-data="chat(...)" e x-data="ingrediente(...)".
Alpine.data('chat', chat);
Alpine.data('ingrediente', ingrediente);

window.Alpine = Alpine;

Alpine.start();

