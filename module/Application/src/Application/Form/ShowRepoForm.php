<?php
namespace Application\Form;

 use Zend\Form\Form;

 class ShowRepoForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('showRepo');

         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         $this->add(array(
             'name' => 'gitowner',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Hey gangsta! Set git owner name:',
             ),
         ));
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Show repositories',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }
