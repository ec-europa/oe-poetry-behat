@poetry
Feature: Poetry mock server

  Scenario: Poetry mock service setup
    Given the following Poetry service response:
    """
    <?xml version="1.0" encoding="UTF-8"?>
    <POETRY xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://intragate.ec.europa.eu/DGT/poetry_services/poetry.xsd">
      <request communication="synchrone" id="7685067" type="translation">
        <demandeId>
          <codeDemandeur>WEB</codeDemandeur>
          <annee>2017</annee>
          <numero>40012</numero>
          <version>0</version>
          <partie>39</partie>
          <produit>TRA</produit>
        </demandeId>
        <attributions format="HTML" lgCode="FR">
          <attributionsFile>File64</attributionsFile>
        </attributions>
      </request>
    </POETRY>
    """

    When I send a POST request to "http://localhost:28080/notification"
    Then the response code should be 200
    And the response should contain "<codeDemandeur>WEB"
