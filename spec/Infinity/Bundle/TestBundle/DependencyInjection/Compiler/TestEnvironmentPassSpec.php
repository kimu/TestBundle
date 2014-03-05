<?php

namespace spec\Infinity\Bundle\TestBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class TestEnvironmentPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Infinity\Bundle\TestBundle\DependencyInjection\Compiler\TestEnvironmentPass');
    }

    function it_should_act_if_the_environment_is_test(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->getParameter('kernel.environment')->willReturn('test');

        $containerBuilder->has('infinity_test.substitutions')->shouldBeCalled();

        $this->process($containerBuilder);
    }

    function it_should_not_act_if_the_environment_is_not_test(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->getParameter('kernel.environment')->willReturn('prod');

        $containerBuilder->has('infinity_test.substitutions')->shouldNotBeCalled();

        $this->process($containerBuilder);
    }

    function it_should_not_replace_services_if_substitutions_is_empty(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->getParameter('kernel.environment')->willReturn('test');
        $containerBuilder->has('infinity_test.substitutions')->willReturn(true);
        $containerBuilder->get('infinity_test.substitutions')->willReturn([]);

        $containerBuilder->hasDefinition(Argument::any())->shouldNotBeCalled();

        $this->process($containerBuilder);
    }

    function it_should_attempt_to_replace_services_if_substitutions_is_not_empty_and_services_are_existing(ContainerBuilder $containerBuilder, Definition $service1Definition)
    {
        $containerBuilder->getParameter('kernel.environment')->willReturn('test');
        $containerBuilder->has('infinity_test.substitutions')->willReturn(true);
        $containerBuilder->get('infinity_test.substitutions')->willReturn([['service1' => ['class' => 'a\namespaced\class', 'inherit_arguments' => true]]]);
        $containerBuilder->hasDefinition('service1')->willReturn(true);
        $containerBuilder->getDefinition('service1')->willReturn($service1Definition);
        $service1Definition->setClass('a\namespaced\class')->willReturn($this);
        $service1Definition->setArguments([])->shouldNotBeCalled();
        $containerBuilder->setDefinition('service1', $service1Definition)->shouldBeCalled();

        $this->process($containerBuilder);
    }

    function it_should_not_attempt_to_replace_services_if_services_are_not_existing(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->getParameter('kernel.environment')->willReturn('test');
        $containerBuilder->has('infinity_test.substitutions')->willReturn(true);
        $containerBuilder->get('infinity_test.substitutions')->willReturn([['notExistingService1' => ['class' => 'a\namespaced\class', 'inherit_arguments' => true]]]);
        $containerBuilder->hasDefinition('notExistingService1')->willReturn(false);
        $containerBuilder->getDefinition(Argument::any())->shouldNotBeCalled();

        $this->process($containerBuilder);
    }

    function it_should_replace_class_names_correctly(ContainerBuilder $containerBuilder, Definition $service1Def, Definition $service2Def, Definition $service3Def, Definition $service4Def)
    {
        $services = [
            ['service1' => ['class' => 'a/wrong/namespaced/class', 'inherit_arguments' => true]],
            ['service2' => ['class' => 'another//wrong//namespaced//class', 'inherit_arguments' => true]],
            ['service3' => ['class' => 'an\unescaped\namespaced\class', 'inherit_arguments' => true]],
            ['service4' => ['class' => 'an\\escaped\\namespaced\\class', 'inherit_arguments' => true]]
        ];

        $containerBuilder->getParameter('kernel.environment')->willReturn('test');
        $containerBuilder->has('infinity_test.substitutions')->willReturn(true);
        $containerBuilder->get('infinity_test.substitutions')->willReturn($services);

        // Service1
        $containerBuilder->hasDefinition('service1')->willReturn(true);
        $containerBuilder->getDefinition('service1')->willReturn($service1Def);
        $service1Def->setClass('a\wrong\namespaced\class')->willReturn($this);
        $containerBuilder->setDefinition('service1', $service1Def)->shouldBeCalled();

        // Service2
        $containerBuilder->hasDefinition('service2')->willReturn(true);
        $containerBuilder->getDefinition('service2')->willReturn($service2Def);
        $service2Def->setClass('another\wrong\namespaced\class')->willReturn($this);
        $containerBuilder->setDefinition('service2', $service2Def)->shouldBeCalled();

        // Service3
        $containerBuilder->hasDefinition('service3')->willReturn(true);
        $containerBuilder->getDefinition('service3')->willReturn($service3Def);
        $service3Def->setClass('an\unescaped\namespaced\class')->willReturn($this);
        $containerBuilder->setDefinition('service3', $service3Def)->shouldBeCalled();

        // Service4
        $containerBuilder->hasDefinition('service4')->willReturn(true);
        $containerBuilder->getDefinition('service4')->willReturn($service4Def);
        $service4Def->setClass('an\escaped\namespaced\class')->willReturn($this);
        $containerBuilder->setDefinition('service4', $service4Def)->shouldBeCalled();

        $this->process($containerBuilder);
    }

    function it_should_remove_arguments_if_inherit_arguments_is_false(ContainerBuilder $containerBuilder, Definition $serviceDefinition)
    {
        $containerBuilder->getParameter('kernel.environment')->willReturn('test');
        $containerBuilder->has('infinity_test.substitutions')->willReturn(true);
        $containerBuilder->get('infinity_test.substitutions')->willReturn([['service' => ['class' => 'a\namespaced\class', 'inherit_arguments' => false]]]);
        $containerBuilder->hasDefinition('service')->willReturn(true);
        $containerBuilder->getDefinition('service')->willReturn($serviceDefinition);
        $serviceDefinition->setClass('a\namespaced\class')->willReturn($serviceDefinition);
        $serviceDefinition->setArguments([])->shouldBeCalled();
        $containerBuilder->setDefinition('service', $serviceDefinition)->shouldBeCalled();

        $this->process($containerBuilder);
    }
}
