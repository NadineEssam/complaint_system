<?php

return [
    'hosts' => explode(',', env('LDAP_HOSTS', '127.0.0.1')),
    'port' => env('LDAP_PORT', 389),
    'domain' => env('LDAP_DOMAIN', 'sfd'),
];