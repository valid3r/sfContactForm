<?php
// src/Controller/Controller.php
namespace App\Controller;

use App\Entity\Contact;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\MessageGenerator;
use DateTime;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer as PHPMailer;
// Required for Routes
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Methods;

// to extend from AbstractController
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Controller extends AbstractController
{
    // Save contact to DB
    private function saveContact($data = null): bool
    {
        // Entity Manager Instance
        $entityManager = $this->getDoctrine()->getManager();

        // Contact Instance
        $contact = new Contact();
        $contact->setFirstname($data->vorname);
        $contact->setLastname($data->nachname);
        $contact->setFromEmail($data->email);
        $contact->setToEmail($data->toEmail);
        $contact->setEmailSubject($data->subject);
        $contact->setEmailMessage($data->message);
        $contact->setDateCreated(new DateTime());

        // Persist to DB
        $entityManager->persist($contact);
        $entityManager->flush();

        // Check if contact created
        if ($entityManager->contains($contact)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @Route("/")
     */
    public function index(): Response
    {
        // Data Manipulation etc
        $path = 'index';

        // Render Template
        // return new Response("This is {$page}");

        // Render Template
        return $this->render('index.html.twig', [
            'path' => $path,
        ]);
    }

    /**
     * @Route("/contacts")
     * Gets Called on page start
     */
    public function contacts(): Response
    {
        // Data Manipulation etc
        $path = 'contacts';

        $contacts = $this->getDoctrine()
            ->getRepository(Contact::class)
            ->findBy([], ['date_created' => 'ASC']);

        if (!$contacts) {
            throw $this->createNotFoundException('No contacts found!');
        }

        // Render Template
        return $this->render('contacts.html.twig', [
            'contacts' => $contacts,
            'path' => $path,
        ]);
    }

    /**
     * @Route("/sendEmail/{data}", methods={"POST"})
     */
    public function sendEmail($data): Response
    {
        // this returns null if not valid json
        // $data = json_decode($rawData);

        $response = [];

        $data = json_decode($data);

        // Save Contact
        $response['backup_saved: '] = $this->saveContact($data);

        // Send Email

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        $dateSent = new DateTime();

        try {
            $mail->isSMTP(); // Send using SMTP

            $mail->Host = $data->smtpAdress; // Set the SMTP server to send through

            $mail->SMTPAuth = true; // Enable SMTP authentication

            $mail->Username = $data->smtpUsername; // SMTP username
            $mail->Password = $data->smtpPassword; // SMTP password

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

            $mail->Port = $data->smtpPort; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients

            $mail->setFrom($data->email, $data->vorname);
            // $mail->setFrom('temp-sergio@webmail.tecno24.de', 'Tester');

            $mail->addAddress($data->toEmail); // Add a recipient
            // $mail->addAddress('temp-sergio@webmail.tecno24.de'); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML

            $mail->Subject = $data->subject;

            $mail->Body = $data->message;

            $mail->AltBody = $data->message;

            if ($mail->send()) {
                $response['email_sent'] = true;
            }
        } catch (Exception $e) {
            $response['email_sent'] = false;
            $response['exception'] = $mail->ErrorInfo;
        }

        // $messageGenerator = new MessageGenerator();
        // $response['message'] = $messageGenerator->getHappyMessage();

        // Return Json

        // $response['data'] = $data;

        $response = new Response(json_encode($response));
        $response->headers->set('Content-Type', 'application/json');

        // Render Template
        return $response;
    }

    /**
     * @Route("/deleteContact/{id}")
     */
    public function deleteContact($id): Response
    {
        // Data Manipulation etc
        $response = 'Contact deleted!';

        $entityManager = $this->getDoctrine()->getManager();

        $contact = $this->getDoctrine()
            ->getRepository(Contact::class)
            ->find($id);

        // Remove it and flush
        $entityManager->remove($contact);
        $entityManager->flush();

        // Render Template
        return new Response("{$response}");
    }

    /**
     * @Route("/getContactInfo/{id}", methods={"POST"})
     */
    public function getContactInfo($id = null)
    {
        //$response = [];

        $contact = $this->getDoctrine()
            ->getRepository(Contact::class)
            ->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact not found!');
        }

        // $response['firstname'] = $contact->getFirstname();
        // $response['lastname'] = $contact->getLastname();

        // Return Json
        //$response = new Response(json_encode($response));
        // $response->headers->set('Content-Type', 'application/json');

        $html =
            '<div class="row">' .
            '       <div class="col-md-12">' .
            '           <div class="signup-form">' .
            '               <form action="" >' .
            '                  ' .
            '                   <div class="row">' .
            '                       <div class="mb-3 col-md-6">' .
            '                           <label>Vorname </label>' .
            '                           <input type="text" name="fname" class="form-control" disabled value="' .
            $contact->getFirstname() .
            '">' .
            '                       </div>' .
            '                        <div class="mb-3 col-md-6">' .
            '                           <label>Nachname: </label>' .
            '                           <input type="text" name="Lname" class="form-control" disabled value="' .
            $contact->getLastname() .
            '">' .
            '                       </div>' .
            '                        ' .
            '                       <div class="mb-3 col-md-6">' .
            '                           <label>From: </label>' .
            '                           <input type="text" name="fname" class="form-control" disabled value="' .
            $contact->getFromEmail() .
            '">' .
            '                       </div>' .
            '                        <div class="mb-3 col-md-6">' .
            '                           <label>To: </label>' .
            '                           <input type="text" name="Lname" class="form-control" disabled value="' .
            $contact->getToEmail() .
            '">' .
            '                       </div>' .
            '                        <div class="mb-3 col-md-12">' .
            '                           <label>Subject</label>' .
            '                           <input type="text" name="password" class="form-control" disabled value="' .
            $contact->getEmailSubject() .
            '">' .
            '                       </div>' .
            '                        <div class="mb-3">' .
            '                         <label for="exampleFormControlTextarea1" class="form-label">Message</label>' .
            '                         <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" disabled> ' .
            $contact->getEmailMessage() .
            ' </textarea>' .
            '                       </div>' .
            '' .
            '                        ' .
            '                   </div>' .
            '               </form>' .
            '           </div>' .
            '       </div>' .
            '   </div>';

        // echo $html;

        // Render Template
        return new Response($html);
    }

    /**
     * @Route("/getAllContacts")
     */
    public function getAllContacts(Contact $data = null): Response
    {
        $contacts = $this->getDoctrine()
            ->getRepository(Contact::class)
            ->findAll();

        if (!$contacts) {
            throw $this->createNotFoundException('No contacts found!');
        }

        $response = [
            'code' => 200,
            'response' => $this->render('contactsData.html.twig', [
                'contacts' => $contacts,
            ])->getContent(),
        ];

        return new JsonResponse($response);
    }

    /**
     * @Route("/massDeleteContacts")
     */
    public function massDeleteContacts(Contact $data = null): Response
    {
        $response = 'Contacts deleted!';

        $entityManager = $this->getDoctrine()->getManager();

        $query = $this->getDoctrine()
            ->getRepository(Contact::class)
            ->deleteOlderThan(14);

        $query->execute();

        $entityManager->flush();

        // Render Template
        return new Response("{$response}");
    }
}
