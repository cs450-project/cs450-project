<?php

namespace CS450\Controller;

class AdminController
{
    /**
     * @codeCoverageIgnore
     */

     /**
     * @Inject
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
     
    /**
     * @Inject
     * @var CS450\Model\UserFactory
     */
    private $userFactory;

    public function getFaculty($params) {
        if (empty($params["token"]) || $params["token"]["role"] !== "ADMINISTRATOR") {
            throw new \Exception("You are not authorized to list faculty. Please talk to your administrator");
        }

        return (object) $this->userFactory->getFacultyInDepartmentForAdminId($params["token"]["uid"]);
    }
}
