<?php    
namespace CS450\Controller;

// require __DIR__.'/vendor/autoload.php';
    
use CS450\Lib\Response;

class GrantController{
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
        $sql = "SELECT name,type FROM tbl_fact_granting_entity";

        $conn = $this->db->getConnection();
        $result = $conn->query($sql);

        if($conn->error) {
            $this->logger->error($conn->error);
            throw new \Exception($conn->error);
        }

        $grants = $result->fetch_all(MYSQLI_ASSOC);

        return $grants;
    }

}
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