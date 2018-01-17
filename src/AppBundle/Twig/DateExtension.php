<?php

namespace AppBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;

class DateExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * DateExtension constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_date';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('elapsed_time', [$this, 'elapsedTimeFilter'])
        ];
    }

    /**
     * Gets elapsed time from a date (in human readable format)
     * @param \DateTime $date
     * @return string
     */
    public function elapsedTimeFilter(\DateTime $date)
    {
        $today = new \DateTime();
        if ($today < $date) {
            throw new \RuntimeException(sprintf("'%d' is a future date", $date->format('Y-m-d H:i:s')));
        }

        //Let's set the current time
        $currentTime = $today->getTimestamp();
        //And the time the notification was set
        $fromTime = $date->getTimestamp();

        //Now calc the difference between the two
        $timeDiff = floor(abs($currentTime - $fromTime) / 60);

        //Now we need find out whether or not the time difference needs to be in
        //minutes, hours, or days
        $parameters = [];
        if ($timeDiff < 2) {
            $message    = "Just now";
        } elseif ($timeDiff >= 2 && $timeDiff < 60) {
            $parameters = ['number' => floor(abs($timeDiff))];
            $message    = '%number% minutes ago';
        } elseif ($timeDiff >= 60 && $timeDiff < 120) {
            $message    = 'a hour ago';
        } elseif ($timeDiff < 1440) {
            $parameters = ['number' => floor(abs($timeDiff / 60))];
            $message    = '%number% hours ago';
        } elseif ($timeDiff >= 1440 && $timeDiff < 2880) {
            $message = 'a day ago';
        } elseif ($timeDiff < 10080) {
            $parameters = ['number' => floor(abs($timeDiff / 1440))];
            $message    = '%number% days ago';
        } elseif ($timeDiff >= 10080 && $timeDiff < 20160) {
            $message    = 'a week ago';
        } elseif ($timeDiff < 40320) {
            $parameters = ['number' => floor(abs($timeDiff / 10080))];
            $message    = '%number% weeks ago';
        } elseif ($timeDiff >= 40320 && $timeDiff < 80640) {
            $message    = 'a month ago';
        } elseif ($timeDiff < 2096640) {
            $parameters = ['number' => floor(abs($timeDiff / 40320))];
            $message    = '%number% months ago';
        } elseif ($timeDiff >= 2096640 && $timeDiff < 4193280) {
            $message    = 'a year ago';
        } elseif ($timeDiff >= 4193280) {
            $parameters = ['number' => floor(abs($timeDiff / 2096640))];
            $message    = '%number% years ago';
        }

        return $this->translator->trans($message, $parameters);
    }
}
