# Installation du driver MongoDB pour PHP (Windows)

Ce dossier contient le driver natif MongoDB pour PHP (`php_mongodb.dll`) necessaire pour utiliser MongoDB avec PixelVerse Studios.

## Fichiers

- `php_mongodb.dll` : Extension PHP MongoDB (version Windows x64)

## Installation

### 1. Verifier votre version de PHP

Ouvrez un terminal et executez :
```bash
php -v
```

Notez :
- La version de PHP (ex: 8.1.0)
- L'architecture (x64 ou x86)
- Le type de thread safety (TS ou NTS)

### 2. Copier le fichier DLL

1. Localisez votre dossier `ext/` de PHP (ex: `C:\wamp64\bin\php\php8.1.0\ext\`)
2. Copiez `php_mongodb.dll` dans ce dossier

### 3. Activer l'extension

1. Ouvrez votre fichier `php.ini`
2. Ajoutez la ligne suivante :
   ```ini
   extension=mongodb
   ```
3. Redemarrez Apache/WAMP

### 4. Verifier l'installation

Creez un fichier `test_mongodb.php` :
```php
<?php
phpinfo();
?>
```

Ouvrez-le dans votre navigateur et recherchez "mongodb". Si vous voyez la section mongodb, l'installation est reussie.

### 5. Alternative : Composer

Si vous preferez utiliser Composer (deja configure dans le projet) :
```bash
composer install
```

Cela installera la librairie PHP `mongodb/mongodb` qui utilise l'extension native.

## Depannage

- **Erreur "Unable to load dynamic library"** : Verifiez que le fichier DLL correspond a votre version de PHP (TS/NTS, x64/x86)
- **MongoDB non visible dans phpinfo()** : Verifiez le chemin du `php.ini` utilise par Apache (peut differer du CLI)
- **WAMP specifique** : Cliquez sur l'icone WAMP > PHP > Extensions > cochez `php_mongodb`

## Liens utiles

- Documentation officielle : https://www.php.net/manual/en/set.mongodb.php
- PECL MongoDB : https://pecl.php.net/package/mongodb
