<?php

class Mahasiswa
{
    public $nama;

    public function tampil()
    {
        echo $this->nama;
    }
}

$mhs = new Mahasiswa();
$mhs->nama = "DAFFA KATIANDA";

$mhs->tampil();

?>