<?php

namespace AppBundle\Tests\Services;

use AppBundle\Entity\ChatRoom;
use AppBundle\Entity\Message;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Repository\MessageRepository;
use AppBundle\Services\ChatService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Tests\KernelTest;

class ChatServiceTest extends KernelTest
{
    private $chatService;

    private $em;

    protected function setUp()
    {
        $this->em = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->chatService = new ChatService($this->em);
    }

    protected function tearDown()
    {
        parent::tearDown();

        unset($this->em);
        unset($this->chatService);
    }

    /**
     * @dataProvider getDataForTestCreateProjectChatRoom
     *
     * @param string $name
     */
    public function testCreateProjectChatRoom($name)
    {
        $project = $this->getMock(Project::class);
        $chatRoom = $this->chatService->createProjectChatRoom($project, $name);
        $this->assertInstanceOf(ChatRoom::class, $chatRoom);
        $this->assertEquals($name, $chatRoom->getName());
        $this->assertSame($project, $chatRoom->getProject());
    }

    /**
     * @return array
     */
    public function getDataForTestCreateProjectChatRoom()
    {
        return [
            ['ChatRoom'],
        ];
    }

    /**
     * @dataProvider getDataForTestCreateMessage
     *
     * @param string $body
     */
    public function testCreateMessage($body)
    {
        $project = $this->getMock(Project::class);
        $user = $this->getMock(User::class);
        $chatRoom = $this->getMock(ChatRoom::class);
        $message = $this->chatService->createMessage($project, $body, $user, $chatRoom);
        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals($body, $message->getBody());
        $this->assertSame($chatRoom, $message->getChatRoom());
    }

    /**
     * @return array
     */
    public function getDataForTestCreateMessage()
    {
        return [
            ['Message Body'],
        ];
    }

    /**
     * @dataProvider getDataForTestGetProjectChatList
     *
     * @param string $body
     */
    public function testGetProjectChatList(array $chatRooms, array $messages, array $expected)
    {
        $project = $this->getMock(Project::class);
        $user = $this->getMock(User::class);
        $project
            ->expects($this->once())
            ->method('getChatRooms')
            ->willReturn($chatRooms)
        ;
        $messageRepository = $this
            ->getMockBuilder(MessageRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->em->expects($this->once())
            ->method('getRepository')
            ->with(Message::class)
            ->willReturn($messageRepository)
        ;
        $messageRepository->expects($this->once())
            ->method('findPrivateByUserAndProject')
            ->with($user, $project)
            ->willReturn($messages)
        ;

        $this->assertEquals($expected, $this->chatService->getProjectChatList($project, $user));
    }

    /**
     * @return array
     */
    public function getDataForTestGetProjectChatList()
    {
        $time1 = (new \DateTime('2016-01-01'))->setTime(18, 20, 0);
        $time2 = (new \DateTime('2016-01-01'))->setTime(20, 00, 0);
        $time3 = (new \DateTime('2016-01-01'))->setTime(11, 30, 0);

        return [
            [
                [
                    (new ChatRoom())
                        ->setName('chat1')
                        ->addMessage(
                            (new Message())
                                ->setBody('chat msg')
                                ->setCreatedAt($time1)
                        ),
                ],
                [
                    (new Message())
                        ->setFrom(new User())
                        ->setBody('msg')
                        ->setCreatedAt($time1),
                    (new Message())
                        ->setFrom(new User())
                        ->setBody('msg2')
                        ->setCreatedAt($time2)
                        ->setChatKey('1-2'),
                    (new Message())
                        ->setFrom(new User())
                        ->setBody('msg3')
                        ->setCreatedAt($time3),
                ],
                [
                    [
                        'id' => null,
                        'name' => '@',
                        'message' => 'msg2',
                        'time' => $time2,
                        'chat_key' => '1-2',
                    ],
                    [
                        'id' => null,
                        'name' => 'chat1',
                        'message' => 'chat msg',
                        'time' => $time1,
                    ],
                    [
                        'id' => null,
                        'name' => '@',
                        'message' => 'msg',
                        'time' => $time1,
                        'chat_key' => null,
                    ],
                    [
                        'id' => null,
                        'name' => '@',
                        'message' => 'msg3',
                        'time' => $time3,
                        'chat_key' => null,
                    ],
                ],
            ],
        ];
    }
}
