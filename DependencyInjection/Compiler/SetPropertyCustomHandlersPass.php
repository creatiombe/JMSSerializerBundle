<?php

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace JMS\SerializerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class SetPropertyCustomHandlersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $serializationHandlers = array();
        foreach ($container->findTaggedServiceIds('jms_serializer.property_serialization_handler') as $id => $attributes) {
            $serializationHandlers[] = new Reference($id);
        }

        $deserializationHandlers = array();
        foreach ($container->findTaggedServiceIds('jms_serializer.property_deserialization_handlers') as $id => $attributes) {
            $deserializationHandlers[] = new Reference($id);
        }

        $container
            ->getDefinition('jms_serializer.json_serialization_visitor')
            ->replaceArgument(2, $serializationHandlers)
        ;
        $container
            ->getDefinition('jms_serializer.xml_serialization_visitor')
            ->replaceArgument(2, $serializationHandlers)
        ;
        $container
            ->getDefinition('jms_serializer.yaml_serialization_visitor')
            ->replaceArgument(2, $deserializationHandlers)
        ;
        $container
            ->getDefinition('jms_serializer.json_deserialization_visitor')
            ->replaceArgument(2, $deserializationHandlers)
        ;
        $container
            ->getDefinition('jms_serializer.xml_deserialization_visitor')
            ->replaceArgument(2, $deserializationHandlers)
        ;
    }
}