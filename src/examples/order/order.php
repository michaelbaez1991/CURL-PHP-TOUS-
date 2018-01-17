<?php
    require_once('C:/wamp/www/WSDLFalabella/src/core/Model/ModelAbstract.php');
    /*
    * Class Order
    **/
    class Order extends ModelAbstract{
        const NRO_F12 = 'NRO_F12';
        const REGION = 'REGION';
        const CIUDAD_RECEPTOR = 'CIUDAD_RECEPTOR';
        const DIRECCION_RECEPTOR = 'DIRECCION_RECEPTOR';
        const NOM_RECEPTOR = 'NOM_RECEPTOR';
        const TELEFONO_COMPRADOR = 'TELEFONO_COMPRADOR';
        const EMAIL = 'EMAIL';
        const DNI_COMPRADOR = 'DNI_COMPRADOR';
        const FECHA_EMISION_OC = 'FECHA_EMISION_OC';
        const FECHA_DESPACHO_PACTADA = 'FECHA_DESPACHO_PACTADA';
        const NRO_OC = 'NRO_OC';
        const UNIDADES = 'UNIDADES';
        const DESCRIPCION = 'DESCRIPCION';
        const MODELO_SKU = 'MODELO_SKU';
        const SKU = 'SKU';
        const PRECIO_COSTO = 'PRECIO_COSTO';
        const OBSERVACION = 'OBSERVACION';
        const UPC = 'OBSERVACION';

        protected $fieldDefinition = [
            self::NRO_F12 => self::TYPE_STRING,
            self::REGION => self::TYPE_STRING,
            self::CIUDAD_RECEPTOR => self::TYPE_STRING,
            self::DIRECCION_RECEPTOR => self::TYPE_STRING,
            self::NOM_RECEPTOR => self::TYPE_STRING,
            self::TELEFONO_COMPRADOR => self::TYPE_STRING,
            self::EMAIL => self::TYPE_STRING,
            self::DNI_COMPRADOR => self::TYPE_STRING,
            self::FECHA_EMISION_OC => self::TYPE_STRING,
            self::FECHA_DESPACHO_PACTADA => self::TYPE_STRING,
            self::NRO_OC => self::TYPE_STRING,
            self::UNIDADES => self::TYPE_STRING,
            self::DESCRIPCION => self::TYPE_STRING,
            self::MODELO_SKU => self::TYPE_STRING,
            self::SKU => self::TYPE_STRING,
            self::PRECIO_COSTO => self::TYPE_STRING,
            self::OBSERVACION => self::TYPE_STRING,
            self::UPC => self::TYPE_STRING
        ];

        public function NRO_F12(){
            return $this->data[self::NRO_F12];
        }

        public function LAST_NAME(){
            return $this->data[self::NOM_RECEPTOR];
        }

        public function DIRECCION_ENVIO(){
            $Direccion_general = '';
            $Direccion_general.= '|City:'.$this->data[self::REGION];
            $Direccion_general.= '|Country:'.$this->data[self::CIUDAD_RECEPTOR];
            $Direccion_general.= '|Address1:'.$this->data[self::DIRECCION_RECEPTOR];
            $Direccion_general.= '|Address2:'.'';
            //$Direccion_general.= '|FirstName:'.self::FIRSTNAME();
            $Direccion_general.= '|LastName: '.self::LAST_NAME();
            $Direccion_general.= '|Phone:'.$this->data[self::TELEFONO_COMPRADOR];
            $Direccion_general.= '|Email:'.$this->data[self::EMAIL];

            return $Direccion_general;
        }

        public function DNI_CLIENTE(){
            $dni_cliente = str_replace("-", "", $this->data[self::DNI_COMPRADOR]);
            return $dni_cliente;
        }

        public function FECHA_EMISION(){
            return $this->data[self::FECHA_EMISION_OC];
        }

        public function FECHA_DESPACHO(){
            return $this->data[self::FECHA_DESPACHO_PACTADA];
        }

        public function NRO_OC(){
            return $this->data[self::NRO_OC];
        }

        public function NRO_ITEMS(){
            return $this->data[self::UNIDADES];
        }

        public function DESCRIPCION_ITEM(){
            return $this->data[self::DESCRIPCION];
        }

        public function SKU(){
            return $this->data[self::MODELO_SKU];
        }

        public function SHOP_SKU(){
            return $this->data[self::SKU];
        }

        public function PRECIO_ITEM(){
            return $this->data[self::PRECIO_COSTO];
        }

        public function OBSERVACION(){
            return $this->data[self::OBSERVACION];
        }  

        public function UPC(){
            return $this->data[self::UPC];
        }     
    }
?>