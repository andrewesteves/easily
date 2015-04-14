<?php
/**
 * Class EasilyMail
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */

namespace Easily;

class EasilyMail
{
	/**
	 * To
	 */
	private $to;

	/**
	 * Subject
	 */
	private $subject;

	/**
	 * From
	 */
	private $from;

	/**
	 * Body
	 */
	private $body;

	/**
	 * Headers
	 */
	private $headers;

	/**
	 * Initiate the mail class
	 *
	 * @param string to
	 * @param string subject
	 * @param string from
	 */
	public function __construct($to, $subject, $from)
	{
		$this->to = $to;
		$this->subject = $subject;
		$this->from = $from;
	}

	/**
	 * Send mail
	 * 
	 * @param string template
	 * @param array vars
	 */
	public function sendMail($template, $vars = [])
	{
		$filepath = APP_TEMPLATE . 'mail' . DS . $template . '.php';
		if(file_exists($filepath)) {
			ob_start();
			include($filepath);
			$this->body = ob_get_clean();

			$this->headers[] = "From: ". $this->from;
			$this->headers[] = "Reply-To: " .$this->from;
			$this->headers[] = "Content-Type: text/html; charset=UTF-8";
			$this->headers[] = "MIME-Version: 1.0";
			$this->headers[] = "X-Priority: 1 (Higuest)";
			$this->headers[] = "X-MSMail-Priority: High";
			$this->headers[] = "Importance: High";

			if(function_exists('mail')) {
				mail($this->to, $this->subject, $this->body, implode("\n", $this->headers));
			}else{
				throw new EasilyException("Please enable the PHP mail function on your server");
				
			}
		}
	}
}