<?php
namespace DevpeakIT\PWDSafe\Traits;

use DevpeakIT\PWDSafe\Container;

trait ContainerInject {
        protected $container;

        public function __construct(Container $container)
        {
                $this->container = $container;
        }
}