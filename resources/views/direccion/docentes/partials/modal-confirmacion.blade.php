<div class="ad-modal-overlay" id="ad-confirm-modal" hidden role="presentation">
  <div class="ad-modal" role="dialog" aria-modal="true" aria-labelledby="ad-confirm-title" aria-describedby="ad-confirm-message">
    <div class="ad-modal-icon"><i class="bi bi-exclamation-triangle"></i></div>
    <h3 id="ad-confirm-title">Confirmar acción</h3>
    <p id="ad-confirm-message"></p>
    <div class="ad-modal-actions"><button type="button" class="ad-btn" id="ad-confirm-cancel">Cancelar</button><button type="button" class="ad-btn danger" id="ad-confirm-accept">Confirmar</button></div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('ad-confirm-modal');
    if (!modal) return;
    const title = document.getElementById('ad-confirm-title');
    const message = document.getElementById('ad-confirm-message');
    const cancel = document.getElementById('ad-confirm-cancel');
    const accept = document.getElementById('ad-confirm-accept');
    let pendingForm = null;
    let previousFocus = null;
    function closeModal() { modal.hidden = true; pendingForm = null; document.body.classList.remove('ad-modal-open'); previousFocus?.focus(); }
    document.querySelectorAll('form[data-confirm-title]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.reportValidity()) return;
            event.preventDefault();
            pendingForm = form;
            previousFocus = document.activeElement;
            title.textContent = form.dataset.confirmTitle;
            message.textContent = form.dataset.confirmMessage || '¿Desea continuar?';
            modal.hidden = false;
            document.body.classList.add('ad-modal-open');
            cancel.focus();
        });
    });
    cancel.addEventListener('click', closeModal);
    accept.addEventListener('click', function () { if (pendingForm) { accept.disabled = true; pendingForm.submit(); } });
    modal.addEventListener('click', function (event) { if (event.target === modal) closeModal(); });
    document.addEventListener('keydown', function (event) {
        if (modal.hidden) return;
        if (event.key === 'Escape') closeModal();
        if (event.key === 'Tab') { const target = document.activeElement === cancel ? accept : cancel; event.preventDefault(); target.focus(); }
    });
});
</script>
