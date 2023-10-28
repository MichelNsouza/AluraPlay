<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\FlashMessageTrait;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginController implements RequestHandlerInterface
{
    use FlashMessageTrait;

    private \PDO $pdo;

    public function __construct()
    {
        $host = 'dpg-ckc82bect0pc73afvsug-a.oregon-postgres.render.com'; // Host do banco de dados PostgreSQL
        $dbname = 'testebd'; // Nome do banco de dados PostgreSQL
        $username = 'testebd_user'; // Nome de usuário do PostgreSQL
        $password = 'PASSWORD'; // Senha do PostgreSQL

        try {
            $this->pdo = new \PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
        } catch (\PDOException $e) {
            // Lidar com erros de conexão aqui, se necessário
            echo "Erro de conexão: " . $e->getMessage();
            die();
        }
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        $sql = 'SELECT * FROM users WHERE email = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $email);
        $statement->execute();

        $userData = $statement->fetch(\PDO::FETCH_ASSOC);
        $correctPassword = password_verify($password, $userData['password'] ?? '');

        if (!$correctPassword) {
            $this->addErrorMessage('Usuário ou senha inválidos');
            return new Response(302, ['Location' => '/login']);
        }

        if (password_needs_rehash($userData['password'], PASSWORD_ARGON2ID)) {
            $statement = $this->pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            $statement->bindValue(1, password_hash($password, PASSWORD_ARGON2ID));
            $statement->bindValue(2, $userData['id']);
            $statement->execute();
        }

        $_SESSION['logado'] = true;
        return new Response(302, ['Location' => '/']);
    }
}
