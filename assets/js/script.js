document.addEventListener('DOMContentLoaded', function () {
    // === ЛОГИКА ВЫБОРА РАЗМЕРА (Твой код) ===
    const productForm = document.getElementById('product-form');
    if (productForm) {
        const errorBlock = document.getElementById('size-error');
        const sizeInputs = document.querySelectorAll('.size-input');

        productForm.onsubmit = function (e) {
            const selectedSize = productForm.querySelector('input[name="product_size"]:checked');
            if (!selectedSize) {
                e.preventDefault();
                errorBlock.style.display = 'block';
                errorBlock.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
        };

        sizeInputs.forEach(input => {
            input.addEventListener('change', () => {
                errorBlock.style.display = 'none';
            });
        });
    }

    // === ЛОГИКА СЛАЙДЕРА ЦЕН (Для каталога) ===
    const slider = document.getElementById('price-slider');
    const inputMin = document.getElementById('input-min');
    const inputMax = document.getElementById('input-max');

    if (slider && inputMin && inputMax) {
        // Инициализация noUiSlider
        noUiSlider.create(slider, {
            // Начальные значения берем прямо из инпутов (которые заполнил PHP)
            start: [parseInt(inputMin.value) || 0, parseInt(inputMax.value) || 50000],
            connect: true,
            range: {
                'min': 0,
                'max': 50000
            },
            step: 500,
            format: {
                to: value => Math.round(value),
                from: value => Number(value)
            }
        });

        // Синхронизация: Слайдер -> Инпуты
        slider.noUiSlider.on('update', function (values, handle) {
            if (handle) {
                inputMax.value = values[handle];
            } else {
                inputMin.value = values[handle];
            }
        });

        // Синхронизация: Инпуты -> Слайдер
        inputMin.addEventListener('change', () => slider.noUiSlider.set([inputMin.value, null]));
        inputMax.addEventListener('change', () => slider.noUiSlider.set([null, inputMax.value]));
    }
});