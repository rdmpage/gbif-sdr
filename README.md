# Species Distribution Repository (SDR)

This repository is base don a export from Google Code, the README is based on the “About.wiki” file.

Species distributions or ranges are a very fundamental information for biodiversity studies and conservation. Without knowledge of where species are most of the envision biodiversity services  are not possible. Most of the information on species distributions comes from:

- Range maps published as images or shapefiles.
- Formatted text, like a list of countries where species occur or other area definitions.
- Raster systems based on multiple “grid” formats, like UTM cells, AFE divisions, etc.

All this information is now currently published on different websites and in multiple formats. This makes very hard the accessibility of information and makes almost impossible merging information from multiple sources into common analysis/reports/APIs etc.

The purpose of the Species Distribution Repository is to provide a common places where all these different sources can be aggregated and transformed into a common data model/format. On top of this common data model a set of visualization APIs and data APIs had been developed to allow programatic access and sharing of the data.

[!http://gbifsdr.s3.amazonaws.com/VisitWebsite.png]

Technically SDR is a;

- PostGIS database for incorporating all data
- a set shell scripts for importing new data sources 
- a website developed in PHP allow the exploring of current data
- a set of Flash/Flex visualizations using Google Maps to present the information as widgets.

The main components of the project are:

- The Geospatial DataStore and the managing scripts
- The Named Area Repository
- The Visualization and data API

GBIF Secretariat contacts for this work:

 * David Remsen, Senior Programme Officer (ECAT) dremsen@gbif.org