<?php

namespace spec\Infinity\Bundle\TestBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class InfinityTestExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Infinity\Bundle\TestBundle\DependencyInjection\InfinityTestExtension');
    }

    function it_should_assign_substitutions_to_the_container_if_substitutions_key_is_in_the_config(ContainerBuilder $containerBuilder)
    {
        $configs = [['substitutions' => []]];

        // Called when services are loaded
        $containerBuilder->addResource(Argument::any())->shouldBeCalled();

        // Assign the substitutions array to the container
        $containerBuilder->setParameter('infinity_test.substitutions', [])->shouldBeCalled();

        $this->load($configs, $containerBuilder);
    }

    function it_should_assign_substitutions_to_the_container_if_substitutions_key_is_not_in_the_config(ContainerBuilder $containerBuilder)
    {
        // Subsitutions is added by the tree by default as an empty array
        $configs = [];

        // Called when services are loaded
        $containerBuilder->addResource(Argument::any())->shouldBeCalled();

        // Assign the substitutions array to the container
        $containerBuilder->setParameter('infinity_test.substitutions', [])->shouldBeCalled();

        $this->load($configs, $containerBuilder);
    }
}
