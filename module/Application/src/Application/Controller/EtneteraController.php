<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Client;
use Zend\Http\ClientStatic;
use Application\Form\ShowRepoForm;
use Application\Form\CleanerForm;
use Application\Model\ActionLog;
use Zend\Crypt\BlockCipher;
use Zend\Crypt\Password\Bcrypt;

class EtneteraController extends AbstractActionController
{
	protected $actionLogTable;

    public function indexAction()
    {
		#https://api.github.com/users/mrwombat/repos
		$form = new ShowRepoForm();
		$form->setAttribute('method','POST');

		$repo = array();

        $request = $this->getRequest();

         if ($request->isPost()) {

			#some validation can be here ...
			
			#save request

            $log = $this->getActionLogTable()->save($request->getPost());


			#get git data
            $gitowner = $request->getPost()['gitowner'];
			$url = "https://api.github.com/users/$gitowner/repos";
						
			 $client = new Client($url, array(
				 'maxredirects' => 5,
					'timeout'      => 30,
					'accept' => 'application/json',
					 'adapter' => 'Zend\Http\Client\Adapter\Curl',
					'curloptions' => array(CURLOPT_FOLLOWLOCATION => true),
				));

				$client->setMethod('GET');
				$response = $client->send();
				$repo = \Zend\Json\Json::decode($response->getBody());

         }

        return new ViewModel(
			array(
				#'actionLog' => $this->getActionLogTable()->fetchAll(),
				'form' => $form,
				'repo' => array_reverse($repo),
		));
    }

    public function logAction()
    {

		$page = $this->params()->fromRoute('page');
		
		$log =  $this->getActionLogTable()->getLog();

		$log->setItemCountPerPage(2);
		$log->setCurrentPageNumber($page);

        return new ViewModel(
			array(
				'log' => $this->getActionLogTable()->getLog(),
		));
    }

    public function cleanerAction()
    {
		$form = new CleanerForm();
		$form->setAttribute('method','POST');

		$repo = array();

        $request = $this->getRequest();

         if ($request->isPost()) {

			$data = $request->getPost();

			#test cipher
			$blockCipher = BlockCipher::factory('mcrypt', array('algo' => 'aes','hash' => 'sha512'));
			$blockCipher->setKey('DA$#3434fsa432dfef32327');
			$hash = 'f19f8bf56c4f61b6b2ca51e4cd5973faa5a165e4db6ad7aae0f065463ba2330fx2kZPSH5xCnLy48nVPWnprIh601be0H2Quh2o88oCws=';
			#\Zend\Debug\Debug::dump($blockCipher->decrypt($hash));

			#test bcrypt
			$bcrypt = new Bcrypt();
			$hash = $bcrypt->create('xxx');
			$hash = '$2y$10$HQORKaG/QUWk.wJGj9lPuOHLTrm11pRdSSBDP.L2JVrAkCid7W5O.';

			#get git data
			$pwd = $request->getPost()['pwd'];
			$hour = $request->getPost()['hour'];

			 if($bcrypt->verify($pwd,$hash) && is_numeric($hour)){

				$this->getActionLogTable()->deleteOlderThan($hour);

				$result['message'] = 'OK';
			 
			 }else{
				$result['message'] = 'Error. Passwd or Hour are not valid.';
			 }

         }

		$result['form'] = $form;

        return new ViewModel($result);
    }




	public function getActionLogTable()
	{
	 if (!$this->actionLogTable) {
		 $sm = $this->getServiceLocator();
		 $this->actionLogTable = $sm->get('Application\Model\ActionLogTable');
	 }
	 return $this->actionLogTable;
	}
}
