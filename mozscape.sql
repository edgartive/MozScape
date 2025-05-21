-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2025 at 02:25 AM
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
  `id_usuario` int(11) NOT NULL,
  `foto_url` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL,
  `tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidosupload`
--

INSERT INTO `pedidosupload` (`id_pedido`, `id_usuario`, `foto_url`, `descricao`, `data_pedido`, `status`, `tipo`) VALUES
(3, 12, 'user_12_681e294edd27a.jpg', 'No Desc', '2025-05-09 18:11:58', 'aprovado', 'retrato'),
(4, 12, 'user_12_681e4bc017212.jpg', 'Weeee', '2025-05-09 20:38:56', 'aprovado', 'natureza'),
(5, 12, 'user_12_681e4d861df06.jpg', 'yy', '2025-05-09 20:46:30', 'aprovado', 'natureza'),
(6, 12, 'user_12_681e4f12a2029.jpg', 'fggfg', '2025-05-09 20:53:06', 'recusado', 'natureza'),
(7, 12, 'user_12_681e528423e23.jpg', 'llllllll', '2025-05-09 21:07:48', 'recusado', 'natureza'),
(8, 12, 'user_12_682134889615d.jpg', 'cxc', '2025-05-12 01:36:40', 'recusado', 'paisagem'),
(9, 12, 'user_12_682280ccc56cd.jpg', 'hc', '2025-05-13 01:14:20', 'recusado', 'natureza'),
(10, 12, 'user_12_6824d85e4c469.jpg', 'Descv', '2025-05-14 19:52:30', 'aprovado', 'natureza'),
(11, 12, 'user_12_6825131a62034.jpg', 'A melhor foto do dia', '2025-05-15 00:03:06', 'aprovado', 'natureza'),
(12, 12, 'user_12_68251444e7942.jpg', 'jjjjjjjjjjjjjjjjjjjjj', '2025-05-15 00:08:04', 'aprovado', 'retrato'),
(13, 12, 'user_12_682517edcb452.jpg', 'hbvkjl', '2025-05-15 00:23:41', 'aprovado', 'céu'),
(14, 12, 'user_12_682524ec58c4e.jpg', 'Tender Love', '2025-05-15 01:19:08', 'aprovado', 'natureza'),
(15, 12, 'user_12_682528bbc0e72.jpg', '   fg', '2025-05-15 01:35:23', 'aprovado', 'natureza'),
(16, 12, 'user_12_68252c89034aa.jpg', 'Simm eu soy lindo', '2025-05-15 01:51:37', 'recusado', 'outros'),
(17, 12, 'user_12_6825319a692e3.jpg', 'ghhuhhhhhhhhhhhhh', '2025-05-15 02:13:14', 'aprovado', 'natureza');

-- --------------------------------------------------------

--
-- Table structure for table `pedidosuploader`
--

CREATE TABLE `pedidosuploader` (
  `id_pedido` int(11) NOT NULL,
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

INSERT INTO `pedidosuploader` (`id_pedido`, `id_usuario`, `foto_url`, `link_rede_social`, `frase_favorita`, `data_pedido`, `status`) VALUES
(1, 13, '68226c8475c75_485147810_1837789230290744_9080401765328246230_n.jpg', 'http://localhost/mozscape/frontend/php/uploaderrequest.php', 'sim', '2025-05-12 23:47:48', 'pendente');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id_upload` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `data_upload` datetime DEFAULT current_timestamp(),
  `descricao` text DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `foto_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id_upload`, `id_usuario`, `tipo`, `data_upload`, `descricao`, `likes`, `foto_url`) VALUES
(27, 12, 'retrato', '2025-05-09 18:12:22', 'No Desc', 33, 'user_12_681e294edd27a.jpg'),
(28, 12, 'retrato', '2025-05-09 18:13:13', 'No Desc', 0, 'user_12_681e294edd27a.jpg'),
(29, 12, 'natureza', '2025-05-09 20:40:04', 'Weeee', 0, 'user_12_681e4bc017212.jpg'),
(30, 12, 'natureza', '2025-05-09 20:46:39', 'Weeee', 0, 'user_12_681e4bc017212.jpg'),
(31, 12, 'natureza', '2025-05-09 20:46:40', 'yy', 0, 'user_12_681e4d861df06.jpg'),
(32, 12, 'natureza', '2025-05-14 20:19:43', 'Descv', 0, 'user_12_6824d85e4c469.jpg'),
(33, 12, 'natureza', '2025-05-15 00:03:20', 'A melhor foto do dia', 0, 'user_12_6825131a62034.jpg'),
(34, 12, 'natureza', '2025-05-15 00:08:12', 'A melhor foto do dia', 0, 'user_12_6825131a62034.jpg'),
(35, 12, 'retrato', '2025-05-15 00:08:27', 'jjjjjjjjjjjjjjjjjjjjj', 0, 'user_12_68251444e7942.jpg'),
(36, 12, 'retrato', '2025-05-15 00:24:00', 'jjjjjjjjjjjjjjjjjjjjj', 0, 'user_12_68251444e7942.jpg'),
(37, 12, 'céu', '2025-05-15 00:24:05', 'hbvkjl', 0, 'user_12_682517edcb452.jpg'),
(38, 12, 'natureza', '2025-05-15 01:20:24', 'Tender Love', 0, 'user_12_682524ec58c4e.jpg'),
(39, 12, 'natureza', '2025-05-15 01:35:29', 'Tender Love', 0, 'user_12_682524ec58c4e.jpg'),
(40, 12, 'natureza', '2025-05-15 01:35:30', '   fg', 0, 'user_12_682528bbc0e72.jpg'),
(41, 12, 'natureza', '2025-05-15 02:22:51', 'ghhuhhhhhhhhhhhhh', 0, 'user_12_6825319a692e3.jpg');

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
(12, 'Edson Magaia', 'edsonpain29@gmail.com', '$2y$10$SVfTkNoQjVuLL3H0HN7bx.EAzTwXT8XDnCABoxtINqlg.TRCnntA.', 'edson', '6821343a70fc0_Lidia.jpg', 'I love boobs and ass', 1, 'No ones loves me for money', 'www.xdiovis.com'),
(13, 'Tilza Magaia', 'tilza@gmail.com', '$2y$10$y0NIkjgv2wGYVkNkVbFsXOt2mYgpfy.Z22xcVxfHz/L08Ui65EMhG', 'tilza', '68226c52e39d5_Lidia.jpg', 'Edson é lindo', 0, 'nadaaa', 'sim\r\n');

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
  ADD KEY `id_uploader` (`id_usuario`);

--
-- Indexes for table `pedidosuploader`
--
ALTER TABLE `pedidosuploader`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id_upload`),
  ADD KEY `usuario_id` (`id_usuario`);

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
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pedidosuploader`
--
ALTER TABLE `pedidosuploader`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id_upload` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  ADD CONSTRAINT `pedidosupload_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `pedidosuploader`
--
ALTER TABLE `pedidosuploader`
  ADD CONSTRAINT `pedidosuploader_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `uploads`
--
ALTER TABLE `uploads`
  ADD CONSTRAINT `uploads_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
