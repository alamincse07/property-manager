<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth
*
* Author: Ben Edmunds
*         ben.edmunds@gmail.com
*         @benedmunds
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  10.01.2009
*
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
* Requirements: PHP5 or above
*
*/

class Ion_auth
{
	/**
	 * account status ('not_activated', etc ...)
	 *
	 * @var string
	 **/
	protected $status;

	/**
	 * extra where
	 *
	 * @var array
	 **/
	public $_extra_where = array();

	/**
	 * extra set
	 *
	 * @var array
	 **/
	public $_extra_set = array();
        
        public $_mail_set;

	/**
	 * caching of users and their groups
	 *
	 * @var array
	 **/
	public $_cache_user_in_group;

	/**
	 * __construct
	 *
	 * @return void
	 * @author Ben
	 **/
	public function __construct()
	{
		$this->load->config('ion_auth', TRUE);
		$this->load->library('email');
		$this->lang->load('ion_auth');
		$this->load->helper('cookie');

		//Load the session, CI2 as a library, CI3 uses it as a driver
		if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->load->library('session');
		}
		else
		{
			$this->load->driver('session');
		}

		// Load IonAuth MongoDB model if it's set to use MongoDB,
		// We assign the model object to "ion_auth_model" variable.
		$this->config->item('use_mongodb', 'ion_auth') ?
			$this->load->model('ion_auth_mongodb_model', 'ion_auth_model') :
			$this->load->model('ion_auth_model');

		$this->_cache_user_in_group =& $this->ion_auth_model->_cache_user_in_group;

		//auto-login the user if they are remembered
		if (!$this->logged_in() && get_cookie('identity') && get_cookie('remember_code'))
		{
			$this->ion_auth_model->login_remembered_user();
		}
                
                

		$_mail_set = $this->ion_auth_model->mailsetting();
                $email_config = array(
                    'protocol'  	=> $_mail_set['mail_type'],
                    'smtp_host' 	=> $_mail_set['smtp_host'],
                    'smtp_user' 	=> $_mail_set['smtp_username'],
                    'smtp_pass' 	=> $_mail_set['smtp_password'],
                    'smtp_crypto' 	=> $_mail_set['smtp_ssl'],
                    'smtp_port' 	=> $_mail_set['smtp_port'],
                    'charset'   	=> $_mail_set['mail_charset'],
                    'mailtype'  	=> $_mail_set['mail_encoding'],
                    '_smtp_auth'        => ($_mail_set['smtp_auth'] == 1) ? TRUE : FALSE,
                );
                
                //$email_config = $this->config->item('email_config','ion_auth');

		if ($this->config->item('use_ci_email', 'ion_auth'))
		{
			$this->email->initialize($email_config);
		}

		$this->ion_auth_model->trigger_events('library_constructor');
		
		// Site setting
		$SETTING = $this->ion_auth_model->get_setting();
		define("LOGO", $SETTING->logo);
		define("INVOICE_LOGO", $SETTING->invoice_logo);
		define("SITE_NAME", $SETTING->site_name);
		define("DEFAULT_TAX", $SETTING->default_tax_rate);
		define("CURRENCY_PREFIX", $SETTING->currency_prefix);
		define("CMAJOR", $SETTING->major);
		define("CMINOR", $SETTING->minor);
		define("NO_OF_ROWS", $SETTING->no_of_rows);
		define("TOTAL_ROWS", $SETTING->total_rows);
		define("ROWS_PER_PAGE", $SETTING->rows_per_page);
		define("PRINT_PAYMENT", $SETTING->print_payment);
		define("RESTRICT_SALES", $SETTING->restrict_sales);
		define("VERSION", '2.1.1');
		
