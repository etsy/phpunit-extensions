<?php

require_once 'PHPUnit/Extensions/TicketListener/Jira/Client.php';

/**
 * @covers PHPUnit_Extensions_TicketListener_Jira_Client
 */
class Tests_Extensions_TicketListener_Jira_ClientTest 
extends PHPUnit_Framework_TestCase {
    
    const JIRA_WSDL = "http://jira.atlassian.com/rpc/soap/jirasoapservice-v2?wsdl";

    public function testGetStatuses() {
        $new = new StdClass;
        $new->name = 'New';
        $new->id = 1;

        $inProgress = new StdClass;
        $inProgress->name = 'In Progress';
        $inProgress->id = 3;

        $reopened = new StdClass;
        $reopened->name = 'Reopened';
        $reopened->id = 4;

        $completed = new StdClass;
        $completed->name = 'Completed';
        $completed->id = 5;

        $closed = new StdClass;
        $closed->name = 'Closed';
        $closed->id = 6;

        $expected = array($new, $inProgress, $reopened, $completed, $closed);
        
        $auth = 'basicAuthKey';
        $soapClient = $this->getMockFromWsdl(
            self::JIRA_WSDL, 'JiraClientGetStatuses');
        $soapClient->expects($this->any())
            ->method('__call')
            ->with('getStatuses', array($auth))
            ->will($this->returnValue($expected));

        $client = new PHPUnit_Extensions_TicketListener_Jira_Client(
            NULL, NULL, NULL);
        $client->connect($soapClient, $auth);
     
        $this->assertEquals($expected, $client->getStatuses());
    }

    public function testGetTicket() {
        $expected = new StdClass;
        $expected->id = 'BUG-220';
        $expected->status = 1;

        $auth = 'basicAuthKey';
        $soapClient = $this->getMockFromWsdl(
            self::JIRA_WSDL, 'JiraClientGetTicket');
        $soapClient->expects($this->any())
            ->method('__call')
            ->with('getIssue', array($auth, $expected->id))
            ->will($this->returnValue($expected));

        $client = new PHPUnit_Extensions_TicketListener_Jira_Client(
            self::JIRA_WSDL, 'username', 'passwd');
        $client->connect($soapClient, $auth);

        $this->assertEquals($expected, $client->getTicket($expected->id));
    }

    public function testProgressWorkflowAction_validResolution() {
        $ticketId = 'BUG-220';

        $action = new StdClass;
        $action->id = 5;
        $action->name = 'Resolve Issue';

        $message = 'Automated message';

        $auth = 'basicAuthKey';
        $soapClient = $this->getMockFromWsdl(
            self::JIRA_WSDL, 'JiraClientProgressWorkflowActionValid');
        $soapClient->expects($this->at(0))
            ->method('__call')
            ->with('getAvailableActions', array($auth, $ticketId))
            ->will($this->returnValue(array($action)));
        $soapClient->expects($this->at(1))
            ->method('__call')
            ->with(
                'progressWorkflowAction',
                array(
                    $auth, 
                    $ticketId, 
                    $action->id, 
                    array()
                )
            );
        $soapClient->expects($this->at(2))
            ->method('__call')
            ->with(
                'addComment',
                array(
                    $auth, 
                    $ticketId, 
                    array('body' => $message)
                )
            );

        $client = new PHPUnit_Extensions_TicketListener_Jira_Client(
            self::JIRA_WSDL, 'username', 'passwd');
        $client->connect($soapClient, $auth);

        $client->progressWorkFlowAction($ticketId, 'Resolve Issue', $message);
    }


    /**
     * @expectedException Exception
     */
    public function testProgressWorkflowAction_invalidResolution() { 
        $ticketId = 'BUG-220';

        $action = new StdClass;
        $action->id = 5;
        $action->name = 'Resolve Issue';

        $auth = 'basicAuthKey';
        $soapClient = $this->getMockFromWsdl(
            self::JIRA_WSDL, 'JiraClientProgressWorkflowActionInvalid');
        $soapClient->expects($this->at(0))
            ->method('__call')
            ->with('getAvailableActions', array($auth, $ticketId))
            ->will($this->returnValue(array($action)));
        $soapClient->expects($this->at(1))
            ->method('__call')
            ->with(
                'progressWorkflowAction',
                array(
                    $auth, 
                    $ticketId, 
                    NULL, 
                    array()
                )
            )
            ->will($this->throwException(new Exception()));

        $client = new PHPUnit_Extensions_TicketListener_Jira_Client(
            self::JIRA_WSDL, 'username', 'passwd');
        $client->connect($soapClient, $auth);

        $client->progressWorkFlowAction(
            $ticketId, 'Reopen Issue', 'Automated message');
    }
}

