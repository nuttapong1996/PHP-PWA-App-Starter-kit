import { togglePassword } from './module/ui.js';

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('BtnPass').addEventListener('click', () => { togglePassword('BtnPass', 'userPass'); });
    document.getElementById('BtnCfPass').addEventListener('click', () => { togglePassword('BtnCfPass', 'cfPass'); });
});