function initCpfMask() {
    const cpfInputs = document.querySelectorAll('input[name*="cpf"]');

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
        input.addEventListener('keyup', (e) => applyMask(e.target));
        input.addEventListener('change', (e) => applyMask(e.target));
        input.addEventListener('paste', (e) => {
            setTimeout(() => applyMask(e.target), 10);
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCpfMask);
} else {
    initCpfMask();
}

document.addEventListener('turbo:load', initCpfMask);
