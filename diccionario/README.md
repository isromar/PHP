# Traductor

Este archivo surge porque al leer textos en inglés y aparecer algunas palabras que desconozco, pienso que estaría bien poder disponer de un diccionario propio donde poder almacenarlas y de ese modo facilitar la memorización.  
Funciona con un formulario que permite ingresar pares de frases en inglés y español.  
Almacena la información en una base de datos y muestra dinámicamente los registros ordenados alfabéticamente, además permite borrar los ya existentes.

## Autor
Isabel Rodenas Marin

## Base de Datos
Para que funcione correctamente hay que crear la base de datos
  
CREATE DATABASE IF NOT EXISTS traducciones;  
CREATE TABLE english_spanish (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    english_text VARCHAR(255) NOT NULL,  
    spanish_text VARCHAR(255) NOT NULL  
)

## Image preview
![Preview](https://raw.githubusercontent.com/isromar/PHP/main/traducciones/preview.JPG)
