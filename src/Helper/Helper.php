<?php

namespace Helper;


use Acme\SecureBundle\Entity\Gps;
use Acme\SecureBundle\Entity\Message;
use Acme\SecureBundle\Entity\Schedule;
use Doctrine\ORM\Query\ResultSetMapping;

class Helper
{
    private static $_container;
    private static $_ymFile;
    private static $_tableUser = 'AcmeAuthBundle:User';
    private static $_tableMessage = 'AcmeSecureBundle:Message';
    private static $_tableUserRole = 'AcmeAuthBundle:UserRole';
    private static $_tableRoom = 'AcmeSecureBundle:Room';
    private static $_tableSubject = 'AcmeSecureBundle:Subject';
    private static $_tableTypeLesson = 'AcmeSecureBundle:TypeLesson';
    private static $_tableSchedule = 'AcmeSecureBundle:Schedule';
    private static $_tableGp = 'AcmeAuthBundle:Gp';
    private static $_tableGps = 'AcmeSecureBundle:Gps';
    private static $_tableGpsAdditional = 'AcmeSecureBundle:GpsAdditional';
    private static $_tableAdditional = 'AcmeSecureBundle:Additional';

    private static $kernel;

    private static $_avatarFolder = '/site/web/uploads/avatars/';

    public function __construct() {
    }

    public static function getContainer() {
        if(self::$kernel instanceof \AppKernel) {
            if(!self::$kernel->getContainer() instanceof Container) {
                self::$kernel->boot();
            }
            return self::$kernel->getContainer();
        }
        $environment = 'dev';
        self::$kernel = new \AppKernel($environment, false);
        self::$kernel->boot();
        return self::$kernel->getContainer();
    }

    public static function addNewUser($user) {
        $em = self::getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();
        return $user;
    }

    public static function getUserById($userId) {
        $user = self::getContainer()->get('doctrine')->getRepository(self::$_tableUser)
            ->findOneById($userId);
        if ($user) {
            return $user;
        }
        return false;
    }

    public static function getUserMessages($user) {
        $em = self::getContainer()->get('doctrine')->getManager();
        $messages = $em->getRepository(self::$_tableMessage)->createQueryBuilder('m')
            ->innerJoin(self::$_tableUser, 'u', 'WITH', 'm.writerId = u')
            ->andWhere('m.responseId = :user')
            ->andWhere('m.is_read = 0')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult();
        return $messages;
    }

    public static function updateUser($postData, $user) {
        $em = self::getContainer()->get('doctrine')->getManager();
        $user->setEmail($postData['fieldEmail']);
        $user->setAbout($postData['fieldAbout']);
        $user->setSurname($postData['fieldSurname']);
        $user->setPatronymic($postData['fieldPatronymic']);
        $user->setName($postData['fieldName']);
        $user->setInstitute($postData['fieldInstitute']);
        $user->setChair($postData['fieldChair']);
        $user->setSpeciality($postData['fieldSpeciality']);
        $user->setGroup($postData['fieldGroup']);
        $user->setCourse($postData['fieldCourse']);
        $user->setWork($postData['fieldWork']);
        $user->setIsShowEmail($postData['fieldIsShowEmail']);
        //var_dump($postData['fieldDateBirthday']);die;
        $user->setDateBirthday($postData['fieldDateBirthday']);
        $user->setPhone($postData['fieldPhone']);
        $em->merge($user);
        $em->flush();
        return $user;
    }

    /**
     * Get user by email and password
     */
    public static function getUserByEmailAndPassword($userEmail, $userPassword) {
        $user = self::getContainer()->get('doctrine')->getRepository(self::$_tableUser)
            ->findOneBy(array('email' => $userEmail, 'password' => $userPassword));
        if (!$user) {
            return false;
        }
        return $user;
    }

    public static function getUserPhoto($user) {
        $userPhoto = $user->getPhoto();
        $pathAvatar = self::$_avatarFolder . $userPhoto;
        $userAvatar = "<img src='$pathAvatar' align='middle' alt='' width='110px' height='auto' class='thumbnail'>";
        return $userAvatar;
    }

    public static function getFormatDateForInsert($sourceDate, $format) {
        $date = \DateTime::createFromFormat($format, $sourceDate);
        $date = $date->format('Y-m-d');
        return new \Datetime($date);
    }

    public static function changeUserPassword($postData, $user) {
        $em = self::getContainer()->get('doctrine')->getManager();
        $user->setPassword($postData['fieldPassNew']);
        $em->merge($user);
        $em->flush();
        return $user;
    }

    public static function getWriterFio($msg) {
        return $msg->getWriter()->getSurname() . ' ' . $msg->getWriter()->getName() . ' ' . $msg->getWriter()->getPatronymic();
    }

