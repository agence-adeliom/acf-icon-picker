# ACF Icon Picker Field

Permet de créer un champ ACF de type 'icon-picker'.

---
## Attention, dans icomoon, bien penser à exporter la version classe et ne pas baser la font sur le tag i (conflit avec le BO)

```
[class^="icon-"], [class*=" icon-"] {
  /* use !important to prevent issues with browser extensions that change fonts */
  font-family: '#{$icomoon-font-family}' !important;
  speak: never;
  font-style: normal;
  font-weight: normal;
  font-variant: normal;
  text-transform: none;
  line-height: 1;
}
```

## Description

Utilisation des icônes icomoon à mettre en place directement dans le dossier du thème /assets/fonts (tout extraire à la raicne de ce dossier).

## Compatibilité

Champ ACF compatible avec :
[x] ACF 5

*Ajouté directement en tant que fonction pour la compatibilité avec les champs ACF wordplate* 

## Screenshots

![Icon Picker](https://raw.githubusercontent.com/houke/acf-icon-picker/master/screenshots/example.png)

## Installation

1. Mettre à jour le composer.json

```php
"repositories": {
  ...
  "adeliom-icon-picker": {
      "type": "vcs",
      "url": "https://github.com/agence-adeliom/acf-icon-picker.git"
  },
  ...
}
...
"require": {
  ...
  "agence-adeliom/acf-icon-picker": "dev-master"
}
```
2. Laner un `composer update`
2. Activer le plugin depuis l'administration de Wordpress
3. Créer un champ à l'endroit ou vous le souhaitez comme n'importe quel autre champ

```php
use Adeliom\Acf\Fields\IconPicker;

...

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
