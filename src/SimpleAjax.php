<?php

define ('TL_MODE', 'FE');
define ('BYPASS_TOKEN_CHECK', true);

require ('system/initialize.php');


/**
 * Class SimpleAjax
 */
class SimpleAjax extends Controller
{
	/**
	 * Call the parent constructor.
	 *
	 * !!! DON'T REMOVE THIS !!!
	 *
	 * If you remove this you get the following error message:
	 * Fatal error: Call to protected System::__construct() from invalid
	 * context
	 *
	 * @param	void
	 * @return	void
	 */
	public function __construct ()
	{
		parent::__construct ();
	}


	/**
	 * Get the ajax request and call all hooks
	 *
	 * @param	void
	 * @return	void
	 */
	public function run ()
	{
		// check if a hook is registered
		if (is_array ($GLOBALS['TL_HOOKS']['simpleAjax']) && count ($GLOBALS['TL_HOOKS']['simpleAjax']) > 0)
		{
			// execute every registered callback
			foreach ($GLOBALS['TL_HOOKS']['simpleAjax'] as $callback)
			{
				$this->import ($callback[0]);
				$this->{$callback[0]}->{$callback[1]} ();
			}
		}

		// if there is no other output, we generate a 412 error response
		header ('HTTP/1.1 412 Precondition Failed');
		die ('Simple Ajax: Invalid AJAX call.');
	}
}


// create a SimpleAjax instance and run it
$objSimpleAjax = new SimpleAjax ();
$objSimpleAjax->run ();
