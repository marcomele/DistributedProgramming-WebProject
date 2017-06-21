CREATE TABLE `bid_state` (
  `value` float NOT NULL DEFAULT '1',
  `UID` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `bid_state` (`value`, `UID`) VALUES
(1, NULL);

CREATE TABLE `users` (
  `UID` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwd` varchar(32) NOT NULL,
  `threshold` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `bid_state`
  ADD KEY `UID` (`UID`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`UID`),
  ADD UNIQUE KEY `EMAIL` (`email`),
  ADD UNIQUE KEY `ID` (`UID`);
  
ALTER TABLE `users`
  MODIFY `UID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
  
INSERT INTO `users` (`UID`, `email`, `passwd`, `threshold`) VALUES
(1, 'a@p.it', '846b0090c302bbe0ed16b670522ad993', NULL),
(2, 'b@p.it', '87addc07a30c5bb9127c06dc686a5766', NULL),
(3, 'c@p.it', 'ac0812b0e7be3cbdbcf3f1dc46375809', NULL);

