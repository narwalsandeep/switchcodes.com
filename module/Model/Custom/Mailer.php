<?php

namespace Model\Custom;

use Zend\Mail;

/**
 *
 * @author Sandeepn
 *        
 */
class Mailer {
	
	/**
	 *
	 * @param unknown $params        	
	 * @return boolean
	 */
	public function prepare($params, $template = null) {
		$template = self::parseToken ( $template, self::_getColumns (), $params );
		return $this->_sendToMailer ( $params, $template );
	}
	
	/**
	 *
	 * @param unknown $params        	
	 * @param unknown $template        	
	 * @return boolean
	 */
	private function _sendToMailer($params, $template) {
		
		// all 3 below must be present
		if (! $template->content || ! $template->name || ! $params->email)
			return false;
		
		$mail = new \Model\Custom\Mailer ();
		$mail->body ( $template->content );
		$mail->from ( \Model\Entity\Constants::SUPPORT_EMAIL, \Model\Entity\Constants::SUPPORT_NAME );
		$mail->to ( $params->email, $params->first_name . ' ' . $params->last_name );
		$mail->subject ( $template->name );
		
		if ($mail->send ()) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 *
	 * @param unknown $template        	
	 * @param unknown $token_array        	
	 * @param unknown $data        	
	 * @return mixed
	 */
	public static function parseToken($template, $token_array, $data) {
		// parse main db table fields
		foreach ( $token_array as $value ) {
			if (trim ( $value )) {
				$token_to_find = '[[' . trim ( $value ) . ']]';
				$template->content = str_replace ( $token_to_find, $data->{$value}, $template->content );
			}
		}
		return $template;
	}
	
	/**
	 */
	public function send() {
		
		// check and reset params final time,
		if (! $this->to_name)
			$this->to_name = 'Customer';
		if (! $this->to_email)
			return false;
		
		if (! $this->from_name)
			$this->from_name = "Support ";
		
		$message = new \Zend\Mail\Message ();
		
		$htmlmsg = $this->body;
		$html = new \Zend\Mime\Part ( $htmlmsg );
		$html->type = "text/html";
		$body = new \Zend\Mime\Message ();
		$body->setParts ( array (
			$html 
		) );
		
		$message->setBody ( $body );
		$message->setFrom ( $this->from_email, $this->from_name );
		$message->addTo ( $this->to_email, $this->to_name );
		$message->setSubject ( $this->subject );
		
		$smtpOptions = new \Zend\Mail\Transport\SmtpOptions ();
		
		$smtpOptions->setHost ( \Model\Entity\Constants::SMTP_HOST );
		$smtpOptions->setConnectionClass ( 'login' );
		$smtpOptions->setName ( \Model\Entity\Constants::SMTP_HOST );
		$smtpOptions->setPort ( \Model\Entity\Constants::SMTP_PORT );
		
		$smtpOptions->setConnectionConfig ( array (
			'username' => \Model\Entity\Constants::SMTP_USERNAME,
			'password' => \Model\Entity\Constants::SMTP_PWD,
			'ssl' => 'tls' 
		) );
		
		$transport = new \Zend\Mail\Transport\Smtp ( $smtpOptions );
		if ($transport->send ( $message )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 *
	 * @param unknown $email        	
	 * @param unknown $name        	
	 */
	public function to($email, $name) {
		$this->to_email = $email;
		$this->to_name = $name;
	}
	
	/**
	 *
	 * @param unknown $email        	
	 * @param unknown $name        	
	 */
	public function from($email, $name) {
		$this->from_email = $email;
		$this->from_name = $name;
	}
	
	/**
	 *
	 * @param unknown $body        	
	 */
	public function body($body) {
		$this->body = $body;
	}
	
	/**
	 *
	 * @param unknown $subject        	
	 */
	public function subject($subject) {
		$this->subject = $subject;
	}
	
	/**
	 *
	 * @param unknown $file        	
	 */
	public function attach($file) {
		$this->file = $file;
	}
}
