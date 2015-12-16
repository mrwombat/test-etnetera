<?php
namespace Application\Form;

 use Zend\Form\Form;
 use Zend\I18n\Validator\IsInt;

 class CleanerForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('cleaner');

         $this->add(array(
             'name' => 'pwd',
             'required' => true,
             'type' => 'Password',
             'options' => array(
                 'label' => 'Password:',
             ),
         ));

         $this->add(array(
             'name' => 'hour',
             'required' => true,
             'type' => 'Text',
             'options' => array(
                 'label' => 'Hour number:',
             ),
			'validators' => array(
				new \Zend\I18n\Validator\IsInt()
			 ),
         ));
 
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Clean',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }
