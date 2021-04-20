<?php

namespace CS450\Controller;

use CS450\Model\UserFactory;
use CS450\Model\User\LoginUserInfo;
use CS450\Model\User\RegisterUserInfo;
use CS450\Lib\Exception;
use CS450\Lib\EmailAddress;

/**
 * @codeCoverageIgnore
 */
final class AuthController
{
    /**
     * @Inject("env")
     */
    private $env;

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

    /**
     * @Inject
     * @var CS450\Service\JwtService
     */
    private $jwt;

    /**
     * @Inject
     * @var CS450\Service\EmailService
     */
    private $email;

    /**
     * @Inject
     * @var CS450\Service\DbService
     */
    private $db;

    private function makeJwt($uid, $role): string {
        $payload = array(
            'uid' => $uid,
            'role' => $role,
        );

        return $this->jwt->encode($payload);
    }

    public function login($params)
    {
        $loginData = $params["post"];
        $this->logger->info($loginData["email"] . " is trying to login.");

        try {
            $loginInfo = LoginUserInfo::create(
                $loginData["email"],
                $loginData["password"],
            );

            $user = $this->userFactory->findByEmail($loginInfo->email);

            if (!$user) {
                throw new \Exception("User not found", 420);
            }
            else if (!$loginInfo->password->verifyhash($user->getPasswordHash())) {
                throw new \Exception("Incorrect password", 69);
            }

            return array(
                'user' => array(
                    "uid" => $user->getUid(),
                    "name" => $user->getName(),
                    "email" => strval($user->getEmail()),
                    "role" => $user->getRole(),
                    "department" => $user->getDepartment(),
                ),
                'token' => $this->makeJwt($user->getUid(), $user->getRole()),
            );

        } catch (\Exception $e) {
            $this->logger->error("caught error throwing new one");
            throw new Exception($e);
        }
    }

    public function register($params)
    {
        $registerData = $params["post"];
        $this->logger->info("Registering user with " . print_r($registerData, true));
     
        try {
            $userInfo = RegisterUserInfo::create(
                $registerData["name"],
                $registerData["email"],
                $registerData["password"],
                $registerData["department"],
            );

            $userDataToken = $registerData["userDataToken"];
            $tokenData = $this->jwt->decode($userDataToken);

            if ($tokenData["email"] !== $registerData["email"]) {
                throw new \Exception("Registration email does not match invitation. Please see your administrator");
            }

            $payload = array(
                'token' => $this->user->register($userInfo),
            );

            // Add a startup fund using uid that needs to be returned from register.
        } catch (\Exception $e) {
            throw new Exception($e);
        }
        
        return $payload;
    }

    private static function hostname(){
        return sprintf(
          "%s://%s",
          isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
          $_SERVER['SERVER_NAME'],
        );
      }

    public function sendInvite($params) {
        if (empty($params["token"]) || $params["token"]["role"] !== "ADMINISTRATOR") {
            throw new \Exception("You are not authorized to invite new faculty. Please talk to your administrator");
        }

        $conn = $this->db->getConnection();
        
        // safe to query using uid token since this is encrypted with a private key.
        // not purely user supplied data. For this to be dangerous our pvt key would
        // have to be compromised. Possible, and probably not worth the risk in the
        // real world but i'm leaving it.
        $senderUid = $params["token"]["uid"];
        $adminEmail = $conn
            ->query("SELECT email FROM tbl_fact_users WHERE ID = $senderUid")
            ->fetch_object()
            ->email;

        $to = $params["post"]["email"];
        $facultyDepartmentId = $params["post"]["department"];
        $startupFundAmount = $params["post"]["startupAmount"];

        // this should be refactored into a Department model
        // with some factory GetFromId() or something like that.
        $departmentNameSql = "SELECT name FROM tbl_fact_departments WHERE ID = ?";
        $stmt = $conn->prepare($departmentNameSql);

        if (!$stmt) {
            $errMsg = sprintf("An error occurred preparing your query: %s, %s", $departmentNameSql, $conn->error);
            throw new \Exception($errMsg);
        }

        $executed = $stmt->bind_param(
            "d",
            $facultyDepartmentId,
        ) && $stmt->execute();

        if (!$executed) {
            throw new \Exception($conn->error);
        }

        $stmt->bind_result($departmentName);
        $stmt->fetch();

        $userDataToken = $this->jwt->encode(array(
            "email" => $to,
            "startupAmount" => $startupFundAmount,
        ));

        $this->email->sendFromTemplate(
            EmailAddress::fromString($to),
            "Welcome! Register with grant management",
            "registration_invitation",
            array(
                "department"  => $departmentName,
                "admin_email" => $adminEmail,
                "startup_amt" => $startupFundAmount,
                "registration_url" => sprintf("%s/#/register/%s",
                    self::hostname(),
                    urlencode(base64_encode(json_encode(array(
                        "email" => $to,
                        "name" => $params["post"]["name"],
                        "department" => $facultyDepartmentId,
                        "userDataToken" => $userDataToken,
                    )))),
                ),
            ),
        );
    }
}
