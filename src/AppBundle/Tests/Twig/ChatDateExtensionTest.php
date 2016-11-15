<?php

namespace AppBundle\Tests\Twig;

use AppBundle\Twig\ChatDateExtension;

class ChatDateExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testFormatChatDate()
    {
        $chatDateExt = new ChatDateExtension();

        $now = new \DateTime();
        $this->assertEquals($now->format('H:i'), $chatDateExt->formatChatDate($this->getDataForTestFormatChatDate()));
        $this->assertEquals('01/01/2016 15:22', $chatDateExt->formatChatDate($this->getDataForTestFormatChatDate('2016-01-01 15:22:01')));
        $this->assertEquals('01/01/2022 18:00', $chatDateExt->formatChatDate($this->getDataForTestFormatChatDate('2022-01-01 18:00:00')));
    }

    /**
     * @return string $value
     */
    public function getDataForTestFormatChatDate($value = null)
    {
        return $value ? new \DateTime($value) : new \DateTime();
    }
}
