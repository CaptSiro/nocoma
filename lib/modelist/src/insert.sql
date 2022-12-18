INSERT INTO `users` (`ID`, `themesID`, `profileSRC`, `email`, `password`, `level`, `website`, `isVerified`, `isDisabled`, `username`) VALUES
(1, 1, NULL, 'admin@nocoma.com', '$2y$10$2SxdsJXmXX0pQO8/hRozwexRygBiP25ObuXS6khbUTotC08ydGOf.', 0, 'admin', 1, 0, 'admin'),
(2, 1, NULL, 'ceyif78060@edinel.com', '$2y$10$E3ipKoFt2LrvwBLhAWLwLeknI4lPUwgtKZLNQfIfSTNroCeO26C/S', 1, 'ceyif', 1, 0, 'Ceyif'),
(3, 1, NULL, 'nibad70145@diratu.com', '$2y$10$Q1oKpaJ63H09XDnCwIoAk.tswlOSqk2JbhvE.oUsbOkZL7XXKtY..', 1, 'nibad', 1, 0, 'Nibad'),
(6, 1, NULL, 'wifono8433@lubde.com', '$2y$10$TSN6ai2/lZ45Qe7tTl5lnO.mIK2fo2eq.GlRVqzIVxacQp/x3O5kq', 1, 'wifono', 1, 0, 'Wifono'),
(8, 1, NULL, 'girodoy421@nazyno.com', '$2y$10$ML16xzaVCp4MTqMQsijHy.02nYYSk1dAKeIRsx/tdAKmFX7.STcSm', 1, 'giro', 1, 0, 'Girodoy'),
(9, 1, NULL, 'user@nocoma.com', '$2y$10$xNL5JA9FYinWkOIsIFehmesCk.kNfFUQrK6z3UOnW/8UYhnnPGBoO', 1, '__test-bot__', 1, 0, 'TestBot');



INSERT INTO `websites` (`ID`, `usersID`, `thumbnailSRC`, `src`, `timeCreated`, `title`, `isTemplate`, `isPublic`, `areCommentsAvailable`, `isHomepage`, `isTakenDown`) VALUES
(9, 1, '', 'AGY6yANfjx', '2022-12-11 22:21:50', 'Untitled website.', 0, 1, 1, 0, 0),
(10, 1, '', 'GZ9j0Pns2X', '2022-12-11 22:22:04', 'Untitled website.', 0, 1, 1, 0, 0),
(11, 1, '', 'REwh__FxnJ', '2022-12-11 22:22:05', 'Untitled website.', 0, 0, 1, 0, 0),
(12, 1, '', 'D5V_aOdZYK', '2022-12-11 22:22:06', 'Untitled website.', 0, 1, 1, 1, 0),
(13, 6, '', 'tHnFJ5Y6X8', '2022-12-12 19:32:55', 'Untitled website.', 0, 1, 1, 1, 0),
(42, 9, '', '4PZKdClryE', '2022-12-14 21:54:11', 'My post', 0, 1, 0, 1, 0);



INSERT INTO `media` (`src`, `extension`, `basename`, `usersID`, `hash`, `timeCreated`) VALUES
('53uDrvQCNZ', '.sql', 'createDBV4', 9, 'ca1f08dcc3353297dae7b9f49e4008ff3746148d', '2022-12-15 22:36:16'),
('i4Zh8LAnCx', '.php', 'access', 9, '9f7544317df9860efae0493a469069ce94ff5946', '2022-12-15 22:36:10'),
('I9ZLcdRiYP', '.txt', 'db-log', 9, 'da39a3ee5e6b4b0d3255bfef95601890afd80709', '2022-12-15 22:36:25'),
('jcerRymsWN', '.htacces', '', 9, '81b14fa2e5a3a70d7cd0a7fbd2385ddbcbe86e9b', '2022-12-15 22:51:54'),
('XuskjpjeOn', 'empty', '', 9, 'eb5d69a93ab70ae73b78abf0c5225c58da3d06c3', '2022-12-15 22:51:35'),
('YG3qZhqs6R', '.sql', 'createDB', 9, 'b6b221e761bf3a608762704a01720b5e25f853d1', '2022-12-15 22:36:06');