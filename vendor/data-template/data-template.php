<?php

	require_once( dirname( __FILE__ ).'/../simple-output-template/simple-output-template.php' );

	/**
	*	Template engine class.
	*/
	class			DataTemplate extends SimpleOutputTemplate
	{
		/**
        *   Template �onstructor.
        */
        function			__construct()
        {
			parent::__construct( '{data}' );
        }
	}

?>