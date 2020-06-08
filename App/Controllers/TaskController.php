<?php

namespace App\Controllers;

use App\View\View;
use App\Models\User;
use App\Models\Task;
use App\Helpers\Pagination;
use Respect\Validation\Validator as v;

class TaskController extends \Core\BaseController {

    const MAX_PER_PAGE = 3;
    protected $pagination;

    public function __construct()
    {
        $this->pagination = new Pagination( );
    }

    public function index()
    {
        $params = $_GET;
        $currentPage = $params['page'] ?? 1;
        $tasks = Task::getAll($params);
        $flash = $flash = $this->getFlashMessages();
        $pagination = $this->pagination->render($tasks, $params);

        View::render('tasks.html', [
            'tasks' => array_slice($tasks, ($currentPage - 1) * self::MAX_PER_PAGE, self::MAX_PER_PAGE),
            'pagination' => $pagination,
            'currentPagepage' => isset($params['page']) ? $params['page'] : 1,
            'sort' => isset($params['sort']) ? explode('|', $params['sort']) : ['', ''],
            'flash' => $flash,
        ]);
    }

    public function store() {
        $task = [
            'status' => 'new',
            'author_name' => $_POST['author_name'],
            'email' => $_POST['email'],
            'description' => $_POST['description'],
        ];

        if (!v::notBlank()->validate($task['author_name']) 
            || !v::notBlank()->validate($task['email'])
            || !v::notBlank()->validate($task['description'])) {
            $_SESSION["flash"] = ["type" => "danger", "message" => "Необходимо заполнить все поля!"];
        } elseif (!v::email()->validate($task['email'])) {
            $_SESSION["flash"] = ["type" => "danger", "message" => "Необходимо ввести валидный email!"];
        } else {
            Task::create($task);
            $_SESSION["flash"] = ["type" => "success", "message" => "Задача создана успешно!"];
        }

        header('Location: ' . '/');
        exit();
    }

    public function edit($id) {
        // Check if the user is admin
        if (!isset($_SESSION["logged_in"])) {
            echo 'you are not allowed';
            return;
        }
        $task = Task::getById($id);

        View::render('edit.html', [
            'task' => $task,
        ]);
    }

    public function update($id) {
        // Check if the user is admin
        if (!isset($_SESSION["logged_in"])) {
            $_SESSION["flash"] = ["type" => "danger", "message" => "Нет прав для выполнения действия!"];
            header('Location: ' . '/');
            exit();
        }

        $task = Task::getById($id);

        $newTask = [
            'id' => $id,
            'status' => $_POST['status'] ? 'complete' : 'new',
            'description' => $_POST['description'],
            'edited' => ($task['description'] == $_POST['description']) ? 0 : 1,
        ];

        Task::update($newTask);
        $_SESSION["flash"] = ["type" => "success", "message" => "Задача обновлена успешно!"];

        header('Location: ' . '/');
        exit();
    }
}
