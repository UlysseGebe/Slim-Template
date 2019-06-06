___
! Les videos ne peuvent pas être versionnées sur git ou déplacées car trop volumineuses !<br/>
! Videos cannot be versioned on git or moved because they are too large !
___
[English](#Slim-Twig-Project) - [Fançais](#Projet-Slim-Twig)
# Projet Slim Twig

Ce projet Slim Twig est un template pour portfolio axé pour les cinématographes et les cinéastes. On peut afficher du contenue via une base de données et modifier cette base de données. Il y a trois tableaux dans la base de données.

## Liste des features

* Génération de contenue en fonction de la base de données.
* Création de liens dynamiques.
* Création d'un contenue dynamique pour un même fichier (templating).
* Possibilité de s'enregistrer pour modifier le contenu du site (après création de l'identifiant, on peut supprimer le fichier).
* Mémorisation du pseudo entre l'enregistrement et la connexion (pendant 10 secondes).
* Affichage du contenue des tableaux catégorie et vidéo.
* possibilité de supprimer le contenu par ligne et le fichier lié.
* possibilité de classer par ordres d'importance via le gestionnaire de la page administrateur
* Ajout de données dans la base de données
* Ajout de fichiers images et vidéos

## Affichage du site

Les différentes pages sont disposées de manière logique. Il y a une page "Home'" qui affiches les différentes catégories et chaque catégorie a un lien qui envoie vers une liste d'article, qui sont des présentations de différents projets. Cette partie du site utilise deux des trois tableaux.<br/>
Le fichier .htacces et index.php sont dans le dossier web.

### Home
#### TWIG

La page 'Home' est la pierre angulaire du site. Celle-ci permet d'afficher les différentes catégories à l'aide d'une boucle for.

```twig
{% for _categorie in categories %}
    {% if _categorie.categorie_name != 'Home' %}
    <article onclick="window.location='{{ path_for('article', { categorie: _categorie.categorie_link }) }}'">
        <div class="cover">
            <img src="../assets/image/{{ _categorie.categorie_image }}">
        </div>
        <div class="title">
            <h3>{{ _categorie.categorie_name }}</h3>
            <span>{{ _categorie.categorie_legend }}</span>
        </div>
    </article>
    {% endif %}
{% endfor %}
```
'Home' est aussi dans le tableau catégorie et il faut donc l'exclure de la boucle avec une condition.

##### Utilisation des arguments
Le lien de redirection vers la page de la catégorie ciblé est unique grace à l'utilisation de path_for et d'un argument.

```twig
{{ path_for('article', { categorie: _categorie.categorie_link }) }}
```

#### PHP
Pour la route de 'Home', il suffit d'afficher le contenu de la table catégorie dans un ordre prédéfini.

```php
$query = $this->db->query('SELECT * FROM categorie ORDER BY classement');
$categories = $query->fetchAll();
```
'classement' permet de modifier l'ordre d'affichage des catégories

### Pages catégories

#### TWIG
Pour afficher le contenu des articles de la base de données, il faut utiliser une boucle for

```twig
{% for _video in videos %}
    <div class="work">
        <video src="../assets/video/{{ _video.video_url }}" poster="../assets/image/{{ _video.video_poster }}">{{_video.video_fallback }}</video>
        <legend>{{ _video.video_description }}</legend>
    </div>
{% endfor %}
```


#### PHP
Les pages catégories contiennent tous les articles liés avec cette catégorie. Il faut donc sélectionner les bons articles.

```php
$prepare = $this->db->prepare('SELECT * FROM video WHERE video_categorie = ? ORDER BY classement');
$prepare->execute(array($arguments['categorie']));
$video = $prepare->fetchAll();
```

## Modification du site
Le possesseur du site peut modifier les informations de la base de données en se connecter à celle-ci via un système de login.<br/>
Le fichier .htacces et index.php sont dans le dossier serveur.

### Register et Login
Les formulaires sont comme vu en cours et il y a une mémorisation du pseudo de login avec l'utilisation des cookies.

```php
setcookie('login', $login, time() + 10, '/');
```
Si l'utilisateur est connecté, il ne peut pas accéder à ces pages.

### Page administrateur

Cette page permet de gérer les catégories et les articles.

```twig
{% for _categorie in categories %}
    <tr>
        {% if _categorie.id == 1 %}
        <th scope="row">Home</th>
        {% else %}
        <th scope="row"><a href="{{ path_for('delete', {categorie: 'categorie' , delete: _categorie.id }) }}">X</a></th>
        {% endif %}
        <th scope="row" class="classement">
            <a class="up" href="{{ path_for('change', {categorie: 'categorie' , change: _categorie.classement , type: '0' }) }}"></a>
            <a class="down" href="{{ path_for('change', {categorie: 'categorie' , change: _categorie.classement , type: '1' }) }}"></a>
        </th>
        <td>{{ _categorie.categorie_name }}</td>
        <td>{{ _categorie.categorie_legend }}</td>
        <td class="modif">{{ _categorie.categorie_content }}</td>
        <td>{{ _categorie.categorie_link }}</td>
        <td>{{ _categorie.categorie_image }}</td>
    </tr>
{% endfor %}
```
Il est possible de supprimer (de la base de données et du fichier assets) et de modifier l'ordre dans les tableaux.<br>
Si l'utilisateur n'est pas connecté, il ne peut pas accéder à cette page.

## Ajout Catégorie et Video/Articles
Ces pages sont des formulaires pour ajouter une catégorie ou un article.

