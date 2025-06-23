import { createServer } from 'http';
import { Server }       from 'socket.io';
import Redis            from 'ioredis';

// 1) HTTP + Socket.IO ----------------------------------------------------------------
const httpServer = createServer();
const io = new Server(httpServer, {
  cors: { origin: '*' },            // permite cualquier origen en desarrollo
});

// 2) Conectar a Redis ---------------------------------------------------------------
//  - Usamos el hostname "redis" porque es el nombre del servicio dentro de docker‑compose.
//  - Puedes sobreescribirlo con REDIS_HOST en producción.
const sub = new Redis({
  host:     process.env.REDIS_HOST     || 'redis',
  port:     process.env.REDIS_PORT     || 6379,
  password: process.env.REDIS_PASSWORD || null,
});

// Eventos de diagnóstico de Redis ---------------------------------------------------
sub.on('connect',      () => console.log('🔗 Redis: conexión establecida'));
sub.on('ready',        () => console.log('✅ Redis: cliente listo'));
sub.on('error',  err   => console.error('🔴 Redis error:', err));
sub.on('close',        () => console.log('⚠️ Redis: conexión cerrada'));
sub.on('reconnecting', () => console.log('🔄 Redis: reconectando…'));
sub.on('end',          () => console.log('⏹️ Redis: conexión terminada'));

// 3) Suscribirnos a los canales -----------------------------------------------------
const CHANNELS = ['events', 'gpslocations'];
sub.subscribe(...CHANNELS, (err, count) => {
  if (err) {
    return console.error('🔴 Error al subscribir:', err);
  }
  console.log(`📡 Redis: suscrito a ${count} canal(es): ${CHANNELS.join(', ')}`);
});
sub.on('subscribe', (channel, count) => {
  console.log(`👍 Redis on \"subscribe\": canal='${channel}', total=${count}`);
});

// 4) Cuando Redis publique algo, parsear, loguear y reenviar ------------------------
sub.on('message', (channel, raw) => {
  console.log(`➡️ Redis → ${channel}: ${raw}`);

  let pkt;
  try {
    pkt = JSON.parse(raw);
  } catch (e) {
    return console.error('❌ JSON inválido:', raw);
  }

  // 4a) Reemitir a todos los clientes Socket.IO con el nombre del canal
  io.emit(channel, pkt);

  // 4b) Compatibilidad con Laravel Echo / Pusher style
  //      Si el payload trae { event, data } reenviamos "data" con el nombre del evento.
  if (pkt.event && pkt.data) {
    io.to(channel).emit(pkt.event, pkt.data);
  }
});

// 5) Conexiones de cliente Socket.IO ------------------------------------------------
io.on('connection', socket => {
  console.log('🔌 Cliente WS conectado:', socket.id);
  socket.on('disconnect', () => {
    console.log('❌ Cliente WS desconectado:', socket.id);
  });
});

// 6) Arrancar -----------------------------------------------------------------------
const PORT = process.env.WS_PORT || 6001;      // 6001 es el puerto típico de Echo
httpServer.listen(PORT, '0.0.0.0', () => {
  console.log(`🚀 WS escuchando en puerto ${PORT}`);
});
