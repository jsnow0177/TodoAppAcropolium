<?php
namespace TodoApp\Models;

use Puppy\AbstractModel;
use TodoApp\ModelException;

/**
 * Class Tasks
 * @package TodoApp\Models
 */
class Tasks extends AbstractModel{

    /**
     * @param int $user_id
     * @param string $title
     * @param string $body
     * @param string $status
     * @return array
     * @throws ModelException
     */
    public function Create(int $user_id, string $title, string $body = '', string $status = 'new'): array
    {
        $created = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare("INSERT INTO `tasks` SET `user_id`=?, `title`=?, `body`=?, `status`=?, `created`=?");
        $res = $stmt->execute([$user_id, $title, $body, $status, $created]);

        if($res === false)
            throw new ModelException('Не удалось создать задачу');

        $task_id = (int)$this->pdo->lastInsertId();

        return [
            'user_id' => $user_id,
            'task_id' => $task_id,
            'title' => htmlspecialchars($title, ENT_QUOTES),
            'body' => htmlspecialchars($body, ENT_QUOTES),
            'status' => $status
        ];
    }

    /**
     * @param int $user_id
     * @param int $task_id
     * @return array|null
     */
    public function Get(int $user_id, int $task_id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `tasks` WHERE `user_id`=? AND `task_id`=?");
        $res = $stmt->execute([$user_id, $task_id]);

        if($res !== false){
            $task = $stmt->fetch(\PDO::FETCH_ASSOC);
            if($task !== false){
                $task['title'] = htmlspecialchars($task['title'], ENT_QUOTES);
                $task['body'] = htmlspecialchars($task['body'], ENT_QUOTES);
                return $task;
            }
        }

        return null;
    }

    /**
     * @param int $user_id
     * @param int $task_id
     * @param string $title
     * @param string $body
     * @param string $status
     * @return array
     * @throws ModelException
     */
    public function Update(int $user_id, int $task_id, string $title, string $body, string $status): array{
        $stmt = $this->pdo->prepare("UPDATE `tasks` SET `title`=?, `body`=?, `status`=? WHERE `user_id`=? AND `task_id`=?");
        $res = $stmt->execute([$title, $body, $status, $user_id, $task_id]);

        if($res === false)
            throw new ModelException("При обновлении задачи что-то пошло не так");

        return [
            'user_id' => $user_id,
            'task_id' => $task_id,
            'title' => htmlspecialchars($title, ENT_QUOTES),
            'body' => htmlspecialchars($body, ENT_QUOTES),
            'status' => $status
        ];
    }

    /**
     * @param int $user_id
     * @param int $task_id
     * @return bool
     */
    public function Delete(int $user_id, int $task_id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM `tasks` WHERE `user_id`=? AND `task_id`=?");
        $res = $stmt->execute([$user_id, $task_id]);

        return $res;
    }

}