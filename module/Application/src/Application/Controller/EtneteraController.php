<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Client;
use Zend\Http\ClientStatic;
use Application\Form\ShowRepoForm;
use Application\Form\CleanerForm;
use Application\Model\ActionLog;

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
		
        return new ViewModel(
			array(
				'log' => $this->getActionLogTable()->fetchAll(),
		));
    }

    public function cleanerAction()
    {
		$form = new CleanerForm();
		$form->setAttribute('method','POST');

		$repo = array();

        $request = $this->getRequest();

         if ($request->isPost()) {

						#get git data
            $pwd = $request->getPost()['pwd'];
            $hour = $request->getPost()['hour'];

         }

        return new ViewModel(
			array(
				#'actionLog' => $this->getActionLogTable()->fetchAll(),
				'form' => $form,
		));

        return new ViewModel(
			array(
				'actionLog' => $this->getActionLogTable()->fetchAll(),
		));
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
