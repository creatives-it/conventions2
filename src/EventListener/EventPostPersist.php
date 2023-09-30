<?php
namespace App\EventListener;

use App\Entity\Courrier;
use App\Entity\CourrierDestinataire;
use App\Entity\CourrierDiffusion;
use App\Entity\CourrierMessage;
use App\Entity\CourrierTraitement;
use App\Entity\StatutCourrier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EventPostPersist
{
    private $container;
    private $translator;
    private $em;

    public function __construct(ContainerInterface $container, TranslatorInterface $translator, EntityManagerInterface $em) {
        $this->container = $container;
        $this->translator = $translator;
        $this->em = $em;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();
        $em = $args->getObjectManager();
        if ((!$object instanceof CourrierDiffusion) and (!$object instanceof CourrierDestinataire)) {
            return;
        }
        if (($object instanceof CourrierDiffusion) or ($object instanceof CourrierDestinataire)) {
            if (!empty($object->getContact())) {
                $courrier = $object->getCourrier();
                $courrierTraitement = $this->em->getRepository(CourrierTraitement::class)->findBy([
                    'courrier' => $courrier,
                    'contact'  => $object->getContact()
                ]);
                if (empty($courrierTraitement)) {
                    $statutCourrier = $this->em->getRepository(StatutCourrier::class)->find(1);

                    $courrierTraitement = new CourrierTraitement();
                    $courrierTraitement->setCourrier($courrier);
                    $courrierTraitement->setCommentaire('');
                    $courrierTraitement->setStatutCourrier($statutCourrier);
                    $courrierTraitement->setPrioriteCourrier($courrier->getPrioriteCourrier());
                    $courrierTraitement->setCloture(false);
                    $courrierTraitement->setArchived(false);
                    $courrierTraitement->setContact($object->getContact());

                    $em->persist($courrierTraitement);
                    $em->flush();
                }

            }
        }


        if ($object instanceof CourrierDiffusion) {
            if (($object->getNotifier() == true) and (!empty($object->getContact()))) {
                $contact = $object->getContact();
                $courrier = $object->getCourrier();
                $nom = $contact->getNomComplet();
                $to  = $contact->getEmail();
//                $to = $this->container->getParameter('app.email.sender');
                if (!empty($to)) {
                    $body = $this->container->get('templating')->render('emails/notificationCourrier.html.twig', array('courrier' => $courrier));
                    $objet = '['.$this->translator->trans('bureau ordre').'] ';
                    $objet .= $this->translator->trans('Courrier');
//                    $to = $this->container->getParameter('app.email.sender');
                    $cc = $this->container->getParameter('app.email.sender'); //'yassineelarabi@yahoo.fr';
                    $message = (new \Swift_Message($objet))
                        ->setFrom($this->container->getParameter('app.email.sender'))
                        ->setTo($to)
                        ->setCc($cc)
                        ->setBody($body, 'text/html');
                    foreach ($courrier->getCourrierDocuments() as $courrierDocument):
                        $media = $courrierDocument->getMedia();
                        if (!empty($media)) {
                            $provider = $this->container->get($media->getProviderName());
                            $url = $provider->generatePublicUrl($media, 'reference');
                            $url = substr($url, 1);
                            //dump($url); die;
                            if (!empty($url)) $message->attach(\Swift_Attachment::fromPath($url));
                        }
                    endforeach;/**/
                    /* */
                    if ($this->container->get('mailer')->send($message)) {
                        $msg = $this->translator->trans("Email envoyé avec succès à");
                        //$this->requestEvent->getSession()->getFlashBag()->add("my_custom_success", $msg." ".$nom." (".$to.")");

                        $courrierMessage = new CourrierMessage();
                        $courrierMessage->setCourrier($courrier);
                        $to = $contact->getEmail();
                        $courrierMessage->setEmailTo($to);
                        $courrierMessage->setEmailCc($cc);
                        $courrierMessage->setNomDestinataire($nom);
                        $courrierMessage->setDateEnvoi(new \DateTime());
                        $courrierMessage->setEnvoye(true);
                        $courrierMessage->setObjet($objet);
                        $courrierMessage->setContenu($body);

                        $object->setNotifier(false);

                        $em->persist($courrierMessage);
                        $em->flush();
                    } else {
                        //$this->requestEvent->getSession()->getFlashBag()->add("my_custom_error", "Erreur lors de l\'envoi de l\'email à ".$nom." (".$to.")");
                    }
                }
            }
        }

    }
}
