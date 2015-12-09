<?php 
namespace Application\Model;

 use Zend\Db\TableGateway\TableGateway;

 class ActionLogTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function save(ActionLog $data)
     {
         $dataPrepared = array(
             'artist' => $data->timestamp,
             'title'  => $data->gitowner,
             'ip'  => $data->ip,
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
