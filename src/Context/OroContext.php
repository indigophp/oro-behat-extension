<?php

namespace Indigo\Oro\Behat\Context;

use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Oro\Bundle\SearchBundle\Engine\EngineInterface;
use Oro\Bundle\TestFrameworkBundle\Test\Client;

/**
 * Context containing Oro hooks.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class OroContext implements KernelAwareContext
{
    use KernelDictionary;

    /** Default WSSE credentials */
    const USER_NAME = 'admin';
    const USER_PASSWORD = 'admin_api_key';

    /**
     * @var Client
     */
    private $client;

    /**
     * @BeforeScenario @dbIsolation
     */
    public function startTransaction()
    {
        $this->getClient()->startTransaction();
    }

    /**
     * @AfterScenario @dbIsolation
     */
    public function rollbackTransaction()
    {
        $this->getClient()->rollbackTransaction();
    }

    /**
     * @BeforeScenario @dbIsolation&&@dbReindex
     */
    public function reindex()
    {
        /** @var EngineInterface $searchEngine */
        $searchEngine = $this->getContainer()->get('oro_search.search.engine');

        $searchEngine->reindex();
    }

    /**
     * @BeforeScenario @wsse
     */
    public function setWsseHeader()
    {
        $this->getClient()->setServerParameters($this->generateWsseAuthHeader());
    }

    /**
     * Returns the test client.
     *
     * @return Client
     */
    public function getClient()
    {
        if (null === $this->client) {
            $this->client = $this->getContainer()->get('test.client');

            if (false === $this->client instanceof Client) {
                throw new \RuntimeException('The test client must be an instance of Oro\Bundle\TestFrameworkBundle\Test\Client');
            }
        }

        return $this->client;
    }

    /**
     * Generates WSSE Auth header.
     *
     * {@link \Oro\Bundle\TestFrameworkBundle\Test\WebTestCase}
     *
     * @param string      $userName
     * @param string      $userPassword
     * @param string|null $nonce
     *
     * @return array
     */
    public function generateWsseAuthHeader(
        $userName = self::USER_NAME,
        $userPassword = self::USER_PASSWORD,
        $nonce = null
    ) {
        if (null === $nonce) {
            $nonce = uniqid();
        }

        $created = date('c');
        $digest = base64_encode(sha1(base64_decode($nonce).$created.$userPassword, true));
        $wsseHeader = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => 'WSSE profile="UsernameToken"',
            'HTTP_X-WSSE' => sprintf(
                'UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
                $userName,
                $digest,
                $nonce,
                $created
            ),
        ];

        return $wsseHeader;
    }
}
