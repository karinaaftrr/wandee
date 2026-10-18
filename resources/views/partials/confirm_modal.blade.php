@once
<style>
    .confirm-modal {
        position: fixed;
        inset: 0;
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .confirm-modal.is-open {
        display: flex;
    }

    .confirm-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .72);
        backdrop-filter: blur(6px);
    }

    .confirm-dialog {
        position: relative;
        width: min(420px, 100%);
        padding: 30px 28px 24px;
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, .1);
        background: #0b1510;
        box-shadow: 0 24px 70px rgba(0, 0, 0, .55);
        text-align: center;
    }

    .confirm-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        margin-bottom: 14px;
        border-radius: 50%;
        background: rgba(255, 79, 129, .12);
        color: #ff4f81;
        font-size: 1.5rem;
        font-weight: 800;
    }

    .confirm-dialog h3 {
        margin: 0 0 8px;
        color: #fff;
        font-size: 1.2rem;
    }

    .confirm-dialog p {
        margin: 0 0 22px;
        color: rgba(232, 240, 235, .68);
        font-size: .95rem;
        line-height: 1.7;
    }

    .confirm-actions {
        display: flex;
        gap: 10px;
    }

    .confirm-actions button {
        flex: 1;
        padding: 12px 16px;
        border-radius: 14px;
        font-size: .92rem;
        font-weight: 700;
        cursor: pointer;
        transition: .2s ease;
    }

    .confirm-cancel {
        border: 1px solid rgba(255, 255, 255, .16);
        background: transparent;
        color: rgba(232, 240, 235, .85);
    }

    .confirm-cancel:hover {
        background: rgba(255, 255, 255, .06);
    }

    .confirm-ok {
        border: 1px solid #ff4f81;
        background: #ff4f81;
        color: #1a0610;
    }

    .confirm-ok:hover {
        filter: brightness(1.08);
    }

    body.confirm-open {
        overflow: hidden;
    }
</style>

<div class="confirm-modal" id="confirmModal" aria-hidden="true">
    <div class="confirm-backdrop" data-confirm-cancel></div>

    <div class="confirm-dialog" role="alertdialog" aria-modal="true" aria-labelledby="confirmTitle" aria-describedby="confirmText">
        <span class="confirm-icon" aria-hidden="true">!</span>
        <h3 id="confirmTitle">Konfirmasi</h3>
        <p id="confirmText"></p>

        <div class="confirm-actions">
            <button type="button" class="confirm-cancel" data-confirm-cancel>Batal</button>
            <button type="button" class="confirm-ok" id="confirmOk">Ya, hapus</button>
        </div>
    </div>
</div>

<script>
    (function () {
        var modal = document.getElementById('confirmModal');
        var titleEl = document.getElementById('confirmTitle');
        var textEl = document.getElementById('confirmText');
        var okBtn = document.getElementById('confirmOk');
        var pendingForm = null;

        function openConfirm(form) {
            pendingForm = form;
            titleEl.textContent = form.getAttribute('data-confirm-title') || 'Konfirmasi';
            textEl.textContent = form.getAttribute('data-confirm') || 'Yakin ingin melanjutkan?';
            okBtn.textContent = form.getAttribute('data-confirm-ok') || 'Ya, hapus';
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('confirm-open');
            okBtn.focus();
        }

        function closeConfirm() {
            pendingForm = null;
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('confirm-open');
        }

        // Form dengan atribut data-confirm tidak langsung terkirim, tampilkan popup dulu
        document.addEventListener('submit', function (event) {
            var form = event.target;
            if (!form || !form.hasAttribute || !form.hasAttribute('data-confirm')) return;
            event.preventDefault();
            openConfirm(form);
        });

        okBtn.addEventListener('click', function () {
            if (!pendingForm) return;
            var form = pendingForm;
            closeConfirm();
            HTMLFormElement.prototype.submit.call(form);
        });

        modal.querySelectorAll('[data-confirm-cancel]').forEach(function (el) {
            el.addEventListener('click', closeConfirm);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                closeConfirm();
            }
        });
    })();
</script>
@endonce