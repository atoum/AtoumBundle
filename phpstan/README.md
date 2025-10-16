# Extension PHPStan pour AtoumBundle

## 📖 Qu'est-ce que c'est ?

Cette extension PHPStan permet à l'analyse statique de comprendre les méthodes et propriétés magiques d'atoum.

## 🎯 Problème résolu

Sans cette extension, PHPStan générait des erreurs sur :

```php
$this
    ->if($user = new User())  // ❌ Call to undefined method if()
    ->then                     // ❌ Access to undefined property $then
        ->object($user)        // ❌ Call to undefined method object()
        ->isInstanceOf(User::class);
```

## ✅ Solution

L'extension `AtoumDynamicReturnTypeExtension` informe PHPStan que les méthodes magiques d'atoum (`if`, `then`, `and`, `when`, `given`, etc.) retournent l'instance courante pour permettre le chaînage fluent.

## 📁 Fichiers

- `AtoumDynamicReturnTypeExtension.php` - Extension PHPStan pour les méthodes magiques
- `extension.neon` - Configuration PHPStan pour enregistrer l'extension
- `README.md` - Ce fichier

## 🔧 Comment ça marche ?

### 1. Extension PHPStan

`AtoumDynamicReturnTypeExtension` implémente `DynamicMethodReturnTypeExtension` de PHPStan. Elle indique que les méthodes magiques retournent le type de l'objet appelant :

```php
public function getTypeFromMethodCall(...): Type
{
    // Retourne le type de l'objet appelant (pour le chaînage)
    $callerType = $scope->getType($methodCall->var);
    return $callerType;
}
```

### 2. Configuration

Dans `phpstan.neon`, l'extension est incluse :

```neon
includes:
    - phpstan/extension.neon

parameters:
    bootstrapFiles:
        - phpstan/AtoumDynamicReturnTypeExtension.php
```

### 3. Autoload Composer

Dans `composer.json`, le namespace est déclaré :

```json
"autoload": {
    "psr-4": {
        "atoum\\AtoumBundle\\PHPStan\\": "phpstan/"
    }
}
```

## 🆚 Différence avec `.ide-helper.php`

| Outil | But | Pour qui |
|-------|-----|----------|
| `.ide-helper.php` | Autocomplétion IDE | PHPStorm, VS Code, etc. |
| `phpstan/` | Analyse statique | PHPStan |
| `atoum/stubs` | Définitions de base | PHPStan + IDE |

**Les trois sont complémentaires et nécessaires !**

## 📊 Résultats

**Avant** (avec `ignoreErrors`) :
```neon
ignoreErrors:
    - '#Call to an undefined method .*::(if|then|and|when|given)#'
```

**Après** (avec l'extension) :
- ✅ Aucune erreur PHPStan sur les méthodes magiques
- ✅ Vérification des types correcte
- ✅ Autocomplétion fonctionnelle

## 🚀 Pour créer votre propre extension

Si vous souhaitez créer une extension PHPStan pour d'autres méthodes magiques :

1. **Créer la classe d'extension** :
   ```php
   class MyDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension {
       public function getClass(): string { /* ... */ }
       public function isMethodSupported(MethodReflection $method): bool { /* ... */ }
       public function getTypeFromMethodCall(...): Type { /* ... */ }
   }
   ```

2. **L'enregistrer dans `extension.neon`** :
   ```neon
   services:
       - class: My\Extension\Class
         tags: [phpstan.broker.dynamicMethodReturnTypeExtension]
   ```

3. **L'inclure dans `phpstan.neon`** :
   ```neon
   includes:
       - path/to/extension.neon
   ```

## 📚 Références

- [PHPStan - Custom Rules](https://phpstan.org/developing-extensions/custom-rules)
- [PHPStan - Dynamic Return Type Extensions](https://phpstan.org/developing-extensions/dynamic-return-type-extensions)
- [atoum Documentation](http://atoum.org/)

## ⚠️ Notes importantes

1. **Cette extension ne gère PAS les classes mock** (`\mock\...`) - ces classes sont générées dynamiquement à l'exécution et doivent rester dans `ignoreErrors`.

2. **Cette extension ne gère PAS les propriétés magiques** - elles sont gérées par `.ide-helper.php` et `atoum/stubs`.

3. **Versionnez ce dossier** - l'extension doit être partagée avec toute l'équipe !

