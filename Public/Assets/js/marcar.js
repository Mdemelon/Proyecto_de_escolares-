const btn = document.getElementById('btn-marcar');
const msg = document.getElementById('mensaje');
const contador = document.getElementById('contador');

// Contador de tiempo transcurrido desde la entrada
if (contador) {
  const inicio = parseInt(contador.dataset.inicio, 10);
  const dos = n => String(n).padStart(2, '0');
  const pintar = () => {
    const seg = Math.max(0, Math.floor(Date.now() / 1000) - inicio);
    contador.textContent = dos(Math.floor(seg / 3600)) + ':' + dos(Math.floor((seg % 3600) / 60)) + ':' + dos(seg % 60);
  };
  pintar();
  setInterval(pintar, 1000);
}

// Botón de marcar entrada / salida (API la define la página)
if (btn) btn.addEventListener('click', async () => {
  btn.disabled = true;
  msg.hidden = true;
  try {
    const r = await fetch(API + btn.dataset.accion + '.php', { method: 'POST' });
    const data = await r.json();
    msg.textContent = data.mensaje;
    msg.className = 'alerta ' + (data.ok ? 'ok' : 'error');
    msg.hidden = false;
    if (data.ok) setTimeout(() => location.reload(), 1000);
    else btn.disabled = false;
  } catch (e) {
    msg.textContent = 'No se pudo conectar con el servidor.';
    msg.className = 'alerta error';
    msg.hidden = false;
    btn.disabled = false;
  }
});
