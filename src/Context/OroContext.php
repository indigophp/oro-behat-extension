<?php

namespace Indigo\Oro\Behat\Context;

use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Oro\Bundle\SearchBundle\Engine\EngineInterface;
use Oro\Bundle\TestFrameworkBundle\Test\Client;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * Context containing Oro hooks.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class OroContext implements KernelAwareContext
{
    use KernelDictionary;

    /**
     * @BeforeScenario @dbIsolation
     */
    public function startTransaction()
    {
        $client = $this->getClient();

        $client->startTransaction();
    }

    /**
     * @AfterScenario @dbIsolation
     */
    public function rollbackTransaction()
    {
        $client = $this->getClient();

        $client->rollbackTransaction();
    }

    /**
     * @BeforeScenario @dbIsolation&&@dbReindex
     */
    public function reindex()
    {
        /** @var EngineInterface $searchEngine */
        $searchEngine = $client = $this->getContainer()->get('oro_search.search.engine');

        $searchEngine->reindex();
    }

    /**
     * @BeforeScenario @wsse
     */
    public function setWsseHeader()
    {
        $client = $this->getClient();

        $client->setServerParameters(WebTestCase::generateWsseAuthHeader());
    }

    /**
     * Returns the test client.
     *
     * @return Client
     */
    protected function getClient()
    {
        $client = $this->getContainer()->get('test.client');

        if (false === $client instanceof Client) {
            throw new \RuntimeException('The test client must be an instance of Oro\Bundle\TestFrameworkBundle\Test\Client');
        }

        return $client;
    }
}
