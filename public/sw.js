// Service Worker untuk Our Plan Background Notifications
const CACHE_NAME = 'ourplan-v1';

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

// Listener untuk Push Event dari Server / Background Sync
self.addEventListener('push', (event) => {
    let data = {
        title: 'Our Plan Notifikasi',
        body: 'Anda memiliki pembaruan tugas baru di Our Plan.',
        icon: '/images/logo-1.png',
        badge: '/images/logo-1.png',
        url: '/dashboard'
    };

    if (event.data) {
        try {
            data = Object.assign(data, event.data.json());
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const options = {
        body: data.body,
        icon: data.icon || '/images/logo-1.png',
        badge: data.badge || '/images/logo-1.png',
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/dashboard'
        },
        actions: [
            { action: 'open', title: '🚀 Buka Our Plan' },
            { action: 'close', title: 'Tutup' }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// Listener saat notifikasi diklik pengguna di Desktop / Mobile OS
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'close') {
        return;
    }

    const targetUrl = (event.notification.data && event.notification.data.url) ? event.notification.data.url : '/dashboard';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            // Jika ada tab yang sudah terbuka, fokuskan ke tab tersebut
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url.includes('/dashboard') && 'focus' in client) {
                    return client.focus();
                }
            }
            // Jika belum ada tab yang terbuka, buka tab baru
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
