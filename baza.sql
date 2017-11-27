create database przychodnia character set utf8 collate utf8_unicode_ci;
use przychodnia;
Set time_zone='+1:00';

CREATE TABLE aktualnosci (
id_aktualnosci INT NOT NULL auto_increment,
data TIMESTAMP NOT NULL,
opis VARCHAR(50) NOT NULL,

CONSTRAINT c_pk0 PRIMARY KEY(id_aktualnosci)
) ENGINE = InnoDB; 

CREATE TABLE pacjenci (
id_pacjenta INT NOT NULL auto_increment,
imie VARCHAR(50) NOT NULL,
nazwisko VARCHAR(50) NOT NULL,
karta_pacjenta VARCHAR(255) NOT NULL,
pesel BIGINT NOT NULL,
nr_telefonu BIGINT NOT NULL,
kod VARCHAR(50) NOT NULL,

CONSTRAINT c_pk PRIMARY KEY(id_osoby)
) ENGINE = InnoDB; 

CREATE TABLE lekarze (
id_lekarza INT NOT NULL auto_increment,
login VARCHAR(50) NOT NULL,
haslo VARCHAR(50) NOT NULL,
imie VARCHAR(50) NOT NULL,
nazwisko VARCHAR(50) NOT NULL,
id_specjalizacji INT NOT NULL,
nr_pokoju VARCHAR(50) NOT NULL,

CONSTRAINT c_pk2 PRIMARY KEY(id_lekarza)
) ENGINE = InnoDB; 


CREATE TABLE spotkania (
id_spotkania INT NOT NULL auto_increment,
id_specjalizacji INT NOT NULL,
id_lekarza INT NOT NULL,
id_osoby INT NOT NULL,
data_odbycia VARCHAR(50),
data_zapisu TIMESTAMP NOT NULL,
stan enum('0','1') NOT NULL DEFAULT '0',

CONSTRAINT c_pk3 PRIMARY KEY(id_zabiegu),
CONSTRAINT c_fk FOREIGN KEY(id_specjalizacji) REFERENCES lekarze (id_lekarza)
ON UPDATE CASCADE ON DELETE CASCADE,
CONSTRAINT c_fk2 FOREIGN KEY(id_lekarza) REFERENCES lekarze (id_lekarza)
ON UPDATE CASCADE ON DELETE CASCADE,
CONSTRAINT c_fk3 FOREIGN KEY(id_osoby) REFERENCES pacjenci (id_pacjenta)
ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB; 



CREATE PROCEDURE zatw ()
BEGIN
UPDATE spotkania SET stan='1' WHERE FROM_UNIXTIME(data_odbycia) <= NOW() AND stan='0';
END;

