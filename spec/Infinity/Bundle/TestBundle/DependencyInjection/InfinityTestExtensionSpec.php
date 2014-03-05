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

    function it_should_assign_substitutions_to_the_container_and_load_services_if_enabled_and_substitutions_key_is_in_the_config(ContainerBuilder $containerBuilder)
    {
        $configs = [['enabled' => true, 'substitutions' => []]];

        // Called when services are loaded
        $containerBuilder->addResource(Argument::any())->shouldBeCalled();

        // Assign the substitutions array to the container
        $containerBuilder->setParameter('infinity_test.substitutions', [])->shouldBeCalled();

        $this->load($configs, $containerBuilder);
    }

    function it_should_assign_substitutions_to_the_container_and_load_services_if_enabled_and_substitutions_key_is_not_in_the_config(ContainerBuilder $containerBuilder)
    {
        // Subsitutions is added by the tree by default as an empty array
        $configs = [['enabled' => true]];

        // Called when services are loaded
        $containerBuilder->addResource(Argument::any())->shouldBeCalled();

        // Assign the substitutions array to the container
        $containerBuilder->setParameter('infinity_test.substitutions', [])->shouldBeCalled();

        $this->load($configs, $containerBuilder);
    }

    function it_should_not_assign_substitutions_to_the_container_and_not_load_services_if_not_enabled(ContainerBuilder $containerBuilder)
    {
        // Enabled is false by default
        $configs = [];

        // Do not load services
        $containerBuilder->addResource(Argument::any())->shouldNotBeCalled();

        // Do not assign substitution to the container
        $containerBuilder->setParameter('infinity_test.substitutions', [])->shouldNotBeCalled();

        $this->load($configs, $containerBuilder);
    }
}
