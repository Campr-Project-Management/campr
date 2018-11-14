<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class CommentControllerTest extends BaseController
{
    /**
     * @dataProvider getDataForEditAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testEditAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $comment = new Comment();
        $comment->setBody('Comment body');
        $author = $this->em->getRepository(User::class)->find(1);
        $comment->setAuthor($author);
        $this->em->persist($comment);
        $this->em->flush();

        $this->client->request(
            'PATCH',
            sprintf('/api/comments/%d', $comment->getId()),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $actual = $this->getClientJsonResponse();

        if ($isResponseSuccessful) {
            $responseContent['createdAt'] = $actual['createdAt'];
            $responseContent['updatedAt'] = $actual['updatedAt'];
            $responseContent['id'] = $comment->getId();
        }

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, $actual);
    }

    /**
     * @return array
     */
    public function getDataForEditAction()
    {
        return [
            [
                [
                    'body' => 'Brand new body',
                    'author' => 1,
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'id' => 1,
                    'body' => 'Brand new body',
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => '2017-01-01 13:00:00',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForDeleteAction()
     *
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     */
    public function testDeleteAction(
        $isResponseSuccessful,
        $responseStatusCode
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        /** @var User $author */
        $author = $this->em->getRepository(User::class)->find(1);

        $comment = new Comment();
        $comment->setBody('Comment body');
        $comment->setAuthor($author);
        $this->em->persist($comment);
        $this->em->flush();

        $this->client->request(
            'DELETE',
            sprintf('/api/comments/%d', $comment->getId()),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            ''
        );
        $response = $this->client->getResponse();

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
    }

    /**
     * @return array
     */
    public function getDataForDeleteAction()
    {
        return [
            [
                true,
                Response::HTTP_NO_CONTENT,
            ],
        ];
    }
}
