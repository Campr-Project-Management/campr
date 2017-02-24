<?php

namespace AppBundle\Utils;

use Symfony\Component\HttpFoundation\Response;

class CsvResponse extends Response
{
    protected $data;

    protected $filename;

    public function __construct($data = [], $filename = 'data.csv', $status = Response::HTTP_OK, $headers = [])
    {
        parent::__construct('', $status, $headers);

        $this->setData($data);
        $this->filename = $filename;
    }

    public function setData(array $data)
    {
        $output = fopen('php://temp', 'r+');

        foreach ($data as $row) {
            fputcsv($output, $row, ',');
        }

        rewind($output);
        $this->data = '';

        while ($line = fgets($output)) {
            $this->data .= $line;
        }

        $this->data .= fgets($output);

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
            $this->headers->set('Content-Type', 'text/csv');
        }

        return $this->setContent($this->data);
    }
}
