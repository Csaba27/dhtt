document.addEventListener('alpine:init', () => {

    Alpine.directive('tooltip', (el, {expression}, {modifiers}) => {
        let tooltipEl;

        const position = (modifiers && modifiers[0]) || 'top';

        const getPositionClasses = () => {
            switch (position) {
                case 'bottom':
                    return 'top-full mt-2 -translate-x-1/2 left-1/2';
                case 'left':
                    return 'right-full mr-2 top-1/2 -translate-y-1/2';
                case 'right':
                    return 'left-full ml-2 top-1/2 -translate-y-1/2';
                default:
                    return 'bottom-full mb-2 -translate-x-1/2 left-1/2';
            }
        };

        el.addEventListener('mouseenter', () => {
            const text = el.getAttribute('x-tooltip')

            tooltipEl = document.createElement('div');
            tooltipEl.textContent = text;
            tooltipEl.className = `
                absolute z-50 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow
                ${getPositionClasses()}
                transition-opacity duration-200 opacity-0 pointer-events-none
            `;

            el.classList.add('relative');
            el.appendChild(tooltipEl);

            requestAnimationFrame(() => {
                tooltipEl.classList.add('opacity-100');
            });
        });

        el.addEventListener('mouseleave', () => {
            tooltipEl?.remove();
        });
    });
});
