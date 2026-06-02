-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 01 juin 2026 à 14:41
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pharmacie_db_v2`
--

-- --------------------------------------------------------

--
-- Structure de la table `alertes`
--

CREATE TABLE `alertes` (
  `id` int(11) NOT NULL,
  `type_alerte` enum('rupture','expiration','seuil_bas','peremption_proche','surstock') NOT NULL,
  `medicament_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `statut` enum('nouvelle','lue','resolue','ignoree') DEFAULT 'nouvelle',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_resolution` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories_medicament`
--

CREATE TABLE `categories_medicament` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories_medicament`
--

INSERT INTO `categories_medicament` (`id`, `nom`, `description`, `created_at`) VALUES
(1, 'Antibiotiques', 'Médicaments anti-infectieux', '2026-05-31 19:03:25'),
(2, 'Antalgiques', 'Médicaments contre la douleur', '2026-05-31 19:03:25'),
(3, 'Anti-inflammatoires', 'Médicaments anti-inflammatoires', '2026-05-31 19:03:25'),
(4, 'Cardiologie', 'Médicaments cardiovasculaires', '2026-05-31 19:03:25'),
(5, 'Dermatologie', 'Médicaments pour la peau', '2026-05-31 19:03:25'),
(6, 'Gastro-entérologie', 'Médicaments digestifs', '2026-05-31 19:03:25'),
(7, 'Neurologie', 'Médicaments neurologiques', '2026-05-31 19:03:25'),
(8, 'Pédiatrie', 'Médicaments pour enfants', '2026-05-31 19:03:25'),
(9, 'Vitamines', 'Compléments vitaminiques', '2026-05-31 19:03:25'),
(10, 'Autres', 'Autres catégories', '2026-05-31 19:03:25');

-- --------------------------------------------------------

--
-- Structure de la table `details_vente`
--

CREATE TABLE `details_vente` (
  `id` int(11) NOT NULL,
  `vente_id` int(11) NOT NULL,
  `medicament_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `remise_ligne` decimal(10,2) DEFAULT 0.00,
  `total_ligne` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entrees_stock`
--

CREATE TABLE `entrees_stock` (
  `id` int(11) NOT NULL,
  `numero_entree` varchar(20) DEFAULT NULL,
  `fournisseur_id` int(11) DEFAULT NULL,
  `medicament_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_achat_unitaire` decimal(10,2) NOT NULL,
  `prix_vente_unitaire` decimal(10,2) NOT NULL,
  `date_fabrication` date DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `numero_lot` varchar(50) DEFAULT NULL,
  `date_entree` datetime DEFAULT current_timestamp(),
  `responsable_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id` int(11) NOT NULL,
  `numero_facture` varchar(20) NOT NULL,
  `vente_id` int(11) DEFAULT NULL,
  `nom_client` varchar(150) DEFAULT NULL,
  `telephone_client` varchar(20) DEFAULT NULL,
  `vendeur_id` int(11) NOT NULL,
  `date_facture` datetime DEFAULT current_timestamp(),
  `date_echeance` date DEFAULT NULL,
  `montant_ht` decimal(10,2) NOT NULL,
  `montant_ttc` decimal(10,2) NOT NULL,
  `montant_paye` decimal(10,2) DEFAULT 0.00,
  `statut` enum('en_attente','payee','partielle','impayee','annulee') DEFAULT 'en_attente',
  `conditions_paiement` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE `fournisseurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `pays` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `nif` varchar(50) DEFAULT NULL,
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique_actions`
--

CREATE TABLE `historique_actions` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_concernee` varchar(50) DEFAULT NULL,
  `id_enregistrement` int(11) DEFAULT NULL,
  `details_avant` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details_avant`)),
  `details_apres` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details_apres`)),
  `adresse_ip` varchar(45) DEFAULT NULL,
  `date_action` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `medicaments`
--

