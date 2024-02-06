<?php
/* Made by Unam Sanctam https://github.com/UnamSanctam */
class SSP
{
    static function data_output($columns, $data) {
        $escapedData = array_map(function ($row) {
            return array_map(function ($value) {
                return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8', false);
            }, $row);
        }, $data);

        return array_map(function ($escapedRow) use ($columns) {
            $result = [];
            $visibleIndex = 0;

            foreach ($columns as $column) {
                if (isset($column["formatter"])) {
                    $columnValue = empty($column['db_column']) ?
                        $column["formatter"]($escapedRow) :
                        $column["formatter"]($escapedRow[$column['db_column']], $escapedRow);
                } else {
                    $columnValue = empty($column['db_column']) ? "" : $escapedRow[$column['db_column']];
                }

                if (!isset($column['hidden']) || !$column['hidden']) {
                    $result[$visibleIndex++] = $columnValue;
                }
            }

            return $result;
        }, $escapedData);
    }

    static function limit($request, $columns) {
        return isset($request["start"]) && $request["length"] != -1 ? "LIMIT " . intval($request["start"]) . ", " . intval($request["length"]) : "";
    }

    static function order($request, $columns) {
        if (!isset($request["order"]) || !count($request["order"])) return "";

        $dtColumns = self::pluck($columns, "dt");
        $orderBy = array_map(function ($order) use ($request, $dtColumns, $columns) {
            $columnIdx = array_search($request["columns"][$order["column"]]["data"], $dtColumns);
            $column = $columns[$columnIdx];

            return $request["columns"][$order["column"]]["orderable"] == "true" ? "`" . $column['db_column'] . "` " . ($order["dir"] === "asc" ? "ASC" : "DESC") : "";
        }, $request["order"]);

        return "ORDER BY " . implode(", ", array_filter($orderBy));
    }

    static function filter($request, $columns, &$bindings) {
        $globalSearch = $columnSearch = [];
        $dtColumns = self::pluck($columns, "dt");

        if (!empty($request["search"]["value"])) {
            $str = $request["search"]["value"];
            foreach ($request["columns"] as $requestColumn) {
                if ($requestColumn["searchable"] == "true") {
                    $columnIdx = array_search($requestColumn["data"], $dtColumns);
                    $column = $columns[$columnIdx];

                    if (!empty($column['db_column'])) {
                        $binding = self::bind($bindings, "%" . $str . "%", PDO::PARAM_STR);
                        $globalSearch[] = "`" . $column['db_column'] . "` LIKE " . $binding;
                    }
                }
            }
        }

        foreach ($request["columns"] as $requestColumn) {
            if ($requestColumn["searchable"] == "true" && $requestColumn["search"]["value"] != "") {
                $columnIdx = array_search($requestColumn["data"], $dtColumns);
                $column = $columns[$columnIdx];

                if (!empty($column['db_column'])) {
                    $binding = self::bind($bindings, "%" . $requestColumn["search"]["value"] . "%", PDO::PARAM_STR);
                    $columnSearch[] = "`" . $column['db_column'] . "` LIKE " . $binding;
                }
            }
        }

        $where = "";
        if (count($globalSearch)) $where = "(" . implode(" OR ", $globalSearch) . ")";
        if (count($columnSearch)) $where .= ($where ? " AND " : "") . implode(" AND ", $columnSearch);

        return $where ? "WHERE " . $where : "";
    }

    static function simple($request, $db, $table, $primaryKey, $columns, $customWhere = []) {
        $visibleColumns = array_map(function ($column, $index) {
            return empty($column['hidden']) ? array_merge($column, ['dt' => $index]) : null;
        }, $columns, array_keys($columns));
        $visibleColumns = array_filter($visibleColumns);


        $bindings = [];
        $limit = self::limit($request, $visibleColumns);
        $order = self::order($request, $visibleColumns);
        $where = self::filter($request, $visibleColumns, $bindings);

        if (!empty($customWhere)) {
            $customWhereStr = implode(' AND ', array_map(function($cond) use (&$bindings) {
                $binding = self::bind($bindings, $cond['db_value'], PDO::PARAM_STR);
                return "`{$cond['db_column']}` {$cond['db_operation']} {$binding}";
            }, $customWhere));

            $where = ($where ? $where . " AND " : "WHERE ") . $customWhereStr;
        }

        $data = self::sql_exec($db, $bindings, "SELECT `" . implode("`, `", self::pluck($columns, 'db_column')) . "` FROM `$table` $where $order $limit");

        $resFilterLength = self::sql_exec($db, $bindings, "SELECT COUNT(`{$primaryKey}`) FROM `$table` $where");
        $recordsFiltered = $resFilterLength[0][0];

        $resTotalLength = self::sql_exec($db, "SELECT COUNT(`{$primaryKey}`) FROM `$table`");
        $recordsTotal = $resTotalLength[0][0];

        return [
            "draw" => isset($request["draw"]) ? intval($request["draw"]) : 0,
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => self::data_output($columns, $data),
        ];
    }

    static function sql_exec($db, $bindings, $sql = null) {
        if ($sql === null) {
            $sql = $bindings;
        }

        $stmt = $db->prepare($sql);

        if (is_array($bindings)) {
            foreach ($bindings as $binding) {
                $stmt->bindValue($binding["key"], $binding["val"], $binding["type"]);
            }
        }

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            self::fatal("An SQL error occurred: " . $e->getMessage());
        }

        return $stmt->fetchAll(PDO::FETCH_BOTH);
    }

    static function fatal($msg) {
        echo json_encode(["error" => $msg]);
        exit(0);
    }

    static function bind(&$a, $val, $type) {
        $key = ":binding_" . count($a);
        $a[] = ["key" => $key, "val" => $val, "type" => $type];
        return $key;
    }

    static function pluck($a, $prop) {
        return array_map(function ($item) use ($prop) {
            return $item[$prop] ?? null;
        }, $a);
    }
}