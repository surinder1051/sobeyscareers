<?php

if (!function_exists('fp_client_api')) {

    class fp_client_api{
        /**
         * [ register wordpress rest end point ]
         * @return [data] [if user can read from API]
         */

        function api_endpoint()
        {

            register_rest_route(
                'fp_client/v1',
                'get_data',
                array(
                    'methods' => 'GET',
                    'callback' => array($this, 'api_endpoint_callback'),
                    'args' => array(
                        'field' => array(
                            'required' => true,
                            'type' => 'text',
                            'description' => 'Field ID',
                        ),
                    ),
                    'permission_callback' => function () {
                        return current_user_can('read');
                    },
                )
            );
        }

        /**
         * [ API Call back, verify's if call is approved or matches custom DB queries ]
         * @param  [array] $request_data [ API Call parameters ]
         * @return [string]         [ ]
         */

        function api_endpoint_callback($request_data)
        {
            $parameters = $request_data->get_params();
            $fp_client_data = new fp_client_data;

            if (method_exists($fp_client_data, $parameters['field'])) {
                if (!$fp_client_data->is_approved_function($parameters['field'])) {
                    return 'API function is not approved.';
                }
                $results = $fp_client_data->{$parameters['field']}();
                return $results;
            } else {
                $fp_client_custom_queries = new fp_client_custom_queries;
                if (!$data = $fp_client_custom_queries->check_custom_queries($parameters['field'])) {
                    return 'API function is not available.';
                } else {
                    return $data;
                }
            }
        }
    }

}