CREATE TABLE `medicaments` (
  `id` int(11) NOT NULL,
  `nom_generique` varchar(150) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `fournisseur_id` int(11) DEFAULT NULL,
  `dosage` varchar(50) DEFAULT NULL,
  `prix_achat` decimal(10,2) NOT NULL DEFAULT 0.00,
  `prix_vente` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantite_stock` int(11) NOT NULL DEFAULT 0,
  `quantite_minimale` int(11) NOT NULL DEFAULT 10,
  `quantite_maximale` int(11) DEFAULT 1000,
  `emplacement` varchar(50) DEFAULT NULL,
  `date_fabrication` date DEFAULT NULL,
  `date_expiration` date NOT NULL,
  `numero_lot` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `contre_indication` text DEFAULT NULL,
  `effets_secondaires` text DEFAULT NULL,
  `statut` enum('actif','inactif','epuise','perime') DEFAULT 'actif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mouvements_stock`
--

CREATE TABLE `mouvements_stock` (
  `id` int(11) NOT NULL,
  `medicament_id` int(11) NOT NULL,
  `type_mouvement` enum('entree','sortie','ajustement','retour','perte') NOT NULL,
  `quantite` int(11) NOT NULL,
  `stock_avant` int(11) NOT NULL,
  `stock_apres` int(11) NOT NULL,
  `reference` varchar(50) DEFAULT NULL,
  `motif` text DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `date_mouvement` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parametres_systeme`
--

CREATE TABLE `parametres_systeme` (
  `id` int(11) NOT NULL,
  `cle` varchar(50) NOT NULL,
  `valeur` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `parametres_systeme`
--

INSERT INTO `parametres_systeme` (`id`, `cle`, `valeur`, `description`, `updated_at`) VALUES
(1, 'nom_pharmacie', 'Pharmacie Centrale', 'Nom de la pharmacie', '2026-05-31 19:03:25'),
(2, 'adresse_pharmacie', '123 Rue Principale', 'Adresse', '2026-05-31 19:03:25'),
(3, 'telephone_pharmacie', '+243 000 000 000', 'Téléphone', '2026-05-31 19:03:25'),
(4, 'email_pharmacie', 'contact@pharmacie.com', 'Email', '2026-05-31 19:03:25'),
(5, 'devise_defaut', 'CDF', 'Devise par défaut', '2026-05-31 19:03:25'),
(6, 'seuil_alerte_stock', '10', 'Seuil minimum avant alerte', '2026-05-31 19:03:25'),
(7, 'jours_alerte_expiration', '90', 'Jours avant expiration pour alerte', '2026-05-31 19:03:25'),
(8, 'theme_defaut', 'light', 'Thème par défaut', '2026-05-31 19:03:25');

-- --------------------------------------------------------

--
-- Structure de la table `taux_change`
--

CREATE TABLE `taux_change` (
  `id` int(11) NOT NULL,
  `devise` varchar(10) NOT NULL DEFAULT 'CDF',
  `taux` decimal(10,4) NOT NULL DEFAULT 1.0000,
  `date_taux` date NOT NULL DEFAULT curdate(),
  `actif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `taux_change`
--

INSERT INTO `taux_change` (`id`, `devise`, `taux`, `date_taux`, `actif`, `created_at`) VALUES
(1, 'CDF', 1.0000, '2026-05-31', 1, '2026-05-31 19:03:25'),
(2, 'USD', 2800.0000, '2026-05-31', 1, '2026-05-31 19:03:25'),
(3, 'EUR', 3000.0000, '2026-05-31', 1, '2026-05-31 19:03:25');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `matricule` varchar(20) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `date_embauche` date NOT NULL DEFAULT curdate(),
  `poste` enum('admin','vendeur','comptable') NOT NULL DEFAULT 'vendeur',
  `salaire` decimal(10,2) DEFAULT 0.00,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','vendeur','comptable') NOT NULL DEFAULT 'vendeur',
  `statut` enum('actif','inactif','suspendu') DEFAULT 'actif',
  `derniere_connexion` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `matricule`, `nom`, `prenom`, `email`, `telephone`, `adresse`, `date_naissance`, `date_embauche`, `poste`, `salaire`, `username`, `password_hash`, `role`, `statut`, `derniere_connexion`, `created_at`, `updated_at`) VALUES
(1, 'ADMIN001', 'Administrateur', 'Système', 'admin@pharmacie.com', '+243999999999', NULL, NULL, '2026-05-31', 'admin', 500000.00, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'actif', NULL, '2026-05-31 19:03:25', '2026-05-31 19:03:25'),
(2, 'VEND001', 'Kasongo', 'Jean', 'vendeur@pharmacie.com', '+243888888888', NULL, NULL, '2026-05-31', 'vendeur', 250000.00, 'vendeur', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vendeur', 'actif', NULL, '2026-05-31 19:03:25', '2026-05-31 19:03:25'),
(3, 'COMP001', 'Mukendi', 'Marie', 'comptable@pharmacie.com', '+243777777777', NULL, NULL, '2026-05-31', 'comptable', 350000.00, 'comptable', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'comptable', 'actif', NULL, '2026-05-31 19:03:25', '2026-05-31 19:03:25');

-- --------------------------------------------------------

--
-- Structure de la table `ventes`
--

CREATE TABLE `ventes` (
  `id` int(11) NOT NULL,
  `numero_vente` varchar(20) NOT NULL,
  `nom_client` varchar(150) DEFAULT NULL,
  `telephone_client` varchar(20) DEFAULT NULL,
  `vendeur_id` int(11) NOT NULL,
  `date_vente` datetime DEFAULT current_timestamp(),
  `sous_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `remise_totale` decimal(10,2) DEFAULT 0.00,
  `total_final` decimal(10,2) NOT NULL DEFAULT 0.00,
  `montant_paye` decimal(10,2) DEFAULT 0.00,
  `monnaie_rendue` decimal(10,2) DEFAULT 0.00,
  `mode_paiement` enum('especes','carte','cheque','virement','mobile_money') DEFAULT 'especes',
  `devise` varchar(10) DEFAULT 'CDF',
  `taux_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `alertes`
--
ALTER TABLE `alertes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicament_id` (`medicament_id`);

--
-- Index pour la table `categories_medicament`
--
ALTER TABLE `categories_medicament`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `details_vente`
--
ALTER TABLE `details_vente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vente_id` (`vente_id`),
  ADD KEY `medicament_id` (`medicament_id`);

--
-- Index pour la table `entrees_stock`
--
ALTER TABLE `entrees_stock`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_entree` (`numero_entree`),
  ADD KEY `fournisseur_id` (`fournisseur_id`),
  ADD KEY `medicament_id` (`medicament_id`),
  ADD KEY `responsable_id` (`responsable_id`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_facture` (`numero_facture`),
  ADD KEY `vente_id` (`vente_id`),
  ADD KEY `vendeur_id` (`vendeur_id`);

--
-- Index pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `historique_actions`
--
ALTER TABLE `historique_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `medicaments`
--
ALTER TABLE `medicaments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `fournisseur_id` (`fournisseur_id`);

--
-- Index pour la table `mouvements_stock`
--
ALTER TABLE `mouvements_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicament_id` (`medicament_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `parametres_systeme`
--
ALTER TABLE `parametres_systeme`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cle` (`cle`);

--
-- Index pour la table `taux_change`
--
ALTER TABLE `taux_change`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_devise_date` (`devise`,`date_taux`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricule` (`matricule`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `ventes`
--
ALTER TABLE `ventes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_vente` (`numero_vente`),
  ADD KEY `vendeur_id` (`vendeur_id`),
  ADD KEY `taux_id` (`taux_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `alertes`
--
ALTER TABLE `alertes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categories_medicament`
--
ALTER TABLE `categories_medicament`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `details_vente`
--
ALTER TABLE `details_vente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `entrees_stock`
--
ALTER TABLE `entrees_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historique_actions`
--
ALTER TABLE `historique_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `medicaments`
--
ALTER TABLE `medicaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `mouvements_stock`
--
ALTER TABLE `mouvements_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `parametres_systeme`
--
ALTER TABLE `parametres_systeme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `taux_change`
--
ALTER TABLE `taux_change`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `ventes`
--
ALTER TABLE `ventes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `alertes`
--
ALTER TABLE `alertes`
  ADD CONSTRAINT `alertes_ibfk_1` FOREIGN KEY (`medicament_id`) REFERENCES `medicaments` (`id`);

--
-- Contraintes pour la table `details_vente`
--
ALTER TABLE `details_vente`
  ADD CONSTRAINT `details_vente_ibfk_1` FOREIGN KEY (`vente_id`) REFERENCES `ventes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `details_vente_ibfk_2` FOREIGN KEY (`medicament_id`) REFERENCES `medicaments` (`id`);

--
-- Contraintes pour la table `entrees_stock`
--
ALTER TABLE `entrees_stock`
  ADD CONSTRAINT `entrees_stock_ibfk_1` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`),
  ADD CONSTRAINT `entrees_stock_ibfk_2` FOREIGN KEY (`medicament_id`) REFERENCES `medicaments` (`id`),
  ADD CONSTRAINT `entrees_stock_ibfk_3` FOREIGN KEY (`responsable_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_ibfk_1` FOREIGN KEY (`vente_id`) REFERENCES `ventes` (`id`),
  ADD CONSTRAINT `factures_ibfk_2` FOREIGN KEY (`vendeur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `historique_actions`
--
ALTER TABLE `historique_actions`
  ADD CONSTRAINT `historique_actions_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `medicaments`
--
ALTER TABLE `medicaments`
  ADD CONSTRAINT `medicaments_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categories_medicament` (`id`),
  ADD CONSTRAINT `medicaments_ibfk_2` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`);

--
-- Contraintes pour la table `mouvements_stock`
--
ALTER TABLE `mouvements_stock`
  ADD CONSTRAINT `mouvements_stock_ibfk_1` FOREIGN KEY (`medicament_id`) REFERENCES `medicaments` (`id`),
  ADD CONSTRAINT `mouvements_stock_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `ventes`
--
ALTER TABLE `ventes`
  ADD CONSTRAINT `ventes_ibfk_1` FOREIGN KEY (`vendeur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `ventes_ibfk_2` FOREIGN KEY (`taux_id`) REFERENCES `taux_change` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
