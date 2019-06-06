[English](#Slim-Twig-Project) - [Fançais](#Projet-Slim-Twig)
# Projet Slim Twig

Ce projet Slim Twig est un template pour portfolio axé pour les cinématographes et les cinéastes. On peut afficher du contenue via une base de données et modifier cette base de données. Il y a trois tableaux dans la base de donnée.

## Affichage du site

Les differentes pages sont disposées de manière logique. Il y a une page "Home'" qui affiches les différentes catégories et chaque catégorie a un lien qui envoie vers une liste d'article, qui sont des présentations de différents projets. Cette partie du site utilise deux des trois tableaux.
Le fichier .htacces et l'index.php sont dans le dossier web.

### Home
#### TWIG

La page 'Home' est la pierre angulaire du site. Celle-ci permet d'afficher les différentes catégories à l'aide d'une boucle for.

```twigs
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
Le lien de redirection vers la page de la catégorie ciblé est unique grace à l'utilisation de path_to et d'un argument.

```twigs
{{ path_for('article', { categorie: _categorie.categorie_link }) }}
```

#### PHP
Pour la route de de 'Home', il suffit d'afficher le contenu de la table catégorie dans un ordre prédefinie.

```php
$query = $this->db->query('SELECT * FROM categorie ORDER BY classement');
$categories = $query->fetchAll();
```
'classement' permet de modifier l'ordre d'affichages des catégories

### Pages catégories

#### TWIG
Pour afficher le contenue des articles de la base de donnée, il faut utiliser une boucle for

```twig
{% for _video in videos %}
    <div class="work">
        <video src="../assets/video/{{ _video.video_url }}" poster="../assets/image/{{ _video.video_poster }}">{{_video.video_fallback }}</video>
        <legend>{{ _video.video_description }}</legend>
    </div>
{% endfor %}
```


#### PHP
Les pages catégories contiennent tous les articles liées avec cette catégorie. Il faut donc selectionner les bons articles.

```php
$prepare = $this->db->prepare('SELECT * FROM video WHERE video_categorie = ? ORDER BY classement');
$prepare->execute(array($arguments['categorie']));
$video = $prepare->fetchAll();
```

## Modification du site
Le possesseur du sute peut modifier les information de la base de donnée en se connecter a celle-ci via un systeme de login 
Le fichier .htacces et l'index.php sont dans le dossier serveur.

### Break down into end to end tests

Explain what these tests test and why

```
Give an example
```

### And coding style tests

Explain what these tests test and why

```
Give an example
```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [Dropwizard](http://www.dropwizard.io/1.0.2/docs/) - The web framework used
* [Maven](https://maven.apache.org/) - Dependency Management
* [ROME](https://rometools.github.io/rome/) - Used to generate RSS Feeds

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Billie Thompson** - *Initial work* - [PurpleBooth](https://github.com/PurpleBooth)

See also the list of [contributors](https://github.com/your/project/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc


___
! Les videos ne peuvent pas être versionnés sur git car trop volumineuses !
___

# Slim Twig Project