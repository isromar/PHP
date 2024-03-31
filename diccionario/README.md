# Diccionario
Funciona con un formulario que permite ingresar pares de frases en inglés y español, además de la pronunciación. Almacena la información en una base de datos y muestra los registros ordenados alfabéticamente, además permite borrar los ya existentes. Tiene un botón 'Play' que al pulsarlo pronuncia el texto en inglés.  

*It works with a form that allows entering pairs of phrases in English and Spanish, along the pronunciation.
Stores the information in a database and displays the records alphabetically, also allowing the deletion of existing ones.
It has a 'Play' button that, when clicked, pronounces the English text.*

## Database
Para que funcione correctamente hay que crear la base de datos

*To work correctly, the database must be created*
  
CREATE DATABASE IF NOT EXISTS traducciones;  
CREATE TABLE english_spanish (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    english_text VARCHAR(255) NOT NULL,  
    spanish_text VARCHAR(255) NOT NULL,
    pronuntiation VARCHAR(150)
)

## Image preview
![Preview](https://raw.githubusercontent.com/isromar/php/main/diccionario/preview.JPG)

## How it works
[Video How it works](https://youtu.be/i-SQhMCgNhQ)

## Idea
Este archivo surge porque al leer textos en inglés y aparecer algunas palabras que desconozco, pienso que estaría bien poder disponer de un diccionario propio donde poder almacenarlas y de ese modo facilitar la memorización.  

*This file arises from reading texts in English and finding some unknown words. I think it would be beneficial to have our own dictionary where we can store them, thus facilitating memorization.*

## Autor
Isabel Rodenas Marin

## Try it
[Try it](https://isabelrodenas.es/diccionario/)