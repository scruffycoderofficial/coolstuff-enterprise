<?php

namespace App\Console\Commands;

use App\Services\Core\Ldap\LdapAgentInterface;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\LdapInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Class LdapPopulatorCommand
 *
 * @package App\Console\Commands
 */
class LdapPopulatorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ldap:user-populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds new Users into the LDAP Server from database after registration.';

    protected $ldapAgent;

    public function __construct(LdapAgentInterface $ldapAgent)
    {
        parent::__construct();

        $this->ldapAgent = $ldapAgent;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $entry = new Entry('cn=Luyanda Mfombi,dc=coolstuff-enterprise,dc=org', [
            'sn' => ['luyanda'],
            'objectClass' => ['inetOrgPerson'],
        ]);

        $entryManager = $this->ldapAgent->getEntryManager();

// Creating a new entry
        $entryManager->add($entry);

// Finding and updating an existing entry
        $query = $this->ldapAgent->query('dc=coolstuff-enterprise,dc=org', '(&(objectclass=person)(ou=Maintainers))');

        $result = $query->execute();
        $entry = $result[0];

        $phoneNumber = $entry->getAttribute('phoneNumber');
        $isContractor = $entry->hasAttribute('contractorCompany');
// attribute names in getAttribute() and hasAttribute() methods are case-sensitive
// pass FALSE as the second method argument to make them case-insensitive
        $isContractor = $entry->hasAttribute('contractorCompany', false);

        $entry->setAttribute('email', ['fabpot@symfony.com']);
        $entryManager->update($entry);

// Adding or removing values to a multi-valued attribute is more efficient than using update()
        $entryManager->addAttributeValues($entry, 'telephoneNumber', ['+1.111.222.3333', '+1.222.333.4444']);
        $entryManager->removeAttributeValues($entry, 'telephoneNumber', ['+1.111.222.3333', '+1.222.333.4444']);

// Removing an existing entry
        $entryManager->remove(new Entry('cn=Test User,dc=coolstuff-enterprise,dc=org'));
    }
}