    public static function getMessagesForGrid($firstRowIndex, $rowsPerPage, $user, $searchVal) {
        $em = self::getContainer()->get('doctrine')->getManager();
        if ($searchVal != null) {
            $writer = $em->getRepository(self::$_tableUser)->findOneByName($searchVal);
            if ($writer) {
                $messages = $em->getRepository(self::$_tableMessage)->createQueryBuilder('m')
                    ->innerJoin(self::$_tableUser, 'u', 'WITH', 'm.responseId = u')
                    ->andWhere('m.writerId = :writer')
                    ->andWhere('m.responseId = :user')
                    ->setParameter('user', $user)
                    ->setParameter('writer', $writer)
                    ->getQuery()
                    ->getResult();
            } else {
                $messages = $em->getRepository(self::$_tableMessage)
                    ->findBy(
                        array('theme' => $searchVal),
                        array('id' => 'ASC'),
                        $rowsPerPage,
                        $firstRowIndex
                    );
                if (!$messages) {
                    return null;
                }
            }
        } else {
            /*$messages = $em->getRepository(self::$_tableMessage)
                ->findBy(
                    array('responseId' => $user),
                    array('id' => 'ASC'),
                    $rowsPerPage,
                    $firstRowIndex
                );*/
            $messages = $em->getRepository(self::$_tableMessage)->createQueryBuilder('m')
                ->innerJoin(self::$_tableUser, 'u', 'WITH', 'm.responseId = u')
                ->andWhere('m.responseId = :user')
                ->setParameter('user', $user)
                ->groupBy('m.writerId')
                ->getQuery()
                ->getResult();
        }
        return $messages;
    }

    public static function deleteMessage($messagesId) {
        $em = self::getContainer()->get('doctrine')->getManager();
        foreach($messagesId as $msgId) {
            $msg = $em->getRepository(self::$_tableMessage)
                ->findOneById($msgId);
            $em->remove($msg);
            $em->flush();
        }
    }

    public static function readMessage($messagesId) {
        $em = self::getContainer()->get('doctrine')->getManager();
        foreach($messagesId as $msgId) {
            $msg = $em->getRepository(self::$_tableMessage)
                ->findOneById($msgId);
            $msg->setIsRead(1);
            $em->flush();
        }
    }

