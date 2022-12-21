INSERT INTO `users` (`ID`, `themesID`, `email`, `password`, `level`, `website`, `isVerified`, `isDisabled`, `username`) VALUES
(1, 1, 'admin@nocoma.com', '$2y$10$2SxdsJXmXX0pQO8/hRozwexRygBiP25ObuXS6khbUTotC08ydGOf.', 0, 'admin', 1, 0, 'admin'),
(2, 1, 'ceyif78060@edinel.com', '$2y$10$E3ipKoFt2LrvwBLhAWLwLeknI4lPUwgtKZLNQfIfSTNroCeO26C/S', 1, 'ceyif', 1, 0, 'Ceyif'),
(3, 1, 'nibad70145@diratu.com', '$2y$10$Q1oKpaJ63H09XDnCwIoAk.tswlOSqk2JbhvE.oUsbOkZL7XXKtY..', 1, 'nibad', 1, 0, 'Nibad'),
(6, 1, 'wifono8433@lubde.com', '$2y$10$TSN6ai2/lZ45Qe7tTl5lnO.mIK2fo2eq.GlRVqzIVxacQp/x3O5kq', 1, 'wifono', 1, 0, 'Wifono'),
(8, 1, 'girodoy421@nazyno.com', '$2y$10$ML16xzaVCp4MTqMQsijHy.02nYYSk1dAKeIRsx/tdAKmFX7.STcSm', 1, 'giro', 1, 0, 'Girodoy'),
(9, 1, 'user@nocoma.com', '$2y$10$xNL5JA9FYinWkOIsIFehmesCk.kNfFUQrK6z3UOnW/8UYhnnPGBoO', 1, '__test-bot__', 1, 0, 'TestBot'),
(11, 1, 'horakja19@zaci.spse.cz', '$2y$10$sMNQPU6chgLpGX/mX/hAY.DJARLFhP4SLGFP5t6jig8UBejPI2PrS', 1, 'horakja19', 1, 0, 'Jan_Horak');



INSERT INTO `websites` (`ID`, `usersID`, `thumbnailSRC`, `src`, `timeCreated`, `title`, `isTemplate`, `isPublic`, `areCommentsAvailable`, `isHomepage`, `isTakenDown`) VALUES
(9, 1, '', 'AGY6yANfjx', '2022-12-11 22:21:50', 'Untitled website.', 0, 1, 1, 0, 0),
(10, 1, '', 'GZ9j0Pns2X', '2022-12-11 22:22:04', 'Untitled website.', 0, 1, 1, 0, 0),
(11, 1, '', 'REwh__FxnJ', '2022-12-11 22:22:05', 'Untitled website.', 0, 0, 1, 0, 0),
(12, 1, '', 'D5V_aOdZYK', '2022-12-11 22:22:06', 'Untitled website.', 0, 1, 1, 1, 0),
(13, 6, '', 'tHnFJ5Y6X8', '2022-12-12 19:32:55', 'Untitled website.', 0, 1, 1, 1, 0),
(42, 9, '', '4PZKdClryE', '2022-12-14 21:54:11', 'My post', 0, 1, 0, 1, 0),
(43, 11, '', 'OWApdG6RW7', '2022-12-18 17:01:12', 'My first post', 0, 1, 0, 1, 0),
(44, 11, '', 'KbfkoGTVTM', '2022-12-21 07:59:41', 'lol', 0, 1, 1, 0, 0),
(45, 11, '', '6PqmTT8UE7', '2022-12-21 09:54:09', 'post', 0, 1, 1, 0, 0);



INSERT INTO `media` (`src`, `usersID`, `basename`, `extension`, `mimeContentType`, `timeCreated`, `hash`, `size`) VALUES
('6Dm_APHe97', 11, 'default-tablet-area', '.json', 'text/plain', '2022-12-18 21:18:20', '928c7fcb7877ce3f8376ec7da1ab91c4f5489c76', 1591),
('hDDe7zjyKC', 11, 'cetba', '.xlsm', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '2022-12-18 21:18:20', '4656c4dbdce106850598efa1d2a5918d6de76ad5', 17029),
('LFg8FpB3-u', 11, 'backup-collection', '.db', 'application/octet-stream', '2022-12-18 21:18:20', '6aa5d007814dd1420ee03e93a93058999f562953', 122559),
('RDmkWFiKLS', 11, 'api', '.js', 'text/plain', '2022-12-18 21:18:20', '89303aae1e5195fee3549c60c8d0639e623ec792', 897),
('RkMdNfmU1l', 11, 'Chatovací aplikace - Dokumentace Jan Horák', '.odt', 'application/vnd.oasis.opendocument.text', '2022-12-18 21:18:20', '77066d71d09e19c1a8dba75b056d31a4b2362fb6', 34806),
('rq1U7ASJbh', 9, 'Document', '.pdf', 'application/pdf', '2022-12-17 23:20:03', 'ea66f72b1ca248a31c0bbc87df46ae00339a0162', 575993),
('t0GV95RK3B', 11, 'CJL_kanon_2020-2021', '.pdf', 'application/pdf', '2022-12-18 21:18:20', 'f942848dfa5e93f5df3f71efccccc84e047e72b8', 580208),
('TRZuXIkjW6', 11, 'Bez názvu-1', '.ai', 'application/pdf', '2022-12-18 21:18:20', 'a5047d1ab8709ff66592d3ccbd56436ea18a3a34', 255578),
('yPA7qmF78b', 11, 'snippets', '.json', 'text/plain', '2022-12-18 21:18:20', '02ba8c3761468dd2bd01963a5fba0c566975699a', 147);