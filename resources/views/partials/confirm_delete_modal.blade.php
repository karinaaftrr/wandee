<div class="confirm-delete-modal" id="confirmDeleteModal" aria-hidden="true">
    <div class="confirm-delete-backdrop" data-delete-cancel></div>
    <div class="confirm-delete-dialog" role="dialog" aria-modal="true">
        <h3>Konfirmasi Penghapusan</h3>
        <p id="confirmDeleteText">Apakah Anda yakin ingin menghapus item ini?</p>
        <div class="confirm-delete-actions">
            <form id="confirmDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="confirm-delete-yes">Hapus</button>
            </form>
            <button type="button" class="confirm-delete-no" data-delete-cancel>Batal</button>
        </div>
    </div>
</div>
