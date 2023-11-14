<?php

namespace App\Services\Core\Ldap;


use Psr\Log\LoggerInterface;
use Symfony\Component\Ldap\Adapter\EntryManagerInterface;
use Symfony\Component\Ldap\Adapter\QueryInterface;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Ldap\Entry;

/**
 * Class LdapAgent
 *
 * @package App\Services\Core\Ldap
 */
final class LdapAgent implements LdapAgentInterface
{
    /**
     * @var LdapInterface $ldap
     */
    private $ldap;

    /**
     * @var LoggerInterface|null $logger
     */
    private $logger = null;

    /**
     * LdapAgent constructor.
     *
     * @param LdapInterface $ldap
     * @param LoggerInterface|null $logger
     */
    public function __construct(LdapInterface $ldap, LoggerInterface $logger = null)
    {
        $this->ldap = $ldap;

        $this->logger = $logger;
    }

    /**
     * @param $entry
     * @param $pointer
     * @return QueryInterface
     */
    public function query($entry, $pointer): QueryInterface
    {
        return $this->ldap->query($entry, $pointer);
    }

    /**
     * @return EntryManagerInterface
     */
    public function getEntryManager(): EntryManagerInterface
    {
        return $this->ldap->getEntryManager();
    }

    /**
     * @param Entry $entry
     * @return mixed|void
     */
    public function addEntry(Entry $entry)
    {
        try {

            $this->getEntryManager()->add($entry);

        } catch (\Exception $exc) {
            if (!is_null($this->logger)) {
                $this->logger->notice($exc->getMessage());
            }
        }
    }

    /**
     * @param Entry $entry
     * @return mixed|void
     */
    public function updateEntry(Entry $entry)
    {
        try {

            $this->getEntryManager()->update($entry);

        } catch (\Exception $exc) {
            if (!is_null($this->logger)) {
                $this->logger->notice($exc->getMessage());
            }
        }
    }
}
