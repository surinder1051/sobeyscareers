<?php

if (!function_exists('fp_client_custom_queries')) {

    class fp_client_custom_queries extends fp_client{
            // Setup Admin Client page for FP Dashboard Client
            //

        function __construct() {
          
        }

        function settings_page($ajax = false)
        {

            ?>
            <div class='wrap'>
            <h1>FlowPress Dashboard Client</h1>
            <div id="fp_client_settings">
                <?php if ($this->user_exists()) : ?>
                        <form class='hidden_json' action='options.php' method='post'>
                            <h2>Flowpress Client Custom Queries</h2>
                            <?php
            settings_fields('custom-queries-settings-section');
            do_settings_sections('fp-custom-queries-settings');
            submit_button();
            ?>
                            <div class="save_warning">unsaved changes present, make sure you save your new queries</div>
                        </form>
                        <h2>Existing Custom Data Queries</h2>
                        <table class="queries">
                            <tr>
                                <td>API variable</td>
                                <td>variables</td>
                                <td>Query</td>
                                <td></td>
                            </tr>
                        </table>
                        <h2>Build New Query</h2>
                        <div>
                            <label>
                                API variable
                            </label>
                            <input class="arg_1" value=''/>
                        </div>
                        <div>
                            <label>
                                SELECT
                            </label>
                            <textarea class="arg_2"></textarea>
                        </div>
                        <div>
                            <label>
                                FROM
                            </label>
                            <textarea class="arg_3"></textarea>
                        </div>
                        <div>
                            <label>
                                LEFT JOIN
                            </label>
                            <textarea class="arg_4"></textarea>
                        </div>
                        <div>
                            <label>
                                WHERE
                            </label>
                            <textarea class="arg_5"></textarea>
                        </div>
                        <div>
                            <div class="button button-primary create_query">Create</div>
                            <div class="button button-primary save_query">Update</div>
                        </div>
                <?php endif; ?>
            </div>
            </div>
            <?php

        }

        /**
         * [ check queries for anything else then SELECT type ]
         * @param  [string] $query [ query to check ]
         * @return [boolval]        [ return true if query is safe ]
         */

        function safe_query($query)
        {
            if (preg_match('/SELECT/', strtoupper($query)) != 0) {
                $disAllow = array(
                    'INSERT',
                    'UPDATE',
                    'DELETE',
                    'RENAME',
                    'DROP',
                    'CREATE',
                    'TRUNCATE',
                    'ALTER',
                    'COMMIT',
                    'ROLLBACK',
                    'MERGE',
                    'CALL',
                    'EXPLAIN',
                    'LOCK',
                    'GRANT',
                    'REVOKE',
                    'SAVEPOINT',
                    'TRANSACTION',
                    'SET',
                );
                $disAllow = implode('|', $disAllow);
                if (preg_match('/(' . $disAllow . ')/', strtoupper($query)) == 0) {
                    return true;
                }
            }
            return false;
        }



        /**
         * [ use to to lookup custom query, if present, execute and serve results ]
         * @param  [text] $id [ custom lookup key setup on plugin options page ]
         * @return [string]   [ restuls from custom db query ]
         */

        function check_custom_queries($id)
        {

            $data = json_decode(get_option('custom_queries_option'));

            foreach ($data as $key => $value) {
                $query = json_decode($value);

                if ($id == $query->arg_1) {

                    $arg_1 = $query->arg_1;
                    $arg_2 = sanitize_text_field($query->arg_2);
                    $arg_3 = sanitize_text_field($query->arg_3);
                    $arg_4 = sanitize_text_field($query->arg_4);
                    $arg_5 = sanitize_text_field($query->arg_5);

                    global $wpdb;

                    $sql = "SELECT $arg_2 FROM $arg_3";

                    if ($arg_4) {
                        $sql .= " LEFT JOIN $arg_4";
                    }
                    if ($arg_5) {
                        $sql .= " WHERE $arg_5";
                    }

                    if (!$this->safe_query($sql)) {
                        $json['error'] = 'Not a safe SQL Query';
                        return $json;
                    } else {
                        $return = $wpdb->get_results($sql);
                        return $return;
                    }
                }
            };

            return false;
        }
    }
}
