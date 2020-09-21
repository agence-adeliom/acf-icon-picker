# ACF Icon Picker Field

Permet de créer un champ ACF de type 'icon-picker'.

---

## Description

Utilisation des icônes icomoon à mettre en place directement dans le dossier du thème /assets/fonts (tout extraire à la raicne de ce dossier).

## Compatibilité

Champ ACF compatible avec :
[x] ACF 5

*Ajouté directement en tant que fonction pour la compatibilité avec les champs ACF wordplate* 

## Screenshots

![Icon Picker](https://raw.githubusercontent.com/houke/acf-icon-picker/master/screenshots/example.png)

## Installation

1. Copier le dossier `acf-icon-picker` dans `wp-content/plugins`
2. Mettre à jour le composer.json et lancer un composer dump-autoload

```php
"autoload": {
  "psr-4": {
    "Plugins\\AcfIconPicker\\": "web/app/plugins/wp-acf-icon-picker/"
  }
}
```

2. Activer le plugin depuis l'administration de Wordpress
3. Créer un champ à l'endroit ou vous le souhaitez comme n'importe quel autre champ

```php
IconPicker::make("Icône", "icon")->required()->icons(['name-of-your-icon', '....']);
```

### Ancienne version

```php
acf_icon_picker([
    'name' => 'icon-picker',
    'label' => __('Icone', ''),
    'required' => true,
    ...
])
```

## Filters

Pour modifier les différents chemin ou URLs dans le cas où ils devaient se trouver ailleurs que dans le dossier VotreTheme/assets/fonts/, voici quelques filtres :

```php
// modify the path to the icons directory
add_filter( 'acf_icon_path_suffix', 'acf_icon_path_suffix' );

function acf_icon_path_suffix( $path_suffix ) {
    return 'assets/img/icons/';
}

// modify the path to the above prefix
add_filter( 'acf_icon_path', 'acf_icon_path' );

function acf_icon_path( $path_suffix ) {
    return plugin_dir_path( __FILE__ );
}

// modify the URL to the icons directory to display on the page
add_filter( 'acf_icon_url', 'acf_icon_url' );

function acf_icon_url( $path_suffix ) {
    return plugin_dir_url( __FILE__ );
}
```
