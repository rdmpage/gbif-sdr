<?php

/**
 * PHP Geometry/WKT encoder/decoder
 *
 *   Mainly inspired by Openlayers/format/WKT.js
 *
 * @package GeoJSON
 *
 * @author Camptocamp <info@camptocamp.com>
 *
 */
class WKT
{

  private $regExes = array(
    'typeStr'               => '/^\s*(\w+)\s*\(\s*(.*)\s*\)\s*$/',
    'spaces'                => '/\s+/',
    'parenComma'            => '/\)\s*,\s*\(/',
    'doubleParenComma'      => '/\)\s*\)\s*,\s*\(\s*\(/',
    'trimParens'            => '/^\s*\(?(.*?)\)?\s*$/'
  );

  const POINT               = 'point';
  const MULTIPOINT          = 'multipoint';
  const LINESTRING          = 'linestring';
  const MULTILINESTRING     = 'multilinestring';
  const POLYGON             = 'polygon';
  const MULTIPOLYGON        = 'multipolygon';
  const GEOMETRYCOLLECTION  = 'geometrycollection';

  /**
   * Read WKT string into geometry objects
   *
   * @param string $WKT A WKT string
   *
   * @return Geometry|GeometryCollection
   */
  public function read($WKT)
  {
    $matches = array();
    if (!preg_match($this->regExes['typeStr'], $WKT, $matches))
    {
      return null;
    }

    return $this->parse(strtolower($matches[1]), $matches[2]);
  }

  /**
   * Parse WKT string into geometry objects
   *
   * @param string $WKT A WKT string
   *
   * @return Geometry|GeometryCollection
   */
  public function parse($type, $str)
  {
    $matches = array();
    $components = array();

    switch ($type)
    {
      case self::POINT:
        preg_match($this->regExes['spaces'], $str, $matches);
        $coords = explode($matches[0], trim($str));
        return new Point($coords[0], $coords[1]);

      case self::MULTIPOINT:
        $points = explode(',', trim($str));
        foreach ($points as $point)
        {
          $components[] = $this->parse(self::POINT, $point);
        }
        return new MultiPoint($components);

      case self::LINESTRING:
        $points = explode(',', trim($str));
        foreach ($points as $point)
        {
          $components[] = $this->parse(self::POINT, $point);
        }
        return new LineString($components);

      case self::MULTILINESTRING:
        preg_match($this->regExes['parenComma'], $str, $matches);
        $lines = explode($matches[0], trim($str));
        foreach ($lines as $l)
        {
          $line = preg_replace($this->regExes['trimParens'], '$1', $l);
          $components[] = $this->parse(self::LINESTRING, $line);
        }
        return new MultiLineString($components);

      case self::POLYGON:
        preg_match($this->regExes['parenComma'], $str, $matches);
        $rings = empty($matches)?array(trim($str)):explode($matches[0], trim($str));
        foreach ($rings as $r)
        {
          $ring = preg_replace($this->regExes['trimParens'], '$1', $r);
          $linestring = $this->parse(self::LINESTRING, $ring);
          $components[] = new LinearRing($linestring->getComponents());
        }
        return new Polygon($components);

      case self::MULTIPOLYGON:
        preg_match($this->regExes['doubleParenComma'], $str, $matches);
        $polygons = empty($matches)?array(trim($str)):explode($matches[0], trim($str));
        foreach ($polygons as $p)
        {
          $polygon = preg_replace($this->regExes['trimParens'], '$1', $p);
          $components[] = $this->parse(self::POLYGON, $polygon);
        }
        return new MultiPolygon($components);

      case self::GEOMETRYCOLLECTION:
        $str = preg_replace('/,\s*([A-Za-z])/', '|$1', $str);
        $wktArray = explode('|', trim($str));
        foreach ($wktArray as $wkt)
        {
          $components[] = $this->read($wkt);
        }
        return new GeometryCollection($components);

      default:
        return null;
    }
  }

  /**
   * Loads a WKT string into a Geometry Object
   *
   * @param string $WKT
   *
   * @return  Geometry
   */
  static public function load($WKT)
  {
    $instance = new self;
    return $instance->read($WKT);
  }

  /**
   * Dumps a Geometry Object into a  WKT string
   *
   * @param Geometry $geometry
   *
   * @return String A WKT string corresponding to passed object
   */
  static public function dump(Geometry $geometry)
  {
    if (is_null($geometry))
    {
      return null;
    }

    // TODO
    return 'LINESTRING(905529.176838753 80934.9861125366,905253.985811447 80952.2857982665,905156.030637281 80163.0305996348,904210.390862349 79519.3906365179,904312.01219508 81144.0717182127,904182.321835833 81980.1850060069,903962.696024282 82130.9557770041,903960.060972813 83649.8910975816,904468.16763647 83488.4655532541,904592.473325324 83933.6746840253,905055.554979101 84145.2807737151,904849.104424894 84999.953989246,906367.925178016 86817.9103678042,907586.120928785 86022.2393916833,907829.11850119 86189.393308769,907731.50702939 85176.8461400207,907115.592390423 85074.4228351032,907259.71824902 84762.4556546889,906779.451476969 84107.5880809658,906362.655075078 83946.9645088245,906314.99501373 83439.4306824435,906629.940947978 83147.0545368624,906547.91065008 82310.9412490683,905529.176838753 80934.9861125366)';
  }

}
