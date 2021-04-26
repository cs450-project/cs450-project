<?php

namespace CS450\Controller;

/**
 * @codeCoverageIgnore
 */
class GrantController
{
    /**
     * @Inject
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @Inject
     * @var CS450\Service\DbService
     */
    private $db;

    /**
     * @Inject
     * @var CS450\Model\GrantFactory
     */
    private $grantFactory;

    public function __invoke($params)
    {
        $userID = $params["params"]["id"];
        if(isset($userID)){
            return $this->grantFactory->findbyUser($userID);
        }
        return $this->grantFactory->findAll();
    }

    public function getFacultyGrants($params){
        return $this->grantFactory->findbyUser($params);
    }
}
