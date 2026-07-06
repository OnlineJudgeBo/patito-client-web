SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Estructura de tabla para la tabla `classification`
--

CREATE TABLE `classification` (
  `classification_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compileinfo`
--

CREATE TABLE `compileinfo` (
  `solution_id` int(11) NOT NULL DEFAULT 0,
  `error` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contest`
--

CREATE TABLE `contest` (
  `contest_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `defunct` char(1) NOT NULL DEFAULT 'N',
  `description` text DEFAULT NULL,
  `private` tinyint(4) NOT NULL DEFAULT 0,
  `langmask` int(11) NOT NULL DEFAULT 0 COMMENT 'bits for LANG to mask',
  `obi` tinyint(1) NOT NULL DEFAULT 0,
  `track` varchar(32) DEFAULT NULL,
  `level` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contest_problem`
--

CREATE TABLE `contest_problem` (
  `contest_id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL DEFAULT 0,
  `title` char(200) DEFAULT '',
  `num` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contest_programming_language`
--

CREATE TABLE `contest_programming_language` (
  `contest_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contest_site`
--

CREATE TABLE `contest_site` (
  `contest_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contest_user`
--

CREATE TABLE `contest_user` (
  `contest_id` int(11) NOT NULL,
  `user_id` varchar(48) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `site_id` int(11) NOT NULL DEFAULT 1,
  `is_owner` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `custom_input`
--

CREATE TABLE `custom_input` (
  `solution_id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `user_id` varchar(48) NOT NULL,
  `site_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `custom_input_case`
--

CREATE TABLE `custom_input_case` (
  `solution_id` int(11) NOT NULL,
  `case_number` int(11) NOT NULL,
  `input_text` mediumtext NOT NULL,
  `expected_output` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `loginlog`
--

CREATE TABLE `loginlog` (
  `id` int(11) NOT NULL,
  `user_id` varchar(48) NOT NULL DEFAULT '',
  `site_id` int(11) NOT NULL DEFAULT 1,
  `password` varchar(40) DEFAULT NULL,
  `ip` text DEFAULT NULL,
  `time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news`
--

CREATE TABLE `news` (
  `news_id` int(11) NOT NULL,
  `user_id` varchar(48) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  `importance` tinyint(4) NOT NULL DEFAULT 0,
  `defunct` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `online`
--

CREATE TABLE `online` (
  `hash` varchar(32) NOT NULL,
  `ip` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `ua` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `refer` varchar(255) DEFAULT NULL,
  `lastmove` int(10) NOT NULL,
  `firsttime` int(10) DEFAULT NULL,
  `uri` varchar(255) DEFAULT NULL
) ENGINE=MEMORY DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `online_history`
--

CREATE TABLE `online_history` (
  `id` int(11) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `user_id` varchar(48) NOT NULL,
  `ip` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `ua` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `refer` text DEFAULT NULL,
  `lastmove` int(10) NOT NULL,
  `firsttime` int(10) DEFAULT NULL,
  `uri` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `privilege`
--

CREATE TABLE `privilege` (
  `privilege_id` int(11) NOT NULL,
  `user_id` char(48) NOT NULL DEFAULT '',
  `rightstr` char(30) NOT NULL DEFAULT '',
  `defunct` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `problem`
--

CREATE TABLE `problem` (
  `problem_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `input` text DEFAULT NULL,
  `output` text DEFAULT NULL,
  `sample_input` text DEFAULT NULL,
  `sample_output` text DEFAULT NULL,
  `spj` char(1) NOT NULL DEFAULT '0',
  `hint` text DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `in_date` datetime DEFAULT NULL,
  `time_limit` int(11) NOT NULL DEFAULT 0,
  `memory_limit` int(11) NOT NULL DEFAULT 0,
  `defunct` char(1) NOT NULL DEFAULT 'N',
  `accepted` int(11) DEFAULT 0,
  `submit` int(11) DEFAULT 0,
  `solved` int(11) DEFAULT 0,
  `tags` varchar(5) DEFAULT NULL,
  `tags2` varchar(256) DEFAULT NULL,
  `origin_source` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `problems_site`
--

CREATE TABLE `problems_site` (
  `problem_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `problem_classification`
--

CREATE TABLE `problem_classification` (
  `problem_id` int(11) NOT NULL,
  `classification_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programing_language`
--

CREATE TABLE `programing_language` (
  `language_id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `remote_clients`
--

CREATE TABLE `remote_clients` (
  `client_id` int(11) NOT NULL,
  `callback_url` varchar(255) NOT NULL,
  `token` text NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `runtimeinfo`
--

CREATE TABLE `runtimeinfo` (
  `solution_id` int(11) NOT NULL DEFAULT 0,
  `error` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sim`
--

CREATE TABLE `sim` (
  `s_id` int(11) NOT NULL,
  `sim_s_id` int(11) DEFAULT NULL,
  `sim` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `similar_code`
--

CREATE TABLE `similar_code` (
  `solution_id` int(11) NOT NULL,
  `similar_s_id` int(11) NOT NULL,
  `percentage` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sites`
--

CREATE TABLE `sites` (
  `site_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solution`
--

CREATE TABLE `solution` (
  `solution_id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL DEFAULT 0,
  `user_id` varchar(48) NOT NULL,
  `time` int(11) NOT NULL DEFAULT 0,
  `memory` int(11) NOT NULL DEFAULT 0,
  `in_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `result` smallint(6) NOT NULL DEFAULT 0,
  `language` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ip` char(15) NOT NULL,
  `contest_id` int(11) DEFAULT NULL,
  `is_virtual` tinyint(1) NOT NULL DEFAULT 0,
  `num` int(11) NOT NULL DEFAULT -1,
  `code_length` int(11) NOT NULL DEFAULT 0,
  `judgetime` datetime DEFAULT NULL,
  `pass_rate` decimal(2,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `is_remote_oj` int(1) NOT NULL DEFAULT 0,
  `remote_id` int(11) DEFAULT NULL,
  `site_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solution_client`
--

CREATE TABLE `solution_client` (
  `id` int(11) NOT NULL,
  `solution_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `assigned_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `source_code`
--

CREATE TABLE `source_code` (
  `solution_id` int(11) NOT NULL,
  `source` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `topic`
--

CREATE TABLE `topic` (
  `topic_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` varchar(48) NOT NULL,
  `site_id` int(11) NOT NULL DEFAULT 1,
  `ip` text NOT NULL DEFAULT '',
  `accesstime` datetime DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `reg_time` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `reset_password_token` varchar(255) DEFAULT NULL,
  `reset_password_expires` datetime DEFAULT NULL,
  `is_active` bigint(20) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_activity`
--

CREATE TABLE `user_activity` (
  `user_id` varchar(48) NOT NULL DEFAULT '',
  `site_id` int(11) NOT NULL DEFAULT 1,
  `submit` int(11) DEFAULT 0,
  `solved` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_profiles`
--

CREATE TABLE `user_profiles` (
  `user_id` varchar(48) NOT NULL DEFAULT '',
  `site_id` int(11) NOT NULL DEFAULT 1,
  `email` varchar(100) DEFAULT NULL,
  `nick` varchar(100) NOT NULL DEFAULT '',
  `school` varchar(100) DEFAULT '',
  `lastname` varchar(100) DEFAULT NULL,
  `pais_id` int(11) DEFAULT 0,
  `ci` text DEFAULT NULL,
  `departament` int(11) NOT NULL DEFAULT 0,
  `district` text DEFAULT NULL,
  `registration_domain` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` varchar(48) NOT NULL,
  `site_id` int(11) NOT NULL DEFAULT 1,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_settings`
--

CREATE TABLE `user_settings` (
  `user_id` varchar(48) NOT NULL DEFAULT '',
  `site_id` int(11) NOT NULL DEFAULT 1,
  `volume` int(11) NOT NULL DEFAULT 1,
  `language` int(11) NOT NULL DEFAULT 1,
  `obi` int(11) DEFAULT NULL,
  `institucion_id` int(11) NOT NULL DEFAULT 0,
  `vcyt` text DEFAULT NULL,
  `rude` text DEFAULT NULL,
  `level` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `classification`
--
ALTER TABLE `classification`
  ADD PRIMARY KEY (`classification_id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Indices de la tabla `compileinfo`
--
ALTER TABLE `compileinfo`
  ADD PRIMARY KEY (`solution_id`);

--
-- Indices de la tabla `contest`
--
ALTER TABLE `contest`
  ADD PRIMARY KEY (`contest_id`);

--
-- Indices de la tabla `contest_problem`
--
ALTER TABLE `contest_problem`
  ADD PRIMARY KEY (`contest_id`,`problem_id`),
  ADD KEY `contest_id` (`contest_id`),
  ADD KEY `problem_id` (`problem_id`);

--
-- Indices de la tabla `contest_programming_language`
--
ALTER TABLE `contest_programming_language`
  ADD PRIMARY KEY (`contest_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indices de la tabla `contest_site`
--
ALTER TABLE `contest_site`
  ADD PRIMARY KEY (`contest_id`,`site_id`),
  ADD KEY `contest_id` (`contest_id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indices de la tabla `contest_user`
--
ALTER TABLE `contest_user`
  ADD PRIMARY KEY (`contest_id`,`user_id`,`site_id`) USING BTREE,
  ADD KEY `contest_id` (`contest_id`,`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indices de la tabla `custom_input`
--
ALTER TABLE `custom_input`
  ADD PRIMARY KEY (`solution_id`),
  ADD KEY `custom_input_problem_id` (`problem_id`),
  ADD KEY `custom_input_user_id` (`user_id`),
  ADD KEY `custom_input_site_id` (`site_id`);

--
-- Indices de la tabla `custom_input_case`
--
ALTER TABLE `custom_input_case`
  ADD PRIMARY KEY (`solution_id`,`case_number`);

--
-- Indices de la tabla `loginlog`
--
ALTER TABLE `loginlog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indices de la tabla `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `online`
--
ALTER TABLE `online`
  ADD PRIMARY KEY (`hash`),
  ADD UNIQUE KEY `hash` (`hash`);

--
-- Indices de la tabla `online_history`
--
ALTER TABLE `online_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hash` (`hash`);

--
-- Indices de la tabla `privilege`
--
ALTER TABLE `privilege`
  ADD PRIMARY KEY (`privilege_id`);

--
-- Indices de la tabla `problem`
--
ALTER TABLE `problem`
  ADD PRIMARY KEY (`problem_id`),
  ADD KEY `problem_id` (`problem_id`);

--
-- Indices de la tabla `problems_site`
--
ALTER TABLE `problems_site`
  ADD PRIMARY KEY (`problem_id`,`site_id`),
  ADD KEY `institution_id` (`site_id`);

--
-- Indices de la tabla `problem_classification`
--
ALTER TABLE `problem_classification`
  ADD PRIMARY KEY (`problem_id`,`classification_id`),
  ADD KEY `classification_id` (`classification_id`);

--
-- Indices de la tabla `programing_language`
--
ALTER TABLE `programing_language`
  ADD PRIMARY KEY (`language_id`);

--
-- Indices de la tabla `remote_clients`
--
ALTER TABLE `remote_clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indices de la tabla `runtimeinfo`
--
ALTER TABLE `runtimeinfo`
  ADD PRIMARY KEY (`solution_id`);

--
-- Indices de la tabla `sim`
--
ALTER TABLE `sim`
  ADD PRIMARY KEY (`s_id`);

--
-- Indices de la tabla `similar_code`
--
ALTER TABLE `similar_code`
  ADD PRIMARY KEY (`solution_id`,`similar_s_id`);

--
-- Indices de la tabla `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`site_id`);

--
-- Indices de la tabla `solution`
--
ALTER TABLE `solution`
  ADD PRIMARY KEY (`solution_id`),
  ADD KEY `uid` (`user_id`),
  ADD KEY `pid` (`problem_id`),
  ADD KEY `res` (`result`),
  ADD KEY `cid` (`contest_id`);

--
-- Indices de la tabla `solution_client`
--
ALTER TABLE `solution_client`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solution_id` (`solution_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indices de la tabla `source_code`
--
ALTER TABLE `source_code`
  ADD PRIMARY KEY (`solution_id`);

--
-- Indices de la tabla `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`topic_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`,`site_id`) USING BTREE,
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indices de la tabla `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`user_id`,`site_id`) USING BTREE,
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indices de la tabla `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`user_id`,`site_id`) USING BTREE,
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indices de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`,`site_id`) USING BTREE,
  ADD KEY `user_roles_ibfk_1` (`user_id`),
  ADD KEY `user_roles_ibfk_2` (`role_id`),
  ADD KEY `site_id` (`site_id`);

--
-- Indices de la tabla `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`user_id`,`site_id`) USING BTREE,
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `site_id` (`site_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `classification`
--
ALTER TABLE `classification`
  MODIFY `classification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contest`
--
ALTER TABLE `contest`
  MODIFY `contest_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `loginlog`
--
ALTER TABLE `loginlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `online_history`
--
ALTER TABLE `online_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `privilege`
--
ALTER TABLE `privilege`
  MODIFY `privilege_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `problem`
--
ALTER TABLE `problem`
  MODIFY `problem_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `programing_language`
--
ALTER TABLE `programing_language`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `remote_clients`
--
ALTER TABLE `remote_clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sites`
--
ALTER TABLE `sites`
  MODIFY `site_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solution`
--
ALTER TABLE `solution`
  MODIFY `solution_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solution_client`
--
ALTER TABLE `solution_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `topic`
--
ALTER TABLE `topic`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `classification`
--
ALTER TABLE `classification`
  ADD CONSTRAINT `classification_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`topic_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `compileinfo`
--
ALTER TABLE `compileinfo`
  ADD CONSTRAINT `compileinfo_ibfk_1` FOREIGN KEY (`solution_id`) REFERENCES `solution` (`solution_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `contest_problem`
--
ALTER TABLE `contest_problem`
  ADD CONSTRAINT `contest_problem_ibfk_1` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`contest_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `contest_problem_ibfk_2` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`problem_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `contest_programming_language`
--
ALTER TABLE `contest_programming_language`
  ADD CONSTRAINT `contest_programming_language_ibfk_1` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`contest_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `contest_programming_language_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `programing_language` (`language_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `contest_user`
--
ALTER TABLE `contest_user`
  ADD CONSTRAINT `contest_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `contest_user_ibfk_2` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`contest_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Filtros para la tabla `problems_site`
--
ALTER TABLE `problems_site`
  ADD CONSTRAINT `problems_site_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`problem_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `problems_site_ibfk_2` FOREIGN KEY (`site_id`) REFERENCES `sites` (`site_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `problem_classification`
--
ALTER TABLE `problem_classification`
  ADD CONSTRAINT `problem_classification_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`problem_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `problem_classification_ibfk_2` FOREIGN KEY (`classification_id`) REFERENCES `classification` (`classification_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `runtimeinfo`
--
ALTER TABLE `runtimeinfo`
  ADD CONSTRAINT `runtimeinfo_ibfk_1` FOREIGN KEY (`solution_id`) REFERENCES `solution` (`solution_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `solution`
--
ALTER TABLE `solution`
  ADD CONSTRAINT `solution_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `solution_ibfk_2` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`problem_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `solution_client`
--
ALTER TABLE `solution_client`
  ADD CONSTRAINT `solution_client_ibfk_1` FOREIGN KEY (`solution_id`) REFERENCES `solution` (`solution_id`),
  ADD CONSTRAINT `solution_client_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `remote_clients` (`client_id`);

--
-- Filtros para la tabla `source_code`
--
ALTER TABLE `source_code`
  ADD CONSTRAINT `source_code_ibfk_1` FOREIGN KEY (`solution_id`) REFERENCES `solution` (`solution_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;
COMMIT;
