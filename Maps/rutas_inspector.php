<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Dashed Lines (Symbols)</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDduv6W06zHtwG2JhbyQEEWu2R6Om4rAkg&sensor=FALSE"></script>
    <script>   

    function initialize(data) {
      var coordinatesOne    = [];
      var coordinatesTwo    = [];
      var coordinatesCenter = [];
      var linePuntos        = [];
      
      coordinatesCenter = data[0].split(":");

      var mapOptions = {
        zoom: 19,
        center: new google.maps.LatLng(coordinatesCenter[0],coordinatesCenter[1]),
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      var map = new google.maps.Map(document.getElementById('map-canvas'),
          mapOptions);

      var symbolOne = {
        path: 'M -2,0 0,-2 2,0 0,2 z',
        strokeColor: '#F00',
        fillColor: '#F00',
        fillOpacity: 3,
        scale: 2
      };

      var polyAerea = {                
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2,          
        map: map
      };
 
      for(i=0;i<data.length+1;i++){        
        coordinatesOne = data[i].split(":");
        if((data.length-i)!=1){
          coordinatesTwo = data[i+1].split(":");
          var poly = new google.maps.Polyline(polyAerea);
          var path = [new google.maps.LatLng(coordinatesOne[0],coordinatesOne[1]), new google.maps.LatLng(coordinatesTwo[0],coordinatesTwo[1])];
          poly.setPath(path);
        }

        linePuntos[i] = new google.maps.LatLng(coordinatesOne[0],coordinatesOne[1]);

        var marker = new google.maps.Marker({
            position: linePuntos[i],
            map: map,
            icon: "../imagenes/iconos/"+i+".png",        
            title: i.toString()
        });
      }             
    }

    </script>
    <script type="text/javascript" src="../js/dataTable/jquery.js"></script> 
    <script type="text/javascript">
     var mes = '<?php echo $_GET["Mes"];?>';     
     var anno  =  '<?php echo $_GET["Anno"];?>';
     var ciclo  =  '<?php echo $_GET["Ciclo"];?>';
     var municipio  =  '<?php echo $_GET["Municipio"];?>';
     var ruta  =  '<?php echo $_GET["Ruta"];?>';
     var insp  =  '<?php echo $_GET["Inspector"];?>';
     
     window.onload = function() {
        ConsultarDatos(mes,anno,ciclo,municipio,ruta,insp);
      }

     function ConsultarDatos(mes,anno,ciclo,municipio,ruta,insp){
      var mesM       = mes;
      var annoM      = anno;
      var cicloM     = ciclo;
      var municipioM = municipio;    
      var rutaM      = ruta;
      var inspM      = insp;

      var SendInformacionN =  $.ajax({    
                            async:    true,
                            type:     "POST",
                            dataType: "json",
                            url:      "../Ajax/AjaxConsultas.php",
                            data:   {   Peticion:   "ConsultaRutaInspector",
                                        Mes:       mesM,
                                        Anno:      annoM,
                                        Ciclo:     cicloM,
                                        Municipio: municipioM,
                                        Ruta:      rutaM,
                                        Inspector: inspM                                                          
                                },
                            success:function(data){                             
                              initialize(data);
                              //alert(JSON.stringify(data));
                            }
                          });

      SendInformacionN.fail(function(jqXHR, textStatus) {
            alert( "Error en la consulta GPS." );
          });
     }

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>