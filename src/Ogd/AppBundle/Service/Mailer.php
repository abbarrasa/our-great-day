<?php

namespace AppBundle\Service;

use AdminBundle\Entity\Enquiry;
use AdminBundle\Entity\Greeting;
use AdminBundle\Entity\AbstractComment;
use AdminBundle\Entity\Joined;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserInterface;
use Nzo\UrlEncryptorBundle\UrlEncryptor\UrlEncryptor;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Config\FileLocatorInterface;

class Mailer implements MailerInterface
{
    const TEMPLATE_ENQUIRY_NOTIFICATION   = 'email/enquiry-notification.txt.twig';
    const TEMPLATE_GREETING_NOTIFICATION  = 'email/greeting-notification.txt.twig';
    const TEMPLATE_COMMENT_NOTIFICATION   = 'email/comment-notification.txt.twig';
    const TEMPLATE_REGISTER_NOTIFICATION  = 'email/register-notification.txt.twig';    
    const TEMPLATE_WEBSITE_ANNOUNCEMENT   = 'email/website-announcement.txt.twig';
    const TEMPLATE_RESETTING_REQUEST      = 'email/resetting-request.txt.twig';

    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var \Twig_Environment */
    protected $environment;

    /** @var Router */
    protected $router;
    
    /** @var FileLocatorInterface */
    protected $fileLocator;

    /** @var UrlEncryptor */
    protected $encryptor;

    /** @var array */
    protected $config;
    
    /**
     * Mailer constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param Router $router
     * @param FileLocatorInterface $fileLocator
     * @param UrlEncryptor $encryptor
     * @param $config
     */
    public function __construct(
            \Swift_Mailer $mailer,
            \Twig_Environment $twig,
            Router $router,
            FileLocatorInterface $fileLocator,
            UrlEncryptor $encryptor,
            $config
    ) {
        $this->mailer      = $mailer;
        $this->environment = $twig;
        $this->router      = $router;
        $this->fileLocator = $fileLocator;
        $this->encryptor   = $encryptor; 
        $this->config      = $config;
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
        //If we use another mailer or other configuration of SwiftMailer different from the one given by Symfony
        if ($transport instanceof \Swift_Transport_SpoolTransport) {
            $spool = $transport->getSpool();
            //In case we use a mail spool persisted in files
            if ($spool instanceof \Swift_FileSpool) {
                $spool->recover();
            }

            return $spool->flushQueue($this->transport);
        }

        return null;
    }

    /**
     * Send an email to a user to confirm the account creation.
     * @param UserInterface $user
     * @return int
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $token = rtrim(strtr(base64_encode($this->encryptor->encrypt($user->getUsername())), '+/', '-_'), '=');
        $url = $this->router->generate('fos_user_security_autologin', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
        $message = $this->getMessage(self::TEMPLATE_REGISTER_NOTIFICATION, [
            'user' => $user,
            'accessUrl' => $url
        ]);

        return $this->sendEmailMessage($this->config['email_admin'], (string) $user->getEmail(), $message);
    }

    /**
     * Send an email to a user to confirm the password reset.
     * @param UserInterface $user
     * @return int
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);
        $message = $this->getMessage(self::TEMPLATE_RESETTING_REQUEST, [
            'user' => $user,
            'confirmationUrl' => $url
        ]);

        return $this->sendEmailMessage($this->config['email_admin'], (string) $user->getEmail(), $message);
    }

    /**
     * Sends a notification email order to moderate a guestbook message.
     * @param Greeting $greeting
     * @return int
     */
    public function sendGreetingNotificationMessage(Greeting $greeting)
    {
        $message = $this->getMessage(self::TEMPLATE_GREETING_NOTIFICATION, ['greeting' => $greeting]);

        return $this->sendEmailMessage($this->config['email_admin'], $this->config['email_manager'], $message);
    }

    /**
     * Sends a notification email order to resolve a enquiry.
     * @param Enquiry $enquiry
     * @return int
     */
    public function sendEnquiryNotificationMessage(Enquiry $enquiry)
    {
        $message = $this->getMessage(self::TEMPLATE_ENQUIRY_NOTIFICATION, ['enquiry' => $enquiry]);

        return $this->sendEmailMessage($this->config['email_admin'], $this->config['email_manager'], $message);
    }
    
    /**
     * Sends a notification email order to moderate a comment.
     * @param AbstractComment $comment
     * @return int
     */
    public function sendCommentNotificationMessage(AbstractComment $comment)
    {
        $message = $this->getMessage(self::TEMPLATE_COMMENT_NOTIFICATION, ['comment' => $comment]);

        return $this->sendEmailMessage($this->config['email_admin'], $this->config['email_manager'], $message);
    }    
    
    /**
     * Sends a notification email order to announce the opening of website.
     * @param Joined $joined
     * @return int
     */
    public function sendAnnouncementMessage(Joined $joined)
    {
        $message = $this->getMessage(self::TEMPLATE_WEBSITE_ANNOUNCEMENT, ['joined' => $joined]);

        return $this->sendEmailMessage($this->config['email_admin'], $joined->getEmail(), $message);
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
        $message   = \Swift_Message::newInstance();
        $images    = $this->embedImages($message);
        $template  = $this->environment->loadTemplate($name);
        $context   = $this->environment->mergeGlobals($parameters);
        $subject   = $template->renderBlock('subject', $context);
        $bodyPlain = $template->renderBlock('body_plain', $context);
        $bodyHtml  = $template->renderBlock('body_html', $context);
        $bodyHtml  = preg_replace('/[\n|\r|\n\r|\t|\0|\x0B]/', '', $bodyHtml);

        // Replace placeholder urls with embedded image reference
        // eg <img src="image1.jpg"> => <img src="cid:XXXXX">
        if (count($images) > 0) {
            $bodyHtml  = strtr($bodyHtml, $images);
        }

        $message->setContentType("text/html")
            ->setSubject($subject)
            ->setBody($bodyHtml, 'text/html')
            ->addPart($bodyPlain, 'text/plain');

        return $message;
    }

    /**
     * Embeds defined images in a message. Returns a list of embedded image references.
     * @param \Swift_Message $message
     * @return array
     */
    protected function embedImages(\Swift_Message &$message)
    {
        if (!array_key_exists('embedded_images', $this->config)) {
            return array();
        }

        $images = array();
        foreach($this->config['embedded_images'] as $file) {
            $path = $this->fileLocator->locate($file, $this->config['images_directory']);
            // Embed images into message and collect references
            $images[basename($path)] = $message->embed(\Swift_Image::fromPath($path)->setDisposition('inline'));
        }

        return $images;
    }
}
