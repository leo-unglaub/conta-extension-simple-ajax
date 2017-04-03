<?php

define ('TL_MODE', 'FE');
define ('BYPASS_TOKEN_CHECK', true);

require ('system/initialize.php');


/**
 * Class SimpleAjaxFrontend
 */
class SimpleAjaxFrontend extends Frontend
{
	/**
	 * Set some important constants
	 *
	 * @param	void
	 * @return	void
	 */
	public function __construct ()
	{
		$objFrontendUser = FrontendUser::getInstance ();

		// call the constructor from Frontend
		parent::__construct ();

		// check if a user is logged in
		define ('BE_USER_LOGGED_IN', $this->getLoginStatus ('BE_USER_AUTH'));
		define ('FE_USER_LOGGED_IN', $this->getLoginStatus ('FE_USER_AUTH'));

		// set static url's in case the user generated HTML code
		\Controller::setStaticUrls ();
	}


	/**
	 * Get the ajax request and call all hooks
	 *
	 * @param	void
	 * @return	void
	 */
	public function run ()
	{
		// run the global hook
		if (is_array ($GLOBALS['TL_HOOKS']['simpleAjax']) === true)
		{
			// execute every registered callback
			foreach ($GLOBALS['TL_HOOKS']['simpleAjax'] as $callback)
			{
				$this->import ($callback[0]);
				$this->{$callback[0]}->{$callback[1]} ();
			}
		}

		// run the frontend exclusive hook
		if (is_array ($GLOBALS['TL_HOOKS']['simpleAjaxFrontend']) === true)
		{
			// execute every registered callback
			foreach ($GLOBALS['TL_HOOKS']['simpleAjaxFrontend'] as $callback)
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
$objSimpleAjaxFrontend = new SimpleAjaxFrontend ();
$objSimpleAjaxFrontend->run ();
