# Blog

Este paquete contiene las funciones de blog.


## Installation (Required)

### Install
```
php artisan blog:install
```

### NPM dependencies (Required)

```
npm install @ckeditor/ckeditor5-vue
npm install wepa-ckeditor5-filemanager
npm install @vuepic/vue-datepicker

npm i @ckeditor/ckeditor5-vue wepa-ckeditor5-filemanager @vuepic/vue-datepicker
```

### Vendor Publish
```
// The web site report issues 
php artisan vendor:publish --tag=blog
```

##### Vendor tags:

`blog, blog-js, blog-lang, blog-config`

[blog]: incluye todos los tags | Include all tags

## Using this package

### JS

Puede personalizar las vistas en la ruta

You can customize the views on the route

`resources/js/Pages/Blog`

##### otros archivos js | another js files

`resources/js/Blog`

### Views

`resources/views/Vendor/Blog`

## Uninstall
```
php artisan blog:uninstall
```
