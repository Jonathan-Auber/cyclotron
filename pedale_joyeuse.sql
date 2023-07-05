-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 22 juin 2023 à 10:43
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pedale_joyeuse`
--

-- --------------------------------------------------------

--
-- Structure de la table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `additional_address` varchar(255) DEFAULT NULL,
  `zip_code` varchar(5) NOT NULL,
  `city` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `customers`
--

INSERT INTO `customers` (`id`, `firstname`, `lastname`, `address`, `additional_address`, `zip_code`, `city`, `email`, `phone_number`) VALUES
(1, 'John', 'Doe', 'Rue Sainte', '', '13000', 'Marseille', 'john@doe.com', ''),
(2, 'Mister', 'T', 'Vieux port', 'Joliette', '13700', 'Marseille', 'mister@t.fr', '0645242638'),
(7, 'Zaid', 'Chakir', '1 rue de l&#039;égo', '', '13000', 'Zaidï', 'Zaid@ego.com', '');

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount_et` int(11) NOT NULL,
  `amount_it` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `invoices`
--

INSERT INTO `invoices` (`id`, `customer_id`, `user_id`, `creation_date`, `amount_et`, `amount_it`) VALUES
(82, 1, 2, '2023-04-23 15:35:44', 334, 400),
(83, 2, 2, '2023-05-23 15:36:04', 518, 601),
(84, 1, 1, '2023-05-24 09:38:33', 165, 198),
(85, 1, 2, '2023-05-24 09:41:11', 162, 194),
(86, 1, 2, '2023-05-24 15:54:03', 447, 524),
(88, 7, 1, '2023-05-25 08:34:53', 3172, 3805),
(99, 7, 1, '2023-05-25 15:52:05', 110, 132),
(100, 7, 1, '2023-05-25 15:53:13', 800, 880),
(101, 7, 1, '2023-05-25 15:53:53', 120, 132),
(102, 7, 1, '2023-05-25 15:54:29', 110, 132),
(103, 7, 1, '2023-05-25 15:56:05', 70, 84),
(104, 7, 1, '2023-05-25 15:57:32', 80, 88),
(119, 7, 1, '2023-05-25 16:28:34', 110, 132),
(120, 7, 1, '2023-05-25 16:29:41', 354, 424),
(121, 7, 1, '2023-05-26 08:32:21', 280, 336),
(122, 7, 1, '2023-06-22 08:55:23', 221, 257),
(123, 2, 2, '2023-06-22 08:58:41', 418, 501),
(124, 1, 2, '2023-06-22 11:09:21', 80, 88);

-- --------------------------------------------------------

--
-- Structure de la table `invoice_lines`
--

CREATE TABLE `invoice_lines` (
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `invoice_lines`
--

INSERT INTO `invoice_lines` (`invoice_id`, `product_id`, `quantity`) VALUES
(82, 1, 3),
(82, 4, 4),
(82, 5, 1),
(83, 5, 4),
(83, 2, 5),
(83, 3, 1),
(83, 4, 4),
(84, 1, 3),
(85, 4, 3),
(85, 5, 2),
(86, 2, 3),
(86, 3, 5),
(88, 1, 20),
(88, 3, 15),
(88, 4, 30),
(88, 5, 1),
(99, 1, 2),
(100, 2, 20),
(101, 2, 3),
(102, 1, 2),
(103, 4, 2),
(104, 2, 2),
(119, 1, 2),
(120, 4, 10),
(121, 5, 10),
(122, 2, 2),
(122, 4, 4),
(123, 1, 2),
(123, 3, 3),
(123, 5, 4),
(124, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_alert` int(11) NOT NULL,
  `price_ht` float(6,2) NOT NULL,
  `vat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `reference`, `stock`, `stock_alert`, `price_ht`, `vat_id`) VALUES
(1, 'Doussofess', 'SD', 26, 15, 55.00, 1),
(2, 'Duracuir', 'SC', 23, 10, 40.00, 2),
(3, 'Voiclair', 'VC', 17, 15, 65.50, 1),
(4, 'Korn2vach', 'GT', 12, 12, 35.40, 1),
(5, 'MacGyver', 'MG', 15, 12, 28.00, 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `status`) VALUES
(1, 'John', '$2y$10$RGUthYvl3sU2Oq6cFgKF7u2asjLtSjHwNC.C.nf9vH0Qj8sy3aEX.', 'boss'),
(2, 'Jojo', '$2y$10$qV4u1pZan2.KGrnLR35qnOzV52wtZYNEj3aLxnXh8zFfAgM.4iwzy', 'seller');

-- --------------------------------------------------------

--
-- Structure de la table `vat`
--

CREATE TABLE `vat` (
  `id` int(11) NOT NULL,
  `rate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `vat`
--

INSERT INTO `vat` (`id`, `rate`) VALUES
(1, 20),
(2, 10);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_FACTURE_CLIENT` (`customer_id`),
  ADD KEY `FK_FACTURE_EMPLOYE` (`user_id`);

--
-- Index pour la table `invoice_lines`
--
ALTER TABLE `invoice_lines`
  ADD KEY `FK_LIGNE_PRODUIT` (`product_id`),
  ADD KEY `FK_LIGNE_FACTURE` (`invoice_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_PRODUIT_TVA` (`vat_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `vat`
--
ALTER TABLE `vat`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `vat`
--
ALTER TABLE `vat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `FK_FACTURE_CLIENT` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `FK_FACTURE_EMPLOYE` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `invoice_lines`
--
ALTER TABLE `invoice_lines`
  ADD CONSTRAINT `FK_LIGNE_FACTURE` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_LIGNE_PRODUIT` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_PRODUIT_TVA` FOREIGN KEY (`vat_id`) REFERENCES `vat` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
