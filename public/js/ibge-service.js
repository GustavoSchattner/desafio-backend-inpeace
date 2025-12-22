document.addEventListener('DOMContentLoaded', function() {
    const stateInput = document.querySelector('.js-state-input');
    const cityInput = document.querySelector('.js-city-input');
    
    if (!stateInput || !cityInput) return;

    const stateSelect = document.createElement('select');
    stateSelect.className = 'form-select mb-3';
    stateSelect.innerHTML = '<option value="">Carregando estados...</option>';
    
    const citySelect = document.createElement('select');
    citySelect.className = 'form-select mb-3';
    citySelect.innerHTML = '<option value="">Selecione um estado primeiro</option>';
    citySelect.disabled = true;

    stateInput.parentNode.insertBefore(stateSelect, stateInput);
    cityInput.parentNode.insertBefore(citySelect, cityInput);

    const API_UFS = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome';
    
    const fetchCities = (ufId, selectedCityName = null) => {
        citySelect.innerHTML = '<option value="">Carregando...</option>';
        citySelect.disabled = true;

        fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${ufId}/municipios`)
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

    fetch(API_UFS)
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
        .catch(err => console.error('Erro ao carregar estados do IBGE:', err));

    
    stateSelect.addEventListener('change', function() {
        stateInput.value = this.value;
        cityInput.value = ''; 

        const selectedOption = this.options[this.selectedIndex];
        const ufId = selectedOption.dataset.id;

        if (ufId) {
            fetchCities(ufId);
        } else {
            citySelect.innerHTML = '<option value="">Selecione um estado primeiro</option>';
            citySelect.disabled = true;
        }
    });

    citySelect.addEventListener('change', function() {
        cityInput.value = this.value;
    });
});