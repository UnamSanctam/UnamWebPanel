<?php

/*
 * Helper functions for building a DataTables server-side processing SQL query
 *
 * The static functions in this class are just helper functions to help build
 * the SQL used in the DataTables demo server-side processing scripts. These
 * functions obviously do not represent all that can be done with server-side
 * processing, they are intentionally simple to show how it works. More complex
 * server-side processing operations will likely require a custom script.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 * @editedby (SilverX) Chris R. McLeod
 *
 * Custom version by Unam Sanctam https://github.com/UnamSanctam
 *
 */

class SSP {
    /**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */
    private static function data_output ( $options, $data ) {
        $out = array();
        $columns = $options['columns'];
        for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
            $row = array();
            $cur = 0;
            for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                $column = $columns[$j];
                // Is there a formatter?
                if(isset($column['hidden']) && $column['hidden']){
                    continue;
                }
                else if (isset( $column['formatting'] ) ) {
                    $row[$cur] = $column['formatting']( $data[$i][ self::column_name_out($column) ], $data[$i] );
                }
                else {
                    $row[$cur] = $data[$i][ self::column_name_out($column) ];
                }
                $cur++;
            }
            $out[] = $row;
        }
        return $out;
    }

    /**
     * Perform the SQL queries needed for an server-side processing requested,
     * utilising the helper functions of this class, limit(), order() and
     * filter() among others. The returned array is ready to be encoded as JSON
     * in response to an SSP request, or can be modified if needed before
     * sending back to the client.
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array|PDO $conn PDO connection resource or connection parameters array
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @return array          Server-side processing response array
     */
    static function process ($conn, $request, $options ) {
        $bindings = array();

        if (!isset($options['db_alias']))
            $options['db_alias'] = $options['db_table'][0];

        $optionsE = $options;
        foreach($options['columns'] as $key=>$value){
            if(isset($value['hidden']) && $value['hidden']){
                unset($optionsE['columns'][$key]);
                $optionsE['columns'] = array_values($optionsE['columns']);
            }
        }

        // Build the SQL query string from the request
        $limitSql = self::limit( $request );
        $orderSql = self::order( $request, $optionsE );
        $whereSql = self::filter( $request, $optionsE, $bindings );
        $joinSql  = self::table_join( $optionsE );

        $whereAllSql = '';

        if (isset($options['whereResult'])) {
            $optWhere = self::where_options( $options['whereResult'], $options['db_alias'], $bindings );

            $whereSql = $whereSql ?
                $whereSql .' AND '.$optWhere :
                'WHERE '.$optWhere;
        }

        if (isset($options['db_where'])) {
            $optWhere = self::where_options( $options['db_where'], $options['db_alias'], $bindings );

            $whereSql = $whereSql ?
                $whereSql .' AND '.$optWhere :
                'WHERE '.$optWhere;

            $whereAllSql = 'WHERE '.$optWhere;
        }

        $query = "SELECT ".implode(", ", self::column_names( $options ))."
            FROM `{$options['db_table']}` {$options['db_alias']}
            $joinSql
            $whereSql
            $orderSql
            $limitSql";

        // Main query to actually get the data
        $data = self::sql_exec($conn, $bindings, $query);

        // Data set length after filtering
        $resFilterLength = self::sql_exec($conn, $bindings,
            "SELECT COUNT({$options['db_alias']}.`{$options['db_primary_key']}`)
             FROM `{$options['db_table']}` {$options['db_alias']}
             $joinSql
             $whereSql"
        );
        $recordsFiltered = $resFilterLength[0][0];

        // Total data set length
        $resTotalLength = self::sql_exec($conn, $bindings,
            "SELECT COUNT({$options['db_alias']}.`{$options['db_primary_key']}`)
             FROM `{$options['db_table']}` {$options['db_alias']}
             $joinSql
             $whereSql"
        );
        $recordsTotal = $resTotalLength[0][0];

        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request['draw'] ) ? intval( $request['draw'] ) : 0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => self::data_output( $options, $data )
        );
    }

    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL limit clause
     */
    private static function limit ( $request ) {
        $limit = '';

        if ( isset($request['start']) && $request['length'] != -1 ) {
            $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
        }

        return $limit;
    }


    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL order by clause
     */
    private static function order ( $request, $options ) {
        $order = '';
        $columns = $options['columns'];

        if ( isset($request['order']) && count($request['order']) ) {
            $orderBy = array();
            for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];
                $column = $columns[ $columnIdx ];

                if ( $requestColumn['orderable'] == 'true' ) {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';

                    $orderBy[] = self::column_name_ref($column, $options['db_alias']).' '.$dir;
                }
            }

            $order = 'ORDER BY '.implode(', ', $orderBy);
        }

        return $order;
    }


    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for PDO bindings, used in the
     *    sql_exec() function
     *  @return string SQL where clause
     */
    private static function filter ( $request, $options, &$bindings ) {
        $globalSearch = array();
        $columnSearch = array();

        $columns = $options['columns'];

        if ( isset($request['search']) && $request['search']['value'] != '' ) {
            $str = $request['search']['value'];

            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $columns );
                if(!$columnIdx){
                    $columnIdx = $i;
                }
                $column = $columns[ $columnIdx ];

                if ( $requestColumn['searchable'] == 'true') {
                    $binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                    if(isset($column['db_command'])){
                        $columnName = "({$column['db_command']['db_command']})";
                    }else{
                        $columnName = self::column_name_ref($column, $options['db_alias']);
                    }
                    $globalSearch[] = $columnName." LIKE ".$binding;
                }
            }
        }

        // Individual column filtering
        if ( isset( $request['columns'] ) ) {
            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $columns );
                $column = $columns[ $columnIdx ];

                $str = $requestColumn['search']['value'];

                if ( $requestColumn['searchable'] == 'true' &&
                    $str != '') {
                    $binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                    if(isset($column['db_command'])){
                        $columnName = "({$column['db_command']['db_command']})";
                    }else{
                        $columnName = self::column_name_ref($column, $options['db_alias']);
                    }
                    $columnSearch[] = $columnName." LIKE ".$binding;
                }
            }
        }

        // Combine the filters into a single string
        $where = '';

        if ( count( $globalSearch ) ) {
            $where = '('.implode(' OR ', $globalSearch).')';
        }

        if ( count( $columnSearch ) ) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where .' AND '. implode(' AND ', $columnSearch);
        }

        if ( $where !== '' ) {
            $where = 'WHERE '.$where;
        }

        return $where;
    }

    /**
     * Retrieves additional WHERE parameters from the supplied DataTable options.
     * Used to globally filter results by fields not supplied by a DataTable request.
     *  @param  array  $where_opts  The options to build the clause from. Two types exist, "where" and "whereResult".
     *  @param  string $tableAlias  The alias of the table in 'FROM' statement.
     *  @return string              WHERE clause SQL to use in select query.
     */
    private static function where_options ( $where_opts, $tableAlias, &$bindings ) {
        $wheres = [];
        foreach($where_opts as $i => $where) {
            $alias = isset($where['db_alias']) ? $where['db_alias'] : $tableAlias;
            $binding = self::bind($bindings, $where['db_value']);

            $wheres[] = "$alias.`{$where['db_column']}` {$where['db_operation']} $binding";
        }

        return implode(' AND ', $wheres);
    }

    /**
     * Retrieves a column name as it would appear in a select statement.
     * This includes any "as" parameters applied to the column.
     *  @param  array  $options     The column to retrieve information from.
     *  @param  string $tableAlias  The alias of the table in 'FROM' statement.
     *  @return string              JOIN statement(s) SQL to use in select query.
     */
    private static function table_join( $options ) {
        $joins = [];
        $columns = $options['columns'];
        foreach($columns as $col){
            if (isset($col['db_join'])) {
                $join = $col['db_join'];
                $table = $join['db_table'];
                $alias = isset($join['db_alias']) ? $join['db_alias'] : $table[0];
                $joins[$alias] = "JOIN `$table` $alias ON ($alias.`{$join['db_on']}` = {$options['db_alias']}.`{$col['db_column']}`)";
            }
        }
        return implode(' ', $joins);
    }

    /**
     * Retrieves a list of column names as they would appear in a select statement.
     * This includes any "as" parameters applied to the column.
     *  @param  array $options Array of DataTable options to retrieve information from.
     *  @return array          List of column name strings to use in SQL select query.
     */
    private static function column_names ( $options ) {
        $names = [];
        $columns = $options['columns'];
        foreach($columns as $column){
            $names[] = self::column_name($column, $options['db_alias']);
        }
        return $names;
    }

    /**
     * Retrieves a column name as it would appear in a select statement.
     * This includes any "as" parameters applied to the column.
     *  @param  array  $column      The column to retrieve information from.
     *  @param  string $tableAlias  The alias of the table in 'FROM' statement.
     *  @return string              Column name string to use in SQL select query.
     */
    private static function column_name ( $column, $tableAlias ) {
        if (isset($column['db_join'])) {
            $join = $column['db_join'];
            $join['db_alias'] = isset($join['db_alias']) ? $join['db_alias'] : $join['db_table'][0];
            return "{$join['db_alias']}.`{$join['db_select']}`".(isset($join['db_as']) ? " AS '{$join['db_as']}'" : '');
        }else if(isset($column['db_command'])){
            $command = $column['db_command'];
            return "({$command['db_command']}) AS '{$command['db_as']}'";
        }
        return "$tableAlias.`{$column['db_column']}`".(isset($column['db_as']) ? " AS '{$column['db_as']}'" : '');
    }

    /**
     * Retrieves a column name as it would appear in a clause (WHERE, GROUP BY, ORDER BY).
     * This is typically the column's original name unless joined with an alias or using "as".
     *  @param  array  $column      The column to retrieve information from.
     *  @param  string $tableAlias  The alias of the table in 'FROM' statement.
     *  @return string              Column name string to use in SQL clause.
     */
    private static function column_name_ref ( $column, $tableAlias ) {
        if (isset($column['db_join'])) {
            $join = $column['db_join'];
            $join['db_alias'] = isset($join['db_alias']) ? $join['db_alias'] : $join['db_table'][0];
            return isset($join['db_as']) ? "`{$join['db_as']}`" : "{$join['db_alias']}.`{$join['db_select']}`";
        }else if (isset($column['db_command'])) {
            return "'{$column['db_command']['db_as']}'";
        }
        return isset($column['db_as']) ? "`{$column['db_as']}`" : "$tableAlias.`{$column['db_column']}`";
    }

    /**
     * Retrieves a column name as it would appear in the final resultset.
     * Results have the alias' removed and will refer to it's "as", if any.
     *  @param  array  $column  The column to retrieve information from.
     *  @return string          Column name string that will appear in the results.
     */
    private static function column_name_out ( $column ) {
        if (isset($column['db_join'])) {
            $join = $column['db_join'];
            return isset($join['db_as']) ? $join['db_as'] : $join['db_select'];
        }else if (isset($column['db_command'])) {
            return $column['db_command']['db_as'];
        }
        return isset($column['db_as']) ? $column['db_as'] : $column['db_column'];
    }

    /**
     * Execute an SQL query on the database
     *
     * @param  resource $db  Database handler
     * @param  array    $bindings Array of PDO binding values from bind() to be
     *   used for safely escaping strings. Note that this can be given as the
     *   SQL query string if no bindings are required.
     * @param  string   $sql SQL query to execute.
     * @return array         Result from the query (all rows)
     */
    private static function sql_exec ($conn, $bindings, $sql=null )
    {
        // Argument shifting
        if ( $sql === null ) {
            $sql = $bindings;
        }

        $stmt = $conn->prepare( $sql );
        //echo $sql;

        // Bind parameters
        if ( is_array( $bindings ) ) {
            foreach($bindings as $binding){
                $stmt->bindValue( $binding['key'], $binding['val'], $binding['type'] );
            }
        }

        // Execute
        try {
            $stmt->execute();
        }
        catch (PDOException $e) {
            self::fatal( "An SQL error occurred: ".$e->getMessage() .$sql);
        }

        // Return all
        return $stmt->fetchAll( PDO::FETCH_BOTH );
    }


    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Internal methods
     */

    /**
     * Throw a fatal error.
     *
     * This writes out an error message in a JSON string which DataTables will
     * see and show to the user in the browser.
     *
     * @param  string $msg Message to send to the client
     */
    private static function fatal ( $msg )
    {
        echo json_encode( array(
            "error" => $msg
        ) );

        exit(0);
    }

    /**
     * Create a PDO binding key which can be used for escaping variables safely
     * when executing a query with sql_exec()
     *
     * @param  array &$a    Array of bindings
     * @param  *      $val  Value to bind
     * @param  int    $type PDO field type
     * @return string       Bound key to be used in the SQL where this parameter
     *   would be used.
     */
    private static function bind ( &$a, $val, $type = NULL )
    {
        $key = ':binding_'.count( $a );

        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => isset($type) ? $type : (is_numeric($val) ? PDO::PARAM_INT : PDO::PARAM_STR)
        );

        return $key;
    }
}