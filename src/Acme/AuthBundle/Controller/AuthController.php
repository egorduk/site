<?php

namespace Acme\AuthBundle\Controller;

use Acme\AuthBundle\Entity\RecoveryFormValidate;
use Acme\AuthBundle\Entity\RegFormValidate;
use Acme\AuthBundle\Entity\User;
use Acme\AuthBundle\Entity\LoginFormValidate;
use Acme\AuthBundle\Form\LoginForm;
use Acme\AuthBundle\Form\RecoveryForm;
use Acme\AuthBundle\Entity\UserInfo;
use Acme\AuthBundle\Form\AuthorRegForm;
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
                        //var_dump($request->request->get('formLogin'));die;
                        $privateKeyRecaptcha = $this->container->getParameter('privateKeyRecaptcha');
                        $resp = recaptcha_check_answer($privateKeyRecaptcha, $_SERVER["REMOTE_ADDR"], $request->request->get('recaptcha_challenge_field'), $request->request->get('recaptcha_response_field'));
                        if ($resp->is_valid) {
                            $postData = $request->request->get('formLogin');
                            $userEmail = $postData['fieldEmail'];
                            $userPassword = $postData['fieldPass'];
                            $user = Helper::getUserByEmailAndIsConfirm($userEmail);
                            if (!$user) {
                                $errorData = "Введен неправильный Email или пароль";
                            } else {
                                $encodedPassword = Helper::getUserPassword($userPassword, $user->getSalt());
                                if (!StringUtils::equals($encodedPassword, $user->getPassword())) {
                                    $errorData = "Введен неправильный Email или пароль";
                                } else {
                                    $roleCode = $user->getUserRole()->getCode();
                                    if ($roleCode == 'author') {
                                        $role = 'ROLE_AUTHOR';
                                    } elseif ($roleCode == 'client') {
                                        $role = 'ROLE_CLIENT';
                                    }
                                    $token = new UsernamePasswordToken($user, null, 'secured_area', array($role));
                                    $this->get('security.context')->setToken($token);
                                    if ($role == 'ROLE_AUTHOR') {
                                        return new RedirectResponse($this->generateUrl('secure_author_index'));
                                    } elseif ($role == 'ROLE_CLIENT') {
                                        return new RedirectResponse($this->generateUrl('secure_client_index'));
                                    }
                                }
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
        return array('formReg' => $formReg->createView(), 'captcha' => $captcha, 'captchaError' => $captchaError);
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
     * Auth by openID provider
     * @Template()
     * @return array
     */
    public function openidAction(Request $request)
    {
        $session = $request->getSession();
        if ($session->has('socialToken')) {
            $socialToken = $session->get('socialToken');
            $socialResponse = file_get_contents('http://ulogin.ru/token.php?token=' . $socialToken . '&host=' . $_SERVER['HTTP_HOST']);
            $socialData = json_decode($socialResponse, true);
            $showWindow = false;
            $captchaError = "";
            if (!isset($socialData['error'])) {
                $userEmail = $socialData['email'];
                $session->remove('socialToken');
                $isExistsUser = Helper::isExistsUserByEmailAndIsConfirmAndIsUnban($userEmail);
                if ($isExistsUser) {
                    $role = Helper::getUserRoleByEmail($userEmail);
                    $roleId = $role->getId();
                    if ($roleId == 1) {
                        $role = 'ROLE_AUTHOR';
                        $pathRedirect = 'secure_author_index';
                    }
                    else {
                        $role = 'ROLE_CLIENT';
                        $pathRedirect = 'secure_client_index';
                    }
                    $token = new UsernamePasswordToken($userEmail, null, 'secured_area', array($role));
                    $this->get('security.context')->setToken($token);
                    return new RedirectResponse($this->generateUrl($pathRedirect));
                }
                else {
                    $clientValidate = new ClientRegFormValidate();
                    $clientValidate->setLogin($socialData['nickname']);
                    $clientValidate->setEmail($socialData['email']);
                    $formReg = $this->createForm(new ClientRegForm(), $clientValidate);
                    $formReg->handleRequest($request);
                    $publicKeyRecaptcha = $this->container->getParameter('publicKeyRecaptcha');
                    $captcha = recaptcha_get_html($publicKeyRecaptcha);
                    if ($request->isMethod('POST')) {
                        if ($formReg->get('reg')->isClicked()) {
                            if ($formReg->isValid()) {
                                $privateKeyRecaptcha = $this->container->getParameter('privateKeyRecaptcha');
                                $resp = recaptcha_check_answer($privateKeyRecaptcha, $_SERVER["REMOTE_ADDR"], $request->request->get('recaptcha_challenge_field'), $request->request->get('recaptcha_response_field'));
                                if (!$resp->is_valid) {
                                    $session->remove('socialToken');
                                    $postData = $request->request->get('formReg');
                                    $userLogin = $postData['fieldLogin'];
                                    $userPassword = $postData['fieldPass'];
                                    $userEmail = $postData['fieldEmail'];
                                    $userInfo = new UserInfo();
                                    //$countryCode = geoip_country_code_by_name($_SERVER["REMOTE_ADDR"]);
                                    $countryCode = 'by';
                                    $country = Helper::getCountryByCode($countryCode);
                                    $userInfo->setCountry($country);
                                    Helper::addNewUserInfo($userInfo);
                                    $user = new User();
                                    $user->setLogin($userLogin);
                                    $user->setEmail($userEmail);
                                    $role = Helper::getUserRoleByRoleId(2);
                                    $user->setUserRole($role);
                                    $salt = Helper::getSalt();
                                    $password = Helper::getRegPassword($userPassword, $salt);
                                    $user->setPassword($password);
                                    $user->setSalt($salt);
                                    $hashCode = Helper::getRandomValue(15);
                                    $user->setHash($hashCode);
                                    $user->setUserInfo($userInfo);
                                    $userId = Helper::addNewOpenIdData($socialData, $country, $user);
                                    Helper::sendConfirmationReg($this->container, $userEmail, $userId, $hashCode);
                                    $showWindow = true;
                                }
                                else {
                                    $captchaError = $resp->error;
                                }
                            }
                        }
                    }
                }
                return $this->render(
                    'AcmeAuthBundle:Client:reg.html.twig', array('formReg' => $formReg->createView(), 'captcha' => $captcha, 'captchaError' => $captchaError, 'showWindow' => $showWindow)
                );
            }
        }
        else {
            return $this->redirect($this->generateUrl('login'));
        }
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


    /**
     * Confirm register and recovery password by Email
     *
     * @Template()
     * @return array
     */
    public function confirmAction(Request $request)
    {
        $hashCode = $request->get('hash_code');
        $userId = $request->get('id');
        $type = $request->get('type');
        $isCorrectUrl = Helper::isCorrectConfirmUrl($hashCode, $userId, $type);
        $showSuccessWindow = false;
        if ($isCorrectUrl) {
            if ($type == "reg") {
                $isExistsUser = Helper::isExistsUserByHashAndByIdAndIsConfirm($userId, $hashCode, 0);
                if ($isExistsUser) {
                    $isSuccess = Helper::updateUserAfterConfirmByMail($userId, $hashCode, $type);
                    if ($isSuccess) {
                        $showSuccessWindow = true;
                    }
                }
            }
            elseif ($type == "rec") {
                $isExistsUser = Helper::isExistsUserByHashAndByIdAndIsConfirm($userId, $hashCode, 1);
                if ($isExistsUser) {
                    $isSuccess = Helper::updateUserAfterConfirmByMail($userId, $hashCode, $type);
                    if ($isSuccess) {
                        $showSuccessWindow = true;
                    }
                }
            }
        }
        return array('showSuccessWindow' => $showSuccessWindow);
    }



}
