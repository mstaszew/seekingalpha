<?php
namespace App\Utils;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\DBAL\Connection;

class Utils
{
    /** @var Registry */
    private $doctrine;
    /**
     * Utils constructor.
     */
    public function __construct($doctrine)
    {
        $this->doctrine=$doctrine;
    }

    /**
     * @param $userId
     * @return string
     */
    public function getUserNameById($userId): string
    {
        /** @var $connection Connection */
        $connection = $this->doctrine->getConnection();
        $sql = "select `name` from `users` where `id`=".$userId." limit 1";
        $retVal = $connection->fetchAll($sql)[0]['name'];
        return($retVal);
    }

    public function getAllUsers() {
        $connection = $this->doctrine->getConnection();
        $sql = "select `users`.`id` as `user_id`, `users`.`name` as `user_name`, `groups`.`name` as `group_name` 
from `users` inner join `groups` on `users`.`group_id` = `groups`.`id` order by `user_name`";

        $users = $connection->fetchAll($sql);
        foreach ($users as &$user) {
            $sql = "select count(*) as `nooffollowers` from `user_follows_user` where `author_id`={$user['user_id']}";
            $user['nooffollowers'] = $connection->fetchAll($sql)[0]['nooffollowers'];
        }
        return($users);
    }

    public function getfollowing($user_id) {
        $connection = $this->doctrine->getConnection();
        $sql = "select `author_id` from `user_follows_user` where `follower_id`={$user_id}";
        $authors = $connection->fetchAll($sql);
        return $authors;
    }

    public function follow($user_id,$author_id) {
        if ($user_id==$author_id) {
            return ['status'=>1,'message'=>'You cannot follow yourself!'];
        }
        $connection = $this->doctrine->getConnection();
        $sql = "insert into `user_follows_user` (`follower_id`, `author_id`) values ($user_id,$author_id)";
        $dbresponse = $connection->exec($sql);
        return ['status'=>0,'message'=>$dbresponse];
    }

    public function unfollow($user_id,$author_id) {
        $connection = $this->doctrine->getConnection();
        $sql = "delete from `user_follows_user` where `follower_id`=$user_id and `author_id`=$author_id";
        $dbresponse = $connection->exec($sql);
        return ['status'=>0,'message'=>$dbresponse];
    }
}