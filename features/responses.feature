@poetry
Feature: Server responses

  Scenario: Poetry server can return a raw XML response.
    Given Poetry will return the following XML response:
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

    When the test application sends the following "request.create_translation_request" message to Poetry:
    """
      identifier:
        code: STSI
        year: 2017
        number: 40017
        version: 0
        part: 11
        product: REV
      details:
        client_id: 'Job ID 3999'
        title: 'NE-CMS: Erasmus+ - Erasmus Mundus Joint Master Degrees'
        author: 'IE/CE/EAC'
        responsible: 'EAC'
        requester: 'IE/CE/EAC/C/4'
        applicationId: 'FPFIS'
        delay: '12/09/2017'
        reference_files_remark: 'https://ec.europa.eu/programmes/erasmus-plus/opportunities-for-individuals/staff-teaching/erasmus-mundus_en'
        procedure: 'NEANT'
        destination: 'PUBLIC'
        type: 'INTER'
    """

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
    Given Poetry will return the following "response.status" message response:
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

    When the test application sends the following "request.create_review_request" message to Poetry:
    """
      identifier:
        code: STSI
        year: 2017
        number: 40017
        version: 0
        part: 11
        product: REV
      details:
        client_id: 'Job ID 3999'
        title: 'NE-CMS: Erasmus+ - Erasmus Mundus Joint Master Degrees'
        author: 'IE/CE/EAC'
        responsible: 'EAC'
        requester: 'IE/CE/EAC/C/4'
        applicationId: 'FPFIS'
        delay: '12/09/2017'
        reference_files_remark: 'https://ec.europa.eu/programmes/erasmus-plus/opportunities-for-individuals/staff-teaching/erasmus-mundus_en'
        procedure: 'NEANT'
        destination: 'PUBLIC'
        type: 'INTER'
      source:
        format: 'HTML'
        name: 'content.html'
        file: 'BASE64ENCODEDFILECONTENT'
        legiswrite_format: 'No'
        source_language:
          -
            code: 'EN'
            pages: 1
      contact:
        -
          type: 'auteur'
          nickname: 'john'
        -
          type: 'secretaire'
          nickname: 'john'
        -
          type: 'contact'
          nickname: 'john'
        -
          type: 'responsable'
          nickname: 'mark'
      target:
        -
          action: 'INSERT'
          format: 'HTML'
          language: 'EN'
          delay: '12/09/2017'
    """

    Then Poetry service should receive the following request:
    """
    <?xml version="1.0" encoding="UTF-8"?>
    <POETRY xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://intragate.ec.europa.eu/DGT/poetry_services/poetry.xsd">
        <request communication="asynchrone" id="STSI/2017/40017/0/11/REV" type="newPost">
            <demandeId>
                <codeDemandeur>STSI</codeDemandeur><annee>2017</annee><numero>40017</numero><version>0</version><partie>11</partie><produit>REV</produit>
            </demandeId>
            <demande>
                <userReference>Job ID 3999</userReference>
                <titre>NE-CMS: Erasmus+ - Erasmus Mundus Joint Master Degrees</titre>
                <organisationResponsable>EAC</organisationResponsable>
                <organisationAuteur>IE/CE/EAC</organisationAuteur>
                <serviceDemandeur>IE/CE/EAC/C/4</serviceDemandeur>
                <applicationReference>FPFIS</applicationReference>
                <delai>12/09/2017</delai>
                <referenceFilesNote>https://ec.europa.eu/programmes/erasmus-plus/opportunities-for-individuals/staff-teaching/erasmus-mundus_en</referenceFilesNote>
                <procedure id="NEANT"/>
                <destination id="PUBLIC"/>
                <type id="INTER"/>
            </demande>
            <contacts type="auteur"><contactNickname>john</contactNickname></contacts>
            <contacts type="secretaire"><contactNickname>john</contactNickname></contacts>
            <contacts type="contact"><contactNickname>john</contactNickname></contacts>
            <contacts type="responsable"><contactNickname>mark</contactNickname></contacts>
            <documentSource format="HTML" legiswrite="No">
                <documentSourceName>content.html</documentSourceName>
                <documentSourceFile>BASE64ENCODEDFILECONTENT</documentSourceFile>
                <documentSourceLang lgCode="EN">
                    <documentSourceLangPages>1</documentSourceLangPages>
                </documentSourceLang>
            </documentSource>
            <attributions action="INSERT" format="HTML" lgCode="EN">
              <attributionsDelai>12/09/2017</attributionsDelai>
            </attributions>
        </request>
    </POETRY>
    """

    And Poetry service received request should contain the following text:
      | <attributionsDelai>12/09/2017</attributionsDelai>  |
      | <serviceDemandeur>IE/CE/EAC/C/4</serviceDemandeur> |

    And Poetry service received request should contain the following XML portion:
    """
    <documentSource format="HTML" legiswrite="No">
        <documentSourceName><![CDATA[content.html]]></documentSourceName>
        <documentSourceFile><![CDATA[BASE64ENCODEDFILECONTENT]]></documentSourceFile>
        <documentSourceLang lgCode="EN">
            <documentSourceLangPages>1</documentSourceLangPages>
        </documentSourceLang>
    </documentSource>
    """

  Scenario: Test Poetry client with specific settings and token replacement.

    Given the Poetry client uses the following settings:
    """
      identifier.code: STSI
      identifier.year: 2017
      identifier.number: 40017
      identifier.version: 0
      identifier.part: 11
      client.wsdl: http://my-client.eu/wsdl
      notification.username: foo
      notification.password: bar
    """

    And Poetry will return the following XML response:
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

    When the test application sends the following "request.create_translation_request" message to Poetry:
    """
      details:
        client_id: 'Job ID 3999'
        title: 'NE-CMS: Erasmus+ - Erasmus Mundus Joint Master Degrees'
        author: 'IE/CE/EAC'
        responsible: 'EAC'
        requester: 'IE/CE/EAC/C/4'
        applicationId: 'FPFIS'
        delay: '12/09/2017'
        reference_files_remark: 'https://ec.europa.eu/programmes/erasmus-plus/opportunities-for-individuals/staff-teaching/erasmus-mundus_en'
        procedure: 'NEANT'
        destination: 'PUBLIC'
        type: 'INTER'
    """

    And Poetry service received request should contain the following XML portion:
    """
    <retour type="webService" action="UPDATE">
       <retourUser><![CDATA[foo]]></retourUser>
       <retourPassword><![CDATA[bar]]></retourPassword>
       <retourAddress><![CDATA[!poetry.client.wsdl]]></retourAddress>
       <retourPath><![CDATA[handle]]></retourPath>
    </retour>
    """
