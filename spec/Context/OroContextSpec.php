<?php

namespace spec\Indigo\Oro\Behat\Context;

use Oro\Bundle\SearchBundle\Engine\EngineInterface;
use Oro\Bundle\TestFrameworkBundle\Test\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OroContextSpec extends ObjectBehavior
{
    function let(KernelInterface $kernel, ContainerInterface $container)
    {
        $kernel->getContainer()->willReturn($container);

        $this->setKernel($kernel);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Oro\Behat\Context\OroContext');
    }

    function it_is_a_kernel_aware_context()
    {
        $this->shouldImplement('Behat\Symfony2Extension\Context\KernelAwareContext');
    }

    function it_reindexes_the_database(EngineInterface $searchEngine, ContainerInterface $container)
    {
        $searchEngine->reindex()->shouldBeCalled();
        $container->get('oro_search.search.engine')->willReturn($searchEngine);

        $this->reindex();
    }

    function it_sets_wsse_headers(Client $client, ContainerInterface $container)
    {
        $client->setServerParameters(Argument::type('array'))->shouldBeCalled();
        $container->get('test.client')->willReturn($client);

        $this->setWsseHeader();
    }
}
