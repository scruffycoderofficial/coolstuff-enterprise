<?php

namespace App\Console\Commands;

use Symfony\Component\Ldap\LdapInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Class LdapConnectionStatusCheck
 *
 * @package App\Console\Commands
 */
class LdapConnectionStatusCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ldap:status-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reports on the targeted Ldap Server connection status.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $style = new OutputFormatterStyle('white', 'blue', ['bold']);

        $this->output->getFormatter()->setStyle('bigBlue', $style);

        if (!function_exists('ldap_connect')) {
            $this->alert('The LDAP Extension is probably not supported. Please install the said extension, and try again.');

            return 0;
        }

        $ldap_conn = ldap_connect(env('LDAP_HOST'), env('LDAP_PORT'));

        if (!$ldap_conn) {
            $this->error('Could not connect to LDAP server.');
        }

        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

        $ldap_dn = 'cn=' . env('LDAP_CN') . ',' . env('LDAP_DC');

        $ldap_bind = ldap_bind($ldap_conn, $ldap_dn , env('LDAP_PASS'));

        if ($ldap_bind) {
            //$this->line("<bigBlue>LDAP bind successful..</bigBlue>");
            $this->line('<fg=red;options=blink;bg=cyan>LDAP binds successful..</>');
        } else {
            $this->info('LDAP bind failed.');
        }
    }
}
