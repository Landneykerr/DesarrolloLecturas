<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Demo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <link rel="stylesheet" type="text/css" href="../jquery.lightbox.css">
  <link rel="stylesheet" type="text/css" href="../galeria.css">
</head>

<body>
<div class="container">
  <h1>Responsive Lightbox</h1>

  <ul class="gallery">
  <?php
        
    $conn = pg_connect("user=consult_fotos password=l3ctur4sf0t0s dbname=fotos_lecturas host=186.115.150.189");
    
    $query = pg_query($conn, "SELECT foto,cuenta, fecha_toma FROM registro.imagenes_visor ORDER BY fecha_toma DESC");
    
    while($row   = pg_fetch_assoc($query)){            
      echo "<br>";
      echo "
        <li>
            <a href='data:image/jpg;base64,".$row['foto']."'  data-caption='Cuenta:".$row['cuenta']." FechaToma:".$row['fecha_toma']."' ><b>Ver Foto</b></a>
        </li>
      ";
      echo "<br>";
      
    }    
    pg_close($conn);
  ?> 
  <br>   
  </ul>
  <footer>
    <p>Lightbox Plugin and photos by <a href="http://www.twitter.com/duncanmcdougall">Duncan McDougall</a></p>
  </footer>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="../jquery.lightbox.js"></script>
<script>  
  $(function() {
    $('.gallery a').lightbox(); 
  });
</script>
</body>
</html>