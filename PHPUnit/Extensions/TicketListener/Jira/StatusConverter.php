<?php

/**
 * Utility for creating Jira status maps.  Jira status and
 * resolution actions are user defined, so the id numbers can vary
 * across different installations.  Also, it is possible to create
 * custom statuses that provide a large variety of statuses to 
 * express that the issue is either open or closed.
 */
class PHPUnit_Extensions_TicketListener_Jira_StatusConverter {

    const TICKET_STATUS_CLOSED = 'closed';
    const TICKET_STATUS_REOPENED = 'reopened';

    /**
     * Creates a map of each Jira status to a TicketListener status.
     *
     * @param array $openStatuses   List of status names that indicate 
     * the issue is open.
     * @param array $closedStatuses List of status names that indicate
     * the issue is closed.
     * @param string $jiraStatuses  The list of statuses retrieved from
     * an API call to the Jira installation.
     * @return array mapping each of the open and closed statuses to 
     * the appropriate generic TicketListener value.
     */
    public function getJiraToTicketStatusMap($openStatuses, $closedStatuses, $jiraStatuses) {
        $statuses = array();
        foreach ($jiraStatuses as $status) {
            if (in_array($status->name, $openStatuses)) {
                $ticketStatus = self::TICKET_STATUS_REOPENED;
            } else if (in_array($status->name, $closedStatuses)) {
                $ticketStatus = self::TICKET_STATUS_CLOSED;
            } else {
              continue;
            }

            $statuses[$status->id] = $ticketStatus;
        }
        return $statuses;
    }

    /**
     * Creates a map of TicketListener status to a Jira workflow action.
     *
     * @param string $resolveAction The name of the action to resolve
     * the issue.
     * @param string $reopenAtion   The name of the action to reopen
     * the issue.
     */
    public function getTicketToJiraActionMap($resolveAction, $reopenAction) {
        return array(
            self::TICKET_STATUS_CLOSED => $resolveAction,
            self::TICKET_STATUS_REOPENED => $reopenAction);
    }
}

