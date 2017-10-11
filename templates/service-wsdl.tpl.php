<?php
/**
 * @file
 * Mock WSDL template file.
 */
?>
<?php
$url = sprintf("http://%s:%s%s", $host, $port, $endpoint);
?>
<definitions name="PoetryBehatTestSoapServer" targetNamespace="<?= $url; ?>" xmlns:tns="<?= $url; ?>" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns="http://schemas.xmlsoap.org/wsdl/" xmlns:ns="<?= $url; ?>/types">
  <types>
    <xsd:schema targetNamespace="<?= $url; ?>/types" xmlns="<?= $url; ?>/types"/>
  </types>
  <message name="requestServiceRequest">
    <part name="user" type="xsd:string"/>
    <part name="password" type="xsd:string"/>
    <part name="msg" type="xsd:string"/>
  </message>
  <message name="requestServiceResponse">
    <part name="requestServiceReturn" type="xsd:string">
    </part>
  </message>
  <portType name="PoetryBehatTestSoapServerPortType">
    <operation name="requestService">
      <input message="tns:requestServiceRequest"/>
      <output message="tns:requestServiceResponse"/>
    </operation>
  </portType>
  <binding name="PoetryBehatTestSoapServerBinding" type="tns:PoetryBehatTestSoapServerPortType">
    <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
    <operation name="requestService">
      <soap:operation soapAction="<?= $url; ?>/#requestService"/>
      <input>
      <soap:body use="literal" namespace="<?= $url; ?>"/>
      </input>
      <output>
        <soap:body use="literal" namespace="<?= $url; ?>"/>
      </output>
    </operation>
  </binding>
  <service name="PoetryBehatTestSoapServerService">
    <port name="PoetryBehatTestSoapServerPort" binding="tns:PoetryBehatTestSoapServerBinding">
      <soap:address location="<?= $url; ?>"/>
    </port>
  </service>
</definitions>
