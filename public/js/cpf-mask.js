function initCpfMask() {
    const cpfInputs = document.querySelectorAll('.js-cpf');

    const applyMask = (input) => {
        let value = input.value;
        
        value = value.replace(/\D/g, "");

        if (value.length > 11) value = value.slice(0, 11);

        if (value) {
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        }

        input.value = value;
    };

    cpfInputs.forEach(input => {
        applyMask(input);

        input.addEventListener('input', (e) => applyMask(e.target));
    });
}

document.addEventListener('turbo:load', initCpfMask);
document.addEventListener('DOMContentLoaded', initCpfMask);

if (document.querySelector('.js-cpf')) {
    initCpfMask();
}