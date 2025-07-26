document.addEventListener('alpine:init', () => {
    Alpine.directive('tooltip', (el, {expression}, {modifiers}) => {
        let tooltipEl;
        const position = (modifiers && modifiers[0]) || 'top';

        const getTooltipPosition = (rect, tooltipRect) => {
            switch (position) {
                case 'bottom':
                    return {
                        top: rect.bottom + 8,
                        left: rect.left + rect.width / 2 - tooltipRect.width / 2
                    };
                case 'left':
                    return {
                        top: rect.top + rect.height / 2 - tooltipRect.height / 2,
                        left: rect.left - tooltipRect.width - 8
                    };
                case 'right':
                    return {
                        top: rect.top + rect.height / 2 - tooltipRect.height / 2,
                        left: rect.right + 8
                    };
                default: // top
                    return {
                        top: rect.top - tooltipRect.height - 8,
                        left: rect.left + rect.width / 2 - tooltipRect.width / 2
                    };
            }
        };

        el.addEventListener('mouseenter', () => {
            const text = el.getAttribute('x-tooltip');
            tooltipEl = document.createElement('div');
            tooltipEl.textContent = text;

            tooltipEl.className = 'fixed z-50 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow opacity-0 pointer-events-none transition-opacity duration-200';
            document.body.appendChild(tooltipEl);

            requestAnimationFrame(() => {
                const rect = el.getBoundingClientRect();
                const tooltipRect = tooltipEl.getBoundingClientRect();
                const pos = getTooltipPosition(rect, tooltipRect);

                tooltipEl.style.top = `${pos.top}px`;
                tooltipEl.style.left = `${pos.left}px`;
                tooltipEl.classList.add('opacity-100');
            });
        });

        el.addEventListener('mouseleave', () => {
            tooltipEl?.remove();
        });
    });
});
