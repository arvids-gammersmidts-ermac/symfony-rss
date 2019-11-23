<?php

namespace App\Http\Controller;

use App\Application\Command\RegisterUser;
use App\Application\Exception\UserAlreadyExists;
use App\Application\Service\RegistrationService;
use App\Form\RegisterType;
use App\Infrastructure\UserRepository;
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

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Page/User/login.twig', array(
            'last_username' => $lastUsername,
            'error' => $error
        ));
    }

//    /**
//     *
//     * @Route("/register/", name="user_registration")
//     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
//     * @param Request $request
//     * @param PasswordUtil $validation
//     * @param UserRepository $userRepository
//     * @param CreateUser $createUser
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
//     * @throws \Doctrine\ORM\ORMException
//     */
//    public function registration(Request $request, PasswordUtil $validation, UserRepository $userRepository, CreateUser $createUser)
//    {
//        if (!empty($this->getUser())) {
//            return $this->redirectToRoute('homepage');
//        }
//        // build the form
//        $registrationModel = new RegistrationModel();
//        $form = $this->createForm(UserType::class, $registrationModel);
//
//        // handle the submit (will only happen on POST)
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            if (!$validation->isPasswordPatternValid($registrationModel->getPlainPassword())) {
//                $this->addFlash(
//                    'error',
//                    "Your password must contain at least one number, one uppercase letter, one lowercase letter, and at least 8 or more characters"
//                );
//            } elseif ($userRepository->findOneByUsername($registrationModel->getUserName())) {
//                $this->addFlash(
//                    'error',
//                    "This email address already exists: " . $registrationModel->getUserName()
//                );
//            } else {
//                /** @var User $user */
//                $user = $createUser->persistUser($registrationModel);
//                $this->addFlash(
//                    'success',
//                    "You're successfully registered with your email address: " . $user->getUsername()
//                );
//
//                return $this->redirectToRoute('user_login');
//            }
//        }
//
//        return $this->render(
//            'Page/User/registration.twig',
//            array(
//                'form' => $form->createView()
//            )
//        );
//    }


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
                    "This email address already exists: " . $command->user->email
                );
            }

            if($success){
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
    public function checkEmail(Request $request, SessionInterface $session, UserRepository $userRepository): JsonResponse
    {
        // TODO to separate API controller
        $data = json_decode(
            $request->getContent(),
            true
        );

        // TODO maybe to forms
        $validator = Validation::createValidator(); // TODO create validator class

        $constraint = new Assert\Collection(array(
            'email' => new Assert\Email(),
        ));

        $violations = $validator->validate($data, $constraint);

        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(
            [
                "exists" => !empty($userRepository->findOneByUsername($data['email']))
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
