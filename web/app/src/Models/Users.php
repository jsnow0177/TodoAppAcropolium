<?php
namespace TodoApp\Models;

use Puppy\AbstractModel;
use TodoApp\ModelException;

/**
 * Class Users
 * @package TodoApp\Models
 */
class Users extends AbstractModel{

    /**
     * Возвращает данные о пользователе или null, если данных о пользователе нет
     * @param string $login
     * @return array|null
     */
    public function Get(string $login): ?array
    {
        $stmt = $this->pdo->prepare("SELECT user_id, login FROM `users` WHERE login=?");
        $res = $stmt->execute([$login]);

        if($res !== false){
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            if($user !== false && !empty($user))
                return $user;
        }

        return null;
    }

    /**
     * Возвращает данные о пользователе по его ID
     * @param int $id
     * @return array|null
     */
    public function GetById(int $id): ?array
    {
        $stmt = $this->pdo->query("SELECT user_id, login FROM `users` WHERE user_id=" . $id);
        if($stmt !== false){
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            if($user !== false && !empty($user))
                return $user;
        }

        return null;
    }

    /**
     * Добавляет нового пользователя в базу данных
     * @param string $login
     * @param string $password
     * @return array
     * @throws \RuntimeException
     * @throws ModelException
     */
    public function Add(string $login, string $password): array
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        if($hashed_password === false)
            throw new \RuntimeException("Can't hash password");

        if(!is_null($this->Get($login)))
            throw new ModelException("User with this login already exists");

        $stmt = $this->pdo->prepare("INSERT INTO `users` SET `login`=?, `pass`=?");
        $stmt->execute([$login, $hashed_password]);

        $userId = (int)$this->pdo->lastInsertId();

        return [
            'user_id' => $userId,
            'login' => $login
        ];
    }

    /**
     * Проверяет, может ли пользователь быть авторизован
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function Confirm(string $login, string $password): bool
    {
        $stmt = $this->pdo->prepare("SELECT `pass` FROM `users` WHERE `login`=?");
        $res = $stmt->execute([$login]);

        if($res !== false){
            $pass = $stmt->fetchColumn(0);
            if($pass !== false){
                return password_verify($password, $pass);
            }
        }

        return false;
    }

}