INSERT INTO themes (`name`) VALUE ("default");
INSERT INTO users (`themesID`, `email`, `password`, `level`, `website`, `isVerified`)
VALUES (1, "admin@nocoma.com", "$2y$10$2SxdsJXmXX0pQO8/hRozwexRygBiP25ObuXS6khbUTotC08ydGOf.", 0, "admin", 1),
       (1, "ceyif78060@edinel.com", "$2y$10$E3ipKoFt2LrvwBLhAWLwLeknI4lPUwgtKZLNQfIfSTNroCeO26C/S", 1, "ceyif", 1),
       (1, "nibad70145@diratu.com", "$2y$10$Q1oKpaJ63H09XDnCwIoAk.tswlOSqk2JbhvE.oUsbOkZL7XXKtY..", 1, "nibad", 1);