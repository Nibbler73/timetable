
--
-- Tabellenstruktur für Tabelle `timetable`
--

CREATE TABLE `timetable` (
  `kind_id` int(11) NOT NULL,
  `schuljahr_id` int(11) NOT NULL,
  `stunde` varchar(40) NOT NULL,
  `Montag` varchar(255) DEFAULT NULL,
  `Dienstag` varchar(255) DEFAULT NULL,
  `Mittwoch` varchar(255) DEFAULT NULL,
  `Donnerstag` varchar(255) DEFAULT NULL,
  `Freitag` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='Stundenplan pro Kind pro Schuljahr';

--
-- Indizes für die Tabelle `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`kind_id`,`schuljahr_id`,`stunde`);
