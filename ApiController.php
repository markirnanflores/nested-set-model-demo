<?php

namespace App;

use App\Node;
use Exception;

class ApiController
{
    public const HTTP_METHOD_GET = 'GET';
    public const ERROR_MSG_REQUIRED_PARAMS = 'Missing mandatory params.';
    public const ERROR_MSG_NODE_ID = 'Invalid node id.';
    public const ERROR_MSG_LANGUAGE = 'Invalid language.';
    public const ERROR_MSG_PAGE_NUMBER = 'Invalid page number requested.';
    public const ERROR_MSG_PAGE_SIZE = 'Invalid page size requested.';

    public function get()
    {
        $method = self::HTTP_METHOD_GET;
        $requiredInput = ['node_id','language'];
        $validLanguages = ['english','italian'];
        $obj = ['nodes' => array()];

        if (detectRequestMethod() != $method) {
            header("HTTP/1.0 405 Method Not Allowed");
            return;
        }

        try {
            if (!$requestInputs = validateRequestInput($requiredInput, $method)) {
                throw new Exception(self::ERROR_MSG_REQUIRED_PARAMS);
            }
            $inputs = $this->prepareInputs($requestInputs);
        } catch (Exception $e) {
            $obj['error'] = $e->getMessage();
            httpJsonResponse(
                'HTTP/1.0 200',
                $obj
            );
            return;
        }

        if (strlen($inputs['search_keyword']) > 0) {
            $obj['nodes'] = Node::findFiltered(
                $inputs['node_id'],
                $inputs['language'],
                $inputs['search_keyword'],
                $inputs['page_size'],
                $inputs['page_num']
            );
        } else {
            $obj['nodes'] = Node::findAll(
                $inputs['node_id'],
                $inputs['language'],
                $inputs['page_size'],
                $inputs['page_num']
            );
        }

        return httpJsonResponse(
            'HTTP/1.0 200',
            $obj
        );
    }

    protected function prepareInputs($inputs)
    {
        $inputs['node_id'] = preg_match("/^[1-9]\d*$/", $inputs['node_id']) ? intval($inputs['node_id']) : 0;

        if ($inputs['node_id'] == 0) {
            throw new Exception(self::ERROR_MSG_NODE_ID);
        }

        $inputs['language'] = preg_match("/english|italian/", $inputs['language']) ? $inputs['language'] : '';

        if ($inputs['node_id'] == 0) {
            throw new Exception(self::ERROR_MSG_NODE_LANGUAGE);
        }

        $inputs['search_keyword'] = is_string($inputs['search_keyword']) ? $inputs['search_keyword'] : '';

        $inputs['page_num'] = isset($inputs['page_num'])
        ? (preg_match("/^[0-9]\d*$/", $inputs['page_num']) ? intval($inputs['page_num']) : 'error') : 0;

        if ($inputs['page_num'] === 'error') {
            throw new Exception(self::ERROR_MSG_PAGE_NUMBER);
        }

        $inputs['page_size'] = isset($inputs['page_size'])
        ? (preg_match("/^(0|[1-9][0-9]{0,2}|1000)$/", $inputs['page_size']) ? intval($inputs['page_size']) : 'error')
        : 100;

        if ($inputs['page_size'] === 'error') {
            throw new Exception(self::ERROR_MSG_PAGE_SIZE);
        }

        return $inputs;
    }
}
