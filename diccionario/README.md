# Diccionario
Almacena la información en una base de datos y muestra dinámicamente los registros ordenados alfabéticamente, además permite borrar los ya existentes.  
Tiene un botón que al pulsarlo pronuncia el texto en inglés.  
Funciona con un formulario que permite ingresar pares de frases en inglés y español, además de la pronunciación.  

## Autor
Isabel Rodenas Marin

## Base de Datos
Para que funcione correctamente hay que crear la base de datos
  
CREATE DATABASE IF NOT EXISTS traducciones;  
CREATE TABLE english_spanish (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    english_text VARCHAR(255) NOT NULL,  
    spanish_text VARCHAR(255) NOT NULL,
    pronuntiation VARCHAR(150)
)

## Image preview
![Preview](https://raw.githubusercontent.com/isromar/php/main/diccionario/preview.JPG)

# Idea
Este archivo surge porque al leer textos en inglés y aparecer algunas palabras que desconozco, pienso que estaría bien poder disponer de un diccionario propio donde poder almacenarlas y de ese modo facilitar la memorización.  
