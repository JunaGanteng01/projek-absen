(() => {
  const notice = () => {
    let message = document.getElementById('demoNotice');
    if (!message) {
      message = document.createElement('div');
      message.id = 'demoNotice';
      message.className = 'alert alert-info';
      message.setAttribute('role', 'status');
      (document.querySelector('.content-wrap') || document.body).prepend(message);
    }
    message.textContent = 'Ini adalah demo tampilan. Tindakan ini tidak menyimpan atau mengirim data.';
    message.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  };
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', event => { event.preventDefault(); notice(); });
  });
  document.querySelectorAll('[data-demo-action], #startScan, #stopScan, #scanFileBtn').forEach(button => {
    button.addEventListener('click', event => { event.preventDefault(); notice(); });
  });
  document.querySelectorAll('.edit-row').forEach(button => {
    button.addEventListener('click', () => {
      const row = JSON.parse(button.dataset.row);
      const form = document.getElementById('crudForm');
      Object.entries(row).forEach(([key, value]) => {
        if (form.elements[key]) form.elements[key].value = value ?? '';
      });
      document.getElementById('formTitle').textContent = 'Preview Edit Data #' + row.id;
      form.scrollIntoView({ behavior: 'smooth' });
    });
  });
  const clock = document.getElementById('clock');
  if (clock) {
    const updateClock = () => { clock.textContent = new Date().toLocaleTimeString('id-ID', { timeZone: 'Asia/Jakarta' }) + ' WIB'; };
    updateClock();
    setInterval(updateClock, 1000);
  }
  const chart = document.getElementById('chart');
  if (chart) {
    const canvas = document.createElement('div');
    canvas.style.cssText = 'display:flex;gap:14px;align-items:end;height:230px;padding:24px 10px';
    [32, 38, 35, 44, 40, 37, 42].forEach((value, index) => {
      const bar = document.createElement('div');
      bar.style.cssText = 'flex:1;text-align:center;color:#9aa09a;font-size:11px';
      bar.innerHTML = `<div>${value}</div><div style="background:#33e818;height:${value * 3}px;border-radius:6px 6px 0 0;margin:8px 0"></div><div>${['Sen','Sel','Rab','Kam','Jum','Sab','Min'][index]}</div>`;
      canvas.append(bar);
    });
    chart.replaceWith(canvas);
  }
  const map = document.getElementById('map');
  if (map) {
    map.style.cssText = 'height:290px;display:grid;place-items:center;background:repeating-linear-gradient(35deg,#111 0,#111 38px,#222 39px,#111 41px);text-align:center';
    map.innerHTML = '<div><i class="bi bi-geo-alt-fill" style="font-size:42px;color:#33e818"></i><div class="mt-2">Kantor Contoh</div><div class="text-secondary small mt-2">Ilustrasi lokasi · Data demo</div></div>';
  }
  const qr = document.getElementById('qrcode');
  if (qr) {
    qr.innerHTML = '<div class="p-4 text-dark"><i class="bi bi-qr-code" style="font-size:200px;line-height:1"></i><div class="small">QR ilustrasi</div></div>';
    document.getElementById('countdown').textContent = '30';
  }
})();
