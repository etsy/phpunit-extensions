<?php

require_once 'PHPUnit/Extensions/TicketListener/Jira/Client.php';
require_once 'PHPUnit/Extensions/TicketListener/Jira/StatusConverter.php';

/**
 * TicketListener which will resolve and reopen issues in Jira based on PHPUnit
 * test results.<p>
 *
 * phpunit.xml
 * <code>
 * &lt;listeners&gt;
 *   &lt;listener class="PHPUnit_Extensions_TicketListener_Jira"&gt;
 *     &lt;arguments&gt;
 *       &lt;string&gt;$wsdl&lt;/string&gt;
 *       &lt;string&gt;$username&lt;/string&gt;
 *       &lt;string&gt;$password&lt;/string&gt;
 *       &lt;array&gt;
 *         &lt;element key="0"&gt;
 *           &lt;string&gt;New&lt;/string&gt;
 *         &lt;/element&gt;
 *         &lt;element key="1"&gt;
 *           &lt;string&gt;In Progress&lt;/string&gt;
 *         &lt;/element&gt;
 *         &lt;element key="2"&gt;
 *           &lt;string&gt;Reopened&lt;/string&gt;
 *         &lt;/element&gt;
 *       &lt;/array&gt;
 *       &lt;array&gt;
 *         &lt;element key="0"&gt;
 *           &lt;string&gt;Completed&lt;/string&gt;
 *         &lt;/element&gt;
 *         &lt;element key="1"&gt;
 *           &lt;string&gt;Closed&lt;/string&gt;
 *         &lt;/element&gt;
 *       &lt;/array&gt;
 *       &lt;string&gt;Resolve Issue&lt;/string&gt;
 *       &lt;string&gt;Reopen Issue&lt;/string&gt;
 *     &lt;/arguments&gt;
 *   &lt;/listener&gt;
 *  &lt;/listeners&gt;
 * </code><p>
 *
 * The above example lists 'New', 'In Progress', and 'Reopened' as 'Open' 
 * issue statuses, and 'Completed' and 'Closed' as 'Closed' issue statuses.
 * The workflow action button to resolve an open issue is 'Resolve Issue',
 * and the workflow action button to reopen a closed issue is 'Reopen Issue'.
 */
class PHPUnit_Extesions_TicketListener_Jira 
extends PHPUnit_Extensions_TicketListener {

    private $client;
    private $jiraToTicketStatusMap;
    private $ticketToJiraActionMap;

    /**
     * @param string $wsdl          https://jira.mycompany.com/rpc/soap/jirasoapservice-v2?wsdl
     * @param string $username      Valid Jira username
     * @param string password       Password for Jira username
     * @param array $openStatuses   List of statuses mapping to an open issue.
     * @param array $closedStatuses List of statuses mapping to a closed issue.
     * @param string $resolveAction Workflow action for resolving an open issue.
     * @param string $reopenAction  Workflow action for reopening a closed issue.
     */
    public function __construct(
        $wsdl, $username, $password,
        $openStatuses, $closedStatuses, $resolveAction, $reopenAction, 
        $soapClient = NULL, $auth = NULL) {

        $this->client = new PHPUnit_Extensions_TicketListener_Jira_Client(
            $wsdl, $username, $password);
        $this->client->connect($soapClient, $auth);
    
        $statusConverter =
            new PHPUnit_Extensions_TicketListener_Jira_StatusConverter();
        $this->jiraToTicketStatusMap = $statusConverter->getJiraToTicketStatusMap(
            $openStatuses, $closedStatuses, $this->client->getStatuses());
        $this->ticketToJiraActionMap = $statusConverter->getTicketToJiraActionMap(
            $resolveAction, $reopenAction);
    }

    /**
     * @param string $ticketId Jira issue id string (not numeric id).
     * @return array Ticket property maps.  'status' is the only supported key.
     */
    protected function getTicketInfo($ticketId = NULL) {
        if ($ticketId === NULL) {
            return;
        }
        $jiraTicket = $this->client->getTicket($ticketId);

        return array('status' => $this->jiraToTicketStatusMap[$jiraTicket->status]);        
    }

    /**
     * @param string $ticketId   Jira issue id string (not numeric id).
     * @param string $newStatus  'reopen' or 'close'
     * @param string $message    Comment on status change.
     * @param string $resolution Ignored.
     */
    protected function updateTicket($ticketId, $newStatus, $message, $resolution) {
        $this->client->progressWorkflowAction(
            $ticketId,
            $this->ticketToJiraActionMap[$newStatus],
            $message);
    }
}

