io.on('connection', socket => {
  console.log('Usuario conectado');

  socket.on('ubicacion', coords => {
    io.emit('nueva-ubicacion', {
      id: socket.id,
      lat: coords.lat,
      lng: coords.lng
    });
  });

  socket.on('disconnect', () => {
    console.log('Usuario desconectado');
  });
});

