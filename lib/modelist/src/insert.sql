INSERT INTO `users` (`ID`, `themesID`, `email`, `password`, `level`, `website`, `isVerified`, `isDisabled`, `username`) VALUES
(1, 1, 'admin@nocoma.com', '$2y$10$2SxdsJXmXX0pQO8/hRozwexRygBiP25ObuXS6khbUTotC08ydGOf.', 0, 'admin', 1, 0, 'admin'),
(2, 1, 'ceyif78060@edinel.com', '$2y$10$E3ipKoFt2LrvwBLhAWLwLeknI4lPUwgtKZLNQfIfSTNroCeO26C/S', 1, 'ceyif', 1, 0, 'Ceyif'),
(3, 1, 'nibad70145@diratu.com', '$2y$10$Q1oKpaJ63H09XDnCwIoAk.tswlOSqk2JbhvE.oUsbOkZL7XXKtY..', 1, 'nibad', 1, 0, 'Nibad'),
(6, 1, 'wifono8433@lubde.com', '$2y$10$TSN6ai2/lZ45Qe7tTl5lnO.mIK2fo2eq.GlRVqzIVxacQp/x3O5kq', 1, 'wifono', 1, 0, 'Wifono'),
(8, 1, 'girodoy421@nazyno.com', '$2y$10$ML16xzaVCp4MTqMQsijHy.02nYYSk1dAKeIRsx/tdAKmFX7.STcSm', 1, 'giro', 1, 0, 'Girodoy'),
(9, 1, 'user@nocoma.com', '$2y$10$xNL5JA9FYinWkOIsIFehmesCk.kNfFUQrK6z3UOnW/8UYhnnPGBoO', 1, '__test-bot__', 1, 0, 'TestBot'),
(11, 1, 'horakja19@zaci.spse.cz', '$2y$10$sMNQPU6chgLpGX/mX/hAY.DJARLFhP4SLGFP5t6jig8UBejPI2PrS', 1, 'horakja19', 1, 0, 'Jan_Horak');



INSERT INTO `websites` (`ID`, `usersID`, `thumbnailSRC`, `src`, `timeCreated`, `title`, `isTemplate`, `isPublic`, `areCommentsAvailable`, `isHomepage`, `isTakenDown`) VALUES
(1, 9, '', 'sdps8Hq3_o', '2023-01-12 19:24:11', 'Hello world!', 0, 1, 1, 1, 0);



INSERT INTO `media` (`src`, `usersID`, `basename`, `extension`, `mimeContentType`, `timeCreated`, `hash`, `size`) VALUES
('6Dm_APHe97', 11, 'default-tablet-area', '.json', 'text/plain', '2022-12-18 21:18:20', '928c7fcb7877ce3f8376ec7da1ab91c4f5489c76', 1591),
('80vB_IbHsY', 9, 'testmysql', '.php', 'text/x-php', '2023-01-04 08:49:35', 'eb5cb8476f0ece9719f2651be9353a91addcc059', 810),
('c5H2woc2Zn', 9, 'renamed', '.php', 'text/x-php', '2023-01-04 08:49:35', 'dbfc629e96a1937580951e336df36c643de86197', 2338),
('hDDe7zjyKC', 11, 'cetba', '.xlsm', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '2022-12-18 21:18:20', '4656c4dbdce106850598efa1d2a5918d6de76ad5', 17029),
('LFg8FpB3-u', 11, 'backup-collection', '.db', 'application/octet-stream', '2022-12-18 21:18:20', '6aa5d007814dd1420ee03e93a93058999f562953', 122559),
('RDmkWFiKLS', 11, 'api', '.js', 'text/plain', '2022-12-18 21:18:20', '89303aae1e5195fee3549c60c8d0639e623ec792', 897),
('RkMdNfmU1l', 11, 'Chatovací aplikace - Dokumentace Jan Horák', '.odt', 'application/vnd.oasis.opendocument.text', '2022-12-18 21:18:20', '77066d71d09e19c1a8dba75b056d31a4b2362fb6', 34806),
('rq1U7ASJbh', 9, 'Document', '.pdf', 'application/pdf', '2022-12-17 23:20:03', 'ea66f72b1ca248a31c0bbc87df46ae00339a0162', 575993),
('t0GV95RK3B', 11, 'CJL_kanon_2020-2021', '.pdf', 'application/pdf', '2022-12-18 21:18:20', 'f942848dfa5e93f5df3f71efccccc84e047e72b8', 580208),
('TRZuXIkjW6', 11, 'Bez názvu-1', '.ai', 'application/pdf', '2022-12-18 21:18:20', 'a5047d1ab8709ff66592d3ccbd56436ea18a3a34', 255578),
('yPA7qmF78b', 11, 'snippets', '.json', 'text/plain', '2022-12-18 21:18:20', '02ba8c3761468dd2bd01963a5fba0c566975699a', 147);



INSERT INTO `profilepictures` (`src`, `usersID`, `hash`, `extension`) VALUES
('6TV8faYGwR', 1, '7f7a867c28f24218f89fb2938856c2b8469652f3', '.jpg'),
('od39VPpL5b', 9, '778e2e39e4c4ed2d4af4a07bc7bec5d2f4ac2bea', '.jpg');