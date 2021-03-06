<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Template
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Template
     * @Route("/ics/{user}", name="ics")
     */
    public function getIcs($user = null)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($user);

        $events = [];

        foreach ($user->getGroups()->getValues() as $group) {
            $events = array_merge($events, $group->getEvents()->getValues());
        }
        $events = array_unique($events);

        $provider = $this->get('bomo_ical.ics_provider');

        $tz = $provider->createTimezone();
        $config = [
            'dtstart' => '19710101T030000',
            'tzoffsetto' => '+0200',
            'tzoffsetfrom' => '+0100',
            'rrule' => [
                'freq' => 'YEARLY',
                'wkst' => 'MO',
                'interval' => 1,
                'bymonth' => 10,
            ],
            'tzname' => 'CET'
        ];
        $tz
            ->setTzid('Europe/Paris')
            ->setProperty('X-LIC-LOCATION', $tz->getTzid())
            ->setDaylight($config);

        $cal = $provider->createCalendar($tz);

        $cal
            ->setName('Uni.lu Calendar')
            ->setDescription('WS17-18');


        foreach ($events as $eventCal) {
            $event = $cal->newEvent();
            //substract 2 hours
            //->sub(new \DateInterval("PT7200S")))
            $event
                ->setStartDate($eventCal->getStartDate()->sub(new \DateInterval("PT3600S")))
                ->setEndDate($eventCal->getEndDate()->sub(new \DateInterval("PT3600S")))
                ->setName($eventCal->getCourse())
                ->setDescription($eventCal->getGroup())
                ->setLocation($eventCal->getLocal());


        }

        $calStr = $cal->returnCalendar();

        return new Response(
            $calStr,
            200,
            array(
                'Content-Type' => 'text/calendar; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="calendar.ics"',
            )
        );
    }
}