    public static function getTalkForGrid($user, $writerId) {
        $em = self::getContainer()->get('doctrine')->getManager();
        /*$writer = $em->getRepository(self::$_tableUser)
            ->findById($writerId);*/
       /* $messages = $em->getRepository(self::$_tableMessage)
            ->findBy(array('writerId' => $writer, 'responseId' => $user));*/
        $query = $em->createQuery('SELECT m.id,m.content,m.date_write,u.id AS writer_id,u.surname,u.name,u.patronymic FROM AcmeSecureBundle:Message m
        INNER JOIN AcmeAuthBundle:User u WITH m.writerId = u.id
        INNER JOIN AcmeAuthBundle:User u1 WITH m.responseId = u1.id
        WHERE (m.writerId=:writer_id AND m.responseId=:response_id)
        OR (m.writerId=:response_id AND m.responseId=:writer_id)');
        $query->setParameter('writer_id', $writerId);
        $query->setParameter('response_id', $user->getId());
        $messages = $query->getResult();
        return $messages;
    }

    public static function deleteSelectedMessage($messageId) {
        $em = self::getContainer()->get('doctrine')->getManager();
        $msg = $em->getRepository(self::$_tableMessage)
            ->findOneById($messageId);
        if ($msg) {
            $em->remove($msg);
            $em->flush();
            return true;
        }
        return false;
    }

    public static function deleteTalk($user, $writerId) {
        $em = self::getContainer()->get('doctrine')->getManager();
        $writer = $em->getRepository(self::$_tableUser)
            ->findOneById($writerId);
        $messages = $em->getRepository(self::$_tableMessage)
            ->findBy(array('responseId' => $user, 'writerId' => $writer));
        if ($messages) {
            foreach($messages as $msg) {
                $em->remove($msg);
            }
            $em->flush();
        }
        $messages = $em->getRepository(self::$_tableMessage)
            ->findBy(array('responseId' => $writer, 'writerId' => $user));
        if ($messages) {
            foreach($messages as $msg) {
                $em->remove($msg);
            }
            $em->flush();
        }
    }

    public static function sendMessage($postData, $user) {
        $em = self::getContainer()->get('doctrine')->getManager();
        $response = $em->getRepository(self::$_tableUser)
            ->findOneById($postData['fieldResponseId']);
        $msg = new Message();
        $msg->setTheme($postData['fieldTheme']);
        $msg->setContent($postData['fieldMessage']);
        $msg->setWriter($user);
        $msg->setResponse($response);
        $em->persist($msg);
        $em->flush();
    }

    public static function addNewSchedule($data) {
        $em = self::getContainer()->get('doctrine')->getManager();
        $room = $em->getRepository(self::$_tableRoom)
            ->findOneById($data['room']);
        $typeLesson = $em->getRepository(self::$_tableTypeLesson)
            ->findOneById($data['type_lesson']);
        $user = $em->getRepository(self::$_tableUser)
            ->findOneById($data['user']);
        $subject = $em->getRepository(self::$_tableSubject)
            ->findOneById($data['subject']);
        $schedule = new Schedule();
        $schedule->setStartDate($data['start_date']);
        $schedule->setEndDate($data['end_date']);
        //$schedule->setText($data['text']);
        $schedule->setEven($data['even']);
        $schedule->setOdd($data['odd']);
        $schedule->setRoom($room);
        $schedule->setTypeLesson($typeLesson);
        $schedule->setUser($user);
        $schedule->setSubject($subject);
        $em->persist($schedule);
        $em->flush();
        $groups = explode(',', $data['group']);
        foreach($groups as $groupId) {
            $gps = new Gps();
            $group = Helper::getGroupById($groupId);
            $gps->setGp($group);
            $gps->setSchedule($schedule);
            $em->persist($gps);
        }
        $em->flush();
    }

    public static function updateSchedule($data) {
        $em = self::getContainer()->get('doctrine')->getManager();
        $room = $em->getRepository(self::$_tableRoom)
            ->findOneByNum($data['room']);
        if (!$room) {
            $room = $em->getRepository(self::$_tableRoom)
                ->findOneById($data['room']);
        }
        $typeLesson = $em->getRepository(self::$_tableTypeLesson)
            ->findOneByName($data['type_lesson']);
        if (!$typeLesson) {
            $typeLesson = $em->getRepository(self::$_tableTypeLesson)
                ->findOneById($data['type_lesson']);
        }
        $user = $em->getRepository(self::$_tableUser)
            ->findOneBySurname($data['user']);
        if (!$user) {
            $user = $em->getRepository(self::$_tableUser)
                ->findOneById($data['user']);
        }
        $subject = $em->getRepository(self::$_tableSubject)
            ->findOneByName($data['subject']);
        if (!$subject) {
            $subject = $em->getRepository(self::$_tableSubject)
                ->findOneById($data['subject']);
        }
        $schedule = $em->getRepository(self::$_tableSchedule)
            ->findOneById($data['id']);
        $schedule->setStartDate($data['start_date']);
        $schedule->setEndDate($data['end_date']);
        $schedule->setEven($data['even']);
        $schedule->setOdd($data['odd']);
        $schedule->setRoom($room);
        $schedule->setTypeLesson($typeLesson);
        $schedule->setUser($user);
        $schedule->setSubject($subject);
        //$em->persist($schedule);
        $em->flush();
        /*$groups = explode(',', $data['group']);
        foreach($groups as $groupId) {
            $gps = new Gps();
            $group = Helper::getGroupById($groupId);
            $gps->setGp($group);
            $gps->setSchedule($schedule);
            $em->persist($gps);
        }
        $em->flush();*/
    }

    public static function deleteSchedule($data) {
        $em = self::getContainer()->get('doctrine')->getManager();
        $schedule = $em->getRepository(self::$_tableSchedule)
            ->findOneById($data['id']);
        $gps = $em->getRepository(self::$_tableGps)
            ->findBySchedule($schedule);
        foreach($gps as $gp) {
            $em->remove($gp);
        }
        $em->remove($schedule);
        $em->flush();
    }

    public static function getFormatDateForInsert1($sourceDate, $format) {
        $date = \DateTime::createFromFormat($format, $sourceDate);
        $date = $date->format('Y-m-d H:i');
        return new \Datetime($date);
    }

    public static function getGroupById($groupId) {
        $em = self::getContainer()->get('doctrine')->getManager();
        $group = $em->getRepository(self::$_tableGp)
            ->findOneById($groupId);
        return $group;
    }

    public static function getAdditionalForGrid() {
        $em = self::getContainer()->get('doctrine')->getManager();
        $query = $em->getConnection()
            ->prepare("SELECT ad.*,s.name AS subject,t.name AS type_lesson,r.num AS room,u.surname AS user,GROUP_CONCAT(DISTINCT gp.name SEPARATOR ', ') AS groups FROM additional ad
                INNER JOIN subject s ON ad.subject_id = s.id
                INNER JOIN type_lesson t ON ad.type_lesson_id = t.id
                INNER JOIN room r ON ad.room_id = r.id
                INNER JOIN user u ON ad.user_id = u.id
                INNER JOIN gps_additional gs ON ad.id = gs.additional_id
                INNER JOIN gp gp ON gp.id = gs.gp_id
                GROUP BY ad.id");
        $query->execute();
        $additional = $query->fetchAll();
        return ($additional);
    }

}
