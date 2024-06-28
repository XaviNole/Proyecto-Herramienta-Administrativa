<?php
    class terapias{
        private $nom_terapias;
        private $precio;
        
        public function __construct($nom_terapias,$precio){
            $this->nom_terapias = $nom_terapias;
            $this->precio = $precio;
        }

        public function setNom_terapias($nom_terapias){
            $this->nom_terapias=$nom_terapias;
        }

        public function getNom_terapias(){
            return $this->nom_terapias;
        }

        public function setPrecio($precio){
            $this->precio=$precio;
        }

        public function getPrecio(){
            return $this->precio;
        }
    }
    
    class terapeutas{
        private string $nom_terapeutas;
        private string $ap_terapeutas;
        private string $fec_contrato;
        private string $fec_despido;
        private int $edad_terapeuta;
        private int $cant_pac;

        public function __construct($nom_terapeutas,$ap_terapeutas,$fec_contrato,$fec_despido,$edad_terapeuta,$cant_pac) {
            $this->nom_terapeutas=$nom_terapeutas;
            $this->ap_terapeutas=$ap_terapeutas;
            $this->fec_contrato=$fec_contrato;
            $this->fec_despido=$fec_despido;
            $this->edad_terapeuta=$edad_terapeuta;
            $this->cant_pac=$cant_pac;
        }
        
        /*Getters y setters
        public function getNomTerapeutas(): string {return $this->nom_terapeutas;}
        public function getApTerapeutas(): string {return $this->ap_terapeutas;}
        public function getFecContrato(): string {return $this->fec_contrato;}
        public function getFecDespido(): string {return $this->fec_despido;}
        public function getEdadTerapeuta(): int {return $this->edad_terapeuta;}
        public function getCantPac(): int {return $this->cant_pac;}
        public function setNomTerapeutas(string $nom_terapeutas): void {$this->nom_terapeutas = $nom_terapeutas;}
        public function setApTerapeutas(string $ap_terapeutas): void {$this->ap_terapeutas = $ap_terapeutas;}
        public function setFecContrato(string $fec_contrato): void {$this->fec_contrato = $fec_contrato;}
        public function setFecDespido(string $fec_despido): void {$this->fec_despido = $fec_despido;}
        public function setEdadTerapeuta(int $edad_terapeuta): void {$this->edad_terapeuta = $edad_terapeuta;}
        public function setCantPac(int $cant_pac): void {$this->cant_pac = $cant_pac;}*/

        //Métodos
        public function insertarTerapeuta($nom_terapeutas,$ap_terapeutas,$fec_contrato,$fec_despido,$edad_terapeuta,$cant_pac) {
            return new terapeutas($nom_terapeutas,$ap_terapeutas,$fec_contrato,$fec_despido,$edad_terapeuta,$cant_pac);
        }
    }