DJS
===


Introduction
------------

DJS est un parseur qui permet d'ajouter quelques petites simplifications d'écriture a JavaScript.
La version de base est en PHP, mais il est prévu d'en écrire une en C++ et pourquoi pas en JS, à chacun sa façon de faire.

Utilisation
-----------

Pour utiliser DJS PHP, il suffit d'instancier le parseur et de lui passer le chemin du fichier:
`````php
<?php

$djs = new \djs\DJS();
$javascriptSrc = $i->parseFile(dirname(__FILE__).'/demo/main.djs');

?>
`````

On peut aussi vouloir générer un fichier JavaScript directement:
`````php
<?php

$djs = new \djs\DJS();
$i->parseFile(
	dirname(__FILE__).'/demo/main.djs', 
	dirname(__FILE__).'/resources/main.js'
);

?>
`````

Les simplifications d'écriture au niveau JavaScript
----------------------------------------------------

Rien n'est plus parlant que des exemples, alors nous allons commencer par l'écriture d'une classe:
`````javascript
class Vehicle
{
	// Déclaration d'un constructeur
	construct()
	{
	}
	
	klaxon()
	{
		console.log("pouet pouet");
	}
}
`````

Ou encore l'héritage d'une classe à une autre
`````javascript
class Truck extends Vehicle
{
	construct()
	{
		// Appel au constructeur parent
		super.construct();
	}
	
	klaxon()
	{
		console.log("TuuuuTuuuuu");
	}
}
`````

Utilisation du mot clé "static"
`````javascript

class MaClasse
{
	static nbInstances = 0;
	
	construct()
	{
		MaClasse.nbInstances++;
	}
	
	static uneMethodeStatique()
	{
	
	}
}

`````

L'utilisation de namespace
`````javascript
namespace myproject
{
	class Customer
	{
		// ...
	}
}
`````

Il est possible de les imbriquer:
`````javascript
namespace project1
{
	namespace subproject1
	{
		// ...
	}
}
`````

Ou encore de les créer d'affilé:
`````javascript
namespace project1.subproject1
{
	// ...
}
`````

Et pour les utiliser:
`````javascript
namespace project1
{
	class MaClasse extends project1.subproject1.MaClassParente
	{
		// ...
	}
}

var o = new project1.MaClasse();

`````

Possibilité d'apporter des éléments variables dans des chaines de caractères:
`````javascript

function sayHello(name)
{
	console.log("Hello ${name}");
}

sayHello("John");

`````

Possibilité d'inclure un ou plusieurs fichiers afin de pouvoir mieux séparer son code (par exemple une classe par fichier):
`````javascript

import "project1/subproject1/MaClassParente.djs";
import "project1/MaClasse.djs";

var o = new project1.MaClasse();

`````

La fonctionnalité "import" utilise le chemin du fichier à parser comme chemin de base pour importer les autres, de ce 
fait, tous les "import" se font a partir du fichier principal.

exemple:
`````javascript
// Fichier /js/test.djs

// Import du fichier /js/project1/MaClasse.djs
import "project1/MaClasse.djs";
`````
