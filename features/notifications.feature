@poetry
Feature: Server notifications

  Scenario: Poetry server can notify the client using raw XML.
    When Poetry notifies the client with the following XML:
    """
    <?xml version="1.0" encoding="UTF-8"?>
    <POETRY xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://intragate.ec.europa.eu/DGT/poetry_services/poetry.xsd">
      <request communication="synchrone" id="1069698" type="status">
        <demandeId>
          <codeDemandeur>WEB</codeDemandeur>
          <annee>2017</annee>
          <numero>40029</numero>
          <version>0</version>
          <partie>0</partie>
          <produit>TRA</produit>
        </demandeId>
        <status code="0" type="request">
          <statusDate>29/09/2017</statusDate>
          <statusTime>15:44:02</statusTime>
          <statusMessage>OK</statusMessage>
        </status>
        <status code="ONG" type="demande">
          <statusDate>29/09/2017</statusDate>
          <statusTime>15:42:34</statusTime>
          <statusMessage>REQUEST ACCEPTED</statusMessage>
        </status>
        <status code="ONG" lgCode="FR" type="attribution">
          <statusDate>29/09/2017</statusDate>
          <statusTime>00:00:00</statusTime>
        </status>
        <attributions format="HTML" lgCode="FR">
          <attributionsDelai>04/10/2017 23:59</attributionsDelai>
          <attributionsDelaiAccepted>04/10/2017 23:59</attributionsDelaiAccepted>
        </attributions>
      </request>
    </POETRY>
    """

    Then the test application log should contain the following entries:
      | TestApplication.INFO: poetry.notification_handler.received_notification |
      | TestApplication.INFO: poetry.notification.parse                         |
      | TestApplication.INFO: poetry.notification.status_updated                |
    And the test application log should not contain the following entries:
      | TestApplication.ERROR: poetry.exception |

  Scenario: Poetry server can notify the client using a message.
    When Poetry notifies the client with the following "notification.translation_received" message:
    """
      identifier:
        code: "WEB"
        year: "2017"
        number: "40012"
        version: "0"
        part: "39"
        product: "TRA"
      targets:
        -
          format: "HTML"
          language: "FR"
          translated_file: "File64"
    """

    Then client response contains the following text:
      | <statusMessage>OK</statusMessage> |

    And the test application log should contain the following entries:
      | TestApplication.INFO: poetry.notification_handler.received_notification |
      | TestApplication.INFO: poetry.notification.parse                         |
      | TestApplication.INFO: poetry.notification.translation_received          |
    And the test application log should not contain the following entries:
      | TestApplication.ERROR: poetry.exception |

  Scenario: Scenarios can override Poetry extension configuration.

    When Poetry service uses the following settings:
    """
      username: foo
      password: bar
    """

    And Poetry notifies the client with the following "notification.translation_received" message:
    """
      identifier:
        code: "WEB"
        year: "2017"
        number: "40012"
        version: "0"
        part: "39"
        product: "TRA"
      targets:
        -
          format: "HTML"
          language: "FR"
          translated_file: "File64"
    """

    Then the test application log should contain the following entries:
      | TestApplication.INFO: poetry.notification_handler.received_notification                      |
      | TestApplication.INFO: poetry.notification.parse                                              |
      | TestApplication.ERROR: poetry.exception                                                      |
      | Poetry service cannot authenticate on notification callback: username or password not valid. |

    And the test application log should not contain the following entries:
      | TestApplication.INFO: poetry.notification.translation_received |
