<?php
namespace App\Services\MailSenders;

use Mailgun\Mailgun;

final class MailgunSender
{
  private $client;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->client = new Mailgun('1d8bc21ff0078b2bc1ca6df43ca4b10d-53ce4923-5bf81416');
    $this->domain = "sandbox344f1662b7e448039a38055e73debec6.mailgun.org";
  }

  /**
   * Send message
   *
   * @return mixed
   */
  public function sendMessage(): mixed
  {
    return $this->client->sendMessage(
      $this->domain, $this->data()
    );
  }

  /**
   * Message Data
   *
   * @return array
   */
  private function data(): array
  {
    return [
      'from'    => 'Mailgun Sandbox',
		  'to'      => 'Henrich Barkoczy <henrich.barkoczy@tutanota.com>',
		  'subject' => 'Hello Henrich Barkoczy',
		  'text'    => 'Congratulations Henrich Barkoczy, you just sent an email with Mailgun! You are truly awesome!'
    ];
  }
}