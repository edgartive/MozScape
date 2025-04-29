-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 11:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mozscape`
--

-- --------------------------------------------------------

--
-- Table structure for table `administradores`
--

CREATE TABLE `administradores` (
  `id_admin` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administradores`
--

INSERT INTO `administradores` (`id_admin`, `nome`, `username`, `senha`) VALUES
(1, 'admin', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `historias`
--

CREATE TABLE `historias` (
  `id_historia` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `info_wiki` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pedidosupload`
--

CREATE TABLE `pedidosupload` (
  `id_pedido` int(11) NOT NULL,
  `id_uploader` int(11) NOT NULL,
  `foto_url` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pedidosuploader`
--

CREATE TABLE `pedidosuploader` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `foto_url` varchar(255) NOT NULL,
  `link_rede_social` varchar(255) DEFAULT NULL,
  `frase_favorita` text DEFAULT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidosuploader`
--

INSERT INTO `pedidosuploader` (`id`, `id_usuario`, `foto_url`, `link_rede_social`, `frase_favorita`, `data_pedido`, `status`) VALUES
(1, 4, '680ba2903efb6_20250412_164613.jpg', 'https://web.whatsapp.com/', 'I hate people with love.', '2025-04-25 16:56:16', 'pendente');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id_upload` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `data_upload` datetime DEFAULT current_timestamp(),
  `descricao` text DEFAULT NULL,
  `likes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome_completo` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `foto_de_perfil_url` varchar(255) DEFAULT NULL,
  `frase_favorita` varchar(200) DEFAULT '0',
  `uploader` tinyint(1) DEFAULT 0,
  `biografia` text DEFAULT NULL,
  `links` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome_completo`, `email`, `senha`, `username`, `foto_de_perfil_url`, `frase_favorita`, `uploader`, `biografia`, `links`) VALUES
(1, 'Pain', 'edsonpain29@gmail.com', '123', 'paindesigner_29', '67ad06e77d946_IMG_9961.jpg', '0', 0, 'I love my penis', 'www.mypika.com'),
(2, 'Edson', 'paindesigner29@gmail.com', '111', 'pain', '67ad1c21589f0_IMG_9955.jpg', '0', 0, 'I like everything on her', 'my links'),
(4, 'Das Doresr', 'edsonpadin29@gmail.com', '$2y$10$2fc/PdgECq6naxyW7U7n1OglsqF.biMqIcGzhJLi0KlGNeVy0hupO', 'edson', '680ba68c157ce_20250412_164613.jpg', '0', 0, 'He loves boobs', 'Linkss'),
(5, 'Ele Lindo', 'edsonpainas29@gmail.com', '$2y$10$tyan569KgwrcOklugX23SOO/GLwt9EZntgq4j4Q/ChYk1K36EP/La', 'lindo', '67af8dc3540be_IMG_0027-3.jpg', '0', 0, 'A chill guy, bro', 'www.www.com'),
(7, 'Pain Designer', 'edsonddspain29@gmail.com', '$2y$10$jth7xsBIxOwNtdwCNmBJO.fEFUJJRwPVzAHut0krQkxDjyrwgd6V.', 'paindzn', '67af9658c427b_IMG_0055.jpg', 'Peace was never an option.', 0, 'Nikes and adidas', 'ess dedsos'),
(9, 'Pain Pik', 'edsonpain249@gmail.com', '$2y$10$VHRRmvGo7cXJ6q0TaMqX7OJ6hJoBAES.L.gRu/0WpQJEQAjGkDhTi', 'dzn', '6800ddf225c82_mapa.gif', 'Peace was never an option.', 0, 'Just a chill guy', 'yes.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`username`);

--
-- Indexes for table `historias`
--
ALTER TABLE `historias`
  ADD PRIMARY KEY (`id_historia`),
  ADD KEY `upload_id` (`upload_id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `pedidosupload`
--
ALTER TABLE `pedidosupload`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_uploader` (`id_uploader`);

--
-- Indexes for table `pedidosuploader`
--
ALTER TABLE `pedidosuploader`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id_upload`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `historias`
--
ALTER TABLE `historias`
  MODIFY `id_historia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pedidosupload`
--
ALTER TABLE `pedidosupload`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pedidosuploader`
--
ALTER TABLE `pedidosuploader`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id_upload` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `historias`
--
ALTER TABLE `historias`
  ADD CONSTRAINT `historias_ibfk_1` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id_upload`) ON DELETE CASCADE,
  ADD CONSTRAINT `historias_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `pedidosupload`
--
ALTER TABLE `pedidosupload`
  ADD CONSTRAINT `pedidosupload_ibfk_1` FOREIGN KEY (`id_uploader`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `pedidosuploader`
--
ALTER TABLE `pedidosuploader`
  ADD CONSTRAINT `pedidosuploader_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `uploads`
--
ALTER TABLE `uploads`
  ADD CONSTRAINT `uploads_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
