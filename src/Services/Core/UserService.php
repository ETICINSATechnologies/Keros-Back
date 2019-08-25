<?php


namespace Keros\Services\Core;

use DateTime;
use Keros\DataServices\Core\UserDataService;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Core\User;
use Keros\Error\KerosException;
use Keros\Tools\Authorization\PasswordEncryption;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class UserService
{
    /**
     * @var UserDataService
     */
    private $userDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->userDataService = $container->get(UserDataService::class);
    }

    public function create(array $fields): User
    {
        $username = $this->getFreshUsername(strtolower(Validator::requiredString($fields["username"])));
        $password = Validator::requiredPassword($fields["password"]);
        $disabled = Validator::optionalBool($fields["disabled"]);
        $encryptedPassword = PasswordEncryption::encrypt($password);
        $createdAt = new DateTime("now");

        $user = new User($username, $encryptedPassword, null, $createdAt, $disabled, null);

        $this->userDataService->persist($user);

        return $user;
    }

    public function getOne(int $id): User
    {
        $id = Validator::requiredId($id);

        $user = $this->userDataService->getOne($id);
        if (!$user) {
            throw new KerosException("The user could not be found", 404);
        }
        return $user;
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->userDataService->getPage($requestParameters);
    }

    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->userDataService->getCount($requestParameters);
    }

    public function update(int $id, ?array $fields): User
    {
        $id = Validator::requiredId($id);
        $user = $this->getOne($id);

        $username = Validator::requiredString($fields["username"]);
        $user->setUsername($username);

        if (isset($fields["password"])) {
            $password = Validator::requiredPassword($fields["password"]);
            $encryptedPassword = PasswordEncryption::encrypt($password);
            $user->setPassword($encryptedPassword);
        }

        $this->userDataService->persist($user);

        return $user;
    }

    public function delete(int $id)
    {
        $id = Validator::requiredId($id);
        $user = $this->getOne($id);

        $this->userDataService->delete($user);
    }

    /**
     * @param string $username
     * @return string
     * @throws KerosException
     */
    public function getFreshUsername(string $username): string
    {
        $newUsername = $username;
        $count = 1;
        while ($this->userDataService->doesUsernameExist($newUsername)) {
            $newUsername = $username . $count;
            ++$count;
        }
        return $newUsername;
    }

}