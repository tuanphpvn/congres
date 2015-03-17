<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Event\Event;
use AppBundle\Entity\Event\EventAdherentRegistration;
use AppBundle\Form\Event\EventType;
use AppBundle\Form\Event\EventAdherentRegistrationType;
use AppBundle\Entity\Payment\EventPayment;

/**
 * Event\Event controller.
 *
 * @Route("/event")
 */
class EventController extends Controller
{

    /**
     * Finds and displays a Event\EventAdherentRegistration entity.
     *
     * @Route("/{event_id}/registration/{event_reg_id}", name="event_registration_show", requirements={
     *     "event_id": "\d+",
     *     "event_reg_id": "\d+"
     *     })
     * @Method("GET")
     * @ParamConverter("event", class="AppBundle:Event\Event", options={"id" = "event_id"})
     * @ParamConverter("eventRegistration", class="AppBundle:Event\EventAdherentRegistration", options={"id" = "event_reg_id"})
     * @Template("event/registration_show.html.twig")
     */
    public function registrationShowAction(Event $event, EventAdherentRegistration $eventRegistration)
    {
        if ($eventRegistration->getAuthor() !=  $this->getUser()->getProfile())
        {
            throw new AccessDeniedException();
        }

        return array(
            'event'      => $event,
            'eventRegistration' => $eventRegistration
        );
    }

    /**
     * Register to a event
     *
     * @Route("/{event_id}/registration/create", name="event_registration_create", requirements={
     *     "event_id": "\d+"
     *     })
     * @ParamConverter("event", class="AppBundle:Event\Event", options={"id" = "event_id"})
     *
     */
    public function registerAction(Request $request, Event $event)
    {
        $adherent = $this->getUser()->getProfile();
        $eventRegistration = new EventAdherentRegistration($this->getUser()->getProfile(), $event);

        $eventRegistration->setAdherent($adherent);
        $form = $this->createRegistrationCreateForm($eventRegistration, $event);

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $eventRegistration = $form->getData();
            $eventRegistration->setEvent($event);
            $eventRegistration->setRegistrationDate(new \DateTime('now'));
            $eventRegistration->setAdherent($adherent);

            $this->getDoctrine()->getManager()->persist($eventRegistration);
            $this->getDoctrine()->getManager()->flush();

            if ($eventRegistration->getPaymentMode() == EventAdherentRegistration::PAYMENT_MODE_ONLINE)
            {
                $eventPayment = new EventPayment($adherent, $event, $eventRegistration);
                $eventPayment->setAmount($eventRegistration->getCost()->getCost())
                    ->setMethod(EventPayment::METHOD_CREDIT_CARD)
                    ->setStatus(EventPayment::STATUS_NEW)
                    ->setDrawer($adherent)
                    ->setRecipient($adherent)
                    ->setDate(new \DateTime('now'))
                    ->setReferenceIdentifierPrefix($event->getNormalizedName())
                    ->setAccount(EventPayment::ACCOUNT_PG); // FIXME : multiple account gestion, the account as to be choosen when creating the event. Needed to modify PayboxBundle to manage multiple id

                $this->getDoctrine()->getManager()->persist($eventPayment);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('payment_pay', array('id' => $eventPayment->getId())));
            }
            else
            {
                return $this->redirect($this->generateUrl('event_registration_show', array('event_id' => $event->getId(), 'event_reg_id' => $eventRegistration->getId())));
            }
        }

        return $this->render("event/registration.html.twig", array(
            'event'      => $event,
            'event_registration'      => $eventRegistration,
            'form'  => $form->createView()))
            ;
    }


    /**
     * Finds and displays a Event\Event entity.
     *
     * @Route("/{event_id}", name="event_show", requirements={
     *     "event_id": "\d+"
     * })
 )
 * @Method("GET")
 * @ParamConverter("event", class="AppBundle:Event\Event", options={"id" = "event_id"})
 * @Template("event/show.html.twig")
     */
    public function showAction(Event $event)
    {
        return array(
            'event'      => $event,
        );
    }

    /**
     * Lists all Event\EventAdherentRegistration entities.
     *
     * @Route("/registration/user", name="event_adherent_registration_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        $adherent = $this->getUser()->getProfile();
        $em = $this->getDoctrine()->getManager();

        $eventRegs = $em->getRepository('AppBundle:Event\EventAdherentRegistration')->findByAuthor($adherent);

        return $this->render('event/adherent_registration_list.html.twig', array(
            'eventRegistrations' => $eventRegs,
            'adherent' => $adherent
        ));

    }


    /**
     * Creates a form to create a Event\EventAdherentRegistration entity.
     *
     * @param EventAdherentRegistration $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createRegistrationCreateForm(EventAdherentRegistration $entity, Event $event)
    {
        $form = $this->createForm(new EventAdherentRegistrationType(), $entity, array(
            'action' => $this->generateUrl('event_registration_create', array('event_id' => $event->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }
}
