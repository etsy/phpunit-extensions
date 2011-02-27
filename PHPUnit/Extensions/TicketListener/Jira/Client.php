<?php

/**
 * Lightweight Jira SOAP API client for the Jira TicketListener.
 */
class PHPUnit_Extensions_TicketListener_Jira_Client {

    private $wsdl;
    private $username;
    private $password;
    private $client;
    private $auth;

    /**
     * @param string $wsdl     WSDL for the Jira SOAP API.
     * @param string $username Username for modifying issues in Jira.
     * @param string $password Password for the username.
     */
    public function __construct($wsdl, $username, $password) {
        $this->wsdl = $wsdl;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Creates a PHP SoapClient pointing at the Jira WSDL, and retrieves
     * the basic auth key for the username.
     *
     * @param SoapClient $client Jira PHP SoapClient
     * @param string $auth       Basic Auth Key for username.
     */
    public function connect($client = NULL, $auth = NULL) {
        ini_set('soap.wsdl_cache_enabled', '0');
        $this->client = ($client) ? $client : 
            new SoapClient(
                $this->wsdl, 
                array('user_agent' => 'PHP-SOAP/php-5.2.9')
            );
        $this->auth = ($auth) 
            ? $auth 
            : $this->__execute('login', $this->username, $this->password);
    }

    /**
     * @return array The list of StatusResponse from the Jira SOAP API.
     */
    public function getStatuses() {
        return $this->__execute('getStatuses', $this->auth);
    }

    /**
     * @param string $ticketId The string id (not numeric id), of the Jira issue.
     * @return The IssueResponse from calling the Jira SOAP API getIssue.
     */
    public function getTicket($ticketId) {
        return $this->__execute('getIssue', $this->auth, $ticketId);
    }

    /**
     * Performs a workflow action to advance the state of the issue.
     *
     * @param string $ticketId   The String id (not the numeric id), of the 
     * Jira issue.
     * @param string $actionName The name of the Jira workflow action.
     * @param string $comment    The comment to add to the issue after
     * advancing the state.
     * @throws Exception         If $actionName is not an avaiable action.
     */
    public function progressWorkflowAction($ticketId, $actionName, $comment) {
        $actions = $this->__execute('getAvailableActions', 
            $this->auth, $ticketId);

        $actionId = NULL;
        foreach ($actions as $action) {
            if ($action->name == $actionName) {
                $actionId = $action->id;
                break;
            }
        }

        $this->__execute('progressWorkflowAction', 
            $this->auth, $ticketId, $actionId, array());
        $this->__execute('addComment', 
            $this->auth, $ticketId, array('body' => $comment));
    }

    private function __execute() {
        $arg_list = func_get_args();
        $function = array_shift($arg_list);

        while(true) {
            try {
                return $this->client->__call($function, $arg_list);
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Error Fetching http headers') {
                    throw $e;
                }
                usleep(10000);
            }
        } 
    }
}

