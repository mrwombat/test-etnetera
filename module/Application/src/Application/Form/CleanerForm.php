<?php
namespace Application\Form;

 use Zend\Form\Form;

 class CleanerForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('cleaner');

         $this->add(array(
             'name' => 'pwd',
             'type' => 'Password',
             'options' => array(
                 'label' => 'Password:',
             ),
         ));

         $this->add(array(
             'name' => 'hour',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Hour number:',
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
