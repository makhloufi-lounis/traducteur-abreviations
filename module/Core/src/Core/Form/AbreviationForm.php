<?php
namespace Core\Form;

use Zend\Form\Form;

class AbreviationForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('abreviation');

		$this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
		));
		$this->add(array(
				'name' => 'iteme',
				'type' => 'Text',
				'options' => array(
						'label' => 'Iteme',
				),
		));
		$this->add(array(
				'name' => 'traduction',
				'type' => 'Text',
				'options' => array(
						'label' => 'Traduction',
				),
		));
		$this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
						'value' => 'Ajouter',
						'id' => 'addbutton',
				),
		));
	}
}