{{-- Notifikasi toast stack — dipakai di admin & petugas layout --}}
<div id="notifStack"
     style="position:fixed;top:18px;right:20px;z-index:1070;
            width:360px;max-width:calc(100vw - 40px);
            display:flex;flex-direction:column;gap:10px;pointer-events:none;">
</div>

<style>
@keyframes notifIn  { from{opacity:0;transform:translateX(24px)} to{opacity:1;transform:translateX(0)} }
@keyframes notifOut { from{opacity:1;transform:translateX(0);max-height:180px;margin-bottom:0}
                      to  {opacity:0;transform:translateX(24px);max-height:0;margin-bottom:0;padding:0} }
.notif-item { animation: notifIn .28s cubic-bezier(.34,1.56,.64,1); pointer-events:auto; }
.notif-item.out { animation: notifOut .25s ease forwards; overflow:hidden; }
</style>

<script>
(function () {
    const CSRF     = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const stack    = document.getElementById('notifStack');
    const seen     = new Set(JSON.parse(sessionStorage.getItem('_notif_seen') ?? '[]'));
    const POLL     = 30_000;

    const COLOR = { laporan:'#ef4444', info:'#3b82f6', warning:'#f59e0b', success:'#10b981' };
    const ICON  = { laporan:'campaign', info:'info',    warning:'warning',  success:'check_circle' };

    function saveSeen() {
        sessionStorage.setItem('_notif_seen', JSON.stringify([...seen]));
    }

    function ago(iso) {
        const s = Math.floor((Date.now() - new Date(iso)) / 1000);
        if (s < 60)    return 'Baru saja';
        if (s < 3600)  return Math.floor(s/60)   + ' mnt lalu';
        if (s < 86400) return Math.floor(s/3600)  + ' jam lalu';
        return Math.floor(s/86400) + ' hari lalu';
    }

    function dismiss(id) {
        const el = stack.querySelector(`[data-nid="${id}"]`);
        if (!el) return;
        el.classList.add('out');
        el.addEventListener('animationend', () => el.remove(), { once: true });

        fetch(`{{ $bacaBase }}/${id}/baca`, {
            method : 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        }).catch(() => {});
    }

    window._sipda ??= {};
    window._sipda.dismiss = dismiss;

    function render(n) {
        if (seen.has(n.id)) return;
        seen.add(n.id);
        saveSeen();

        const color = COLOR[n.tipe] ?? '#6366f1';
        const icon  = ICON[n.tipe]  ?? 'notifications';

        const wrap = document.createElement('div');
        wrap.className   = 'notif-item';
        wrap.dataset.nid = n.id;
        wrap.innerHTML = `
<div style="background:var(--bs-body-bg,#fff);border-radius:14px;
            border-left:4px solid ${color};
            box-shadow:0 8px 32px rgba(0,0,0,.18);overflow:hidden;">

  {{-- Header --}}
  <div style="display:flex;align-items:flex-start;gap:11px;padding:13px 13px 8px;">
    <div style="width:38px;height:38px;border-radius:10px;background:${color};flex-shrink:0;
                display:flex;align-items:center;justify-content:center;">
      <span class="material-symbols-outlined msf text-white ms-sm">${icon}</span>
    </div>
    <div style="flex:1;min-width:0;">
      <div style="font-weight:700;font-size:13px;color:var(--bs-body-color);">${n.judul}</div>
      <div style="font-size:12px;color:var(--bs-secondary-color);margin-top:3px;line-height:1.45;">${n.pesan}</div>
      <div style="font-size:10.5px;color:var(--bs-secondary-color);opacity:.65;margin-top:5px;">${ago(n.created_at)}</div>
    </div>
    <button onclick="window._sipda.dismiss(${n.id})" title="Tutup"
            style="background:none;border:none;cursor:pointer;padding:1px;
                   color:var(--bs-secondary-color);flex-shrink:0;line-height:1;margin-top:-2px;">
      <span class="material-symbols-outlined ms-sm">close</span>
    </button>
  </div>

  {{-- Footer actions --}}
  <div style="display:flex;gap:8px;padding:0 13px 12px;">
    ${n.url
        ? `<a href="${n.url}" onclick="window._sipda.dismiss(${n.id})"
              style="display:inline-flex;align-items:center;gap:5px;
                     background:${color};color:#fff;border-radius:8px;
                     padding:5px 13px;font-size:12px;font-weight:600;text-decoration:none;">
             <span class="material-symbols-outlined ms-sm">arrow_forward</span>Lihat Laporan
           </a>`
        : ''}
    <button onclick="window._sipda.dismiss(${n.id})"
            style="background:var(--bs-secondary-bg);border:none;border-radius:8px;
                   padding:5px 12px;font-size:12px;cursor:pointer;
                   color:var(--bs-body-color);">
      Tutup
    </button>
  </div>
</div>`;

        stack.appendChild(wrap);

        // Auto-dismiss setelah 15 detik
        setTimeout(() => dismiss(n.id), 15_000);
    }

    function poll() {
        fetch('{{ $unreadUrl }}', {
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        })
        .then(r => r.ok ? r.json() : [])
        .then(list => list.forEach(render))
        .catch(() => {});
    }

    poll();
    setInterval(poll, POLL);
})();
</script>
