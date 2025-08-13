export function togglePassword(passBtn, passField) {
    const passwordField = document.getElementById(passField);
    const icon = document.querySelector(`#${passBtn} i`);

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    }
}