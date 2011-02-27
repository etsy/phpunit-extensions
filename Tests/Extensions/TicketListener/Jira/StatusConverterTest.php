<?php

require_once 'PHPUnit/Extensions/TicketListener/Jira/StatusConverter.php';

/**
 * @covers PHPUnit_Extensions_TicketListener_Jira_StatusConverter
 */
class Tests_Extensions_TicketListener_Jira_StatusConverterTest 
extends PHPUnit_Framework_TestCase {

    const TICKET_STATUS_CLOSED = 
        PHPUnit_Extensions_TicketListener_Jira_StatusConverter::TICKET_STATUS_CLOSED;
    const TICKET_STATUS_REOPENED = 
        PHPUnit_Extensions_TicketListener_Jira_StatusConverter::TICKET_STATUS_REOPENED;

    private $new;
    private $inProgress;
    private $reopened;
    private $completed;
    private $closed;

    private $resolveAction;
    private $reopenAction;

    private $statuses;

    private $converter;

    protected function setUp() {
        parent::setUp();

        $this->new = new StdClass;
        $this->new->name = 'New';
        $this->new->id = 1;

        $this->inProgress = new StdClass;
        $this->inProgress->name = 'In Progress';
        $this->inProgress->id = 3;

        $this->reopened = new StdClass;
        $this->reopened->name = 'Reopened';
        $this->reopened->id = 4;

        $this->completed = new StdClass;
        $this->completed->name = 'Completed';
        $this->completed->id = 5;

        $this->closed = new StdClass;
        $this->closed->name = 'Closed';
        $this->closed->id = 6;

        $this->resolveAction = 'Resolve Issue';
        $this->reopenAction = 'Reopen Issue';

        $this->statuses =
            array(
                $this->new,
                $this->inProgress,
                $this->reopened,
                $this->completed,
                $this->closed
            );

        $this->converter = 
            new PHPUnit_Extensions_TicketListener_Jira_StatusConverter();
    }

    public function testGetJiraToTicketStatusMap() {
        $openStatuses =
            array(
                $this->new->name, 
                $this->inProgress->name,
                $this->reopened->name
            );
        $closedStatuses = array($this->completed->name, $this->closed->name);

        $expected = array(
            $this->new->id => self::TICKET_STATUS_REOPENED,
            $this->inProgress->id => self::TICKET_STATUS_REOPENED,
            $this->reopened->id => self::TICKET_STATUS_REOPENED,
            $this->completed->id => self::TICKET_STATUS_CLOSED,
            $this->closed->id => self::TICKET_STATUS_CLOSED);

        $this->assertEquals($expected,
            $this->converter->getJiraToTicketStatusMap(
                $openStatuses, $closedStatuses, $this->statuses));

        return $expected;
    }

    public function testGetTicketToJiraActionMap() {
        $expected = array(
            self::TICKET_STATUS_CLOSED => $this->resolveAction,
            self::TICKET_STATUS_REOPENED => $this->reopenAction);

        $this->assertEquals($expected, 
            $this->converter->getTicketToJiraActionMap(
                $this->resolveAction, $this->reopenAction));

        return $expected;
    }
}

