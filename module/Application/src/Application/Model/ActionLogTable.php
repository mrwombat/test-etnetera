<?php 
namespace Application\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Select;
 use Zend\Db\ResultSet\ResultSet;
 use Zend\Paginator\Paginator;
 use Zend\Paginator\Adapter\DbTableGateway;
 use Zend\Paginator\Adapter\Iterator as paginatorIterator;

 class ActionLogTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
        $this->tableGateway = $tableGateway;
     }

	 public function getLog(){

		 $result = $this->tableGateway->select(function (Select $select){
			$select->order('timestamp DESC');
		 });

		$result = new ResultSet();
		$result->setArrayObjectPrototype(new ActionLog());

		$select = new Select();
		$select->from('actionLog');
		$select->order('timestamp DESC');

		$paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect($select,$this->tableGateway->getAdapter(),$result);
		$paginator = new Paginator($paginatorAdapter);

		return $paginator;
	 }

	 public function deleteOlderThan($number,$type = 'hour'){

		 switch ($type) {
		 	case 'hour':
				$timeDelta = time() - ($number * 60 * 60);
		 		break;
		 }
	 
        $this->tableGateway->delete(array('timestamp < ?' => $timeDelta));
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
