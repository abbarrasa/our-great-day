<?php

namespace AppBundle\Command;

use AppBundle\Entity\Joined;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnnounceWebsiteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ogd:announce-website')
            ->setDescription('Announces the opening of the website to all the emails in the joined list')
            ->setHelp(<<<'EOT'
The <info>ogd:announce-website</info> command announces the opening of the website
EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em    = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $list  = $em->getRepository(Joined::class)->findBy(['notified' => false]);
        $count = 0;

        if (count($list) > 0) {
            $mailer = $this->getContainer()->get('AppBundle\Service\Mailer');
            foreach($list as $joined) {
                //Send email. If the email is sent correctly, it is marked as notified.
                if (($result = $mailer->sendAnnouncementMessage($joined)) > 0) {
                    $count += $result;
                    $output->writeln('A email has been sent to: ' . $joined->getEmail());

                    //Update joined
                    $joined->setNotified(true);
                    $em->persist($joined);
                }
            }

            $em->flush();
        }

        $output->writeln(sprintf('<info>Command succesfully finished with %d emails sent!</info>', $count));
    }
}