<?php

namespace Nuntius\WebhooksRounting;

use Nuntius\Nuntius;
use Nuntius\WebhooksRoutingControllerInterface;
use SlackHttpService\Payloads\SlackHttpPayloadServiceAttachments;
use SlackHttpService\Payloads\SlackHttpPayloadServicePostMessage;
use SlackHttpService\SlackHttpService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handling incoming webhooks from Drupal.
 *
 * This is a ready to go webhoook routing for nuntius. This webhook will post
 * message to a a room about a new node.
 */
class Drupal implements WebhooksRoutingControllerInterface {

  /**
   * {@inheritdoc}
   */
  public function response(Request $request) {
    $payload = json_decode($request->request->get('object'));
    $slack_room = $request->request->get('slack_room');
    $token = $request->request->get('token');
    $url = $request->request->get('url');

    if ($token != 'me') {
      Nuntius::getEntityManager()->get('logger')->save([
        'type' => 'error',
        'message' => 'The token sent in the header, "' . $token. '", is not valid.',
      ]);
      return;
    }

    // Get the slack http service.
    $slack_http = new SlackHttpService();
    $slack = $slack_http->setAccessToken(Nuntius::getSettings()->getSetting('access_token'));

    // Build the attachment.
    $attachment = new SlackHttpPayloadServiceAttachments();
    $attachment
      ->setColor('#36a64f')
      ->setTitle($payload->title)
      ->setTitleLink($url);

    if (!empty($payload->body->und[0]->value)) {
      $attachment->setText($payload->body->und[0]->value);
    }

    $attachments[] = $attachment;

    // Build the payload of the message.
    $message = new SlackHttpPayloadServicePostMessage();
    $message
      ->setChannel($slack_room)
      ->setAttachments($attachments)
      ->setText('A new content on the site! Yay!');

    // Posting the message.
    $slack->Chat()->postMessage($message);

    return new Response();
  }

}
