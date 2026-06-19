// MakanMana service worker — offline fallback + light caching
const CACHE = 'makanmana-v1';
const PRECACHE = [
    '/offline.html',
    '/icons/icon.svg',
    '/manifest.json',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE)
            .then((cache) => cache.addAll(PRECACHE))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const req = event.request;
    if (req.method !== 'GET') return;

    // Halaman (navigasi): cuba rangkaian dahulu, fallback ke offline.html
    if (req.mode === 'navigate') {
        event.respondWith(
            fetch(req).catch(() => caches.match('/offline.html'))
        );
        return;
    }

    // Aset same-origin: cache-first dengan kemas kini latar
    const url = new URL(req.url);
    if (url.origin === self.location.origin) {
        event.respondWith(
            caches.match(req).then((cached) => {
                const network = fetch(req).then((res) => {
                    if (res && res.status === 200) {
                        const copy = res.clone();
                        caches.open(CACHE).then((c) => c.put(req, copy));
                    }
                    return res;
                }).catch(() => cached);
                return cached || network;
            })
        );
    }
});
