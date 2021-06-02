<?php

namespace App;

use App\Query;

class Node
{
    public const PARAMETER_ID_NODE = 'idNode';
    public const PARAMETER_LANGUAGE = 'language';
    public const PARAMETER_FILTER = 'filter';

    /**
     * Retrieve all nodes child of specific node
     * @return array
     */
    public static function findAll($idNode, $language, $page_size = 100, $page_num = 0)
    {
        $query = self::queryBuilder($page_size, $page_num);

        $parameters = [
            self::PARAMETER_ID_NODE => $idNode,
            self::PARAMETER_LANGUAGE => $language
        ];

        return Query::select(Database::connection(';charset=utf8'), $query, $parameters);
    }

    /**
     * Retrieve all nodes child of specific node with filter parameter
     * @return array
     */
    public static function findFiltered($idNode, $language, $filter, $page_size = 100, $page_num = 0)
    {
        $query = self::queryBuilder($page_size, $page_num, true);

        $parameters = [
            self::PARAMETER_ID_NODE => $idNode,
            self::PARAMETER_LANGUAGE => $language,
            self::PARAMETER_FILTER => '%' . $filter . '%'
        ];

        return Query::select(Database::connection(';charset=utf8'), $query, $parameters);
    }

    /**
     * Compose the query string
     * @return string
     */
    private function queryBuilder($page_size = 100, $page_num = 0, bool $withFilter = false)
    {
        //phpcs:ignore
        $query = "SELECT node_tree.idNode as node_id, node_tree_names.nodeName as name, node_tree.children as children_count
        FROM (
        SELECT node.idNode, node.iLeft, node.iRight, node.level, (
        SELECT COUNT(sub_node.idNode) 
        FROM node_tree as sub_node, node_tree as sub_parent
        WHERE
            sub_node.level = sub_parent.level + 1
            AND sub_node.iLeft > sub_parent.iLeft
            AND sub_node.iRight < sub_parent.iRight
            AND sub_parent.idNode = node.idNode
        ) as children
        FROM node_tree as node, node_tree as parent
        WHERE
            node.level = parent.level + 1
            AND node.iLeft > parent.iLeft
            AND node.iRight < parent.iRight
            AND parent.idNode = :" . self::PARAMETER_ID_NODE . "
        ) as node_tree,
        node_tree_names
        WHERE node_tree.idNode = node_tree_names.idNode
        AND node_tree_names.language = :" . self::PARAMETER_LANGUAGE;

        if ($withFilter) {
            //phpcs:ignore
            $query = "SELECT sub_nodes.node_id, sub_nodes.name, sub_nodes.children_count
            FROM (" . $query . ") as sub_nodes
            WHERE sub_nodes.name LIKE :" . self::PARAMETER_FILTER;
        }

        return $query . " LIMIT " . $page_size . " OFFSET " . ($page_num * $page_size) . ";";
    }
}
