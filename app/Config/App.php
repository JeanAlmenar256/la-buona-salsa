<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * Auto-detect host or local IP
     */
    public string $baseURL = 'http://192.168.0.100/';

    public function __construct()
    {
        parent::__construct();

        if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $this->baseURL = $protocol . $_SERVER['HTTP_HOST'] . '/';
        }
    }

    public array $allowedHostnames = [];

    public string $indexPage = '';

    public string $uriProtocol = 'REQUEST_URI';

    public string $permittedURIChars = 'a-z 0-9~%.:_\-';

    public string $defaultLocale = 'es';

    public bool $negotiateLocale = false;

    public array $supportedLocales = ['es', 'en'];

    public string $appTimezone = 'America/Argentina/Buenos_Aires';

    public string $charset = 'UTF-8';

    /**
     * Disable force https for local network development
     */
    public bool $forceGlobalSecureRequests = false;

    public array $proxyIPs = [];

    public bool $CSPEnabled = false;
}
