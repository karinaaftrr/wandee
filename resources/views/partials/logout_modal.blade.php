@once
<div class="logout-modal" id="logoutModal" aria-hidden="true">
    <div class="logout-modal-backdrop" data-logout-cancel></div>

    <div class="logout-dialog" role="dialog" aria-modal="true" aria-labelledby="logoutTitle">
        <h3 id="logoutTitle">Apakah anda ingin keluar?</h3>
        <p>Sesi akun akan ditutup dan anda perlu masuk kembali untuk melanjutkan.</p>

        <div class="logout-actions">
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="logout-confirm">Ya</button>
            </form>
            <button type="button" class="logout-cancel" data-logout-cancel>Kembali</button>
        </div>
    </div>
</div>
@endonce