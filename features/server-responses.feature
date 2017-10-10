@poetry
Feature: Server responses

  Scenario: Poetry server can return a raw XML response.
    Given that Poetry will return the following XML response:
    """
    <?xml version="1.0" encoding="utf-8"?><POETRY xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://intragate.ec.europa.eu/DGT/poetry_services/poetry.xsd">
        <request communication="synchrone" id="WEB/2017/40029/0/0/TRA" type="status">
            <demandeId>
                <codeDemandeur>WEB</codeDemandeur>
                <annee>2017</annee>
                <numero>40029</numero>
                <version>0</version>
                <partie>0</partie>
                <produit>TRA</produit>
            </demandeId>
            <status type="request" code="0">
                <statusDate>06/10/2017</statusDate>
                <statusTime>02:41:53</statusTime>
                <statusMessage>OK</statusMessage>
            </status>
        </request>
    </POETRY>
    """

    When the test application sends a request to Poetry
    Then the test application should receive the following response:
    """
    <?xml version="1.0" encoding="utf-8"?><POETRY xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://intragate.ec.europa.eu/DGT/poetry_services/poetry.xsd">
        <request communication="synchrone" id="WEB/2017/40029/0/0/TRA" type="status">
            <demandeId>
                <codeDemandeur>WEB</codeDemandeur>
                <annee>2017</annee>
                <numero>40029</numero>
                <version>0</version>
                <partie>0</partie>
                <produit>TRA</produit>
            </demandeId>
            <status type="request" code="0">
                <statusDate>06/10/2017</statusDate>
                <statusTime>02:41:53</statusTime>
                <statusMessage>OK</statusMessage>
            </status>
        </request>
    </POETRY>
    """

  Scenario: Poetry server can return a response from a message array.
    Given that Poetry will return the following "response.status" message response:
    """
    identifier:
      code: WEB
      year: 2017
      number: 40029
      version: 0
      part: 0
      product: TRA
    status:
      -
        type: request
        code: '0'
        date: 06/10/2017
        time: 02:41:53
        message: OK
    """

    When the test application sends a request to Poetry
    Then the test application should receive the following response:
    """
    <?xml version="1.0" encoding="utf-8"?><POETRY xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://intragate.ec.europa.eu/DGT/poetry_services/poetry.xsd">
        <request communication="synchrone" id="WEB/2017/40029/0/0/TRA" type="status">
            <demandeId>
                <codeDemandeur>WEB</codeDemandeur>
                <annee>2017</annee>
                <numero>40029</numero>
                <version>0</version>
                <partie>0</partie>
                <produit>TRA</produit>
            </demandeId>
            <status type="request" code="0">
                <statusDate>06/10/2017</statusDate>
                <statusTime>02:41:53</statusTime>
                <statusMessage>OK</statusMessage>
            </status>
        </request>
    </POETRY>
    """
