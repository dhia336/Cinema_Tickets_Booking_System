-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2025 at 11:04 PM
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
-- Database: `cinema_ticket_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `showtime_id` int(11) DEFAULT NULL,
  `seats` text NOT NULL,
  `booking_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_status` enum('paid','unpaid') DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `showtime_id`, `seats`, `booking_time`, `payment_status`) VALUES
(29, 11, 9, '[\"A2\"]', '2024-11-17 16:32:46', 'paid'),
(30, 11, 1, '[\"A2\"]', '2024-11-17 16:40:35', 'paid'),
(31, 11, 5, '[\"A4\"]', '2024-11-17 16:40:44', 'paid'),
(32, 10, 2, '[\"A3\"]', '2024-11-17 17:01:14', 'paid'),
(33, 10, 6, '[\"A3\",\"A5\"]', '2024-11-17 17:01:29', 'paid'),
(34, 10, 8, '[\"A2\"]', '2024-11-17 18:49:54', 'paid'),
(35, 12, 4, '[\"A3\",\"A5\"]', '2024-12-02 16:11:59', 'paid'),
(36, 12, 6, '[\"A1\",\"A2\",\"A3\",\"A4\",\"A5\"]', '2024-12-03 13:18:07', 'paid'),
(37, 13, 4, '[\"A3\",\"A5\"]', '2024-12-03 13:23:51', 'paid'),
(38, 13, 3, '[\"A2\",\"A4\"]', '2024-12-03 13:23:56', 'paid'),
(39, 12, 1, '[\"A2\",\"A5\"]', '2024-12-03 16:26:33', 'paid'),
(40, 12, 9, '[\"A4\"]', '2024-12-03 17:48:57', 'paid'),
(41, 12, 7, '[\"A5\"]', '2024-12-03 17:56:05', 'paid'),
(42, 13, 2, '[\"A5\"]', '2024-12-03 20:36:36', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `genre` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `poster_url` varchar(255) DEFAULT NULL,
  `trailer_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `genre`, `description`, `duration`, `poster_url`, `trailer_url`) VALUES
