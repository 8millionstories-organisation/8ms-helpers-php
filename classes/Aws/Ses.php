<?php declare(strict_types = 1);

namespace Ems\Aws;

use Aws\Ses\SesClient;
use Ems\Api;

class Ses
{
	private ?SesClient $instance = null;

	public $from = null;
	public $to = [];
	public $cc = [];
	public $bcc = [];
	public $html = null;
	public $subject = null;

	function __construct($region)
	{
		$config = Aws::getConfig(region: $region);

		$this->instance = new SesClient([
			...$config,
			'version' => '2010-12-01',
		]);
	}

	public function setFrom($from) : Ses
	{
		$this->from = $from;

		return $this;
	}

	public function setTo($to) : Ses
	{
		$this->to = $to;

		return $this;
	}

	public function setCc($cc) : Ses
	{
		$this->cc = $cc;

		return $this;
	}

	public function setBcc($bcc) : Ses
	{
		$this->bcc = $bcc;

		return $this;
	}

	public function setHtml($html) : Ses
	{
		$this->html = $html;

		return $this;
	}

	public function setSubject($subject) : Ses
	{
		$this->subject = $subject;

		return $this;
	}

	public function send()
	{
		$response = [
			...Api::DefaultResponse,
		];

		// https://docs.aws.amazon.com/ses/latest/DeveloperGuide/send-using-sdk-php.html
		$params = [
			'Destination' => [
				'BccAddresses' => $this->bcc,
				'CcAddresses'  => $this->cc,
				'ToAddresses'  => $this->to,
			],
			'Message'     => [
				'Body'    => [
					'Html' => [
						'Charset' => "UTF-8",
						'Data'    => $this->html,
					],
					'Text' => [
						'Charset' => "UTF-8",
						'Data'    => $this->html,
					],
				],
				'Subject' => [
					'Charset' => "UTF-8",
					'Data'    => $this->subject,
				],
			],
			'Source'      => $this->from,
		];

		try
		{
			$apiResponse = $this->instance->sendEmail($params);

			if(Aws::isResponse200($apiResponse))
			{
				$response['state'] = Api::States['SUCCESS'];
			}
			else
			{
				$response['state'] = Api::States['ERROR'];
				$response['error'] = Api::UnexpectedError;
			}
		}
		catch(\Exception $exception)
		{
			$response['state'] = Api::States['ERROR'];
			$response['error'] = $exception->getMessage();
		}

		return $response;
	}

	public function reset() : Ses
	{
		$this->from    = null;
		$this->to      = [];
		$this->cc      = [];
		$this->bcc     = [];
		$this->html    = null;
		$this->subject = null;

		return $this;
	}
}
