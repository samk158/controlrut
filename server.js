const express = require('express');
const app = express();
const http = require('http').createServer(app);
const io = require('socket.io')(http);

const PORT = 3000;

// Almacena ubicaciones de conductores
let ubicaciones = {};

app.use(express.static('public')); // Carpeta para archivos frontend

io.on('connection', (socket) => {
  console.log('Usuario conectado:', socket.id);

  // Cuando un conductor envía su ubicación
  socket.on('ubicacion', (data) => {
    // data: { idConductor, lat, lng }
    ubicaciones[data.idConductor] = {
      lat: data.lat,
      lng: data.lng,
      ultimaActualizacion: new Date()
    };

    // Enviar ubicaciones actualizadas a todos los conectados
    io.emit('actualizarUbicaciones', ubicaciones);
  });

  socket.on('disconnect', () => {
    console.log('Usuario desconectado:', socket.id);
    // Opcional: podrías eliminar al conductor de ubicaciones aquí
  });
});

http.listen(PORT, () => {
  console.log(`Servidor escuchando en http://localhost:${PORT}`);
});
