<?php    


require __DIR__.'/vendor/autoload.php';
    
use CS450\Lib\Response;
$grants=array(
        array(
           "name"=>"Grant 1",
           "type"=> "NSF"
        ),
        array(
           "name"=>"Grant 2",
           "type"=> "ODU"
        ),
        array(
           "name"=>"Grant 3",
           "type"=> "VT"
        )
    );

echo Response::OK()->toJSON($grants);
?>

<!--- HERE ARE SOME PHP CODE TO SHOW YOU WHAT I HOPED TO USE BUT THE ABOVE CODE IS TO MAKE SURE EVERYTHING IS WORKING.

namespace CS450\Controller;

class DepartmentController {
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

    public function __invoke() {
        $sql = "SELECT id, name FROM tbl_fact_grant_entity;";

        $conn = $this->db->getConnection();
        $result = $conn->query($sql);

        $this->logger->info(sprintf("Fetched %d rows", $result->num_rows));

        if($conn->error) {
            $this->logger->error($conn->error);
            throw new \Exception($conn->error);
        }

        $grants = $result->fetch_all(MYSQLI_ASSOC);

        return $grants;
    }
}

->