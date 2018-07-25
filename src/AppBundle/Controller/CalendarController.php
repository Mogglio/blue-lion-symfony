<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Month;
use AppBundle\Entity\Event;
use AppBundle\Repository\EventRepository;
use AppBundle\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CalendarController extends Controller
{

    protected $errors = [];

    /**
     * @Route("/calendar", name="calendar")
     */
    public function index()
    {
        $datasDate = $this->instantiateDate();
        $months = array();
        foreach ($datasDate['months'] as $key => $value){
            $months[$key] = $value;
        }
        $months['month_name'] = $datasDate['months']->toString();
        $nextMonth = $datasDate['months']->nextMonth();

        $previousMonth = $datasDate['months']->previousMonth();

        $dayStart = $datasDate['start']->format('Y-m-d');
        $dayEnd = $datasDate['end']->format('Y-m-d');
        $events = $this->getEventsBetweenByDay($dayStart, $dayEnd);

        $weeks = array();
        $datas_days = array();
        for ($i = 0; $i < $datasDate['weeks']; $i++) {
            foreach ($months['days'] as $k => $day) {
                $date = $datasDate['start']->modify('+' . ($k + $i * 7) . " days");

//                var_dump($events[$date->format('Y-m-d')]);

                if(!empty($events[$date->format('Y-m-d')])) {
                    $eventsForDay = $events[$date->format('Y-m-d')];
                } else {
                    $eventsForDay = [];
                }
                $isToday = date('Y-m-d') === $date->format('Y-m-d');
                $withinMonth = $datasDate['months']->withinMonth($date);
                $day_nbr = $date->format('d');


                $datas_days[$day] = array(
                    'date'         => $date,
                    'eventsForDay' => $eventsForDay,
                    'isToday'      => $isToday,
                    'withinMonth'  => $withinMonth,
                    'day_nbr'      => $day_nbr
                );
            }
            $weeks[$i] = $datas_days;
        }

        return $this->render('calendar/calendar.html.twig', [
            'datasDate'       => $datasDate,
            'months'          => $months,
            'next_month'      => $nextMonth,
            'previous_month'  => $previousMonth,
            'weeks'           => $weeks,
        ]);
    }

    /**
     * @Route("/addcalendar", name="addcalendar")
     */
    public function add(Request $request){

        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('calendar');
        }

        return $this->render('calendar/add.html.twig', [
            'controller_name' => 'AddController',
            'form' => $form->createView()
        ]);
    }

    public function instantiateDate(){

        if(empty($_GET['month'])) {
            $month = null;
        } else {
            $month = $_GET['month'];
        }

        if(empty($_GET['year'])) {
            $year = null;
        } else {
            $year = $_GET['year'];
        }

        $month = new Month($month, $year);

        $start = $month->getStartingDay();
        $start = $start->format('N') === '1' ? $start : $month->getStartingDay()->modify('last monday');
        $weeks = $month->getWeeks();
        $end = $start->modify('+' . (6 + 7 * ($weeks -1)) . ' days');
        $previousMonth = $month->previousMonth()->month;
        $nextMonth = $month->nextMonth()->month;

        $datas = array(
            'months'        => $month,
            'start'         => $start,
            'weeks'         => $weeks,
            'end'           => $end,
            'previousMonth' => $previousMonth,
            'nextMonth'     => $nextMonth
        );

        return $datas;
    }

    public function instantiateEvents($dayStart, $dayEnd) {
        $events = $this->getDoctrine()
            ->getRepository(Event::class)
            ->getEventsBetween($dayStart, $dayEnd);

        return $events;
    }
    public function getEventsBetweenByDay ($dayStart, $dayEnd) {
        $events = $this->instantiateEvents($dayStart, $dayEnd);
        $days = [];
        foreach($events as $event) {
            $date = $event->getDate()->format('Y-m-d');
            /* Deprecated */
            $days[$date][] = [$event];
        }
        return $days;
    }

    /**
     * @param array $data
     * @return array|bool
     */
    public function validates(array $data) {
        $this->errors = [];
        $this->data = $data;
        return $this->errors;
    }

    public function validate($field, $method, ...$parameters) {
        if (!isset($this->data[$field])) {
            $this->errors[$field] = "Le champs $field n'est pas rempli";
            return false;
        } else {
            return call_user_func([$this, $method], $field, ...$parameters);
        }
    }

    public function minLength($field, $length) {
        if (mb_strlen($field) < $length) {
            $this->errors[$field] = "Le champs doit avoir plus de $length caractères";
            return false;
        }
        return true;
    }

    public function date ($field) {
        if (\DateTime::createFromFormat('Y-m-d', $this->data[$field]) === false) {
            $this->errors[$field] = "La date ne semble pas valide";
            return false;
        }
        return true;
    }

    public function time ($field) {
        if (\DateTime::createFromFormat('H:i', $this->data[$field]) === false) {
            $this->errors[$field] = "Le temps ne semble pas valide";
            return false;
        }
        return true;
    }

    public function beforeTime ($startField, $endField) {
        if ($this->time($startField) && $this->time($endField)) {
            $start = \DateTime::createFromFormat('H:i', $this->data[$startField]);
            $end = \DateTime::createFromFormat('H:i', $this->data[$endField]);
            if ($start->getTimestamp() > $end->getTimestamp()) {
                $this->errors[$startField] = "Le temps doit être inférieur au temps de fin";
                return false;
            }
            return true;
        }
        return false;
    }
}