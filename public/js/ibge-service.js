/**
 * Serviço IBGE - Versão Turbo-Proof
 * Este script roda toda vez que é injetado no DOM pelo formulário.
 */

(function() {
    const runIbgeService = () => {
        const stateInput = document.querySelector('.js-state-input');
        const cityInput = document.querySelector('.js-city-input');

        if (!stateInput || !cityInput) return;

        if (stateInput.parentNode.querySelector('select.js-ibge-select')) return;

        stateInput.style.display = 'none';
        cityInput.style.display = 'none';

        const stateWrapper = stateInput.parentNode;
        const cityWrapper = cityInput.parentNode;

        const stateSelect = document.createElement('select');
        stateSelect.className = 'form-select mb-3 js-ibge-select';
        stateSelect.innerHTML = '<option value="">Carregando estados...</option>';
        stateSelect.required = true;

        const citySelect = document.createElement('select');
        citySelect.className = 'form-select mb-3 js-ibge-select';
        citySelect.innerHTML = '<option value="">Selecione um estado primeiro</option>';
        citySelect.disabled = true;
        citySelect.required = true;

        stateWrapper.appendChild(stateSelect);
        cityWrapper.appendChild(citySelect);

        const API_BASE = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';

        const fetchCities = (ufId, selectedCityName = null) => {
            citySelect.innerHTML = '<option value="">Carregando cidades...</option>';
            citySelect.disabled = true;

            fetch(`${API_BASE}/${ufId}/municipios`)
                .then(res => res.json())
                .then(cities => {
                    citySelect.innerHTML = '<option value="">Selecione a Cidade</option>';
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.nome;
                        option.textContent = city.nome;
                        citySelect.appendChild(option);
                    });
                    citySelect.disabled = false;

                    if (selectedCityName) {
                        citySelect.value = selectedCityName;
                        cityInput.value = selectedCityName;
                    }
                })
                .catch(err => console.error('Erro ao buscar cidades:', err));
        };

        fetch(`${API_BASE}?orderBy=nome`)
            .then(response => response.json())
            .then(states => {
                stateSelect.innerHTML = '<option value="">Selecione o Estado</option>';
                states.forEach(uf => {
                    const option = document.createElement('option');
                    option.value = uf.sigla;
                    option.dataset.id = uf.id;
                    option.textContent = `${uf.nome} (${uf.sigla})`;
                    stateSelect.appendChild(option);
                });

                if (stateInput.value) {
                    stateSelect.value = stateInput.value;
                    const selectedOption = [...stateSelect.options].find(opt => opt.value === stateInput.value);

                    if (selectedOption) {
                        fetchCities(selectedOption.dataset.id, cityInput.value);
                    }
                }
            })
            .catch(err => {
                console.error('Erro ao carregar estados:', err);
                stateSelect.innerHTML = '<option value="">Erro ao carregar</option>';
            });

        stateSelect.addEventListener('change', function() {
            stateInput.value = this.value;
            cityInput.value = '';
            citySelect.value = '';

            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption && selectedOption.dataset.id) {
                fetchCities(selectedOption.dataset.id);
            } else {
                citySelect.innerHTML = '<option value="">Selecione um estado primeiro</option>';
                citySelect.disabled = true;
            }
        });

        citySelect.addEventListener('change', function() {
            cityInput.value = this.value;
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runIbgeService);
    } else {
        runIbgeService();
    }

    document.addEventListener('turbo:load', runIbgeService);

})();
