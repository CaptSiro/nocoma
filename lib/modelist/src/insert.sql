INSERT INTO `users` (`ID`, `email`, `password`, `level`, `website`, `isVerified`, `isDisabled`, `username`) VALUES
(1, 'admin@nocoma.com', '$2y$10$2SxdsJXmXX0pQO8/hRozwexRygBiP25ObuXS6khbUTotC08ydGOf.', 0, 'admin', 1, 0, 'admin'),
(2, 'ceyif78060@edinel.com', '$2y$10$E3ipKoFt2LrvwBLhAWLwLeknI4lPUwgtKZLNQfIfSTNroCeO26C/S', 1, 'ceyif', 1, 0, 'Ceyif'),
(3, 'nibad70145@diratu.com', '$2y$10$Q1oKpaJ63H09XDnCwIoAk.tswlOSqk2JbhvE.oUsbOkZL7XXKtY..', 1, 'nibad', 1, 0, 'Nibad'),
(6, 'wifono8433@lubde.com', '$2y$10$TSN6ai2/lZ45Qe7tTl5lnO.mIK2fo2eq.GlRVqzIVxacQp/x3O5kq', 1, 'wifono', 1, 0, 'Wifono'),
(8, 'girodoy421@nazyno.com', '$2y$10$ML16xzaVCp4MTqMQsijHy.02nYYSk1dAKeIRsx/tdAKmFX7.STcSm', 1, 'giro', 1, 0, 'Girodoy'),
(9, 'user@nocoma.com', '$2y$10$xNL5JA9FYinWkOIsIFehmesCk.kNfFUQrK6z3UOnW/8UYhnnPGBoO', 1, '__test-bot__', 1, 0, 'TestBot'),
(11, 'horakja19@zaci.spse.cz', '$2y$10$sMNQPU6chgLpGX/mX/hAY.DJARLFhP4SLGFP5t6jig8UBejPI2PrS', 1, 'horakja19', 1, 0, 'Jan_Horak');



INSERT INTO `websites` (`ID`, `themesSRC`, `thumbnailSRC`, `usersID`, `src`, `timeCreated`, `title`, `isTemplate`, `isPublic`, `isHomepage`, `isTakenDown`) VALUES
(1, NULL, 'N1oPpZttIv', 9, 'sdps8Hq3_o', '2023-01-12 19:24:11', 'Hello world!', 0, 0, 1, 0),
(4, NULL, 'ok_fxbB2oe', 9, 'OZ32ziRpiW', '2023-01-25 20:08:54', 'The post I promised about ASYNC and AWAIT', 0, 0, 0, 0),
(5, NULL, 'DF4rPgYg-Z', 9, '9KbB7i-fCN', '2023-01-25 20:28:33', 'Best operators in Javascript', 0, 1, 0, 0),
(6, NULL, 'Jxvhlm_p-N', 9, 'hjP7JvX90I', '2023-01-26 09:45:21', 'How to use SVG currentColor', 0, 1, 0, 0),
(7, NULL, '99sGLq1Gfv', 9, 'Sp9OP_ybn3', '2023-01-28 11:54:24', 'New', 0, 1, 0, 0);



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
('jdxFXNSMQI', 1, '6d02d033793a276359596bf81f277981b53036d7', '.png'),
('mEazL2UHiT', 9, '28ede335f124a5b68737d71954684bc2dcef2c49', '.png');



INSERT INTO `themes` (`src`, `usersID`, `name`, `hash`) VALUES
('000000_d', 0, 'Nocoma (Dark)', 'd7210444a19e7455f62acf7a5f10445b072f78b6'),
('000000_l', 0, 'Nocoma (Light)', 'e633ecbd8b1817fb7ec7ad3295e7c2f4a61c213c'),
('000001_d', 0, 'Leafy (Dark)', '7692ea40457abe48ff3838f71f391bd1d74cd1b9'),
('000001_l', 0, 'Leafy (Light)', 'be4d3a79fb0f847ec2214b29e4bed3ad8577761a'),
('000002_d', 0, 'Orange (Dark)', '81c1e78b576b2170a8a655d4858e7267e57ffab2'),
('000002_l', 0, 'Orange (Light)', 'c56d87a9c89f101da89e18ff307386bec73ad460'),
('000003_l', 0, 'Weathered Copper (Light)', '14a219ca3605ce11e33a99ec5097a57e42fae142'),
('000004_l', 0, 'Ruby (Light)', 'ca1d037bfccfdbb19194887e15a9f98e832397df'),
('000005_b', 0, 'Idea (Dark Blue)', 'e43231edd743c13f28e5ab4918a326d1347a8b28'),
('000005_w', 0, 'Idea (Light)', 'a1448b76a8e4d9a20ac643e84d2e940e52e42a32'),
('000006_d', 0, 'Glacier Ice (Dark)', '382e8fafc740175f6c61c8665242fced1ec5d966'),
('000006_l', 0, 'Glacier Ice (Light)', '8aede0b85e227329ccd848a87f40dbd52d821be8'),
('000007_d', 0, 'Red Wine (Dark)', 'f316afa992dd6f25ee129a5e926a8f0cd78333e0'),
('000008_d', 0, 'Cherry Blossom (Dark)', '7054b7d770eb571076a7b9d60076d7f50ca01f88');