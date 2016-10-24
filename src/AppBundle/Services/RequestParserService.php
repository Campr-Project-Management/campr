<?php

namespace AppBundle\Services;

class RequestParserService
{
    /** @var string|null */
    public $key;

    /** @var string|null */
    public $field;

    /** @var string|null */
    public $order;

    /** @var int */
    public $offset;

    /** @var int */
    public $limit;

    public function parse($params)
    {
        $this->offset = intval(($params['current'] - 1) * $params['rowCount'], 10);
        $this->limit = intval($params['rowCount'], 10);

        if (isset($params['sort'])) {
            $this->field = key($params['sort']);
            $this->order = strtoupper($params['sort']["$this->field"]);
        } else {
            $this->field = null;
            $this->order = null;
        }

        if (isset($params['searchPhrase']) && !empty($params['searchPhrase'])) {
            $this->key = $params['searchPhrase'];
        } else {
            $key = null;
        }
    }
}
