<?php 
	require_once"src/core/Model/SqlConexion.php";
    require_once"VariablesEntorno.php";

    $db = new SqlConexion();
    $variables_entorno = new VariablesEntorno();
    //Toma los valores que vienen en las variables de entorno
		$user = $variables_entorno->getUser(); 
		$pass = $variables_entorno->getPass(); 
		$host = $variables_entorno->getHost(); 
		$portal = $variables_entorno->getPortal();

	// $portal = 'TOUS';
	// $host = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX"; //Host to connect
 // 	$user = "linio.kronotime@gmail.com";
	// $pass = 'kronotime2017';
        
    //Conexion

    	$inbox = imap_open($host, $user, $pass) or die ("No se puede conectar: ".imap_last_error());
	 
	//Busco todos los mensajes que coincidan con el asunto
		$emails = imap_search($inbox, 'SUBJECT "Ordenes tous"');

	//El ultimo q coincide
	    $indice = 0;
	    for ($index = 0; $index < count($emails); $index++) {
	       $indice = $emails[$index];
	    }
	 
	//Si encuentra algún correo electrónico, repita cada correo electrónico
		if($emails) {
		    $count = 1;
	        //Muestra informacion referente al correo
	        	$overview = imap_fetch_overview($inbox, $indice, 0);
	 
	        //Muestra mensaje
	        	$message = imap_fetchbody($inbox, $indice, 2);
	 
	        //Muestra la estructura de l correo
	        	$structure = imap_fetchstructure($inbox, $indice);
	 
	        $attachments = array();
	 
	        //Si hay archivos adjuntos encontrados.        
		        if(isset($structure->parts) && count($structure->parts)){
		            for($i = 0; $i < count($structure->parts); $i++){
		                $attachments[$i] = array(
		                    'is_attachment' => false,
		                    'filename' => '',
		                    'name' => '',
		                    'attachment' => ''
		                );
		 
		                if($structure->parts[$i]->ifdparameters){
		                    foreach($structure->parts[$i]->dparameters as $object) 
		                    {
		                        if(strtolower($object->attribute) == 'filename') 
		                        {
		                            $attachments[$i]['is_attachment'] = true;
		                            $attachments[$i]['filename'] = $object->value;
		                        }
		                    }
		                }
		 
		                if($structure->parts[$i]->ifparameters){
		                    foreach($structure->parts[$i]->parameters as $object) 
		                    {
		                        if(strtolower($object->attribute) == 'name') 
		                        {
		                            $attachments[$i]['is_attachment'] = true;
		                            $attachments[$i]['name'] = $object->value;
		                        }
		                    }
		                }
		 
		                if($attachments[$i]['is_attachment']){
		                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $indice, $i+1);
		 
		                    // 4 = codificación QUOTED-PRINTABLE
		                    if($structure->parts[$i]->encoding == 3) 
		                    { 
		                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
		                    }
		                    // 3 = codificación BASE64
		                    elseif($structure->parts[$i]->encoding == 4) 
		                    { 
		                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
		                    }
		                }
		            }
		        }
	 
	        //Iterar a través de cada archivo adjunto y guardarlo
		        foreach($attachments as $attachment) {
		        	//var_dump($attachment);
		            if($attachment['is_attachment'] == TRUE) {
		                $filename = $attachment['name'];

		                //if(empty($filename)) $filename = $attachment['filename'];
		 
		                //if(empty($filename)) $filename = time().".dat";
		 
		                /* prefix the email number to the filename in case two emails
		                 * have the attachment with the same file name.
		                 */
		                
		                $indicio = 'Base';

		               	if (strncmp($indicio, $filename, 4) === 0){
						   	$fp = fopen($filename, "w+");
			                fwrite($fp, $attachment['attachment']);
			                fclose($fp);

			                $nombre_archivo_descargado = $filename;
						}else{
							$fp = fopen($filename, "w+");
			                fwrite($fp, $attachment['attachment']);
			                fclose($fp);

			                $nombre_archivo_descargado = $filename;
						}
		            }
		        }
		} 

	//Cerrar la conexion
		imap_close($inbox);
		//echo "Baje el archivo<br>"; 

    $cant = 0;
    if(strtoupper($portal) == 'TOUS'){
	    $pedido = '';
	    $fecha = '';
	    $total = '';
	    $usuario = '';
	    $nombres = '';
	    $mail = '';
	    $identidad = '';
	    $pago = '';
	    $estadoPago = '';
	    $entrega = '';
	    $estadoEntrega = '';
	    $direccion = '';
	    $ciudad = '';
	    $departamento = '';
	    $arraydireccion =  array( );
	    $costoEntrega = '';
	    $referencias = '';
	    $observciones = '';
	    $telefono = '';

	    //$nombre_archivo_descargado = "list.csv";
	    $fp = fopen($nombre_archivo_descargado, "r");
	    $query = "DECLARE @xml XML = '<root>";
	    $queryItems = "DECLARE @xml XML = '<root>";
	    $FECHAAAA = new DateTime("now", new DateTimeZone("America/New_York"));
	    $cuenta_100 = 0;

	    while ($data = fgetcsv($fp, 5000, ";")) {
	        //var_dump($data);
	        if($cuenta_100 >= 250){
	            break;
	        }    

	        $cuenta_100 ++; 
	        $pedido = $data[0];
	        $fecha = $data[1];       
	        $fecha = str_replace('CEST 2017', '', $fecha);
	        $fechaEntrega = date("d-m-Y  H:i:s", strtotime($fecha.'+ 7 days'));
	        $fecha = Date("d-m-Y  H:i:s",strtotime($fecha));

	        $dateTime = new DateTime(($fecha));
	        $interval = date_diff($dateTime, $FECHAAAA);

	        $diferecia = $interval->format('%a');
	           
	        $total = $data[2];
	        $usuario = explode('[', $data[3]);

	        if(isset($usuario[0])){
	            $nombres = $usuario[0];  
	        } 
	        
	        if(isset($usuario[1])){
	            $mail = $usuario[1];
	        } 

	        $pos = strrpos($mail, '|');

	        if($pos !== 0){
	        	if ($pos >= 36) {
	        		$mail = substr($mail, $pos+1);
		            $mail = str_replace(']', '', $mail);
	        	}else{
	        		$mail = substr($mail, $pos);
		            $mail = str_replace(']', '', $mail);
	        	} 
	        }

	        $estatus_orden = $data[6];
	        if(isset($estatus_orden)){
	        	if ($estatus_orden == 'PAID') {
	        		$estatus_orden = 'Pendiente';
	        	}elseif ($estatus_orden == 'REJECTED') {
	        		$estatus_orden = 'Rechazado';
	        	}elseif ($estatus_orden == 'IN_PROCESS') {
	        		$estatus_orden = 'En proceso';
	        	}elseif ($estatus_orden == 'ACCEPTED') {
	        		$estatus_orden = 'Pendiente';
	        	}elseif ($estatus_orden == 'REFUNDED') {
	        		$estatus_orden = 'Reintegrado';
	        	}
	        }

	        $identidad = $data[4];
	        $pago = $data[5];
	        $estadoPago = $data[6];
	        $entrega = $data[7];
	        $estadoEntrega = $data[8];
	        $arraydireccion = explode(',', $data[9]);

	        // if(isset($arraydireccion[0])){
	        //     $direccion = $arraydireccion[0];
	        // }

	        // if(isset($arraydireccion[1])){
	        //     $ciudad = $arraydireccion[1];
	        // } 

	        // if(isset($arraydireccion[2])){
	        //     $departamento = $arraydireccion[2];
	        // }

	        if(isset($arraydireccion[0])) $direccion = implode(" ", $arraydireccion);
	        if(isset($arraydireccion[1])) $ciudad = $arraydireccion[count($arraydireccion)-2];
	        if(isset($arraydireccion[2])) $departamento = $arraydireccion[count($arraydireccion)-1];
	            
	        $costoEntrega = $data[10];
	        $referencias = explode(',', $data[11]);
	        $observciones = $data[12];
	        $telefono = $data[13];

	        $AddressBilling = 'LastName:'.$nombres.'|';
	        $AddressBilling.= 'City:'.$ciudad.'|';
	        $AddressBilling.= 'Address1:'.$direccion.'|';
	        $AddressBilling.= 'Address2:'.$departamento.'|';
	        $AddressBilling.= 'Email:'.$mail.'|';
	        $AddressBilling.= 'Phone:'.$telefono.'|';

	        if($cant > 0){
	            $query.= "<order>";
	            $query.= "<OrderId>".$pedido."-".$portal."</OrderId>";
	            $query.= "<OrderNumber>".$pedido."-".$portal."</OrderNumber>";
	            $query.= "<CustomerFirstName>".$nombres."</CustomerFirstName>";
	            $query.= "<CustomerLastName>".$nombres."</CustomerLastName>";
	            $query.= "<NationalRegistrationNumber>".$identidad."</NationalRegistrationNumber>";
	            $total = (int) $total;
	            $query.= "<Price>".$total."</Price>";
	            $query.= "<Statuses>$estatus_orden</Statuses>";          
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
	            $contador = 0;

	           	$cantidad_items = count($referencias);
	        	$costo_envio = $costoEntrega / $cantidad_items;
	            
	            foreach ($referencias as $items) {
	                $parts = preg_split('/\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s\s+/', $items);
	                //var_dump($parts);	
	                $barras = '';

	                $cantidad = '';
	                $precio = '';
	        
	                if(isset($parts[0])) {
	                    $temp = explode('[', $parts[0]);
	                    if(isset($temp[0])){
	                        $descripcion = $temp[0];
	                    } 

	                    if(isset($temp[1])) {
	                        $barras = $temp[1];
	                        $barras = str_replace(']','', $barras) ;
	                        $barras = str_replace(':','', $barras) ;
	                    }
	                }

	                if(isset($parts[1])) {
	                    $temp = explode('x', $parts[1]);
	                    if(isset($temp[0])){
	                       $cantidad = $temp[0];
	                    }

	                    if(isset($temp[1])){
	                        $precio =  $temp[1];
	                    } 
	                }

	                $costo_total = $costo_envio + $precio;
	                // if ($cantidad == 0) {
	                // 	$precio_general_items = 0;
	                // 	$precio_con_flete = $precio + $precio_general_items;
	                // }else{
	                // 	$precio_general_items = $costoEntrega / $cantidad;
	                // 	$precio_con_flete = $precio + $precio_general_items;
	                // }
	                for ($i = 0; $i < $cantidad; $i++) { 
	                    $queryItems.= "<orderItem>";
                        $queryItems.= "<OrderItemId>".$pedido.$contador."-".$portal."</OrderItemId>";
                        $queryItems.= "<ShopId>".$pedido."</ShopId>";
                        $queryItems.= "<OrderId>".$pedido."-".$portal."</OrderId>";
                        $queryItems.= "<Sku>".$barras."</Sku>";
                        $queryItems.= "<ShopSku>".$barras."</ShopSku>";
                        $queryItems.= "<Imagen></Imagen>";
                        //$costo_total = (int) $costo_total;
                        //echo gettype($costo_total);
                        $queryItems.= "<ItemPrice>{$costo_total}</ItemPrice>";
                        //echo $precio_con_flete.'<br>';
                        $queryItems.= "<CreatedAt>".$fecha."</CreatedAt>";
                        $queryItems.= "<UpdatedAt>".$fecha."</UpdatedAt>";
                        $queryItems.= "<PromisedShippingTimes>".$fechaEntrega."</PromisedShippingTimes>";
                        $queryItems.= "<Status>$estatus_orden</Status>";
                        $queryItems.= "<ShipmentProvider>Coordinadora</ShipmentProvider>";
                        $queryItems.= "</orderItem>";
                      	$contador++;
	                }
	            }
	        }
	        $cant++;
	    }

	    fclose($fp);

	    $query.= "</root>'"; //Cierre del XML
        $queryItems.= "</root>'"; //Cierre del XML
        
        $query = str_replace("&","Y", $query);
        $query = $query."EXEC [dbo].[hrValidarOrdenesApiLinio] @xml,'".$portal."'";

        $queryItems = str_replace("&","Y", $queryItems);
        $queryItems = $queryItems."EXEC [dbo].[hrValidarOrdenesApiLinioItems] @xml,'".$portal."'";
	    //$queryReplace = "SELECT replace (orderid, concat('-','".$portal."'), '') FROM [TEMP-ORDENES_API_LINIO] WHERE NMRSPR is null and portal = '".$portal."'";
	    $querySeparado = "EXEC [dbo].[hrValidarOrdenesApiLinioCrearSeparado] '".$portal."'";
	   	// echo htmlentities($query);
	    // echo htmlentities($queryItems);
	   	$consulta = $db->query($query); 
        print_r($consulta);

        $consulta = $db->query($queryItems); 
        print_r($consulta);

	    $consulta = $db->query($querySeparado);
	    print_r($consulta);
    }
    unlink($nombre_archivo_descargado);
?>