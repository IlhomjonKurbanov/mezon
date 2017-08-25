<?php

	require_once( dirname( __FILE__ ).'/../functional/functional.php' );

    /**
    *   Class for basic http authentication.
    */
	class			BasicAuth
	{
		/**
		*	Name of the realm (site or appication name).
		*/
		var 					$Realm = '';

		/**
		*	Entity wich provides access to the set if users. The
		*	easyest way is to pass an array with user logins and passwords.
		*
		*	@example [ 'login' => 'admin' , 'password' => '1234567' ]
		*
		*	@example [ [ 'login' => 'admin' , 'password' => '1234567' ] , 
		*			   [ 'login' => 'manager' , 'password' => '7654321' ] ]
		*/
		var 					$UserSet = false;

		/**
		*	Constructor.
		*
		*	@param $Realm - authorization realm.
		*
		*	@param $UserSet - entity wich provides access to the set if users.
		*/
		public function			__construct( $Realm , $UserSet = [] )
		{
			$this->Realm = $Realm;

			$this->UserSet = $UserSet;
		}

		/**
		*	Method displays login form with modified realm.
		*	It can be used to display error message.
		*
		*	@param $RealmSuffix - realm suffix to be added at the end of the $this->Realm
		*/
		private function		login_form( $RealmSuffix = '' )
		{
			header( 'WWW-Authenticate: Basic realm="'.$this->Realm.$RealmSuffix.'"' );
			header( 'HTTP/1.0 401 Unauthorized' );
			exit;
		}

		/**
		*	Method returns full object of the logged in user.
		*/
		public function			get_logged_in_user()
		{
			if( is_array( $this->UserSet ) && isset( $this->UserSet[ 'login' ] ) )
			{
				return( $this->UserSet );
			}
			foreach( $this->UserSet as $i => $User )
			{
				if( Functional::get_field( $User , 'login' ) == $_SERVER[ 'PHP_AUTH_USER' ] )
				{
					return( $User );
				}
			}

			return( false );
		}

		/**
		*	Login validation method.
		*/
		protected function		validate_login()
		{
			if( is_array( $this->UserSet ) && isset( $this->UserSet[ 'login' ] ) )
			{
				return( $this->UserSet[ 'login' ] == $_SERVER[ 'PHP_AUTH_USER' ] );
			}
			elseif( is_array( $this->UserSet ) )
			{
				foreach( $this->UserSet as $i => $User )
				{
					if( Functional::get_field( $User , 'login' ) == $_SERVER[ 'PHP_AUTH_USER' ] )
					{
						return( true );
					}
				}
			}

			return( false );
		}

		/**
		*	Password validation method.
		*/
		protected function		validate_password()
		{
			
			if( is_array( $this->UserSet ) && isset( $this->UserSet[ 'password' ] ) )
			{
				return( $this->UserSet[ 'login' ] == $_SERVER[ 'PHP_AUTH_USER' ] && 
                        ( $this->UserSet[ 'password' ] == md5( $_SERVER[ 'PHP_AUTH_PW' ] ) || 
						  $this->UserSet[ 'password' ] == $_SERVER[ 'PHP_AUTH_PW' ] ) );
			}
			elseif( is_array( $this->UserSet ) )
			{
				foreach( $this->UserSet as $i => $User )
				{
					if( Functional::get_field( $User , 'login' ) == $_SERVER[ 'PHP_AUTH_USER' ] && 
                        ( Functional::get_field( $User , 'password' ) == md5( $_SERVER[ 'PHP_AUTH_PW' ] ) || 
						  Functional::get_field( $User , 'password' ) == $_SERVER[ 'PHP_AUTH_PW' ] ) )
					{
						return( true );
					}
				}
			}

			return( false );
		}

		/**
		*	Login method.
		*/
		public function			login()
		{
			if( !isset( $_SERVER[ 'PHP_AUTH_USER' ] ) )
			{
				$this->login_form();
			}

			if( $this->validate_login() === false )
			{
				$this->login_form();
			}

			if( $this->validate_password() === false )
			{
				$this->login_form();
			}
		}

		/**
		*	Logout method.
		*/
		public function			logout( $RealmSuffix = '' )
		{
			header( 'HTTP/1.0 401 Unauthorized' );
			header( 'WWW-Authenticate: Basic realm="'.$this->Realm.$RealmSuffix.'"' );
			exit;
		}

		/**
		*	Getting logged in user login.
		*/
		public static function	get_login()
		{
			return( $_SERVER[ 'PHP_AUTH_USER' ] );
		}

		/**
		*	Setting user login.
		*/
		public static function	set_login( $Login )
		{
			$_SERVER[ 'PHP_AUTH_USER' ] = $Login;
		}

		/**
		*	Setting user password.
		*/
		public static function	set_password( $Password )
		{
			$_SERVER[ 'PHP_AUTH_PW' ] = $Password;
		}
	}

?>