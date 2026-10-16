
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const submenuButtons = document.querySelectorAll(
        '[data-atlas-submenu]'
    );

    submenuButtons.forEach(function (button) {
        const submenu = document.getElementById(
            button.dataset.atlasSubmenu
        );

        if (!submenu) {
            return;
        }

        button.addEventListener('click', function () {
            const isExpanded = button.getAttribute(
                'aria-expanded'
            ) === 'true';

            submenuButtons.forEach(function (otherButton) {
                const otherSubmenu = document.getElementById(
                    otherButton.dataset.atlasSubmenu
                );

                otherButton.setAttribute(
                    'aria-expanded',
                    'false'
                );

                otherButton.classList.remove('active');

                if (otherSubmenu) {
                    otherSubmenu.hidden = true;
                }
            });

            if (!isExpanded) {
                button.setAttribute('aria-expanded', 'true');
                button.classList.add('active');
                submenu.hidden = false;
            }
        });
    });
});
</script>
@endpush
