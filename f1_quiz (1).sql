-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2026 at 08:47 AM
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
-- Database: `f1_quiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `timestamp`) VALUES
(1, 8, 'Logged into Pit Wall', '2026-05-24 07:12:22'),
(2, 8, 'Logged into Pit Wall', '2026-05-24 07:14:20'),
(3, 9, 'Logged into Pit Wall', '2026-05-24 07:14:43'),
(4, 8, 'Logged into Pit Wall', '2026-05-24 07:33:38'),
(5, 8, 'Completed quiz run with score: 9/10', '2026-05-24 07:40:10'),
(6, 8, 'Completed medium quiz: 7/10 correct. Earned 11 pts.', '2026-05-24 07:53:31'),
(7, 8, 'Completed easy quiz: 9/10 correct. Earned 90 pts.', '2026-05-24 08:02:15'),
(8, 8, 'Logged into Pit Wall', '2026-05-24 09:13:46'),
(9, 9, 'Logged into Pit Wall', '2026-05-24 09:18:08'),
(10, 9, 'Completed medium quiz: 5/10 correct. Earned 100 pts.', '2026-05-24 09:29:37'),
(11, 9, 'Logged into Pit Wall', '2026-05-24 09:44:31'),
(12, 9, 'Logged into Pit Wall', '2026-06-06 06:19:25'),
(13, 9, 'Logged into Pit Wall', '2026-06-06 06:38:06'),
(14, 9, 'Logged into Pit Wall', '2026-06-12 00:33:14'),
(15, 9, 'Completed easy quiz: 9/10 correct. Earned 90 pts.', '2026-06-12 00:36:02'),
(16, 9, 'Logged into Pit Wall', '2026-06-12 01:16:08'),
(17, 9, 'Completed easy quiz: 5/10 correct. Earned 50 pts.', '2026-06-12 01:18:01'),
(18, 9, 'Logged into Pit Wall', '2026-06-13 08:15:23'),
(19, 9, 'Logged into Pit Wall', '2026-06-13 08:27:52'),
(20, 8, 'Logged into Pit Wall', '2026-06-13 08:28:15'),
(21, 8, 'Completed easy quiz: 10/10 correct. Earned 100 pts.', '2026-06-13 09:30:29'),
(24, 22, 'Logged into Pit Wall', '2026-06-13 09:47:14'),
(25, 22, 'Completed easy quiz: 10/10 correct. Earned 100 pts.', '2026-06-13 09:48:17'),
(26, 22, 'Logged into Pit Wall', '2026-06-15 13:44:24'),
(27, 22, 'Logged into Pit Wall', '2026-06-15 14:33:13'),
(28, 22, 'Logged into Pit Wall', '2026-06-15 14:46:05'),
(29, 22, 'Logged into Pit Wall', '2026-06-16 04:47:01'),
(30, 22, 'Completed Easy Quiz — Secured +80 points (80% Accuracy).', '2026-06-16 04:47:59'),
(31, 22, 'Logged into Pit Wall', '2026-06-16 04:54:40'),
(32, 22, 'Logged into Pit Wall', '2026-06-16 04:56:42');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'AdminUser', 'telemetry123');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `difficulty` varchar(10) DEFAULT 'medium'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `difficulty`) VALUES
(1, 'Who holds the record for the most World Drivers\' Championships?', 'Ayrton Senna', 'Lewis Hamilton', 'Max Verstappen', 'Sebastian Vettel', 'Lewis Hamilton', 'medium'),
(2, 'Which circuit is known as the \'Temple of Speed\'?', 'Silverstone', 'Monza', 'Spa-Francorchamps', 'Monaco', 'Monza', 'medium'),
(3, 'Which track formerly hosted the Malaysian Grand Prix?', 'Johor Circuit', 'Sepang International Circuit', 'Marina Bay Street Circuit', 'Buddh International Circuit', 'Sepang International Circuit', 'medium'),
(5, 'Who won the first ever Formula 1 World Drivers\' Championship in 1950?', 'Juan Manuel Fangio', 'Alberto Ascari', 'Giuseppe Farina', 'Stirling Moss', 'Giuseppe Farina', 'medium'),
(6, 'Which driver holds the record for the most Grand Prix victories?', 'Michael Schumacher', 'Lewis Hamilton', 'Max Verstappen', 'Alain Prost', 'Lewis Hamilton', 'medium'),
(7, 'Which constructor has won the most Formula 1 World Constructors\' Championships?', 'McLaren', 'Williams', 'Mercedes', 'Ferrari', 'Ferrari', 'medium'),
(8, 'Who is the youngest ever Formula 1 World Champion?', 'Lewis Hamilton', 'Fernando Alonso', 'Sebastian Vettel', 'Max Verstappen', 'Sebastian Vettel', 'medium'),
(9, 'At which circuit is the Italian Grand Prix traditionally held?', 'Imola', 'Mugello', 'Monza', 'Misano', 'Monza', 'medium'),
(10, 'Which driver is famously known by the nickname \"The Professor\"?', 'Niki Lauda', 'Alain Prost', 'Jackie Stewart', 'Ayrton Senna', 'Alain Prost', 'medium'),
(11, 'What does \"DRS\" stand for in Formula 1?', 'Drag Reduction System', 'Downforce Recovery System', 'Dynamic Racing Setup', 'Driver Response System', 'Drag Reduction System', 'medium'),
(12, 'Which tire manufacturer became the sole supplier for Formula 1 in 2011?', 'Michelin', 'Bridgestone', 'Goodyear', 'Pirelli', 'Pirelli', 'medium'),
(13, 'Who is the only driver to win the Formula 1 World Championship posthumously?', 'Gilles Villeneuve', 'Jochen Rindt', 'Ronnie Peterson', 'Wolfgang von Trips', 'Jochen Rindt', 'medium'),
(14, 'Which circuit is the longest on the modern Formula 1 calendar?', 'Circuit of the Americas', 'Silverstone', 'Spa-Francorchamps', 'Suzuka', 'Spa-Francorchamps', 'medium'),
(15, 'In what year did the inaugural night race in Singapore take place?', '2007', '2008', '2009', '2010', '2008', 'medium'),
(16, 'Which driver famously won his first race on his debut for Red Bull Racing in 2016?', 'Daniel Ricciardo', 'Sebastian Vettel', 'Max Verstappen', 'Pierre Gasly', 'Max Verstappen', 'medium'),
(17, 'What color flag is waved to instruct a driver to return to the pits due to a mechanical issue?', 'Black flag', 'Black flag with an orange circle', 'Black and white diagonal flag', 'Red flag', 'Black flag with an orange circle', 'medium'),
(18, 'Which two drivers are tied for the most Formula 1 World Drivers\' Championships?', 'Alain Prost and Ayrton Senna', 'Michael Schumacher and Lewis Hamilton', 'Juan Manuel Fangio and Lewis Hamilton', 'Sebastian Vettel and Alain Prost', 'Michael Schumacher and Lewis Hamilton', 'medium'),
(19, 'Which team achieved an unprecedented 15 wins out of 16 races during the 1988 season?', 'Williams', 'Ferrari', 'McLaren', 'Benetton', 'McLaren', 'medium'),
(20, 'Who was the first Chinese driver to compete in a Formula 1 race?', 'Ma Qinghua', 'Yifei Ye', 'Zhou Guanyu', 'Ho-Pin Tung', 'Zhou Guanyu', 'medium'),
(21, 'Which driver is known by the nickname \"The Honey Badger\"?', 'Valtteri Bottas', 'Lando Norris', 'Daniel Ricciardo', 'Nico Hülkenberg', 'Daniel Ricciardo', 'medium'),
(22, 'How many points are awarded to the winner of a standard Formula 1 Grand Prix?', '10', '15', '25', '30', '25', 'medium'),
(23, 'Which driver holds the record for the most pole positions in Formula 1 history?', 'Ayrton Senna', 'Michael Schumacher', 'Sebastian Vettel', 'Lewis Hamilton', 'Lewis Hamilton', 'medium'),
(24, 'What is the minimum age requirement to compete in Formula 1 under current FIA regulations?', '16', '17', '18', '19', '18', 'medium'),
(25, 'Which father-son duo both won the Formula 1 World Drivers\' Championship?', 'Gilles and Jacques Villeneuve', 'Graham and Damon Hill', 'Jos and Max Verstappen', 'Mario and Michael Andretti', 'Graham and Damon Hill', 'medium'),
(26, 'Which circuit features the famous \"Eau Rouge\" and \"Raidillon\" corners?', 'Monaco', 'Spa-Francorchamps', 'Suzuka', 'Interlagos', 'Spa-Francorchamps', 'medium'),
(27, 'Who was the team principal of Ferrari during their dominant era from 2000 to 2004?', 'Stefano Domenicali', 'Maurizio Arrivabene', 'Jean Todt', 'Mattia Binotto', 'Jean Todt', 'medium'),
(28, 'Which driver famously won the 2007 World Championship by a single point?', 'Fernando Alonso', 'Lewis Hamilton', 'Kimi Räikkönen', 'Felipe Massa', 'Kimi Räikkönen', 'medium'),
(29, 'What does the yellow flag signify in Formula 1?', 'Danger ahead, slow down', 'Session suspended', 'Slippery surface', 'Slower car ahead', 'Danger ahead, slow down', 'medium'),
(30, 'Which driver holds the record for the most race starts in Formula 1 history?', 'Kimi Räikkönen', 'Fernando Alonso', 'Rubens Barrichello', 'Lewis Hamilton', 'Fernando Alonso', 'medium'),
(31, 'In what year was the halo cockpit protection device made mandatory in Formula 1?', '2016', '2017', '2018', '2019', '2018', 'medium'),
(32, 'Which driver famously survived a fiery crash on the opening lap of the 2020 Bahrain Grand Prix?', 'Kevin Magnussen', 'Romain Grosjean', 'Lance Stroll', 'Daniil Kvyat', 'Romain Grosjean', 'medium'),
(33, 'Which Formula 1 team is based in Maranello, Italy?', 'AlphaTauri', 'Alfa Romeo', 'Ferrari', 'Haas', 'Ferrari', 'medium'),
(34, 'Who won the Formula 1 World Championship in 2016 before unexpectedly retiring?', 'Jenson Button', 'Sebastian Vettel', 'Nico Rosberg', 'Felipe Massa', 'Nico Rosberg', 'medium'),
(35, 'Which flag indicates the end of a Formula 1 race?', 'Chequered flag', 'Red flag', 'Yellow flag', 'Green flag', 'Chequered flag', 'easy'),
(36, 'What does DRS stand for?', 'Drag Reduction System', 'Downforce Racing System', 'Direct Racing Speed', 'Driver Reaction System', 'Drag Reduction System', 'easy'),
(37, 'How many tires does a standard Formula 1 car have?', '3', '4', '6', '8', '4', 'easy'),
(38, 'Which team does Max Verstappen drive for?', 'Mercedes', 'Ferrari', 'Red Bull Racing', 'McLaren', 'Red Bull Racing', 'easy'),
(39, 'What color are the soft compound dry tires in F1?', 'White', 'Yellow', 'Red', 'Green', 'Red', 'easy'),
(40, 'Who holds the record for the most World Drivers\' Championships alongside Michael Schumacher?', 'Ayrton Senna', 'Lewis Hamilton', 'Sebastian Vettel', 'Alain Prost', 'Lewis Hamilton', 'easy'),
(41, 'What does a yellow flag mean?', 'Pit lane closed', 'Danger, slow down', 'Session stopped', 'Faster car approaching', 'Danger, slow down', 'easy'),
(42, 'Which of these is a street circuit?', 'Silverstone', 'Monza', 'Monaco', 'Spa-Francorchamps', 'Monaco', 'easy'),
(43, 'What is the minimum age to compete in Formula 1?', '16', '17', '18', '21', '18', 'easy'),
(44, 'Which company is the sole tire supplier for F1?', 'Michelin', 'Bridgestone', 'Goodyear', 'Pirelli', 'Pirelli', 'easy'),
(45, 'What part of the car protects the driver\'s head?', 'DRS', 'Halo', 'Monocoque', 'Diffuser', 'Halo', 'easy'),
(46, 'How many drivers race for a single constructor in a standard race?', '1', '2', '3', '4', '2', 'easy'),
(47, 'Which penalty requires a driver to drive through the pit lane without stopping?', 'Stop-and-Go penalty', 'Drive-through penalty', 'Grid penalty', 'Time penalty', 'Drive-through penalty', 'easy'),
(48, 'What is the maximum number of championship points awarded for a race win?', '10', '15', '25', '30', '25', 'easy'),
(49, 'What does Pole Position mean?', 'Starting first on the grid', 'Fastest lap of the race', 'Winning the race', 'Leading the championship', 'Starting first on the grid', 'easy'),
(50, 'Which circuit is known as the Temple of Speed?', 'Silverstone', 'Suzuka', 'Monza', 'Interlagos', 'Monza', 'easy'),
(51, 'What does DNF stand for?', 'Did Not Finish', 'Did Not Fit', 'Driver Not Found', 'Delayed National Flag', 'Did Not Finish', 'easy'),
(52, 'Who is known as the Honey Badger?', 'Max Verstappen', 'Daniel Ricciardo', 'Lando Norris', 'Fernando Alonso', 'Daniel Ricciardo', 'easy'),
(53, 'Which flag requires a driver to let a faster car pass?', 'Blue flag', 'Black flag', 'White flag', 'Yellow flag', 'Blue flag', 'easy'),
(54, 'What is the primary purpose of the front wing?', 'Cooling the engine', 'Generating downforce', 'Storing fuel', 'Housing the telemetry', 'Generating downforce', 'easy'),
(55, 'In what year did the V6 turbo hybrid era begin?', '2012', '2014', '2016', '2018', '2014', 'medium'),
(56, 'Which driver won the World Championship in 2007 by just one point?', 'Lewis Hamilton', 'Fernando Alonso', 'Kimi Räikkönen', 'Felipe Massa', 'Kimi Räikkönen', 'medium'),
(57, 'What is the name of the corner sequence Maggotts, Becketts, and Chapel?', 'Spa-Francorchamps', 'Silverstone', 'Suzuka', 'Albert Park', 'Silverstone', 'medium'),
(58, 'Which constructor has won the most World Constructors\' Championships?', 'McLaren', 'Williams', 'Ferrari', 'Mercedes', 'Ferrari', 'medium'),
(59, 'What is the approximate weight of an F1 car (including the driver) under modern regulations?', '650 kg', '798 kg', '850 kg', '900 kg', '798 kg', 'medium'),
(60, 'Who was the first ever Formula 1 World Champion in 1950?', 'Juan Manuel Fangio', 'Alberto Ascari', 'Giuseppe Farina', 'Stirling Moss', 'Giuseppe Farina', 'medium'),
(61, 'What is the duration of the penalty if a driver jumps the start?', '5 seconds', '10 seconds', 'Drive-through', 'Stop-and-go', 'Drive-through', 'medium'),
(62, 'Which circuit features the famous Wall of Champions?', 'Circuit Gilles Villeneuve', 'Marina Bay Street Circuit', 'Baku City Circuit', 'Circuit de Monaco', 'Circuit Gilles Villeneuve', 'medium'),
(63, 'How many points does a driver get for finishing 10th in a Grand Prix?', '0', '1', '2', '5', '1', 'medium'),
(64, 'What is the maximum fuel allowance for an F1 car during a race?', '100 kg', '110 kg', '120 kg', '150 kg', '110 kg', 'medium'),
(65, 'Who is the youngest race winner in Formula 1 history?', 'Sebastian Vettel', 'Charles Leclerc', 'Max Verstappen', 'Fernando Alonso', 'Max Verstappen', 'medium'),
(66, 'What does MGU-K stand for?', 'Motor Generator Unit - Kinetic', 'Motor Generator Unit - Kilo', 'Mechanical Gear Unit - Kinetic', 'Main Generator Unit - Kinetic', 'Motor Generator Unit - Kinetic', 'medium'),
(67, 'Which driver is famously associated with the number 27?', 'Ayrton Senna', 'Gilles Villeneuve', 'Niki Lauda', 'Alain Prost', 'Gilles Villeneuve', 'medium'),
(68, 'How long is a typical Formula 1 race distance (excluding Monaco)?', '250 km', '305 km', '350 km', '400 km', '305 km', 'medium'),
(69, 'Which team famously introduced the six-wheeled P34 in 1976?', 'Lotus', 'Brabham', 'Tyrrell', 'Williams', 'Tyrrell', 'medium'),
(70, 'What is the term used for rapid aerodynamic bouncing on straight sections of the track?', 'Tire degradation', 'Porpoising', 'Engine misfiring', 'Brake locking', 'Porpoising', 'medium'),
(71, 'Which track has the longest straight on the F1 calendar?', 'Monza', 'Baku', 'Mexico City', 'Las Vegas', 'Baku', 'medium'),
(72, 'Who was the team principal of Ferrari during their dominant era in the early 2000s?', 'Jean Todt', 'Stefano Domenicali', 'Ross Brawn', 'Maurizio Arrivabene', 'Jean Todt', 'medium'),
(73, 'In a dry race, how many tire compounds must a driver use at minimum?', '1', '2', '3', '4', '2', 'medium'),
(74, 'What material are modern F1 brake discs primarily made of?', 'Steel', 'Carbon Fiber', 'Ceramic', 'Aluminum', 'Carbon Fiber', 'medium'),
(75, 'What was the displacement of the naturally aspirated V10 engines used in the early 2000s?', '2.4 liters', '3.0 liters', '3.5 liters', '4.0 liters', '3.0 liters', 'hard'),
(76, 'Who is the only driver to win a World Championship posthumously?', 'Jim Clark', 'Jochen Rindt', 'Ronnie Peterson', 'François Cevert', 'Jochen Rindt', 'hard'),
(77, 'What is the maximum allowed width of a Formula 1 car under the current ground-effect regulations?', '1.8 meters', '1.9 meters', '2.0 meters', '2.1 meters', '2.0 meters', 'hard'),
(78, 'In what year did active suspension get banned from Formula 1?', '1991', '1993', '1994', '1996', '1994', 'hard'),
(79, 'Which engine manufacturer powered Brawn GP to their 2009 championship?', 'Honda', 'Ferrari', 'Renault', 'Mercedes', 'Mercedes', 'hard'),
(80, 'How many gear ratios are mandated in a modern Formula 1 gearbox?', '6', '7', '8', '9', '8', 'hard'),
(81, 'Who holds the record for the most consecutive Grand Prix entries without a win?', 'Nico Hülkenberg', 'Andrea de Cesaris', 'Martin Brundle', 'Romain Grosjean', 'Andrea de Cesaris', 'hard'),
(82, 'What is the precise limit on the MGU-K energy deployment per lap?', '2 Megajoules', '4 Megajoules', '6 Megajoules', '8 Megajoules', '4 Megajoules', 'hard'),
(83, 'Which track hosted the only Formula 1 race to feature a 6-car grid?', 'Spa-Francorchamps', 'Indianapolis', 'Suzuka', 'Imola', 'Indianapolis', 'hard'),
(84, 'What is the maximum internal temperature an F1 tire blanket is permitted to reach under 2024 regulations?', '70 °C', '90 °C', '100 °C', '110 °C', '70 °C', 'hard'),
(85, 'Who was the chief designer of the dominant McLaren MP4/4?', 'Adrian Newey', 'Gordon Murray', 'John Barnard', 'Rory Byrne', 'Gordon Murray', 'hard'),
(86, 'The Brabham BT46B fan car competed in and won exactly how many races before being withdrawn?', '1', '2', '3', '5', '1', 'hard'),
(87, 'What is the operational pressure of an F1 hydraulic system?', '100 bar', '150 bar', '200 bar', '250 bar', '200 bar', 'hard'),
(88, 'Which driver scored the most points in the turbo era (1977-1988) without ever winning a championship?', 'Gilles Villeneuve', 'René Arnoux', 'Michele Alboreto', 'Riccardo Patrese', 'René Arnoux', 'hard'),
(89, 'What aerodynamic device was banned midway through the 2006 season?', 'F-Duct', 'Mass Damper', 'Double Diffuser', 'Blown Diffuser', 'Mass Damper', 'hard'),
(90, 'How many sensors does a typical modern F1 car have transmitting telemetry data?', 'approx 50', 'approx 150', 'approx 300', 'approx 500', 'approx 300', 'hard'),
(91, 'Which constructor pioneered the carbon fiber monocoque chassis?', 'Lotus', 'McLaren', 'Williams', 'Ferrari', 'McLaren', 'hard'),
(92, 'Under the cost cap regulations introduced in 2021, what was the baseline budget limit in USD?', '125 million', '135 million', '145 million', '175 million', '145 million', 'hard'),
(93, 'What is the ignition timing delay utilized to create an off-throttle blown diffuser effect?', 'Retarded ignition', 'Advanced ignition', 'Dual spark timing', 'Cut-off sequencing', 'Retarded ignition', 'hard'),
(94, 'Which driver holds the record for the shortest Formula 1 career, measuring just 2 meters?', 'Marco Apicella', 'Ernst Loof', 'Perry McCarthy', 'Chanoch Nissany', 'Ernst Loof', 'Hard');

-- --------------------------------------------------------

--
-- Table structure for table `quizresult`
--

CREATE TABLE `quizresult` (
  `resultID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `accuracy` int(100) NOT NULL,
  `score` int(11) NOT NULL,
  `dateCompleted` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Qdifficulty` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizresult`
