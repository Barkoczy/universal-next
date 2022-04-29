<?php
namespace App\Services\MailSenders;

use Mailgun\Mailgun;
use App\Kernel\Environment;

final class MailgunSender
{
  private $client;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->client = new Mailgun(Environment::var('MAILGUN_PRIVATE_API_KEY'));
    $this->domain = Environment::var('MAILGUN_DOMAIN');
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