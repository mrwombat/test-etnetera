<?php 
namespace Application\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Select;

 class ActionLogTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
		 $resultSet = $this->tableGateway->select(function (Select $select){
			$select->order('timestamp DESC');
		 });
         return $resultSet;
     }

     public function save($data)
     {
         $dataPrepared = array(
             'gitowner'  => (!empty($data['gitowner'])) ? $data['gitowner'] : null,
             'ip'  => $_SERVER['REMOTE_ADDR'],
             'timestamp'  => time(),
         );

         $id = (int) $data->id;
         if ($id == 0) {
             $this->tableGateway->insert($dataPrepared);
         } else {
             if ($this->getAlbum($id)) {
                 $this->tableGateway->update($dataPrepared, array('id' => $id));
             } else {
                 throw new \Exception('ActionLog id does not exist');
             }
         }
     }
 }
