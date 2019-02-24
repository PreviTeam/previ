const cacheName = 'pwa-conf-test';
const staticAssets = [
  './login.php',
  '../css/login.css',
  '../js/passation.js'
];



self.addEventListener('fetch', event => {
  const req = event.request;

  if (/.*(json)$/.test(req.url)) {
    event.respondWith(networkFirst(req));
  } else {
    event.respondWith(cacheFirst(req)); // FOnctionnement des immages en Cache first
  }
});

self.addEventListener('install', async event => {
  const cache = await caches.open(cacheName); // (1)
  await cache.addAll(staticAssets); // (2)
});


// FOnctionnement des cache en définissant l'opérateur prioritaire, cache ou network
async function cacheFirst(req) {
  const cache = await caches.open(cacheName);
  const cachedResponse = await cache.match(req);
  return networkFirst(req) || cachedResponse ;
}

async function networkFirst(req) {
  const cache = await caches.open(cacheName);
  try { // (1)
    const fresh = await fetch(req);

    if (fresh != null)
      cache.put(req, fresh.clone());

    return fresh;
  } catch (e) { // (2)
    const cachedResponse = await cache.match(req);
    return cachedResponse;
  }
}
