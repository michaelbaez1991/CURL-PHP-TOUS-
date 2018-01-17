<?php 

include "dbase.php";

$portal = "";
$ruta = 'C:/Users/Soporte/Downloads/';

$cant = 0;
$db = new SqlConexion();
if(isset($_GET["portal"])){
  $portal = $_GET["portal"];
}
if(isset($_GET["error"])){
  // save history
        //$db->query("INSERT INTO EXITO_HSTRL_GMAIL (fecha, nombre_archivo, referencia) VALUES( GETDATE(),'".$_GET["filenamedw"]."', 'ERROR - Size Undefined' )");
echo "<br><div style='width:100%; height:100%; background-color:black; color:white;'><h1>Script Finalizado! - El archivo <b>NO</b> contenia Informacion..  El Navegador se cerrará Automáticamente en 4 Minutos. </h1></div><hr>";
  
    die();
}
echo 'portal:'.$portal.'<br>';
echo 'File:'. $_GET["filenamedw"].'<br>';

if (strtoupper($portal) == 'TOUS'){
  if (isset($_GET["filenamedw"])){
    $pedido='';
    $fecha='';
    $total='';
    $usuario='';
    $nombres='';
    $mail='';
    $identidad='';
    $pago='';
    $estadoPago='';
    $entrega='';
    $estadoEntrega='';
    $direccion='';
    $ciudad='';
    $departamento='';
    $arraydireccion= array( );
    $costoEntrega='';
    $referencias='';
    $observciones='';
    $telefono='';
                 
    $fp = fopen ($ruta.$_GET["filenamedw"],"r");
    $query="DECLARE @xml XML=
              '<root>";
    $queryItems="DECLARE @xml XML=
                    '<root>";
      $FECHAAAA =new DateTime("now",new DateTimeZone("America/New_York"));
    //print_r($FECHAAAA);
    $cuenta_100 = 0;
      while ($data = fgetcsv ($fp, 5000, ";")) {
             if($cuenta_100 >= 100 ){
                   break;
                 }    
         $cuenta_100 ++; 
         
                 $pedido=$data[0];
                 $fecha=$data[1];       
                 $fecha = str_replace('CEST 2017', '', $fecha);
                 $fechaEntrega = date("d-m-Y  H:i:s", strtotime($fecha.'+ 7 days'));
                 $fecha = Date("d-m-Y  H:i:s",strtotime($fecha));

                 $dateTime = new DateTime(($fecha));
                 $interval = date_diff($dateTime, $FECHAAAA);
                 
                 $diferecia = $interval->format('%a');
                 //echo $diferecia.'<br>';
                 /*
         if($diferecia > 30 ){
                   continue;
                 }
                */
                 
                 $total=$data[2];
                 $usuario=explode('[',$data[3]);
                 if(isset($usuario[0])) $nombres =$usuario[0];
                 if(isset($usuario[1])) $mail =$usuario[1];
                 $pos = strrpos($mail,'|');
                 if($pos !== 0 ){
                    //echo 'posicion :'.$pos;
                    $mail = substr($mail,$pos+1);
                    $mail = str_replace(']','',$mail);
                 }
                 $identidad=$data[4];
                 $pago=$data[5];
                 $estadoPago=$data[6];
                 $entrega=$data[7];
                 $estadoEntrega=$data[8];
                 $arraydireccion=explode(',',$data[9]);
                 if(isset($arraydireccion[0])) $direccion =$arraydireccion[0];
                 if(isset($arraydireccion[1])) $ciudad =$arraydireccion[1];
                 if(isset($arraydireccion[2])) $departamento =$arraydireccion[2];
                 $costoEntrega=$data[10];
                 $referencias=explode(',',$data[11]);
                 $observciones=$data[12];
                 $telefono=$data[13];
                  
                 /* 
                 echo $nombres.'<br>';
                 echo $direccion.'<br>';
                 echo $ciudad.'<br>';
                 echo $departamento.'<br>';
                 print_r($referencias);
                 echo '<br>';
                 print_r($parts);
                 
                 echo '<br>';
                 */
                 $AddressBilling='LastName:'.$nombres.'|';
                 $AddressBilling.='City:'.$ciudad.'|';
                 $AddressBilling.='Address1:'.$direccion.'|';
                 $AddressBilling.='Address2:'.$departamento.'|';
                 $AddressBilling.='Email:'.$mail.'|';
                 $AddressBilling.='Phone:'.$telefono.'|';
                  
              if($cant > 0 ){
                  echo $fecha.'<br>';
                  echo $pedido.'<br>';
                  
                  $query.= "<order>";
                  $query.= "<OrderId>".$pedido."-".$portal."</OrderId>";
                  $query.= "<OrderNumber>".$pedido."-".$portal."</OrderNumber>";
                  $query.= "<CustomerFirstName>".$nombres."</CustomerFirstName>";
                  $query.= "<CustomerLastName>".$nombres."</CustomerLastName>";
                  $query.= "<NationalRegistrationNumber>".$identidad."</NationalRegistrationNumber>";
                  $query.= "<Price>".$total."</Price>";
                  $query.= "<Statuses>Pending</Statuses>";          
                  $query.= "<CreatedAt>".$fecha."</CreatedAt>";
                  $query.= "<UpdatedAt>".$fecha."</UpdatedAt>";
                  $query.= "<PromisedShippingTime>".$fechaEntrega."</PromisedShippingTime>";
                  $query.= "<ItemsCount>".count($referencias)."</ItemsCount>";
                  $query.= "<AddressBilling>".$AddressBilling."</AddressBilling>";
                  $query.= "<AddressShipping>".$AddressBilling."</AddressShipping>";
                  $query.= "<PaymentMethod>".$pago."</PaymentMethod>";
                  $query.= "<Remarks>1</Remarks>";
                  $query.= "<DeliveryInfo>1</DeliveryInfo>";
                  $query.= "<GiftMessage>1</GiftMessage>";
                  $query.= "<VoucherCode>1</VoucherCode>";
                  $query.= "<ExtraAttributes>1</ExtraAttributes>";
                  $query.= "</order>";
                  $contador=0;
                  foreach ( $referencias as $items ) {

                    $parts = preg_split('/\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s+/', $items);
                    //  $parts = preg_split('/\t\t\t\t\t+/', $items);
                      var_dump($parts);
                      $descripcion = '';
                      $barras = '';

                      $cantidad = '';
                      $precio = '';
                      
                      if(isset($parts[0])) {
                        $temp = explode('[', $parts[0]);
                        if(isset($temp[0])) $descripcion = $temp[0];
                        if(isset($temp[1])) {
                          $barras = $temp[1];
                          $barras = str_replace(']','', $barras) ;
                          $barras = str_replace(':','', $barras) ;
                        }
                      }
                      //echo '$barras:'.$barras;
                      if(isset($parts[1])) {
                      //echo $pedido.'<br>';
                        
                        //$temp = explode('pieces x', $parts[1]);
                        $temp = explode('x', $parts[1]);
                        if(isset($temp[0])) $cantidad = $temp[0];
                        if(isset($temp[1])) $precio =  $temp[1];
                      }
                      //echo $cantidad.'<br>';

                      for ($i=0; $i < $cantidad; $i++) { 
                        //echo 'erdaaaaa';
                        $queryItems.= "<orderItem>";
                        $queryItems.= "<OrderItemId>".$pedido.$contador."-".$portal."</OrderItemId>";
                        $queryItems.= "<ShopId>".$pedido."</ShopId>";
                        $queryItems.= "<OrderId>".$pedido."-".$portal."</OrderId>";
                        $queryItems.= "<Sku>".$barras."</Sku>";
                        $queryItems.= "<ShopSku>".$barras."</ShopSku>";
                        $queryItems.= "<Imagen></Imagen>";
                        $queryItems.= "<ItemPrice>".$precio."</ItemPrice>";
                        $queryItems.= "<CreatedAt>".$fecha."</CreatedAt>";
                        $queryItems.= "<UpdatedAt>".$fecha."</UpdatedAt>";
                        $queryItems.= "<PromisedShippingTimes>".$fechaEntrega."</PromisedShippingTimes>";
                        $queryItems.= "<Status>Pending</Status>";
                        $queryItems.= "<ShipmentProvider>Coordinadora</ShipmentProvider>";
                        $queryItems.= "</orderItem>";
                      $contador++;
                    }
                  }
                   
                  // save history
                  /*$db->query("INSERT INTO EXITO_HSTRL_GMAIL
                        (fecha, nombre_archivo, referencia) 
                        VALUES(
                        GETDATE(),
                        '".$_GET["filenamedw"]."', '".
                        $pedido."')");*/
                }
                $cant++;
                
      
                  
      }
      fclose($fp);
      $query =$query ."</root>'";
      $queryItems.= "</root>'";
      $query =$query ."EXEC [dbo].[hrValidarOrdenesApiLinio] @xml,'".$portal."'";
      //echo $query;
      $consulta = $db->query($query);
      /* 
      $query = "select replace (orderid,concat('-','".$portal."'),'') from [TEMP-ORDENES_API_LINIO] where NMRSPR is null  and portal ='".$portal."'";
      $consulta = $db->query($query);
      $arreglo =$db->getResultQueryMatriz($query);
      */ 
      //print $queryItems;
      $queryItems =str_replace("&","Y",$queryItems);
      $queryItems.= "EXEC [dbo].[hrValidarOrdenesApiLinioItems] @xml,'".$portal."'";
      $consulta = $db->query($queryItems);
      $queryItems= "EXEC [dbo].[hrValidarOrdenesApiLinioCrearSeparado] '".$portal."'";
      print $queryItems;
      $consulta = $db->query($queryItems);
      print_r($db->getResultQueryMatriz($queryItems));
      echo "<br><div style='width:100%; height:100%; background-color:black; color:white;'><h1>Script Finalizado! - El Navegador se cerrará Automáticamente en 4 Minutos. </h1></div><hr>";
 /*
      

    }else {

    // save history
        /*  $db->query("INSERT INTO EXITO_HSTRL_GMAIL
                (fecha, nombre_archivo, referencia) 
                VALUES(
                GETDATE(),
                '".$_GET["filenamedw"]."', 'ERROR IN FILE' )");*/
  }
}
echo 'unlink';
unlink($ruta.$_GET["filenamedw"]);
print_r(error_get_last());

/*rename ($ruta.$_GET["filenamedw"], $ruta.'OK_'.$_GET["filenamedw"]);

print_r(error_get_last());*/
/*if($cant == 0){
  // save history
  $db->query("INSERT INTO EXITO_HSTRL_GMAIL (fecha, nombre_archivo, referencia) 
              VALUES(GETDATE(),'".$_GET["filenamedw"]."', 'No found' )");
}*/
?>