--

INSERT INTO `quizresult` (`resultID`, `userID`, `accuracy`, `score`, `dateCompleted`, `Qdifficulty`) VALUES
(1, 1, 1, 2, '2026-05-20 15:18:08', ''),
(2, 1, 1, 1, '2026-05-20 15:18:08', ''),
(3, 2, 0, 0, '2026-05-20 15:18:08', ''),
(4, 2, 1, 3, '2026-05-20 15:18:08', ''),
(5, 8, 100, 100, '2026-06-13 09:30:29', 'easy'),
(6, 22, 100, 100, '2026-06-13 09:48:17', 'easy'),
(7, 22, 80, 80, '2026-06-16 04:47:59', 'easy');

-- --------------------------------------------------------

--
-- Table structure for table `rank`
--

CREATE TABLE `rank` (
  `rankID` int(11) NOT NULL,
  `rankName` varchar(50) NOT NULL,
  `minPoints` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rank`
--

INSERT INTO `rank` (`rankID`, `rankName`, `minPoints`) VALUES
(1, 'Rookie', 0),
(2, 'Test Driver', 20),
(3, 'Midfield Contender', 50),
(4, 'Podium Finisher', 90),
(5, 'Grand Prix Winner', 140),
(6, 'World Champion', 200);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `teamID` int(11) NOT NULL,
  `teamName` varchar(50) NOT NULL,
  `teamColor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`teamID`, `teamName`, `teamColor`) VALUES
