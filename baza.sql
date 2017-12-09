create database przychodnia character set utf8 collate utf8_unicode_ci;
use przychodnia;
Set time_zone='+1:00';

CREATE TABLE specjalizacje (
id_specjalizacji INT NOT NULL auto_increment,
nazwa_specjalizacji VARCHAR(50) NOT NULL,

CONSTRAINT c_pk1 PRIMARY KEY(id_specjalizacji)

) ENGINE = InnoDB; 

CREATE TABLE recepcja (
id_recepcjonisty INT NOT NULL auto_increment,
imie VARCHAR(50) NOT NULL,
nazwisko VARCHAR(50) NOT NULL,
login VARCHAR(50) NOT NULL,
haslo VARCHAR(50) NOT NULL,

CONSTRAINT c_pk2 PRIMARY KEY(id_recepcjonisty)

) ENGINE = InnoDB; 

CREATE TABLE dyrektor (
id_dyrektora INT NOT NULL auto_increment,
imie VARCHAR(50) NOT NULL,
nazwisko VARCHAR(50) NOT NULL,
login VARCHAR(50) NOT NULL,
haslo VARCHAR(50) NOT NULL,

CONSTRAINT c_pk3 PRIMARY KEY(id_dyrektora)

) ENGINE = InnoDB; 

CREATE TABLE aktualnosci (
id_aktualnosci INT NOT NULL auto_increment,
data TIMESTAMP NOT NULL,
opis VARCHAR(50) NOT NULL,

CONSTRAINT c_pk4 PRIMARY KEY(id_aktualnosci)
) ENGINE = InnoDB; 

CREATE TABLE pacjenci (
id_pacjenta INT NOT NULL auto_increment,
imie VARCHAR(50) NOT NULL,
nazwisko VARCHAR(50) NOT NULL,
karta_pacjenta VARCHAR(255) NOT NULL,
pesel BIGINT NOT NULL,
nr_telefonu BIGINT NOT NULL,
kod VARCHAR(50) NOT NULL,

CONSTRAINT c_pk5 PRIMARY KEY(id_pacjenta)
) ENGINE = InnoDB; 

CREATE TABLE lekarze (
id_lekarza INT NOT NULL auto_increment,
login VARCHAR(50) NOT NULL,
haslo VARCHAR(50) NOT NULL,
imie VARCHAR(50) NOT NULL,
nazwisko VARCHAR(50) NOT NULL,
id_specjalizacji INT NOT NULL,
nr_pokoju VARCHAR(50) NOT NULL,

CONSTRAINT c_pk6 PRIMARY KEY(id_lekarza)
) ENGINE = InnoDB; 


CREATE TABLE spotkania (
id_spotkania INT NOT NULL auto_increment,
id_specjalizacji INT NOT NULL,
id_lekarza INT NOT NULL,
id_osoby INT NOT NULL,
data_odbycia VARCHAR(50),
data_zapisu TIMESTAMP NOT NULL,
stan enum('0','1') NOT NULL DEFAULT '0',

CONSTRAINT c_pk7 PRIMARY KEY(id_spotkania),
CONSTRAINT c_fk FOREIGN KEY(id_specjalizacji) REFERENCES lekarze (id_lekarza)
ON UPDATE CASCADE ON DELETE CASCADE,
CONSTRAINT c_fk2 FOREIGN KEY(id_lekarza) REFERENCES lekarze (id_lekarza)
ON UPDATE CASCADE ON DELETE CASCADE,
CONSTRAINT c_fk3 FOREIGN KEY(id_osoby) REFERENCES pacjenci (id_pacjenta)
ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB; 


CREATE OR REPLACE VIEW widok_lekarze(imie, nazwisko, specjalizacja, nr_pokoju) AS SELECT imie, nazwisko, specjalizacje.nazwa_specjalizacji, nr_pokoju FROM lekarze, specjalizacje WHERE lekarze.id_specjalizacji=specjalizacje.id_specjalizacji;
CREATE OR REPLACE VIEW widok_lista(id_spotkania, id_osoby, id_lekarza, pesel, imie_osoby, nazwisko_osoby, specjalizacja, imie_lekarza, nazwisko_lekarza, nr_pokoju, data_odbycia, data_zapisu, stan) AS SELECT id_spotkania, pacjenci.id_pacjenta, spotkania.id_lekarza, pacjenci.pesel, pacjenci.imie, pacjenci.nazwisko, specjalizacje.nazwa_specjalizacji, lekarze.imie, lekarze.nazwisko, lekarze.nr_pokoju, data_odbycia, data_zapisu, stan FROM spotkania, lekarze, specjalizacje, pacjenci where spotkania.id_lekarza=lekarze.id_lekarza and spotkania.id_specjalizacji=specjalizacje.id_specjalizacji and spotkania.id_osoby=pacjenci.id_pacjenta;



CREATE PROCEDURE zatw ()
BEGIN
UPDATE spotkania SET stan='1' WHERE FROM_UNIXTIME(data_odbycia) <= NOW() AND stan='0';
END;

