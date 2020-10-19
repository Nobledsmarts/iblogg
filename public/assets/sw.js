let cacheName = 'v1';
self.addEventListener('install', (e) => {
    console.log('installed - here')
})

self.addEventListener('activate', (e) => {
    console.log('service worker activated');
    e.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if(cache !== cacheName){
                        console.log('clearing old cache');
                        return caches.delete(cache);
                    }
                })
            )
        })
    )
})

self.addEventListener('fetch', e => {
    console.log('fetch');
    e.respondWith(
        fetch(e.request)
        .then(res => {
            let clone = res.clone();
            caches
            .open(cacheName)
            .then(cache => {
                cache.put(e.request, clone);
            });
            return res;
        }).catch(err => caches.match(e.request).then(res => res))
    );
});
