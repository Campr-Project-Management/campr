<?php

namespace AppBundle\Utils;

use Jsvrcek\ICS\CalendarExport;
use Jsvrcek\ICS\CalendarStream;
use Jsvrcek\ICS\Utility\Formatter;
use Symfony\Component\HttpFoundation\Response;

class IcsResponse extends Response
{
    protected $data;

    protected $filename;

    public function __construct($data = [], $filename = 'data.ics', $status = Response::HTTP_OK, $headers = [])
    {
        parent::__construct('', $status, $headers);

        $this->setData($data);
        $this->filename = $filename;
    }

    public function setData(array $data)
    {
        $calendarExport = new CalendarExport(new CalendarStream(), new Formatter());
        foreach ($data as $calData) {
            $calendarExport->addCalendar($calData);
        }

        $this->data .= $calendarExport->getStream();

        return $this->update();
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this->update();
    }

    protected function update()
    {
        $this->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $this->filename));
        if (!$this->headers->has('Content-Type')) {
            $this->headers->set('Content-Type', 'text/calendar');
        }

        return $this->setContent($this->data);
    }
}
