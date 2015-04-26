<?php

namespace Acme\AuthBundle\Controller;

use Acme\AuthBundle\Entity\RecoveryFormValidate;
use Acme\AuthBundle\Entity\RegFormValidate;
use Acme\AuthBundle\Entity\User;
use Acme\AuthBundle\Entity\LoginFormValidate;
use Acme\AuthBundle\Form\LoginForm;
use Acme\AuthBundle\Form\RecoveryForm;
use Acme\AuthBundle\Form\RegForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\ExpressionLanguage\Parser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Iterator\SortableIterator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Util\StringUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Helper\Helper;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

require_once '..\src\Acme\AuthBundle\Lib\recaptchalib.php';


class AuthController extends Controller
{
    /**
     * @Template()
     * @return Response
     */
    public function loginAction(Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $errorData = "";
            $captchaError = "";
            $publicKeyRecaptcha = $this->container->getParameter('publicKeyRecaptcha');
            $captcha = recaptcha_get_html($publicKeyRecaptcha);
            $loginValidate = new LoginFormValidate();
            $formLogin = $this->createForm(new LoginForm(), $loginValidate);
            $formLogin->handleRequest($request);
            $recoveryValidate = new RecoveryFormValidate();
            $formRecovery = $this->createForm(new RecoveryForm(), $recoveryValidate);
            $formRecovery->handleRequest($request);
            if ($request->isMethod('POST')) {
                if ($formLogin->get('enter')->isClicked()) {
                    if ($formLogin->isValid()) {
                        $privateKeyRecaptcha = $this->container->getParameter('privateKeyRecaptcha');
                        $resp = recaptcha_check_answer($privateKeyRecaptcha, $_SERVER["REMOTE_ADDR"], $request->request->get('recaptcha_challenge_field'), $request->request->get('recaptcha_response_field'));
                        if ($resp->is_valid) {
                            $postData = $request->request->get('formLogin');
                            $userEmail = $postData['fieldEmail'];
                            $userPassword = $postData['fieldPass'];
                            $user = Helper::getUserByEmailAndPassword($userEmail, $userPassword);
                            if (!$user) {
                                $errorData = "Введен неправильный Email или пароль";
                            } else {
                                $roleCode = $user->getUserRole()->getCode();
                                if ($roleCode == 'student') {
                                    $role = 'ROLE_STUDENT';
                                } elseif ($roleCode == 'employee') {
                                    $role = 'ROLE_EMPLOYEE';
                                } elseif ($roleCode == 'admin') {
                                    $role = 'ROLE_ADMIN';
                                } elseif ($roleCode == 'other') {
                                    $role = 'ROLE_OTHER';
                                } else {
                                    $role = 'ROLE_DEVELOPER';
                                }
                                $token = new UsernamePasswordToken($user, null, 'secured_area', array($role));
                                $this->get('security.context')->setToken($token);
                                return new RedirectResponse($this->generateUrl('secure_index'));
                            }
                        } else {
                            $captchaError = $resp->error;
                        }
                    }
                } elseif ($formRecovery->get('recovery')->isClicked()) {
                    $postData = $request->request->get('formRecovery');
                    var_dump($postData);die;
                    if ($formRecovery->isValid()) {

                        /*$userEmail = $postData['fieldEmail'];
                        $user = Helper::generateNewPassForRecovery($userEmail);
                        if ($user) {
                            Helper::sendRecoveryPasswordMail($this->container, $user);
                        }*/
                    }
                }
            }
            return array('formLogin' => $formLogin->createView(), 'errorData' => $errorData, 'captcha' => $captcha, 'captchaError' => $captchaError, 'formRecovery' => $formRecovery->createView());
        } else {
            return new RedirectResponse($this->generateUrl('secure_index'));
        }
    }


    /**
     * @Template()
     * @return Response
     */
    public function indexAction(Request $request)
    {
    }


    /**
     * @Template()
     * @return Response
     */
    public function logoutAction(Request $request)
    {
        $session = $request->getSession();
        $session->clear();
        return array();
    }


    /**
     * @Template()
     * @return array
     */
    public function regAction(Request $request)
    {
        $captchaError = "";
        $responseMessage = '';
        $regFormValidate = new RegFormValidate();
        $formReg = $this->createForm(new RegForm(), $regFormValidate);
        $formReg->handleRequest($request);
        $publicKeyRecaptcha = $this->container->getParameter('publicKeyRecaptcha');
        $captcha = recaptcha_get_html($publicKeyRecaptcha);
        if ($request->isMethod('POST')) {
            if ($formReg->get('reg')->isClicked()) {
                if ($formReg->isValid()) {
                    $privateKeyRecaptcha = $this->container->getParameter('privateKeyRecaptcha');
                    $resp = recaptcha_check_answer($privateKeyRecaptcha, $_SERVER["REMOTE_ADDR"], $request->request->get('recaptcha_challenge_field'), $request->request->get('recaptcha_response_field'));
                    if (!$resp->is_valid) {
                        $postData = $request->request->get('formReg');
                        $user = new User();
                        $user->setEmail($postData['fieldEmail']);
                        $user->setChair($postData['fieldChair']);
                        $user->setDescribe($postData['fieldDescribe']);
                        $user->setGroup($postData['fieldGroup']);
                        $user->setInfo($postData['fieldInfo']);
                        $user->setInstitute($postData['fieldInstitute']);
                        $user->setWork($postData['fieldWork']);
                        $user->setName($postData['fieldName']);
                        $user->setPassword($postData['fieldPass']);
                        $user->setPatronymic($postData['fieldPatronymic']);
                        $user->setSpeciality($postData['fieldSpeciality']);
                        $user->setSurname($postData['fieldSurname']);
                        $role = Helper::getUserRoleByRoleName($postData['fieldOptions']);
                        $user->setUserRole($role);
                        $user = Helper::addNewUser($user);
                        $responseMessage = 'Как только ваша заявка будет рассмотрена вам на почту придет ответ';
                        //Helper::sendConfirmationReg($this->container, $user);
                    }
                    else {
                        $captchaError = $resp->error;
                    }
                }
            }
        }
        /*else {
            throw new AccessException();
        }*/
        return array('formReg' => $formReg->createView(), 'captcha' => $captcha, 'captchaError' => $captchaError, 'responseMessage' => $responseMessage);
    }


    /**
     * Template for denied area
     *
     * @Template()
     * @return array
     */
    public function unauthorizedAction(Request $request) {
        return array();
    }


    /**
     * @Template()
     * @return array
     */
    public function recoveryAction(Request $request)
    {
        $formRecovery = $this->createForm(new RecoveryForm());
        $clonedFormRecovery = clone $formRecovery;
        $formRecovery->handleRequest($request);
        $showWindow = false;
        if ($request->isMethod('POST')) {
            if ($formRecovery->get('recovery')->isClicked()) {
                if ($formRecovery->isValid()) {
                    $postData = $request->request->get('formRecovery');
                    $userEmail = $postData['fieldEmail'];
                    $user = Helper::generateNewPassForRecovery($userEmail);
                    if ($user) {
                        $showWindow = true;
                        Helper::sendRecoveryPasswordMail($this->container, $user);
                    }
                }
            }
        }
        return array('formRecovery' => $formRecovery->createView(), 'showWindow' => $showWindow);
    }
}