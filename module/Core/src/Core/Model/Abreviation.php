<?php
namespace Core\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class Abreviation
{
	public $id;
	public $iteme;
	public $traduction;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->iteme = (!empty($data['iteme'])) ? $data['iteme'] : null;
		$this->traduction  = (!empty($data['traduction'])) ? $data['traduction'] : null;
	}
	
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	
	
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
	
			$inputFilter->add(array(
					'name'     => 'id',
					'required' => true,
					'filters'  => array(
							array('name' => 'Int'),
					),
			));
	
			$inputFilter->add(array(
					'name'     => 'iteme',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'StringLength',
									'options' => array(
											'encoding' => 'UTF-8',
											'min'      => 2,
											'max'      => 50,
									),
							),
					),
			));
	
			$inputFilter->add(array(
					'name'     => 'traduction',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'StringLength',
									'options' => array(
											'encoding' => 'UTF-8',
											'min'      => 1,
											'max'      => 256,
									),
							),
					),
			));
	
			$this->inputFilter = $inputFilter;
		}
	
		return $this->inputFilter;
	}
}