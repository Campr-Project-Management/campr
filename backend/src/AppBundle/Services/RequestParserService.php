<?php

namespace AppBundle\Services;

/**
 * Class RequestParserService
 * Service used to parse request parameters.
 */
class RequestParserService
{
    /** @var string|null */
    public $searchPhrase;

    /** @var string|null */
    public $field;

    /** @var string|null */
    public $order;

    /** @var int */
    public $offset = null;

    /** @var int */
    public $limit = null;

    /**
     * Parse sent parameters.
     *
     * @param $params
     */
    public function parse($params)
    {
        if (intval($params['rowCount']) === -1) {
            return;
        }

        $this->offset = intval(($params['current'] - 1) * $params['rowCount'], 10);
        $this->limit = intval($params['rowCount'], 10);

        if (isset($params['sort'])) {
            $this->field = key($params['sort']);
            $this->order = strtoupper($params['sort']["$this->field"]);
        } else {
            $this->field = null;
            $this->order = null;
        }

        if (!empty($params['searchPhrase'])) {
            $this->searchPhrase = $params['searchPhrase'];
        } else {
            $this->searchPhrase = null;
        }
    }
}
