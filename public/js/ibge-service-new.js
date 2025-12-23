/**
 * Serviço IBGE - Cria select boxes dinâmicos para Estado e Cidade
 */

function initIbgeService() {
    const stateInput = document.querySelector('input[name*="state"]');
    const cityInput = document.querySelector('input[name*="city"]');

    if (!stateInput || !cityInput) return;

    if (document.querySelector('.ibge-state-select')) return;

    stateInput.style.display = 'none';
    cityInput.style.display = 'none';

    const stateWrapper = stateInput.parentElement;
    const cityWrapper = cityInput.parentElement;

    const stateSelect = document.createElement('select');
    stateSelect.className = 'form-control ibge-state-select';
    stateSelect.innerHTML = '<option value="">Carregando estados...</option>';

    const citySelect = document.createElement('select');
    citySelect.className = 'form-control ibge-city-select';
    citySelect.innerHTML = '<option value="">Selecione um estado</option>';
    citySelect.disabled = true;

    stateWrapper.insertBefore(stateSelect, stateInput);
    cityWrapper.insertBefore(citySelect, cityInput);

    const API = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';

    const loadStates = () => {
        fetch(`${API}?orderBy=nome`)
            .then(res => res.json())
            .then(states => {
                stateSelect.innerHTML = '<option value="">Selecione o Estado</option>';
                states.forEach(uf => {
                    const option = document.createElement('option');
                    option.value = uf.sigla;
                    option.dataset.id = uf.id;
                    option.text = `${uf.nome} (${uf.sigla})`;
                    stateSelect.appendChild(option);
                });

                if (stateInput.value) {
                    stateSelect.value = stateInput.value;
                    const selected = stateSelect.options[stateSelect.selectedIndex];
                    if (selected.dataset.id) {
                        loadCities(selected.dataset.id, cityInput.value);
                    }
                }
            })
            .catch(err => console.error('Erro ao carregar estados:', err));
    };

    const loadCities = (stateId, selectedCity = '') => {
        citySelect.innerHTML = '<option value="">Carregando cidades...</option>';
        citySelect.disabled = true;

        fetch(`${API}/${stateId}/municipios`)
            .then(res => res.json())
            .then(cities => {
                citySelect.innerHTML = '<option value="">Selecione a Cidade</option>';
                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.nome;
                    option.text = city.nome;
                    citySelect.appendChild(option);
                });

                citySelect.disabled = false;
                if (selectedCity) {
                    citySelect.value = selectedCity;
                    cityInput.value = selectedCity;
                }
            })
            .catch(err => console.error('Erro ao carregar cidades:', err));
    };

    stateSelect.addEventListener('change', function() {
        stateInput.value = this.value;
        cityInput.value = '';
        citySelect.value = '';

        const selected = this.options[this.selectedIndex];
        if (selected.dataset.id) {
            loadCities(selected.dataset.id);
        } else {
            citySelect.innerHTML = '<option value="">Selecione um estado</option>';
            citySelect.disabled = true;
        }
    });

    citySelect.addEventListener('change', function() {
        cityInput.value = this.value;
    });

    loadStates();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initIbgeService);
} else {
    initIbgeService();
}

document.addEventListener('turbo:load', initIbgeService);
