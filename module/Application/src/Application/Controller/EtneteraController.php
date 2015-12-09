<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\ShowRepoForm;

class EtneteraController extends AbstractActionController
{
	protected $actionLogTable;

    public function indexAction()
    {
		#https://api.github.com/users/mrwombat/repos
		$form = new ShowRepoForm();

        $request = $this->getRequest();


        return new ViewModel(
			array(
				'actionLog' => $this->getActionLogTable()->fetchAll(),
				'form' => $form,
			)
		);
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