(1, 'McLaren', '#FF8700'),
(2, 'Red Bull Racing', '#367FA9'),
(3, 'Ferrari', '#E80020'),
(4, 'Mercedes-AMG', '#27F4D2'),
(5, 'Williams', '#37BEDD'),
(6, 'Aston Martin', '#229971'),
(7, 'Alpine', '#0093CC'),
(8, 'Haas', '#B6BABD'),
(9, 'Racing Bulls', '#66BBFF'),
(10, 'Audi', '#F5192F'),
(11, 'Cadillac', '#FFFFFF');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `teamID` int(50) DEFAULT NULL,
  `super_license_points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `teamID`, `super_license_points`) VALUES
(8, 'hyjatt', 'ijat1', 'izzatjek12@gmail.com', 4, 295),
(9, 'adi_zarf', '$2y$10$vJTv5IBkNvMAGcljNMaj1eERBMtP5X43FWN2rqAiY6Qo5eiWZA5he', 'adios@gmail.com', 1, 360),
(12, 'iskandar06', 'iskandarp18', 'iskandarp18@gmail.com', 2, 0),
(13, 'MaxHezriqtapen', 'osskur123', 'maxosstapen@yahoo.com', 2, 0),
(14, 'zuraidah74', 'zuraidahbaddie', 'zuraidahosem1974@gmail.com', 5, 0),
(22, 'Zharfan', '$2y$10$.T2YyP.g1u.P8xXKQ45muexmS4slWzNisZWlsWQgQtT5EMqttY2Aa', 'zhfrans@gmail.com', 3, 180),
(23, 'Ismail', 'mailmail', 'mail98@yahoo.com', 8, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quizresult`
--
ALTER TABLE `quizresult`
  ADD PRIMARY KEY (`resultID`);

--
-- Indexes for table `rank`
--
ALTER TABLE `rank`
  ADD PRIMARY KEY (`rankID`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`teamID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `quizresult`
--
ALTER TABLE `quizresult`
  MODIFY `resultID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rank`
--
ALTER TABLE `rank`
  MODIFY `rankID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `teamID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
