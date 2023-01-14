<?php
//
//namespace App\Controller\Api;
//
//use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\Persistence\ManagerRegistry;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
//use App\Entity\User;
//use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Core\User\UserInterface;
//
//class ApiAuthController extends ApiController
//{
//    #[Route('/api/register')]
//    public function register(Request $request, UserPasswordHasherInterface $encoder , EntityManagerInterface $em)
//    {
//
////        $em = $this->doctrine->getManager();
////        $request = $this->transformJsonBody($request);
////        $request = $this->transformJsonBody($request);
//        $firstName = $request->request->get('firstname');
//        $lastName = $request->request->get('lastname');
//        $password = $request->request->get('password');
//        $email = $request->request->get('email');
////
////        if (empty($firstName) || empty($lastName)||empty($password) || empty($email)){
////            return $this->respondValidationError("Invalid Username or Password or Email");
////        }
//
//
//        $user = new User();
//        $user->setPassword($password);
//        $user->setEmail($email);
//        $user->setFirstName($firstName);
//        $user->setLastName($lastName);
//        $em->persist($user);
//        $em->flush();
//        return $this->respondWithSuccess(sprintf('User %s successfully created', $user->getFirstName()));
//    }
//
////    /**
////     * @param UserInterface $user
////     * @param JWTTokenManagerInterface $JWTManager
////     * @return JsonResponse
////     */
//
//
//    #[Route('/api/login_check' , methods: ['POST'])]
//    public function getTokenUser( JWTTokenManagerInterface $JWTManager)
//    {
//        return new JsonResponse(['token' => $JWTManager->create(new User())]);
//    }
//
//}