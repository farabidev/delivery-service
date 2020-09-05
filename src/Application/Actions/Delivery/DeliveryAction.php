<?php
declare(strict_types=1);

namespace App\Application\Actions\Delivery;

use App\Application\Actions\Action;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

abstract class DeliveryAction extends Action
{
    /**
     * @param ContainerInterface $container
     */
    protected $container;
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, ContainerInterface $container)
    {
        parent::__construct($logger);
        $this->container = $container;
    }
}
