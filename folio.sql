-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le :  jeu. 06 juin 2019 à 13:44
-- Version du serveur :  5.6.38
-- Version de PHP :  7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `folio`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `classement` int(11) NOT NULL,
  `categorie_name` text NOT NULL,
  `categorie_legend` text NOT NULL,
  `categorie_content` text NOT NULL,
  `categorie_link` text NOT NULL,
  `categorie_image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `classement`, `categorie_name`, `categorie_legend`, `categorie_content`, `categorie_link`, `categorie_image`) VALUES
(1, 1, 'Home', '', 'Hi ! I\'m a young film director and cinematographer based in Paris. \r\nYou can find some of my work here !', '', ''),
(2, 2, 'Director', '2019', 'Few shorts films I have made trough the years. Many more to come !\r\n\r\nI\' am also a camera operator on some projects. They will be marked with this sign : U+1F3A5', 'director-of-filming', 'maladie_damour.png'),
(3, 3, 'Cinematographer', '2019', 'Here are some project I have been working on as a cinematographer trough the years. Many more to come !\r\n\r\nI\' am also a camera operator on some projects. They will be marked with this sign : U+1F3A5', 'cinematographer', 'babylove.png'),
(4, 4, 'Photography', 'Comming very soon ...', '', '', 'acceuil.JPG');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `login`, `password`) VALUES
(1, 'test', '8de84272f7f5b1e6bad6a70d00d6ab140617c82ad6d60f08f7e896c14f296c79'),
(33, 'Ulysse-The_best_ever', 'cade0e4a09abede90831a7c1bc6162461a09a72e6cf4ecf9d834019f0fda5afc');

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `classement` int(11) NOT NULL,
  `video_name` text NOT NULL,
  `video_categorie` text NOT NULL,
  `video_url` text NOT NULL,
  `video_description` text NOT NULL,
  `video_poster` text NOT NULL,
  `video_fallback` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `classement`, `video_name`, `video_categorie`, `video_url`, `video_description`, `video_poster`, `video_fallback`) VALUES
(1, 1, 'Baby Love', 'cinematographer', 'BabyLove.mp4', 'Music clip \"Baby Love\" from the band Sugar Pill, directed by Camila Djadja and Malou Mallerin : U+1F3A5', 'babylove.png', 'Baby Love - Camila Djadja & Malou Mallerin'),
(2, 2, 'Le projet Moteur', 'cinematographer', 'PROMO_MOTEUR.mp4', 'Advertisment for \"Le projet Moteur!\" saison 3, directed by Romane Massard U+1F3A5', 'Pub_moteur.png', '\"Le projet Moteur!\" - Romane Massard'),
(3, 5, 'Concours Moteur !', 'director-of-filming', 'PROMO_MOTEUR.mp4', 'Short film presented for the \"Concours Moteur !\" rewarded with the first price. U+1F3A5', 'Moteur.png', 'Concours Moteur! - César CADENE'),
(4, 3, 'Je suis donc je pense', 'cinematographer', 'Je_suis_donc_je_pense.mp4', 'Short film for the \"Nikon film festival\", directed by Léo Boucry U+1F3A5', 'je_pense.png', 'Movie for the \"Nikon film festival\" - Léo Boucry'),
(5, 4, 'Je suis donc je pense', 'cinematographer', 'Confettis.mov', 'Short film made for the 48H film project 2018 in Nantes. U+1F3A5', 'confetti.png', 'Movie for the \"48H film project 2018\"'),
(6, 6, 'Maladie d\'amour', 'director-of-filming', 'Maladie_damour.mp4', 'Short film made in 2018.', 'maladie_damour.png', 'Maladie d\'amour - César CADENE'),
(7, 8, 'Le tricheur à l\'as de carreau', 'director-of-filming', 'Tricheur.mov', 'Short film made in school. We had to recreate the painting \"le tricheur à l\'as de carreaux\" from the painter Georges De La Tour', 'tricheur.png', 'Le tricheur à l\'as de carreau - César CADENE');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
