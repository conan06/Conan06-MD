var dataCacheName = 'blogData-v1';
var cacheName = 'Conan06%27s blog';
var filesToCache = [
  '../../../../',
  '../style.css',
  '../assets/css/material.min.css',
  '../assets/css/material.icons.css',
  '../assets/fonts/MaterialIcons-Regular.woff2',
  '../assets/images/header.jpg',
  '../assets/images/null_page.png',
  '../assets/images/null_search.png',
  '../assets/images/site_icon.png',
  '../assets/images/svg-icons.svg',
  '../assets/images/player/ic_audiotrack.svg',
  '../assets/images/player/ic_pause_circle_filled.svg',
  '../assets/images/player/ic_play_circle_filled.svg',
  '../assets/images/player/ic_skip_next.svg',
  '../assets/images/player/ic_skip_previous.svg',
  '../assets/images/player/ic_volume_off.svg',
  '../assets/images/player/ic_volume_up.svg',
  '../assets/js/global.js',
  '../assets/js/html5.js',
  '../assets/js/material.min.js',
  '../assets/js/parallax.min.js',
  '../assets/js/swiper.min.js'
];

self.addEventListener('install', function(e) {
  console.log('[ServiceWorker] Install');
  e.waitUntil(
    caches.open(cacheName).then(function(cache) {
      console.log('[ServiceWorker] Caching app shell');
      return cache.addAll(filesToCache);
    })
  );
});

self.addEventListener('activate', function(e) {
  console.log('[ServiceWorker] Activate');
  e.waitUntil(
    caches.keys().then(function(keyList) {
      return Promise.all(keyList.map(function(key) {
        if (key !== cacheName && key !== dataCacheName) {
          console.log('[ServiceWorker] Removing old cache', key);
          return caches.delete(key);
        }
      }));
    })
  );

  return self.clients.claim();
});

self.addEventListener('fetch', function(e) {  
  console.log('[ServiceWorker] Fetch', e.request.url);  
  e.respondWith(  
    caches.match(e.request).then(function(response) {  
      return response || fetch(e.request);  
    })  
  );  
});
