<?php

namespace App;

use App\Node;
use Exception;

class ApiController
{
    public const HTTP_METHOD_NOT_ALLOWED_RESPONSE = "Method Not Allowed";
    public const HTTP_METHOD_GET = 'GET';
    public const ERROR_MSG_REQUIRED_PARAMS = 'Missing mandatory params.';
    public const ERROR_MSG_NODE_ID = 'Invalid node id.';
    public const ERROR_MSG_LANGUAGE = 'Invalid language.';
    public const ERROR_MSG_PAGE_NUMBER = 'Invalid page number requested.';
    public const ERROR_MSG_PAGE_SIZE = 'Invalid page size requested.';
    public const INVALID_VALUE = 'invalid_value';

    protected $getRequiredInput = ['node_id','language'];

    /**
     * Display listing of nodes
     *
     * @return json
     */
    public function get()
    {
        if (detectRequestMethod() != self::HTTP_METHOD_GET) {
            return httpMethodNotAllowedResponse(self::HTTP_METHOD_NOT_ALLOWED_RESPONSE);
        }

        try {
            if (!$requestInputs = validateRequestInput($this->getRequiredInput, self::HTTP_METHOD_GET)) {
                throw new Exception(self::ERROR_MSG_REQUIRED_PARAMS);
            }
            $inputs = $this->prepareInputs($requestInputs);
        } catch (Exception $e) {
            httpJsonResponse(
                'HTTP/1.0 200',
                [
                    'nodes' => [],
                    'error' => $e->getMessage()
                ]
            );
            return;
        }

        /**
         * When search_keyword is provided, restrict
         * the results to "all children nodes under ​node_id​ whose ​nodeName​ in the given ​language
         * contains ​search_keyword​ (case insensitive)".
         */
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

    /**
     * Prepare parameter for Node class
     * @param array
     * @throws Exception
     * @return array
     */
    protected function prepareInputs(array $inputs)
    {
        $inputs['node_id'] = $this->validateNodeId($inputs['node_id']);
        $inputs['language'] = $this->validateLanguage($inputs['language']);
        //Optional value, no validation needed
        $inputs['search_keyword'] = is_string($inputs['search_keyword']) ? $inputs['search_keyword'] : '';
        $inputs['page_num'] = $this->validatePageNum($inputs['page_num']);
        $inputs['page_size'] = $this->validatePageSize($inputs['page_size']);

        return $inputs;
    }

    /**
     * Validate node id parameter
     * @param number
     * @throws Exception
     * @return int
     */
    protected function validateNodeId($id)
    {
        /**
         * id must be an integer
         */
        $id = preg_match("/^[1-9]\d*$/", $id) ? intval($id) : self::INVALID_VALUE;

        if ($id == self::INVALID_VALUE) {
            throw new Exception(self::ERROR_MSG_NODE_ID);
        }

        return $id;
    }

    /**
     * Validate language parameter
     * @param string
     * @throws Exception
     * @return string
     */
    protected function validateLanguage($language)
    {
        /**
         * Possible values: "english", "italian".
         */
        $language = preg_match("/^english$|^italian$/", $language) ? $language : '';

        if (strlen($language) == 0) {
            throw new Exception(self::ERROR_MSG_LANGUAGE);
        }

        return $language;
    }

    /**
     * Validate page number parameter
     * @param int
     * @throws Exception
     * @return int
     */
    protected function validatePageNum($number)
    {
        /**
         * If number is null, set it to “0”.
         * If number is not numeric throw exception
         */
        $number = isset($number)
        ? (preg_match("/^[0-9]\d*$/", $number) ? intval($number) : self::INVALID_VALUE) : 0;

        if ($number === self::INVALID_VALUE) {
            throw new Exception(self::ERROR_MSG_PAGE_NUMBER);
        }

        return $number;
    }

    /**
     * Validate page size parameter
     * @param int
     * @throws Exception
     * @return int
     */
    protected function validatePageSize($size)
    {
        /**
         * size value must range from 0 to 1000.
         * If not provided, defaults to “100”.
         */
        $size = isset($size)
        ? (preg_match("/^(0|[1-9][0-9]{0,2}|1000)$/", $size) ? intval($size)
        : self::INVALID_VALUE)
        : 100;

        if ($size === self::INVALID_VALUE) {
            throw new Exception(self::ERROR_MSG_PAGE_SIZE);
        }

        return $size;
    }
}
