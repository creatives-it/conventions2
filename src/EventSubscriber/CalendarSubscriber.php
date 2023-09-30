<?php

namespace App\EventSubscriber;

use App\Repository\ConventionRepository;
use App\Repository\ConventionSuiviExecutionRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $conventionRepository;
    private $conventionSuiviExecutionRepository;
    private $router;

    public function __construct(ConventionRepository $conventionRepository, ConventionSuiviExecutionRepository $conventionSuiviExecutionRepository, UrlGeneratorInterface $router)
    {
        $this->conventionRepository = $conventionRepository;
        $this->conventionSuiviExecutionRepository = $conventionSuiviExecutionRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();
        if (1==2):
        // Modify the query to fit to your entity and needs
        // Change event.date by your start date property
        $conventions = $this->conventionRepository
            ->createQueryBuilder('convention')
            //->where('convention.displayOnCalendar = true')
            ->andWhere('convention.dateSignature BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()->getResult();
        foreach ($conventions as $convention) {
            // this create the conventions with your data (here convention data) to fill calendar
            $conventionEvent = new Event(
                $convention->getObjetConvention(),
                $convention->getDateSignature(),
                $convention->getEndDateSignature()// If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $conventionEvent->setOptions([
                'backgroundColor' => '#FCF8E3',
                'borderColor' => '#ccc',
                'textColor' => '#000',
            ]);
            $conventionEvent->addOption('url', $this->router->generate('admin_app_convention_view', ['id' => $convention->getId()]));

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($conventionEvent);
        }
        endif;

        // SUIVI EXECUTION
        $conventionSuiviExecutions = $this->conventionSuiviExecutionRepository
            ->createQueryBuilder('conventionSuiviExecution')
            //->where('conventionSuiviExecution.displayOnCalendar = true')
            ->andWhere('conventionSuiviExecution.date BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()->getResult();
        //dump($conventionSuiviExecutions);die;
        foreach ($conventionSuiviExecutions as $conventionSuiviExecution) {
            $conventionSuiviExecutionEvent = new Event(
                $conventionSuiviExecution->getTitle(),
                $conventionSuiviExecution->getDate(),
                $conventionSuiviExecution->getEndDate()// If the end date is null or not defined, a all day event is created.
            );
            $conventionSuiviExecutionEvent->setOptions([
                'backgroundColor' => '#FCF8E3',
                'borderColor' => '#ccc',
                'textColor' => '#000',
            ]);
            $conventionSuiviExecutionEvent->addOption('url', $this->router->generate('admin_app_convention_edit', ['id' => $conventionSuiviExecution->getConvention()->getId()]));
            $calendar->addEvent($conventionSuiviExecutionEvent);
        }
    }
}