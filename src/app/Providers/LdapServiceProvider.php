<?php

namespace App\Providers;

use App\Services\Core\Ldap\LdapAgent;
use App\Services\Core\Ldap\LdapAgentInterface;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\LdapInterface;

/**
 * Class LdapServiceProvider
 *
 * @package App\Providers
 */
class LdapServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        /** @see \Symfony\Component\Ldap\Ldap::create */
        $this->app->bind(LdapInterface::class, function (){
            $ldap = Ldap::create('ext_ldap', [ 'connection_string' => env('LDAP_HOST') ]);

            $ldap->bind('cn=' . env('LDAP_CN') . ',' . env('LDAP_DC'), env('LDAP_PASS'));

            return $ldap;
        });

        $this->app->bind(LdapAgentInterface::class, function(){
            return new LdapAgent($this->app->get(LdapInterface::class), $this->app->get(LoggerInterface::class));
        });
    }
}