(1, 'Taxi Driver (1976)', 'Action', 'Directed by Martin Scorsese, this psychological thriller follows Travis Bickle, a mentally unstable Vietnam War veteran working as a taxi driver in New York City. He becomes disillusioned by the city\'s decay and spirals into violent vigilante justice.', 114, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQl4bQdxogeILcSAPXHiQO00d9xRZ0pxgRnMA&s\r\n', 'https://www.youtube.com/watch?v=cujiHDeqnHY\r\n'),
(2, 'Fight Club (1999)', 'Action', 'Directed by David Fincher, this film explores themes of consumerism, identity, and anarchism. It follows an insomniac office worker and a charismatic soap salesman who start an underground fight club that spirals into chaos and rebellion against societal norms', 139, 'https://m.media-amazon.com/images/I/81Luju2cHuL._AC_UF894,1000_QL80_.jpg\r\n', 'https://www.youtube.com/watch?v=JOFgLVjchHU\r\n'),
(3, 'The Good, the Bad and the Ugly (1966)', 'Action', 'Directed by Sergio Leone, this classic spaghetti Western follows three gunslingers competing to locate buried gold amid the chaos of the American Civil War. Starring Clint Eastwood, Lee Van Cleef, and Eli Wallach, it features epic storytelling, iconic music, and unforgettable standoffs', 178, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRooRQRQud4T1qEUcHwbki9jBBmnYBMvXGcIg&s\r\n', 'https://www.youtube.com/watch?v=3VQjSSB78es\r\n'),
(4, 'Dragon Ball Super: Broly (2018)', 'Animation', 'Directed by Tatsuya Nagamine, this anime movie expands the Dragon Ball universe by introducing Broly, a Saiyan with a mysterious past and overwhelming power. As Goku and Vegeta face Broly in an epic battle, the story weaves in rich Saiyan history and intergalactic drama', 100, 'https://i.ebayimg.com/images/g/EeQAAOSw1hZeT3VF/s-l1200.jpg\r\n', 'https://www.youtube.com/watch?v=JjTGZX3Y_j0\r\n'),
(5, 'The Shawshank Redemption (1994)', 'Action', 'This iconic prison drama, directed by Frank Darabont, is based on a novella by Stephen King. It tells the story of Andy Dufresne, a banker wrongly convicted of murder, and his journey of survival and redemption at Shawshank prison. The film is celebrated for its themes of hope and perseverance', 142, 'https://m.media-amazon.com/images/M/MV5BMDAyY2FhYjctNDc5OS00MDNlLThiMGUtY2UxYWVkNGY2ZjljXkEyXkFqcGc@._V1_.jpg\r\n', 'https://www.youtube.com/watch?v=hPiuRFTsD8M\r\n'),
(6, 'Seven (1995)', 'Action', 'Another David Fincher masterpiece, this crime thriller stars Morgan Freeman and Brad Pitt as detectives hunting a serial killer who uses the seven deadly sins as motives.', 127, 'https://m.media-amazon.com/images/M/MV5BN2U5ZDE4OTgtYzY4ZC00MWFhLTg2ZjUtNDQ2ZGE0MDUyNmVkXkEyXkFqcGc@._V1_.jpg\r\n', 'https://www.youtube.com/watch?v=znmZoVkCjpI\r\n'),
(7, '12 Angry Men (1957)', 'Legal/Crime', '\"12 Angry Men\" (1997) is a legal drama about 12 jurors deliberating the guilt of a young man accused of murder. Initially divided, their heated discussions uncover biases and test the concept of reasonable doubt.', 117, 'https://i.etsystatic.com/23647903/r/il/2bea99/2366803684/il_fullxfull.2366803684_m8w5.jpg\r\n', 'https://youtu.be/TEN-2uTi2c0\r\n'),
(8, 'Scarface (1987)', 'Crime', 'Directed by Brian De Palma and starring Al Pacino, this version reimagines Tony Montana, a Cuban immigrant in 1980s Miami, who builds a massive drug empire but faces the consequences of his ambition, paranoia, and violent nature. It’s famous for its gritty portrayal of excess and iconic quotes.', 170, 'https://m.media-amazon.com/images/I/61jWFb1KJ5L._AC_UF894,1000_QL80_.jpg\r\n', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `movie_ratings`
--

CREATE TABLE `movie_ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movie_ratings`
--

INSERT INTO `movie_ratings` (`id`, `user_id`, `movie_id`, `rating`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 1, 5, 1),
(6, 1, 6, 1),
(7, 1, 7, 1),
(8, 1, 8, 1),
(9, 12, 2, 5),
(10, 12, 3, 5),
(11, 12, 6, 4),
(12, 12, 8, 5),
(13, 12, 7, 5),
(14, 12, 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int(11) NOT NULL,
  `showtime_id` int(11) DEFAULT NULL,
  `seat_number` varchar(10) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `showtime_id`, `seat_number`, `is_available`) VALUES
(1, 1, 'A1', 1),
(2, 1, 'A2', 1),
(3, 1, 'A3', 1),
(4, 1, 'A4', 1),
(5, 1, 'A5', 1),
(6, 2, 'A1', 1),
(7, 2, 'A2', 1),
(8, 2, 'A3', 1),
(9, 2, 'A4', 1),
(10, 2, 'A5', 1),
(11, 3, 'B1', 1),
(12, 3, 'B2', 1),
(13, 3, 'B3', 1),
(14, 3, 'B4', 1),
(15, 3, 'B5', 1),
(16, 4, 'B1', 1),
(17, 4, 'B2', 1),
(18, 4, 'B3', 1),
(19, 4, 'B4', 1),
(20, 4, 'B5', 1),
(21, 5, 'C1', 1),
(22, 5, 'C2', 1),
(23, 5, 'C3', 1),
(24, 5, 'C4', 1),
(25, 5, 'C5', 1),
(26, 6, 'C1', 1),
(27, 6, 'C2', 1),
(28, 6, 'C3', 1),
(29, 6, 'C4', 1),
(30, 6, 'C5', 1);

-- --------------------------------------------------------

--
-- Table structure for table `showtimes`
--

CREATE TABLE `showtimes` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `show_date` date NOT NULL,
  `show_time` time NOT NULL,
  `hall_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `showtimes`
--

INSERT INTO `showtimes` (`id`, `movie_id`, `show_date`, `show_time`, `hall_number`) VALUES
(1, 1, '2024-11-15', '18:00:00', 1),
(2, 8, '2024-11-15', '21:00:00', 2),
(3, 7, '2024-11-16', '19:00:00', 1),
(4, 2, '2024-11-16', '22:00:00', 3),
(5, 3, '2024-11-17', '15:00:00', 2),
(6, 3, '2024-11-17', '17:30:00', 1),
(7, 4, '2024-11-08', '23:06:10', 3),
(8, 5, '2025-03-13', '11:27:10', 1),
(9, 6, '2024-08-19', '11:27:10', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'john_doe', 'password123', 'john.doe@example.com', 'user'),
(3, 'admin', 'admin', 'admin@gmail.com', 'admin'),
(10, 'dhia', '1234', 'medhianaffeti@gmail.com', 'user'),
(11, 'Diego', '7410', 'Diego.brando@gmail.com', 'user'),
(12, 'medouvic', '123', 'm@chaabi.com', 'user'),
(13, 'zakariya abidi', '159', 'zakariya@abidi.com', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `showtime_id` (`showtime_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movie_ratings`
--
ALTER TABLE `movie_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`movie_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `showtime_id` (`showtime_id`);

--
-- Indexes for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `movie_ratings`
--
ALTER TABLE `movie_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `showtimes`
--
ALTER TABLE `showtimes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `movie_ratings`
--
ALTER TABLE `movie_ratings`
  ADD CONSTRAINT `movie_ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movie_ratings_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD CONSTRAINT `showtimes_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
