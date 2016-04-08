<?php
// No direct access
defined('_JEXEC') or die;

class WarrantyViewWarranty extends JViewLegacy
{
	public function display($tpl = null)
	{
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        parent::display($tpl);
	}
}

