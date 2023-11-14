<?php

namespace App\Services\Core\Ldap;

use Symfony\Component\Ldap\Adapter\EntryManagerInterface;
use Symfony\Component\Ldap\Entry;

/**
 * Interface LdapAgentInterface
 *
 * @package App\Services\Core\Ldap
 */
interface LdapAgentInterface
{
    /**
     * @return EntryManagerInterface
     */
    public function getEntryManager(): EntryManagerInterface;

    /**
     * @param Entry $entry
     * @return mixed
     */
    public function addEntry(Entry $entry);

    /**
     * @param Entry $entry
     * @return mixed
     */
    public function updateEntry(Entry $entry);
}