		define("CAL_OPT", $SETTING->calendar);
		
		
		if ($this->logged_in()) {
			$user = $this->user()->row();
			define("LI_USER", $user->first_name);
			define("USER_ID", $user->id);

			if($df = $this->ion_auth_model->getDateFormat($SETTING->dateformat)) {
				define("JS_DATE", $df->js);
				define("PHP_DATE", $df->php);
				define("MYSQL_DATE", $df->sql);
			} else {
				define("JS_DATE", 'mm-dd-yyyy');
				define("PHP_DATE", 'm-d-Y');
				define("MYSQL_DATE", '%m-%d-%Y');
			}
			if ($cal = $this->ion_auth_model->getEvents()) {
				$cal_d = '';
				foreach($cal as $dt) {
					$cal_d .= '<li><strong>'.date(PHP_DATE, strtotime($dt->date)).':</strong> '.$dt->data.'</span><div class="clearfix"></div><hr /></li>';
				}
		
				define("UP_EVENTS", $cal_d);
			} else {
				define("UP_EVENTS", NULL);
			}
		
		}
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 **/
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->ion_auth_model, $method) )
		{
			throw new Exception('Undefined method Ion_auth::' . $method . '() called');
		}

		return call_user_func_array( array($this->ion_auth_model, $method), $arguments);
	}

	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 *
	 * I can't remember where I first saw this, so thank you if you are the original author. -Militis
	 *
	 * @access	public
	 * @param	$var
	 * @return	mixed
	 */
	public function __get($var)
	{
		return get_instance()->$var;
	}


	/**
	 * forgotten password feature
	 *
	 * @return mixed  boolian / array
	 * @author Mathew
	 **/
	public function forgotten_password($identity)    //changed $email to $identity
	{
		if ( $this->ion_auth_model->forgotten_password($identity) )   //changed
		{
			// Get user information
			$user = $this->where($this->config->item('identity', 'ion_auth'), $identity)->users()->row();  //changed to get_user_by_identity from email

			if ($user)
			{
				$data = array(
					'identity'		=> $user->{$this->config->item('identity', 'ion_auth')},
					'forgotten_password_code' => $user->forgotten_password_code
				);

				if(!$this->config->item('use_ci_email', 'ion_auth'))
				{
					$this->set_message('forgot_password_successful');
					return $data;
				}
				else
				{
                                        $maildata = $this->ion_auth_model->mailsetting();
					$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password', 'ion_auth'), $data, true);
					$this->email->clear();
					$this->email->from($maildata['from_email'], $maildata['from_name']);
					$this->email->to($user->email);
					$this->email->subject($maildata['site_title'] . ' - ' . $this->lang->line('email_forgotten_password_subject'));
					$this->email->message($message);
					if ($this->email->send())
					{
						$this->set_message('forgot_password_successful');
						return TRUE;
					}
					else
					{
						$this->set_error('forgot_password_unsuccessful');
						return FALSE;
					}
				}
			}
			else
			{
				$this->set_error('forgot_password_unsuccessful');
				return FALSE;
			}
		}
		else
		{
			$this->set_error('forgot_password_unsuccessful');
			return FALSE;
		}
	}

	/**
	 * forgotten_password_complete
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password_complete($code)
	{
		$this->ion_auth_model->trigger_events('pre_password_change');

		$identity = $this->config->item('identity', 'ion_auth');
		$profile  = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if (!$profile)
		{
			$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$new_password = $this->ion_auth_model->forgotten_password_complete($code, $profile->salt);

		if ($new_password)
		{
			$data = array(
				'identity'     => $profile->{$identity},
				'new_password' => $new_password
			);
			if(!$this->config->item('use_ci_email', 'ion_auth'))
			{
				$this->set_message('password_change_successful');
				$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
					return $data;
			}
			else
			{
                                $maildata = $this->ion_auth_model->mailsetting();
				$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password_complete', 'ion_auth'), $data, true);
                                
				$this->email->clear();
				$this->email->from($maildata['from_email'], $maildata['from_name']);
				$this->email->to($profile->email);
				$this->email->subject($maildata['site_title'] . ' - ' . $this->lang->line('email_new_password_subject'));
				$this->email->message($message);

				if ($this->email->send())
				{
					$this->set_message('password_change_successful');
					$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
					return TRUE;
				}
				else
				{
					$this->set_error('password_change_unsuccessful');
					$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
					return FALSE;
				}

			}
		}

		$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
		return FALSE;
	}

	/**
	 * forgotten_password_check
	 *
	 * @return void
	 * @author Michael
	 **/
	public function forgotten_password_check($code)
	{
		$profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if (!is_object($profile))
		{
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}
		else
		{
			if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0) {
				//Make sure it isn't expired
				$expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
				if (time() - $profile->forgotten_password_time > $expiration) {
					//it has expired
					$this->clear_forgotten_password_code($code);
					$this->set_error('password_change_unsuccessful');
					return FALSE;
				}
			}
			return $profile;
		}
	}

	/**
	 * register
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function register($username, $password, $email, $additional_data = array(), $group_ids = array()) //need to test email activation
	{
		$this->ion_auth_model->trigger_events('pre_account_creation');

        // to enable ther email activation set the options table
		$email_activation = $this->ion_auth_model->getSet('email_activation')->value;

		if (!$email_activation)
		{
			$id = $this->ion_auth_model->register($username, $password, $email, $additional_data, $group_ids);
			if ($id !== FALSE)
			{
				$this->set_message('account_creation_successful');
				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful'));
				return $id;
			}
			else
			{
				$this->set_error('account_creation_unsuccessful');
				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
				return FALSE;
			}
		}
		else
		{
			$id = $this->ion_auth_model->register($username, $password, $email, $additional_data, $group_ids);

			if (!$id)
			{
				$this->set_error('account_creation_unsuccessful');
				return FALSE;
			}

			$deactivate = $this->ion_auth_model->deactivate($id);

			if (!$deactivate)
			{
				$this->set_error('deactivate_unsuccessful');
				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
				return FALSE;
			}

			$activation_code = $this->ion_auth_model->activation_code;
			$identity        = $this->config->item('identity', 'ion_auth');
			$user            = $this->ion_auth_model->user($id)->row();

			$data = array(
				'identity'   => $user->{$identity},
				'id'         => $user->id,
				'email'      => $email,
				'activation' => $activation_code,
			);
			if(!$this->config->item('use_ci_email', 'ion_auth'))
			{
				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
				$this->set_message('activation_email_successful');
					return $data;
			}
			else
			{
                                $maildata = $this->ion_auth_model->mailsetting();
				$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_activate', 'ion_auth'), $data, true);
				$this->email->clear();
				$this->email->from($maildata['from_email'], $maildata['from_name']);
				$this->email->to($email);
				$this->email->subject($maildata['site_title'] . ' - ' . $this->lang->line('email_activation_subject'));
				$this->email->message($message);

				if ($this->email->send() == TRUE)
				{
					$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
					$this->set_message('activation_email_successful');
					return $id;
				}
			}

			$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful', 'activation_email_unsuccessful'));
			$this->set_error('activation_email_unsuccessful');
			return FALSE;
		}
	}

	/**
	 * logout
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function logout()
	{
		$this->ion_auth_model->trigger_events('logout');

		$identity = $this->config->item('identity', 'ion_auth');
		$this->session->unset_userdata($identity);
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('user_id');

		//delete the remember me cookies if they exist
		if (get_cookie('identity'))
		{
			delete_cookie('identity');
		}
		if (get_cookie('remember_code'))
		{
			delete_cookie('remember_code');
		}

		//Destroy the session
		$this->session->sess_destroy();

		//Recreate the session
		if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->session->sess_create();
		}

		$this->set_message('logout_successful');
		return TRUE;
	}

	/**
	 * logged_in
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function logged_in()
	{
		$this->ion_auth_model->trigger_events('logged_in');

		$identity = $this->config->item('identity', 'ion_auth');

		return (bool) $this->session->userdata($identity);
	}

	/**
	 * logged_in
	 *
	 * @return integer
	 * @author jrmadsen67
	 **/
	public function get_user_id()
	{
		$user_id = $this->session->userdata('user_id');
		if (!empty($user_id))
		{
			return $user_id;
		}
		return null;
	}

    public function get_user_name()
    {
        $user_id = $this->session->userdata('name');
        if (!empty($user_id))
        {
            return $user_id;
        }
        return null;
    }
    public function get_user_email()
    {
        $user_id = $this->session->userdata('email');
        if (!empty($user_id))
        {
            return $user_id;
        }
        return null;
    }


	/**
	 * is_admin
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function is_admin($level = 2,$id=false)
	{
		$this->ion_auth_model->trigger_events('is_admin');

		return $this->in_group(strval($level), $id);
	}

	/**
	 * in_group
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 **/
	public function in_group($check_group, $id=false)
	{
		$this->ion_auth_model->trigger_events('in_group');

		$id || $id = $this->session->userdata('user_id');

		if (!is_array($check_group))
		{
			$check_group = array($check_group);
		}

		if (isset($this->_cache_user_in_group[$id]))
		{
			$groups_array = $this->_cache_user_in_group[$id];
		}
		else
		{
			$users_groups = $this->ion_auth_model->get_users_groups($id)->result();
			$groups_array = array();
			foreach ($users_groups as $group)
			{
				$groups_array[$group->id] = $group->level;
			}
			$this->_cache_user_in_group[$id] = $groups_array;
		}
        /*\application\helpers\Generic::_setTrace('$check_group',0);
        print_r($check_group);
        \application\helpers\Generic::_setTrace('$groups_array',0);
        print_r($users_groups);
        print_r($groups_array);
        \application\helpers\Generic::_setTrace('debug',1);
		*/
        foreach ($check_group as $key => $value)
		{
			$groups = (is_string($value)) ? $groups_array : array_keys($groups_array);

			foreach ($groups as $nkey => $nvalue)
			{
				if ($value <= $nvalue)
				{
					return TRUE;
				}
			}

		}

		return FALSE;
	}

}
