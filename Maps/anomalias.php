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
    <script type="text/javascript" src="../FrameWork/dataTables/js/jquery.js"></script> 
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBLVhMqSQIQ4NfReV9ZtyZ-8RJpdg-DSaQ"></script>
    <script>   

    function initialize(data) {  
    var coordinatesCenter = [];
    var coordinatesOne    = [];   

    coordinatesCenter = data[0]['pocision'].split(":");
    
    var mapOptions = {
      zoom: 18,
      center: new google.maps.LatLng(coordinatesCenter[0],coordinatesCenter[1])
    };

    var map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    var infowindow;
 
    for (var i=0;i<data.length;i++) {
      coordinatesOne = data[i]['pocision'].split(":");      
      var myLatLng = new google.maps.LatLng(coordinatesOne[0],coordinatesOne[1]);
      
      var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        icon: "../imagenes/iconos/number_"+data[i]['anomalia']+".png",  
        title: data[i]['cuenta'],
        zIndex: 2,
        animation: google.maps.Animation.DROP
      });

      var marker1 = new google.maps.Marker({
        position: myLatLng,
        map: map,
        zIndex: 1,
        icon: "../imagenes/iconos/home1.png"              
      });
      
      (function(i, marker) {
          google.maps.event.addListener(marker,'click',function() {
          if (!infowindow) {
            infowindow = new google.maps.InfoWindow();
          } 

           var contentString = "<img src='img/"+img+"' WIDTH='60' HEIGHT='60'></img>";
            var info = "<p><b>Cuenta: </b>"+data[i]['cuenta']+"</p><p><b>Mensaje: </b>"+data[i]['mensaje']+"</p>"+"<img src='../TreeFiles/FotosActas/"+data[i]['cuenta']+"/"+data[i]['fecha']+"/"+data[i]['cuenta']+"_0"+".png' WIDTH='320' HEIGHT='174'></img>";
            infowindow.setContent(info);
            infowindow.open(map, marker);
          });
      })(i, marker);
     }            
    }

    </script>    
    <script type="text/javascript">
     var mes = '<?php echo $_GET["Mes"];?>';     
     var anno  =  '<?php echo $_GET["Anno"];?>';
     var ciclo  =  '<?php echo $_GET["Ciclo"];?>';
     var municipio  =  '<?php echo $_GET["Municipio"];?>';
     var ruta  =  '<?php echo $_GET["Ruta"];?>';
     
     window.onload = function() {
        ConsultarDatos(mes,anno,ciclo,municipio,ruta);
      }

     function ConsultarDatos(mes,anno,ciclo,municipio,ruta){
      var mesM       = mes;
      var annoM      = anno;
      var cicloM     = ciclo;
      var municipioM = municipio;    
      var rutaM      = ruta;

      var SendInformacionN =  $.ajax({    
                            async:    true,
                            type:     "POST",
                            dataType: "json",
                            url:      "../Ajax/AjaxConsultas.php",
                            data:   {   Peticion:   "ConsultaAnomalias",
                                        Mes:       mesM,
                                        Anno:      annoM,
                                        Ciclo:     cicloM,
                                        Municipio: municipioM,
                                        Ruta:      rutaM                                                            
                                },
                            success:function(data){                                                        
                            // alert(JSON.stringify(data));
                             initialize(data);
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