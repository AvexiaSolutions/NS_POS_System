import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

const reverbHost = import.meta.env.VITE_REVERB_HOST;
const isLocalHostConfig = !reverbHost || reverbHost === 'localhost' || reverbHost === '127.0.0.1';
const currentHost = window.location.hostname;

// Automatically resolve host so it works from both localhost and external domain/IP
const resolvedHost = (isLocalHostConfig && currentHost !== 'localhost' && currentHost !== '127.0.0.1')
    ? currentHost
    : (reverbHost || currentHost);

const isSecure = (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https' || window.location.protocol === 'https:';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY || 'pos_reverb_key',
    wsHost: resolvedHost,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: isSecure,
    enabledTransports: ['ws', 'wss'],
});
