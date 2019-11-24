<?php

namespace App\Http\Controller;

use App\Application\Command\RegisterUser;
use App\Application\Exception\InvalidPassword;
use App\Application\Exception\UserAlreadyExists;
use App\Application\Service\RegistrationService;
use App\Application\Service\UserExistenceCheckService;
use App\Form\RegisterType;
use App\Http\Validator\EmailCheckValidator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Security\Core\Authorization\ExpressionLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

/**
 * Class BinanceController
 * @package App\Controller
 */
class UserController extends Controller
{
    /**
     * @Route("/login/", name="user_login")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     * https://symfony.com/doc/current/reference/configuration/security.html#reference-security-firewall-form-login
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        if (!empty($this->getUser())) {
            return $this->redirectToRoute('homepage');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Page/User/login.twig', array(
            'last_username' => $lastUsername,
            'error' => $error
        ));
    }

    /**
     *
     * @Route("/register/", name="user_registration")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @param Request $request
     * @param RegistrationService $service
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function registration(Request $request, RegistrationService $service)
    {
        $form = $this->createForm(RegisterType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new RegisterUser();
            $command->user = $form->getData();
            $success = true;

            try {
                $service->registerUser($command);
            } catch (UserAlreadyExists $exception) {
                $success = false;
                $this->addFlash(
                    'error',
                    $exception->getMessage()
                );
            } catch (InvalidPassword $exception) {
                $success = false;
                $this->addFlash(
                    'error',
                    $exception->getMessage()
                );
            }

            if ($success) {
                $this->addFlash(
                    'success',
                    "You're successfully registered with your email address: " . $command->user->email
                );

                return $this->redirectToRoute('user_login');
            }
        }

        return $this->render(
            'Page/User/registration.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/checkemail/", name="user_registration_email_check")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function checkEmail(Request $request, UserExistenceCheckService $userExistenceCheckService): JsonResponse
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $emailCheckValidator = new EmailCheckValidator($data);
        if (!$emailCheckValidator->validate()) {
            return new JsonResponse(["error" => $emailCheckValidator->getErrors()], JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(
            [
                "exists" => $userExistenceCheckService->exists($data['email'])
            ],
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/user/logout/", name="user_logout")
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logout(Request $request, SessionInterface $session)
    {
        $session->clear();
        return $this->redirectToRoute('user_login');
    }

}
