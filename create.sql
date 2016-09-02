
CREATE TABLE `kinder` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `kinder`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `kinder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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

ALTER TABLE timetable
  ADD FOREIGN KEY (kind_id) REFERENCES kinder(id);


CREATE TABLE `schuljahre` (
  `id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `schuljahre`
--
ALTER TABLE `schuljahre`
  ADD PRIMARY KEY (`id`);

ALTER TABLE timetable
  ADD FOREIGN KEY (schuljahr_id) REFERENCES schuljahre(id);
