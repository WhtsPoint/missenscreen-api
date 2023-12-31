<?php

namespace App\Controllers;

use App\Exceptions\SubscriptionAlreadyExistsException;
use App\Exceptions\SubscriptionNotFoundException;
use App\Interfaces\AuthenticationInterface;
use App\Interfaces\SubscriptionRepositoryInterface;
use App\Models\Subscription;
use App\Utils\Email;
use App\Utils\Pagination;
use App\Utils\Validator;
use Leaf\Controller;
use Lib\Uuid;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionRepositoryInterface $repository,
        protected Validator $validator,
        protected AuthenticationInterface $authentication
    ) {
        parent::__construct();
    }

    public function create(): void
    {
        $body = $this->request->body();

        $this->validator->validate([
            'email' => ['required', 'regex:"' . Email::REGEX . '"']
        ], $body);

        try {
            $this->repository->create(new Subscription(
                $id = Uuid::v4(),
                new Email($body['email'])
            ));

            $this->response->json(['id' => $id]);
        } catch (SubscriptionAlreadyExistsException) {
            $this->response->json(['error' => 'This email is already subscribed'], 400);
        }
    }

    public function getAll(): void
    {
        $body = $this->request->body();

        $this->validator->validate([
            'page' => ['required', 'regex:"^[1-9][0-9]*$"'],
            'count' => ['required', 'regex:"^[1-9][0-9]*$"']
        ], $body);

        $response = $this->repository->getAll(new Pagination(
            (int) $body['page'],
            (int) $body['count']
        ));
        $this->response->json($response);
    }

    public function deleteById(string $id): void
    {
        try {
            $this->repository->deleteById($id);
            $this->response->json(['success' => true]);
        } catch (SubscriptionNotFoundException) {
            $this->response->json(['error' => 'Subscription not found'], 404);
        }
    }
}
