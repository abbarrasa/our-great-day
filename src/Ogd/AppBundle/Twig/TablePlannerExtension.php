<?php

namespace AppBundle\Twig;

use AdminBundle\Entity\Table;

class TablePlannerExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_table_planner';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('render_table', [$this, 'renderFunction'], [
                'needs_environment' => true, 'pre_escape' => 'html', 'is_safe' => ['html']
	        ])
        ];
    }

    public function renderFunction(\Twig_Environment $environment, Table $table)
    {
        if ($table->getPicture() === null) {
		    $picture = null;
	    } else {
		    $picture = [
			    'path' => $table->getPictureFile()->getPathname(),
			    'alt' => basename($table->getPictureFile()->getPathname())
		    ];
	    }

	    return $environment->render('@App/tables/table.html.twig', [
	        'id' => uniqid(),
            'title' => $table->getTitle(),
		    'subtitle' => $table->getSubtitle(),
		    'picture' => $picture,
		    'seats' => $table->getSeats()
        ]);	
    }
}
