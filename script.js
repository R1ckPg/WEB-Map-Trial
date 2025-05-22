let mapa = L.map('mapa').setView([-23.5505, -46.6333], 12);
let marcadores = [];

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(mapa);

async function carregarPontos(filtro = "") {
  marcadores.forEach(m => mapa.removeLayer(m));
  marcadores = [];

  const res = await fetch("pontos.php");
  const pontos = await res.json();

  pontos.forEach(p => {
    if (filtro && !p.tipo_doacao.includes(filtro)) return;
    const marcador = L.marker([p.latitude, p.longitude])
      .bindPopup(`<b>${p.nome}</b><br>${p.endereco}<br><b>Tipos:</b> ${p.tipo_doacao}`);
    marcador.addTo(mapa);
    marcadores.push(marcador);
  });

  if (marcadores.length) {
    const group = L.featureGroup(marcadores);
    mapa.fitBounds(group.getBounds());
  }
}

carregarPontos();
