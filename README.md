# Mapster

## Description

<p>The idea behind the project arose due to the common difficulties faced by new comers visiting / moving to the various regions within New Zealand, it is currently difficult to identify certain important socioeconomic factors which one would like to identify prior to travelling to New Zealand. Online sources currently available do not give a very comprehensive overview of these factors, thus this project was born mainly out of necessity and to provide everyone with an easy overview of these socioeconomic factors.</p>
<p>The project is created to help identifying areas within a particular region like Hawkes bay (but not limited to that region) to provide a geographical overview by making use of various data sets available online. the site once opened gives a bird’s eye view of the region and the user can then use filters to view a colour coded map with a description of what each colour represents or the approximate value each colour is associated with.</p>
<p>The potential of the idea is immense and can be expanded to various regions and even the world, the application is designed and compatible to include other data set and can cater to any region if the required data is available
The biggest advantage of the program is that it does not require a lot of information which makes it easier for any authority within any region to collect the data required to generate the results using the Mapster maps. </p>

## Installation Instructions
### Requirements
- MySQL database (v5.7.19 or newer)
- Webserver running PHP (v5.6 or newer)
- QGIS (v2.18 or newer)
- Text Editor

### Steps
- Download the sources
- Uploading the meshblock data to the database (using the ogr2ogr tool shipped with QGIS)
```
ogr2ogr -f \"MySQL\" MYSQL:\"DATABASE,host=HOSTNAME,user=USERNAME,password=PASSWORD,port=3306\" -nln \"mesh\" -s_srs \"EPSG:2193\" -t_srs \"EPSG:4326\" SHAPEFILE.shp -update -overwrite -lco GEOMETRY_NAME=SHAPE -lco ENGINE=MyISAM
```

- If you want to load our dataset, insert the sqldump to your database
```
mysql -h HOSTNAME -u USERNAME -p DATABASE < govhack.sql
```

- Crate a settings file to store the login credentials for the database called 'settings.ini' with the following content

```
[settings]
server = SERVERNAME
user = USERNAME
pass = PASSWORD
db = DATABASE
```


- To add additional aspects on meshblock level create new columns in the table
```
ALTER TABLE mesh ADD COLUMN name datatype
```



## Sources used
http://www3.stats.govt.nz/meshblock/2013/excel/2013_mb_dataset_Hawke's_Bay_Region.zip
http://policedata.nz/SASVisualAnalyticsViewer/VisualAnalyticsViewer_guest.jsp?reportName=Victim%20Time%20and%20Place&reportPath=/Live/Reports/&viewerMode=Classic&reportViewOnly=true
http://www3.stats.govt.nz/digitalboundaries/annual/ESRI_Shapefile_2017_Digital_Boundaries_High_Def_Clipped.zip
