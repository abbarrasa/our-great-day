<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Enquiry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Mailer
{
    const TEMPLATE_ENQUIRY_NOTIFICATION   = 'email/enquiry-notification.txt.twig';
    const TEMPLATE_GUESTBOOK_NOTIFICATION = 'email/guestbook-notification.txt.twig';
    const TEMPLATE_OPENING_NOTIFICATION   = 'email/opening-notification.txt.twig';

    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var UrlGeneratorInterface  */
    protected $router;

    /** @var \Twig_Environment */
    protected $environment;

    /** @var array */
    protected $config;

    /**
     * Mailer constructor.
     * @param \Swift_Mailer $mailer
     * @param UrlGeneratorInterface $router
     * @param \Twig_Environment $environment
     * @param $config
     */
    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface  $router, \Twig_Environment $environment, $config)
    {
        $this->mailer       = $mailer;
        $this->router       = $router;
        $this->environment  = $environment;
        $this->config       = $config;
    }

    /**
     * Adds a sleep when we send several mails in a row, to avoid losing communication between
     * the client and the mail server, using SwiftMailer AntiFloodPlugin plugin.
     * @param int $threshold
     * @param null $sleep
     */
    public function sleepSendMail($threshold = 99, $sleep = null)
    {
        if ($sleep === null) {
            $sleep = $this->config['default_sleep'];
        }

        $this->mailer->registerPlugin(new \Swift_Plugins_AntiFloodPlugin($threshold, $sleep));
    }

    /**
     * Forces the sending of all those mails that at this moment are prepared in the outgoing mail tray.
     * @return int|null
     */
    public function flushSpool()
    {
        $transport = $this->mailer->getTransport();
        //Por si usaramos otro mailer u otra configuración de SwiftMailer diferente a la que da Symfony
        if ($transport instanceof \Swift_Transport_SpoolTransport) {
            $spool = $transport->getSpool();
            //Por si usamos un spool de correo persistido en ficheros
            if ($spool instanceof \Swift_FileSpool) {
                $spool->recover();
            }

            return $spool->flushQueue($this->transport);
        }

        return null;
    }

    /**
     * Sends a notification email order to moderate a guestbook message.
     * @param Guessbook $guestbook
     * @return int
     */
    public function sendGuestbookNotificationMessage(Guessbook $guestbook)
    {
        $message = $this->getMessage(self::TEMPLATE_GUESTBOOK_NOTIFICATION, ['guestbook' => $guestbook]);

        return $this->sendEmailMessage($this->config['email_admin'], $this->config['email_no_reply'], $message);
    }

    /**
     * Sends a notification email order to resolve a enquiry.
     * @param Enquiry $enquiry
     * @return int
     */
    public function sendEnquiryNotificationMessage(Enquiry $enquiry)
    {
        $message = $this->getMessage(self::TEMPLATE_ENQUIRY_NOTIFICATION, ['enquiry' => $enquiry]);

        return $this->sendEmailMessage($this->config['email_admin'], $this->config['email_no_reply'], $message);
    }
    
    /**
     * Sends a notification email order to announce the opening of website.
     * @param Joined $joined
     * @return int
     */
    public function sendOpenningNotificationMessage(Joined $joined)
    {
        $message = $this->getMessage(self::TEMPLATE_OPENING_NOTIFICATION, ['joined' => $joined]);

        return $this->sendEmailMessage($this->config['email_admin'], $this->config['email_no_reply'], $message);
    }    

    /**
     * Sends a email.
     * @param $fromEmail
     * @param $toEmail
     * @param \Swift_Message $message
     * @param array $attachments
     * @return int
     */
    protected function sendEmailMessage($fromEmail, $toEmail, \Swift_Message $message, array $attachments = array())
    {
        // FROM_NAME is given to the given $ fromEmail email.
        // To pass another name that is displayed in the mail, it must be passed as an array in the
        // $fromEmail variable in an array ($address, $name). Then, setFrom method of Swift_Message
        // takes care of plugging it correctly.
        $message
            ->setFrom($fromEmail, $this->config['from_name'])
            ->setTo($toEmail)
        ;

        foreach($attachments as $attachment) {
            $message->attach(\Swift_Attachment::fromPath($attachment));
        }

        return $this->mailer->send($message);
    }

    /**
     * Gets the mail message from a template, either in html format or in plain text format.
     * @param $name
     * @param array $parameters
     * @return $this
     */
    protected function getMessage($name, array $parameters = array())
    {
        $template  = $this->environment->loadTemplate($name);
        $context   = $this->environment->mergeGlobals($parameters);
        $subject   = $template->renderBlock('subject', $context);
        $bodyHtml  = $template->renderBlock('body_html', $context);
        $bodyHtml  = preg_replace('/[\n|\r|\n\r|\t|\0|\x0B]/', '', $bodyHtml);
        $bodyPlain = $template->renderBlock('body_plain', $context);

        $message  = \Swift_Message::newInstance()
            ->setContentType("text/html")
            ->setSubject($subject)
            ->setBody($bodyHtml, 'text/html')
            ->addPart($bodyPlain, 'text/plain');

        return $message;
    }
}
