<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactController extends AbstractController
{
    #[Route('/{_locale}/contact', name: 'app_contact', requirements: ['_locale' => 'fr|en'], defaults: ['_locale' => 'fr'], methods: ['POST'])]
    public function contact(Request $request, MailerInterface $mailer, ValidatorInterface $validator, TranslatorInterface $translator): Response
    {
        // Get form data
        $name = trim($request->request->get('name', ''));
        $email = trim($request->request->get('email', ''));
        $message = trim($request->request->get('message', ''));
        $honeypot = $request->request->get('website', '');

        // Honeypot spam protection
        if (!empty($honeypot)) {
            $this->addFlash('error', $translator->trans('contact.error'));
            return $this->redirectToRoute('app_home', ['_locale' => $request->getLocale()]);
        }

        // Validation constraints
        $constraints = new Assert\Collection([
            'name' => [
                new Assert\NotBlank(['message' => 'form.required']),
                new Assert\Length(['max' => 255])
            ],
            'email' => [
                new Assert\NotBlank(['message' => 'form.required']),
                new Assert\Email(['message' => 'form.invalid_email'])
            ],
            'message' => [
                new Assert\NotBlank(['message' => 'form.required']),
                new Assert\Length(['min' => 10, 'max' => 2000])
            ]
        ]);

        // Validate data
        $violations = $validator->validate([
            'name' => $name,
            'email' => $email,
            'message' => $message
        ], $constraints);

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $this->addFlash('error', $translator->trans($violation->getMessage()));
            }
            return $this->redirectToRoute('app_home', ['_locale' => $request->getLocale()]);
        }

        try {
            // Create email
            $contactEmail = (new Email())
                ->from('noreply@vincentferry.dev')
                ->to('vincent.ferry78490@gmail.com')
                ->replyTo($email)
                ->subject('Nouveau message de contact - ' . $name)
                ->html($this->renderView('emails/contact.html.twig', [
                    'name' => $name,
                    'email' => $email,
                    'message' => $message,
                    'date' => new \DateTime()
                ]));

            // Send email
            $mailer->send($contactEmail);

            // Send confirmation email to sender
            $confirmationEmail = (new Email())
                ->from('noreply@vincentferry.dev')
                ->to($email)
                ->subject($translator->trans('contact.confirmation_subject'))
                ->html($this->renderView('emails/contact_confirmation.html.twig', [
                    'name' => $name,
                    'locale' => $request->getLocale()
                ]));

            $mailer->send($confirmationEmail);

            $this->addFlash('success', $translator->trans('contact.success'));

        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans('contact.error'));
        }

        return $this->redirectToRoute('app_home', ['_locale' => $request->getLocale()]);
    }
}