```php
$query = $this->db->query('SELECT COUNT(*) AS totale FROM video');
$video = $query->fetch();
$totale = $video->totale + 1;
$data = [
    'classement' => (int)$totale,
    'name' => trim($_POST['name']),
    'categorie' => trim($_POST['categorie']),
    'url' => trim($_FILES["videoToUpload"]["name"]),
    'description' => trim($_POST['description']),
    'poster' => trim($_FILES["imageToUpload"]["name"]),
    'fallback' => trim($_POST['fallback']),
];

$prepare = $this->db->prepare('INSERT INTO video (classement, video_name, video_categorie, video_url, video_description,video_poster, video_fallback) VALUES (:classement, :name, :categorie, :url, :description, :poster, :fallback)');
$prepare->execute($data);
```
___
___

[English](#Slim-Twig-Project) - [Fançais](#Projet-Slim-Twig)
# Slim Twig Project

This Slim Twig project is a portfolio template for cinematographers and filmmakers. You can display content via a database and modify this database. There are three tables in the database.

## Features list

* Content generation based on the database.
* Creation of dynamic links.
* Creation of a dynamic content for the same file (templating).
* Possibility to register to modify the content of the site (after creation of the identifier, you can delete the file).
* Memorization of the nickname between recording and connection (for 10 seconds).
* Display of the content of the category and video tables.
* possibility to delete the content per line and the linked file.
* possibility to rank by importance order via the administrator page manager
* Adding data to the database
* Adding image and video files

## Display of the site

The different pages are arranged in a logical way. There is a "Home'" page that displays the different categories and each category has a link that sends to a list of articles, which are presentations of different projects. This part of the site uses two of the three tables.<br/>
The.htacces file and index.php are in the web folder.

### Home
#### TWIG

The 'Home' page is the cornerstone of the site. This allows you to display the different categories using a for loop.

```twig
{% for _categorie in categories %}
    {% if _categorie.categorie_name != 'Home' %}
    <article onclick="window.location='{{ path_for('article', { categorie: _categorie.categorie_link }) }}'">
        <div class="cover">
            <img src="../assets/image/{{ _categorie.categorie_image }}">
        </div>
        <div class="title">
            <h3>{{ _categorie.categorie_name }}</h3>
            <span>{{ _categorie.categorie_legend }}</span>
        </div>
    </article>
    {% endif %}
{% endfor %}
```
'Home' is also in the category table and must therefore be excluded from the loop with a condition.

##### Use of arguments
The redirect link to the page of the targeted category is unique thanks to the use of path_for and an argument.

```twig
{{ path_for('article', { categorie: _categorie.categorie_link }) }}
```

#### PHP
For the'Home' route, simply display the content of the category table in a predefined order.

```php
$query = $this->db->query('SELECT * FROM categorie ORDER BY classement');
$categories = $query->fetchAll();
```
'classement' allows you to change the order in which the categories are displayed.

### Pages categories

#### TWIG
To display the content of the articles in the database, you must use a for loop

```twig
{% for _video in videos %}
    <div class="work">
        <video src="../assets/video/{{ _video.video_url }}" poster="../assets/image/{{ _video.video_poster }}">{{_video.video_fallback }}</video>
        <legend>{{ _video.video_description }}</legend>
    </div>
{% endfor %}
```


#### PHP
The category pages contain all articles related to this category. It is therefore necessary to select the right items.

```php
$prepare = $this->db->prepare('SELECT * FROM video WHERE video_categorie = ? ORDER BY classement');
$prepare->execute(array($arguments['categorie']));
$video = $prepare->fetchAll();
```

## Modification of the site
The owner of the site can modify the information in the database by connecting to it via a login system.<br/>
The .htacces and index.php files are in the server folder.

### Register & Login
The forms are as seen in progress and there is a memorization of the login username with the use of cookies.

```php
setcookie('login', $login, time() + 10, '/');
```
If the user is logged in, he or she cannot access these pages.

### Administrator page
This page allows you to manage categories and articles.

```twig
{% for _categorie in categories %}
    <tr>
        {% if _categorie.id == 1 %}
        <th scope="row">Home</th>
        {% else %}
        <th scope="row"><a href="{{ path_for('delete', {categorie: 'categorie' , delete: _categorie.id }) }}">X</a></th>
        {% endif %}
        <th scope="row" class="classement">
            <a class="up" href="{{ path_for('change', {categorie: 'categorie' , change: _categorie.classement , type: '0' }) }}"></a>
            <a class="down" href="{{ path_for('change', {categorie: 'categorie' , change: _categorie.classement , type: '1' }) }}"></a>
        </th>
        <td>{{ _categorie.categorie_name }}</td>
        <td>{{ _categorie.categorie_legend }}</td>
        <td class="modif">{{ _categorie.categorie_content }}</td>
        <td>{{ _categorie.categorie_link }}</td>
        <td>{{ _categorie.categorie_image }}</td>
    </tr>
{% endfor %}
```
It is possible to delete (from the database and assets file) and change the order in the tables.<br/>
If the user is not logged in, he/she cannot access this page.

## Add Category and Video/Articles
These pages are forms to add a category or article.

```php
$query = $this->db->query('SELECT COUNT(*) AS totale FROM video');
$video = $query->fetch();
$totale = $video->totale + 1;
$data = [
    'classement' => (int)$totale,
    'name' => trim($_POST['name']),
    'categorie' => trim($_POST['categorie']),
    'url' => trim($_FILES["videoToUpload"]["name"]),
    'description' => trim($_POST['description']),
    'poster' => trim($_FILES["imageToUpload"]["name"]),
    'fallback' => trim($_POST['fallback']),
];

$prepare = $this->db->prepare('INSERT INTO video (classement, video_name, video_categorie, video_url, video_description,video_poster, video_fallback) VALUES (:classement, :name, :categorie, :url, :description, :poster, :fallback)');
$prepare->execute($data);
```