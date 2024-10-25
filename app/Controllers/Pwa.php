<?php

namespace App\Controllers;

class Pwa extends App_Controller {
  function __construct() {
    parent::__construct();
    helper(array('general'));
  }

  public function manifest() {
    $base_url = base_url();

    $pwa_theme_color = get_setting("pwa_theme_color");
    if (!$pwa_theme_color) {
      $pwa_theme_color = "#1c2026";
    }


    $icon_name = "default-pwa-icon.png";
    $pwa_icon = get_setting("pwa_icon");

    if ($pwa_icon) {
      try {
        $pwa_icon = unserialize($pwa_icon);
        if (is_array($pwa_icon)) {
          $icon_name = get_array_value($pwa_icon, "file_name");
        }
      } catch (\Exception $ex) {
      }
    }

    $system_file_path = get_setting("system_file_path");

    $manifest = [
      "name" => get_setting("app_title"),
      "short_name" => get_setting("app_title"),
      "start_url" => "{$base_url}index.php",
      "display" => "standalone",
      "background_color" => $pwa_theme_color,
      "theme_color" => $pwa_theme_color,
      "icons" => [
        [
          "src" => "{$base_url}{$system_file_path}pwa/{$icon_name}",
          "sizes" => "192x192",
          "type" => "image/png"
        ]
      ]
    ];

    // Set the content type to application/json
    return $this->response->setContentType('application/json')
      ->setBody(json_encode($manifest));
  }

  public function service_worker() {
    $app_version = get_setting("app_version");
    $base_url = base_url();

    $serviceWorkerScript = "
            const CACHE_NAME = 'pwa-cache-{$app_version}';
            const urlsToCache = [
              '{$base_url}assets/css/app.all.css',
              '{$base_url}assets/js/app.all.js',
            ];

            self.addEventListener('install', event => {
              event.waitUntil(
                caches.open(CACHE_NAME)
                  .then(cache => {
                    return cache.addAll(urlsToCache);
                  })
              );
            });

            self.addEventListener('fetch', event => {
              event.respondWith(
                caches.match(event.request)
                  .then(response => {
                    return response || fetch(event.request);
                  })
              );
            });

            self.addEventListener('activate', event => {
              const cacheWhitelist = [CACHE_NAME];
              event.waitUntil(
                caches.keys().then(cacheNames => {
                  return Promise.all(
                    cacheNames.map(cacheName => {
                      if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                      }
                    })
                  );
                })
              );
            });
        ";

    // Set the content type to application/javascript and return the script
    return $this->response->setContentType('application/javascript')->setBody($serviceWorkerScript);
  }
}