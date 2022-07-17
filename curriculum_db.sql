-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Creato il: Ott 30, 2015 alle 13:26
-- Versione del server: 5.6.26
-- Versione PHP: 5.5.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `curriculum_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `anagrafica`
--

CREATE TABLE IF NOT EXISTS `anagrafica` (
  `user_id` int(5) unsigned NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cognome` varchar(30) NOT NULL,
  `luogo_nascita` varchar(30) NOT NULL,
  `data_nascita` date NOT NULL,
  `citta_residenza` enum('Ancona','Roma','Milano','Nessuna città') NOT NULL,
  `indirizzo_residenza` varchar(64) NOT NULL,
  `citta_domicilio` enum('Ancona','Milano','Roma','nessuna Città') NOT NULL,
  `indirizzo_domicilio` varchar(40) DEFAULT NULL,
  `codice_fiscale` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Struttura della tabella `certificazione`
--

CREATE TABLE IF NOT EXISTS `certificazione` (
  `user_id` int(5) unsigned NOT NULL,
  `titolo_certificazione` varchar(20) DEFAULT NULL,
  `cod_licenza` varchar(20) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
`ente_certificante` varchar(50) DEFAULT NULL,
  `da` date DEFAULT NULL,
  `a` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



--
-- Struttura della tabella `conoscenze`
--

CREATE TABLE IF NOT EXISTS `conoscenze` (
  `user_id` int(10) unsigned NOT NULL,
  
  `livello` enum('Principiante','Intermedio','Avanzato') NOT NULL,
  `tipologia` varchar(60) NOT NULL,
  `descrizione` varchar(1000) NOT NULL,
  `corso` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Struttura della tabella `esperienza`
--

CREATE TABLE IF NOT EXISTS `esperienza` (
  `user_id` int(5) unsigned DEFAULT NULL,
  `azienda` varchar(20) DEFAULT NULL,
  `indirizzo` varchar(30) DEFAULT NULL,
  `mansione` varchar(50) DEFAULT NULL,
  `da` date DEFAULT NULL,
  `a` date DEFAULT NULL,
  `attuale` enum('Si','No') NOT NULL,
  `note` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Struttura della tabella `formazione`
--

CREATE TABLE IF NOT EXISTS `formazione` (
  `user_id` int(5) unsigned NOT NULL,
  `istituto` varchar(30) DEFAULT NULL,
  `da` date DEFAULT NULL,
  `a` date DEFAULT NULL,
  `titolo` varchar(50) DEFAULT NULL,
  `corso` varchar(40) NOT NULL,
  `voto` varchar(50) NOT NULL,
  `descrizione` varchar(1000) NULL,
  `esperienza_estera` enum('Si','No') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Struttura della tabella `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `user_idd` int(5) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `login`
--

INSERT INTO `login` (`user_idd`, `username`, `password`, `email`, `phone`, `is_admin`, `is_active`) VALUES
(1, 'matteo', 'ciao', 'matteo.scarda@service-tech.org', '0832879607', 0, 1),
(2, 'zavattolo', 'domenico', 'domenico.zavattolo@service-tec', '123456789', 0, 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `anagrafica`
--

ALTER TABLE `anagrafica`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `codice_fiscale` (`codice_fiscale`),
  ADD KEY `user_log1` (`user_id`);

--
-- Indici per le tabelle `certificazione`
--
ALTER TABLE `certificazione`
  ADD KEY `ind_ana` (`user_id`);

--
-- Indici per le tabelle `conoscenze`
--
ALTER TABLE `conoscenze`
  ADD KEY `ind_ana1` (`user_id`);

--
-- Indici per le tabelle `esperienza`
--
ALTER TABLE `esperienza`
  ADD KEY `ind_ana4` (`user_id`);


--
-- Indici per le tabelle `formazione`
--

ALTER TABLE `formazione`
  ADD KEY `ind_ana3` (`user_id`);

--
-- Indici per le tabelle `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`user_idd`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `login`
--
ALTER TABLE `login`
  MODIFY `user_idd` int(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `anagrafica`
--
ALTER TABLE `anagrafica`
  ADD CONSTRAINT `anagrafica_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login` (`user_idd`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `certificazione`
--
ALTER TABLE `certificazione`
  ADD CONSTRAINT `certificazione_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `anagrafica` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `conoscenze`
--
ALTER TABLE `conoscenze`
  ADD CONSTRAINT `conoscenze_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `anagrafica` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;


--
-- Limiti per la tabella `esperienza`
--
ALTER TABLE `esperienza`
  ADD CONSTRAINT `esperienza_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `anagrafica` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;


--
-- Limiti per la tabella `formazione`
--
ALTER TABLE `formazione`
  ADD CONSTRAINT `formazione_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `anagrafica` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
