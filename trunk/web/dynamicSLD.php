<?php
header("Content-Type: text/xml");
header("Pragma: no-cache");
echo("<?xml version=\"1.0\" encoding=\"UTF-8\" ?>");
$dbHandle = new PDO('pgsql:host=ec2-174-129-85-138.compute-1.amazonaws.com port=5432 dbname=sdr user=postgres password=atlas');
$sql="select tag from status_tags_resources as str inner join status_tags as st on str.status_tags_fk=st.id where resource_fk=:resource order by tag";
$stmt = $dbHandle->prepare($sql);
$stmt->bindParam(':resource', $_REQUEST['resource']);
$stmt->execute();
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
$numelements=count($tags) +1;
$step = floor(255/$numelements);

?>
<StyledLayerDescriptor version="1.0.0" xmlns="http://www.opengis.net/sld" xmlns:ogc="http://www.opengis.net/ogc"
  xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://www.opengis.net/sld http://schemas.opengis.net/sld/1.0.0/StyledLayerDescriptor.xsd">
  <NamedLayer>
    <Name>1</Name>
    <UserStyle>
      <Title>1</Title>
      <Abstract>1</Abstract>
      <FeatureTypeStyle>
		<?php
		$i=1;
		foreach($tags as $tag) {
			$l=$tag['tag'];
			$colVal =($i*$step);
			$color = "#". sprintf("%02X%02X%02X", $colVal, $colVal, $colVal);
			$i++;
		?>
        <Rule>
          <Title><?php echo($l)?></Title>
          <ogc:Filter>
            <ogc:PropertyIsEqualTo>
                <ogc:PropertyName>tag</ogc:PropertyName>
                <ogc:Literal><?php echo($l)?></ogc:Literal>
           </ogc:PropertyIsEqualTo>
         </ogc:Filter>
          <PolygonSymbolizer>
            <Fill>
              <CssParameter name="fill"><?php echo($color)?></CssParameter>
            </Fill>
            <Stroke>
              <CssParameter name="stroke">#333333</CssParameter>
              <CssParameter name="stroke-width">1</CssParameter>
            </Stroke>
          </PolygonSymbolizer>
        </Rule>
		<?php } 
			$colVal =($i*$step);
			$color = "#". sprintf("%02X%02X%02X", $colVal, $colVal, $colVal);				
		?>
		
		<Rule>
          <Title>no data</Title>
          <ogc:Filter>
            <ogc:PropertyIsNull>
                <ogc:PropertyName>tag</ogc:PropertyName>
           </ogc:PropertyIsNull>
         </ogc:Filter>
          <PolygonSymbolizer>
            <Fill>
              <CssParameter name="fill"><?php echo($color)?></CssParameter>
            </Fill>
            <Stroke>
              <CssParameter name="stroke">#333333</CssParameter>
              <CssParameter name="stroke-width">1</CssParameter>
            </Stroke>
          </PolygonSymbolizer>
        </Rule>
      </FeatureTypeStyle>
    </UserStyle>
  </NamedLayer>
</StyledLayerDescriptor>
