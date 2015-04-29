<?php

namespace Acme\SecureBundle\Controller;

use Acme\SecureBundle\Entity\ProfileFormValidate;
use Acme\SecureBundle\Form\MessageTalkForm;
use Acme\SecureBundle\Form\ProfileForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderAdapter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Iterator\SortableIterator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\Security\Core\SecurityContext;
use Helper\Helper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Csrf\CsrfToken;
use Zend\I18n\Validator\DateTime;

require_once '..\src\Acme\SecureBundle\common\connector\scheduler_connector.php';
require_once '..\src\Acme\SecureBundle\common\connector\combo_connector.php';


class SecureController extends Controller
{
    /**
     * @Template()
     * @return array
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $profileFormValidate = new ProfileFormValidate($user->getUserRole()->getCode());
        $profileFormValidate->setFieldSurname($user->getSurname());
        $profileFormValidate->setFieldName($user->getName());
        $profileFormValidate->setFieldPatronymic($user->getPatronymic());
        $profileFormValidate->setFieldInstitute($user->getInstitute());
        $profileFormValidate->setFieldChair($user->getChair());
        $profileFormValidate->setFieldSpeciality($user->getSpeciality());
        $profileFormValidate->setFieldGroup($user->getGroup()->getName());
        $profileFormValidate->setFieldCourse($user->getCourse());
        $profileFormValidate->setFieldWork($user->getWork());
        $profileFormValidate->setFieldDateBirthday($user->getDateBirthday()->format("d.m.Y"));
        $profileFormValidate->setFieldPhone($user->getPhone());
        $profileFormValidate->setFieldAbout($user->getAbout());
        $profileFormValidate->setFieldEmail($user->getEmail());
        $profileFormValidate->setFieldIsShowEmail($user->getIsShowEmail());
        $profileFormValidate->setFieldTypeProfile($user->getUserRole()->getName());
        $profileFormValidate->setFieldUserId($user->getId());
        $formProfile = $this->createForm(new ProfileForm(), $profileFormValidate);
        $formProfile->handleRequest($request);
        $formMessageTalk = $this->createForm(new MessageTalkForm());
        $formMessageTalk->handleRequest($request);
        $messages = Helper::getUserMessages($user);
        $message = [];
        $message['support'] = 0;
        $message['private'] = 0;
        $message['chair'] = 0;
        $message['request'] = 0;
       /* foreach($messages as $msg) {
            if ($msg->getWriter()->getUserRole()->getCode() == 'suppoer') {

            }
        }*/
        $message['total'] = count($messages);
        $userPhoto = Helper::getUserPhoto($user);
        if ($request->isXmlHttpRequest()) {
            $postData = $request->request->all();
            if (isset($postData['oper']) && $postData['oper'] == 'del') {
                $messagesId = explode(',', $postData['id']);
                Helper::deleteMessage($messagesId);
                exit;
            } elseif (isset($postData['oper']) && $postData['oper'] == 'readable') {
                $messagesId = explode(',', $postData['id']);
                Helper::readMessage($messagesId);
                exit;
            }
            $curPage = $postData['page'];
            $rowsPerPage = $postData['rows'];
            $search = $postData['search'];
            $searchVal = null;
            if (isset($search) && $search == "true") {
                $searchVal = $postData['searchVal'];
            }
            $firstRowIndex = $curPage * $rowsPerPage - $rowsPerPage;
            $messages = Helper::getMessagesForGrid($firstRowIndex, $rowsPerPage, $user, $searchVal);
            $countMessages = count($messages);
            $response = new Response();
            $response->total = ceil($countMessages / $rowsPerPage);
            $response->records = $countMessages;
            $response->page = $curPage;
            if ($messages) {
                foreach($messages as $index => $msg) {
                    $writerFio = Helper::getWriterFio($msg);
                    $response->rows[$index]['id'] = $msg->getId();
                    $response->rows[$index]['cell'] = array(
                        $msg->getId(),
                        $writerFio,
                        $msg->getDateWrite()->format("d.m.Y H:i:s"),
                        $msg->getTheme(),
                        $msg->getWriter()->getId()
                    );
                }
            }
            return new JsonResponse($response);
        } elseif ($request->isMethod('POST')) {
            if ($formProfile->get('save')->isClicked()) {
                if ($formProfile->isValid()) {
                    $uploadedFile = $request->files->get('formProfile')['fieldPhoto'];
                    if ($uploadedFile) {
                        $directory = $this->get('kernel')->getRootDir() . '/../web/uploads/avatars/';
                        $uploadedFile->move($directory, $uploadedFile->getClientOriginalName());
                        $user->setPhoto($uploadedFile->getClientOriginalName());
                    }
                    $postData = $request->request->get('formProfile');
                    $user = Helper::updateUser($postData, $user);
                    $userPhoto = Helper::getUserPhoto($user);
                    //Helper::sendConfirmationReg($this->container, $user);
                }
            } elseif ($formProfile->get('saveNewPassword')->isClicked()) {
                if ($formProfile->isValid()) {
                    $postData = $request->request->get('formProfile');
                    $user = Helper::changeUserPassword($postData, $user);
                }
            } elseif ($formMessageTalk->get('send')->isClicked()) {
                $postData = $request->request->get('formMessageTalk');
                Helper::sendMessage($postData, $user);
            }
        }
        return array('user' => $user, 'formProfile' => $formProfile->createView(), 'messages' => $message, 'userPhoto' => $userPhoto, 'formMessageTalk' => $formMessageTalk->createView());
    }

    /**
     * @Template()
     * @return array
     */
    public function talkAction(Request $request)
    {
        $user = $this->getUser();
        if ($request->isXmlHttpRequest()) {
            $postData = $request->request->all();
            $writerId = $postData['writerId'];
            $oper = $postData['oper'];
            if ($oper == 'delete-message') {
                $messageId = $postData['id'];
                Helper::deleteSelectedMessage($messageId);
            } elseif ($oper == 'delete-talk') {
                $writerId = $postData['id'];
                Helper::deleteTalk($user, $writerId);
            }
            $messages = Helper::getTalkForGrid($user, $writerId);
            $response = new Response();
            if ($messages) {
                foreach($messages as $index => $msg) {
                    $writerFio = 'Вы';
                    if ($msg['writer_id'] != $user->getId()) {
                        $writerFio = $msg['surname'] . ' ' . $msg['name'] . ' ' . $msg['patronymic'];
                    }
                    $response->rows[$index]['id'] = $msg['id'];
                    $response->rows[$index]['cell'] = array(
                        $msg['id'],
                        $writerFio,
                        $msg['content'],
                        $msg['date_write']->format("d.m.Y H:i:s"),
                        ''
                    );
                }
            }
            return new JsonResponse($response);
        }
    }


    /**
     * @Template()
     * @return array
     */
    public function scheduleAction(Request $request, $mode)
    {
        $param = $request->query->get('p');
        if ($mode == 'data') {
            if ($param == 'subject') {
                /* $scheduler = new \JSONSchedulerConnector($res);
                 $scheduler->render_table("schedule","id","start_date,end_date,text,user_id,room_id,subject_id,type_lesson_id");*/
                /*$scheduler = new \schedulerConnector($res);
                $scheduler->render_table("schedule","id","start_date,end_date,text,user_id,room_id,subject_id,type_lesson_id");*/
                $res = self::getDB();
                $combo = new \ComboConnector($res);
               /* $combo->event->attach("subject", "by_id");
                function by_id($filter) {
                    if (isset($_GET['id'])) {
                        $filter->add("id", $_GET['id'], '=');
                    }
                }*/
                //$combo->dynamic_loading(3);
                $combo->render_table("subject","id","name");
                /*$scheduler = new \schedulerConnector($res);
                if ($scheduler->is_select_mode())//code for loading data
                    $scheduler->render_sql("Select * from subject", "id", "name");
                else //code for other operations - i.e. update/insert/delete
                    $scheduler->render_table("tableA","id","name,price");*/
                //$scheduler = new \schedulerConnector($res);
                //$scheduler->render_table("subject","id","name");
            } elseif ($param == 'room') {
                $res = self::getDB();
                $combo = new \ComboConnector($res);
                $combo->render_table("room","id","num");
            } elseif ($param == 'type_lesson') {
                $res = self::getDB();
                $combo = new \ComboConnector($res);
                $combo->render_table("type_lesson","id","name");
            } elseif ($param == 'user') {
                $res = self::getDB();
                $combo = new \ComboConnector($res);
                $combo->render_sql("SELECT * FROM user u INNER JOIN user_role r ON u.user_role_id = r.id WHERE r.code = 'employee'", "id", "surname");
            } elseif ($param == 'group') {
                $res = self::getDB();
                $combo = new \ComboConnector($res);
                $combo->render_table("gp","id","name");
            };
        } elseif ($mode == 'proc') {
            if ($param == 'load_data') {
                $res = self::getDB();
                $scheduler = new \schedulerConnector($res);
                //$scheduler->render_table("schedule", "id", "start_date,end_date,text,even,odd,user_id,subject_id,type_lesson_id,room_id");
                $scheduler->render_sql("SELECT  sch.*,s.name AS subject,t.name AS type_lesson,r.num AS room,u.surname AS user,GROUP_CONCAT(DISTINCT gp.name SEPARATOR ', ') AS groups FROM schedule sch
                INNER JOIN subject s ON sch.subject_id = s.id
                INNER JOIN type_lesson t ON sch.type_lesson_id = t.id
                INNER JOIN room r ON sch.room_id = r.id
                INNER JOIN user u ON sch.user_id = u.id
                INNER JOIN gps gs ON sch.id = gs.schedule_id
                INNER JOIN gp gp ON gp.id = gs.gp_id
                GROUP BY sch.id", "id", "start_date,end_date,subject,subject,type_lesson,room,user,groups,even,odd");
            } else {
                $data = $request->query->all();
                if ($data['!nativeeditor_status'] == 'inserted') {
                    Helper::addNewSchedule($data);
                    exit;
                } elseif ($data['!nativeeditor_status'] == 'updated') {
                    Helper::updateSchedule($data);
                    exit;
                } else {
                    Helper::deleteSchedule($data);
                    exit;
                }
            }
        }
    }

    public function getDB() {
        $res = mysql_connect("localhost", "root", "");
        mysql_select_db("project_site");
        return $res;
    }


    /**
     * @Template()
     * @return array
     */
    public function profileAction(Request $request, $type)
    {
    }


    /**
     * @Route("/upload", name="upload")
     */
    public function uploadAction(Request $request)
    {
    }

    /**
     * @Template()
     * @return array
     */
    public function settingsAction(Request $request, $type)
    {
    }

    /**
     * Serves a file
     */
    public function downloadFileAction($type, $num, $filename = null)
    {
    }
}
