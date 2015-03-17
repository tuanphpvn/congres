<?php

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Lexik\Bundle\PayboxBundle\Event\PayboxResponseEvent;

class PaymentResponseListener
{


    public function onPaymentIpnResponse(PayboxResponseEvent $event)
    {

        if ($event->isVerified())
        {
            $em = $this->getDoctrine()->getManager();
            $data = $event->getData();
            $ref = explode('-', $data['Ref']);
            $payment_id = $ref[count($ref) - 1];

            $payment = $em->getRepository('AppBundle:Payment\Payment')->findOneById();
            $payment->setPaymentIPN($data);

            if ($data['Erreur'] == 0000)
            {
                $payment->setStatus(Payment::STATUS_BANKED);
            }
            else
            {
                $payment->setStatus(Payment::STATUS_REFUSED);
            }
        }

    }
}

?>